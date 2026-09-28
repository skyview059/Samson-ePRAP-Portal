<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Error Reporter
 *
 * Saves every error (404, DB, PHP, uncaught exception, general) first to a
 * daily log file and the `error_logs` table, then emails it to the developer.
 *
 * Called from MY_Exceptions / MY_Log, so it can run before the CI instance,
 * the DB or the helpers exist. Everything here must therefore be defensive
 * and must never throw or trigger another report.
 *
 * .env:
 *   ERROR_REPORT_ENABLED=true           master switch
 *   ERROR_REPORT_DEV_EMAIL=a@x.com,b@y  comma separated; empty = no email
 *   ERROR_REPORT_EMAIL_404=false        email 404s too (they are always logged)
 *   ERROR_REPORT_THROTTLE_MINUTES=30    same error is emailed at most once per window
 */
class Error_reporter
{
    const MAX_PER_REQUEST = 20;

    private static $busy       = false;
    private static $seen       = [];
    private static $db_handled = false;

    /**
     * @param string $type     404 | db | php | exception | general
     * @param array  $data     heading, message, severity, file, line, backtrace
     */
    public static function report($type, array $data = [])
    {
        if (self::$busy || !filter_var(env('ERROR_REPORT_ENABLED', true), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        // A DB failure usually reaches us twice (log_message + error_db view)
        if ($type === 'db') {
            if (self::$db_handled) {
                return;
            }
            self::$db_handled = true;
        }

        self::$busy = true;
        try {
            $row = self::build($type, $data);

            // Same error repeated inside one request (e.g. a notice in a loop)
            if (isset(self::$seen[$row['signature']]) || count(self::$seen) >= self::MAX_PER_REQUEST) {
                return;
            }
            self::$seen[$row['signature']] = true;

            // 1. Save
            self::saveToFile($row);
            $id = self::saveToDb($row); // skipped automatically if the DB connection is down

            // 2. Email
            if (self::shouldEmail($row) && self::sendEmail($row, $id)) {
                self::markEmailed($id);
            }
        } catch (\Throwable $e) {
            // Never let the reporter itself break the error page
        } finally {
            self::$busy = false;
        }
    }

    private static function build($type, array $data)
    {
        $message = isset($data['message']) ? $data['message'] : '';
        if (is_array($message)) {
            $message = implode("\n", $message);
        }
        $message = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", (string) $message)));

        $https  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        $host   = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        $uri    = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $url    = $host ? (($https ? 'https://' : 'http://') . $host . $uri) : (is_cli() ? 'CLI: ' . implode(' ', isset($_SERVER['argv']) ? $_SERVER['argv'] : []) : '');

        list($user_id, $student_id) = self::loginIds();

        $file = isset($data['file']) ? str_replace('\\', '/', (string) $data['file']) : '';
        $line = isset($data['line']) ? (int) $data['line'] : null;

        // 404 message is generic, so the URL path is what makes it unique.
        // Numbers are normalised so "id = 12" and "id = 13" count as one error.
        $sig_source = $type . '|' . $message . '|' . $file . '|' . $line . ($type === '404' ? '|' . strtok($uri, '?') : '');

        return [
            'type'       => $type,
            'severity'   => isset($data['severity']) ? (string) $data['severity'] : null,
            'heading'    => isset($data['heading']) ? mb_substr(strip_tags((string) $data['heading']), 0, 255) : null,
            'message'    => $message,
            'file'       => $file ? mb_substr($file, 0, 500) : null,
            'line'       => $line,
            'url'        => mb_substr($url, 0, 2000),
            'method'     => isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : null,
            'ip'         => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null,
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? mb_substr($_SERVER['HTTP_USER_AGENT'], 0, 500) : null,
            'referrer'   => isset($_SERVER['HTTP_REFERER']) ? mb_substr($_SERVER['HTTP_REFERER'], 0, 2000) : null,
            'user_id'    => $user_id,
            'student_id' => $student_id,
            'backtrace'  => isset($data['backtrace']) ? self::formatTrace($data['backtrace']) : null,
            'signature'  => md5(preg_replace('/\d+/', 'N', $sig_source)),
            'emailed'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }

    private static function loginIds()
    {
        $user_id = $student_id = null;
        try {
            if (function_exists('get_instance') && is_object(get_instance()) && isset(get_instance()->input)) {
                if (function_exists('getLoginUserData')) {
                    $user_id = getLoginUserData('user_id');
                }
                if (function_exists('getLoginStudentData')) {
                    $student_id = getLoginStudentData('student_id');
                }
            }
        } catch (\Throwable $e) {
        }
        return [$user_id ? (int) $user_id : null, $student_id ? (int) $student_id : null];
    }

    private static function formatTrace($trace)
    {
        if (is_string($trace)) {
            return $trace;
        }
        $lines = [];
        foreach ((array) $trace as $i => $f) {
            // Skip CodeIgniter's own system files, same as the stock error views
            if (!isset($f['file']) || strpos(str_replace('\\', '/', $f['file']), str_replace('\\', '/', BASEPATH)) === 0) {
                continue;
            }
            $fn      = (isset($f['class']) ? $f['class'] . $f['type'] : '') . (isset($f['function']) ? $f['function'] : '');
            $lines[] = '#' . $i . ' ' . $f['file'] . '(' . (isset($f['line']) ? $f['line'] : '?') . '): ' . $fn . '()';
        }
        return $lines ? implode("\n", $lines) : null;
    }

    private static function saveToFile(array $row)
    {
        $entry = str_repeat('-', 80) . "\n"
            . "[{$row['created_at']}] " . strtoupper($row['type']) . ($row['severity'] ? " ({$row['severity']})" : '') . "\n"
            . ($row['heading'] ? "Heading : {$row['heading']}\n" : '')
            . "Message : {$row['message']}\n"
            . ($row['file'] ? "File    : {$row['file']}:{$row['line']}\n" : '')
            . "URL     : {$row['method']} {$row['url']}\n"
            . "IP      : {$row['ip']}\n"
            . "User    : " . ($row['user_id'] ?: '-') . " | Student: " . ($row['student_id'] ?: '-') . "\n"
            . ($row['referrer'] ? "Referrer: {$row['referrer']}\n" : '')
            . "Agent   : {$row['user_agent']}\n"
            . ($row['backtrace'] ? "Trace   :\n{$row['backtrace']}\n" : '');

        @file_put_contents(APPPATH . 'logs/error_report-' . date('Y-m-d') . '.log', $entry, FILE_APPEND | LOCK_EX);
    }

    private static function saveToDb(array $row)
    {
        if (!function_exists('get_instance')) {
            return 0;
        }
        $ci = get_instance();
        if (!is_object($ci) || !isset($ci->db) || !is_object($ci->db) || empty($ci->db->conn_id)) {
            return 0;
        }

        $debug             = $ci->db->db_debug;
        $ci->db->db_debug  = false; // a failing insert must not render error_db
        try {
            $ok = $ci->db->insert('error_logs', $row);
            return $ok ? (int) $ci->db->insert_id() : 0;
        } finally {
            $ci->db->db_debug = $debug;
        }
    }

    private static function markEmailed($id)
    {
        if (!$id) {
            return;
        }
        $ci               = get_instance();
        $debug            = $ci->db->db_debug;
        $ci->db->db_debug = false;
        $ci->db->where('id', $id)->update('error_logs', ['emailed' => 1]);
        $ci->db->db_debug = $debug;
    }

    private static function shouldEmail(array $row)
    {
        if (!env('ERROR_REPORT_DEV_EMAIL')) {
            return false;
        }
        if ($row['type'] === '404' && !filter_var(env('ERROR_REPORT_EMAIL_404', false), FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        // Throttle by signature using a marker file (works even when the DB is down)
        $dir = APPPATH . 'cache/error_reports/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $marker = $dir . $row['signature'];
        $window = max(0, (int) env('ERROR_REPORT_THROTTLE_MINUTES', 30)) * 60;
        if (is_file($marker) && (time() - filemtime($marker)) < $window) {
            return false;
        }
        @touch($marker);
        return true;
    }

    private static function sendEmail(array $row, $id)
    {
        if (!class_exists(PHPMailer::class)) {
            return false;
        }

        $e     = function ($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); };
        $rows  = [
            'Type'     => strtoupper($row['type']) . ($row['severity'] ? " ({$row['severity']})" : ''),
            'Heading'  => $row['heading'],
            'Message'  => $row['message'],
            'File'     => $row['file'] ? "{$row['file']}:{$row['line']}" : '',
            'URL'      => "{$row['method']} {$row['url']}",
            'Referrer' => $row['referrer'],
            'IP'       => $row['ip'],
            'User ID'  => $row['user_id'],
            'Student'  => $row['student_id'],
            'Agent'    => $row['user_agent'],
            'Log ID'   => $id ?: 'not saved in DB (see logs/error_report-' . date('Y-m-d') . '.log)',
            'Time'     => $row['created_at'],
        ];
        $body = '<table cellpadding="6" cellspacing="0" border="1" style="border-collapse:collapse;font:13px Arial,sans-serif">';
        foreach ($rows as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $body .= '<tr><th align="left" valign="top" style="background:#f5f5f5">' . $label . '</th>'
                . '<td style="white-space:pre-wrap">' . $e($value) . '</td></tr>';
        }
        $body .= '</table>';
        if ($row['backtrace']) {
            $body .= '<h4 style="font-family:Arial">Backtrace</h4><pre style="font-size:12px">' . $e($row['backtrace']) . '</pre>';
        }

        $site    = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : env('APP_NAME', 'ePRAP');
        $subject = "[Error][{$site}] " . strtoupper($row['type']) . ': ' . mb_substr(preg_replace('/\s+/', ' ', $row['message']), 0, 120);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->Timeout   = 10;
            if (self::isLocal()) {
                // Mailpit, same as Mail::send()
                $mail->Host        = '127.0.0.1';
                $mail->Port        = 1025;
                $mail->SMTPAuth    = false;
                $mail->SMTPSecure  = '';
                $mail->SMTPAutoTLS = false;
            } else {
                $mail->Host     = env('SMTP_HOST', 'mail.eprap.com');
                $mail->SMTPAuth = env('SMTP_USER') ? true : false;
                $mail->Username = env('SMTP_USER', '');
                $mail->Password = env('SMTP_PASS', '');
                $secure         = env('SMTP_SECURE', '');
                if ($secure) {
                    $mail->SMTPSecure = $secure;
                } else {
                    $mail->SMTPAutoTLS = false;
                }
                $mail->Port = (int) env('SMTP_PORT', 465);
            }

            $mail->setFrom(env('SMTP_FROM', 'noreply@eprap.com'), env('SMTP_FROM_NAME', 'ePRAP Error Reporter'));
            foreach (explode(',', env('ERROR_REPORT_DEV_EMAIL')) as $to) {
                if ($to = trim($to)) {
                    $mail->addAddress($to);
                }
            }
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags(str_replace('</tr>', "\n", $body));
            $mail->send();
            return true;
        } catch (\Throwable $ex) {
            @file_put_contents(APPPATH . 'logs/error_report-' . date('Y-m-d') . '.log', 'Email to dev failed: ' . $mail->ErrorInfo . "\n", FILE_APPEND | LOCK_EX);
            return false;
        }
    }

    private static function isLocal()
    {
        $host = isset($_SERVER['HTTP_HOST']) ? strtolower($_SERVER['HTTP_HOST']) : '';
        return (bool) preg_match('/^(localhost|127\.0\.0\.1|.+\.(test|local|localhost))(:\d+)?$/', $host);
    }
}
