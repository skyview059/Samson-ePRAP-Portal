<?php

require_once APPPATH . 'libraries/Error_reporter.php';

/**
 * Every error passes through here, whether or not it is displayed
 * (APP_DEBUG only controls display), so this is where it gets reported.
 * See Error_reporter for storage (file + error_logs table) and dev email.
 */
class MY_Exceptions extends CI_Exceptions {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * PHP errors and uncaught exceptions (called even when display_errors is off)
     */
    function log_exception($severity, $message, $filepath, $line)
    {
        $trace     = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
        $exception = null;
        foreach ($trace as $frame) {
            // CI's _exception_handler() only passes us the message; pick the object off its frame
            if (isset($frame['function'], $frame['args'][0]) && $frame['function'] === '_exception_handler' && $frame['args'][0] instanceof Throwable) {
                $exception = $frame['args'][0];
                break;
            }
        }

        if ($exception) {
            Error_reporter::report('exception', [
                'severity'  => get_class($exception),
                'message'   => $exception->getMessage(),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'backtrace' => $exception->getTraceAsString(),
            ]);
        } else {
            Error_reporter::report('php', [
                'severity'  => isset($this->levels[$severity]) ? $this->levels[$severity] : $severity,
                'message'   => $message,
                'file'      => $filepath,
                'line'      => $line,
                'backtrace' => array_slice($trace, 2),
            ]);
        }

        parent::log_exception($severity, $message, $filepath, $line);
    }

    /**
     * error_404, error_db and error_general all render through here
     */
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        $types = ['error_404' => '404', 'error_db' => 'db'];
        Error_reporter::report(isset($types[$template]) ? $types[$template] : 'general', [
            'heading'   => $heading,
            'message'   => $message,
            'severity'  => $status_code,
            'backtrace' => $template === 'error_404' ? null : debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
        ]);

        return parent::show_error($heading, $message, $template, $status_code);
    }

}
