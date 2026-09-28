<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/Error_reporter.php';

/**
 * With db_debug off (APP_DEBUG=false) a failed query never reaches the
 * error_db view - the DB driver only calls log_message(). Catch it here.
 */
class MY_Log extends CI_Log {

    public function write_log($level, $msg)
    {
        if (strtolower($level) === 'error'
            && (strpos($msg, 'Query error:') === 0 || strpos($msg, 'Unable to connect to the database') === 0)) {
            Error_reporter::report('db', [
                'heading'   => 'A Database Error Occurred',
                'message'   => $msg,
                'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
            ]);
        }

        return parent::write_log($level, $msg);
    }

}
