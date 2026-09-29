<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/Error_reporter.php';

/* Viewer for application/logs/error_report.log (written by Error_reporter)
 * URL: admin/module/error_log
 */

class Error_log extends Admin_controller{
    const READ_BYTES = 2097152; // only read the newest 2 MB of the log
    const MAX_ROWS   = 300;

    function __construct(){
        parent::__construct();
    }

    public function index(){
        $type = $this->input->get('type', TRUE);
        $q    = trim((string) $this->input->get('q', TRUE));
        $file = APPPATH . Error_reporter::LOG_FILE;

        $entries = [];
        $total   = 0;
        foreach (array_reverse($this->_read_entries($file)) as $entry) {
            $total++;
            if ($type && strcasecmp($entry['type'], $type) !== 0) {
                continue;
            }
            if ($q !== '' && stripos($entry['raw'], $q) === false) {
                continue;
            }
            $entries[] = $entry;
            if (count($entries) >= self::MAX_ROWS) {
                break;
            }
        }

        $data = [
            'entries'   => $entries,
            'total'     => $total,
            'type'      => $type,
            'q'         => $q,
            'file_path' => Error_reporter::LOG_FILE,
            'file_size' => is_file($file) ? filesize($file) : 0,
            'max_rows'  => self::MAX_ROWS,
        ];
        $this->viewAdminContent('module/error_log/index', $data);
    }

    public function clear(){
        if ($this->input->method() !== 'post' || !$this->acls->checkPermission('module/error_log', $this->role_id)) {
            redirect(site_url(Backend_URL . 'module/error_log'));
        }

        $file = APPPATH . Error_reporter::LOG_FILE;
        if (is_file($file)) {
            file_put_contents($file, '', LOCK_EX);
        }
        $this->session->set_flashdata('message', 'Error log cleared');
        redirect(site_url(Backend_URL . 'module/error_log'));
    }

    private function _read_entries($file){
        if (!is_file($file) || !filesize($file)) {
            return [];
        }

        $size    = filesize($file);
        $handle  = fopen($file, 'rb');
        $partial = $size > self::READ_BYTES;
        if ($partial) {
            fseek($handle, -self::READ_BYTES, SEEK_END);
        }
        $content = stream_get_contents($handle);
        fclose($handle);

        $chunks = explode(Error_reporter::LOG_SEPARATOR . "\n", $content);
        if ($partial) {
            array_shift($chunks); // first chunk is cut in the middle
        }

        $entries = [];
        foreach ($chunks as $chunk) {
            $chunk = trim($chunk);
            if ($chunk === '' || !preg_match('/^\[([^\]]+)\]\s+(\S+)(?:\s+\((.*)\))?/', $chunk, $m)) {
                continue;
            }
            $entry = [
                'time'     => $m[1],
                'type'     => strtolower($m[2]),
                'severity' => isset($m[3]) ? $m[3] : '',
                'message'  => '',
                'url'      => '',
                'user'     => '',
                'raw'      => $chunk,
            ];
            if (preg_match('/^Message : (.*)$/m', $chunk, $mm)) {
                $entry['message'] = $mm[1];
            }
            if (preg_match('/^URL     : (.*)$/m', $chunk, $mm)) {
                $entry['url'] = $mm[1];
            }
            if (preg_match('/^User    : (.*)$/m', $chunk, $mm)) {
                $entry['user'] = $mm[1];
            }
            $entries[] = $entry;
        }
        return $entries;
    }
}
