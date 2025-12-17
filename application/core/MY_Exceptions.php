<?php

class MY_Exceptions extends CI_Exceptions {

    function __construct()
    {
        parent::__construct();
    }

    function log_exception($severity, $message, $filepath, $line)

    {   
        if (ENVIRONMENT === 'production') {
            $ci =& get_instance();

            $ci->load->library('email');
            $ci->email->from('no-reply@samsoncourses.com', 'Error No-Reply');
            $ci->email->to('flickmedialtd@gmail.com');
//            $ci->email->cc('another@another-example.com');
//            $ci->email->bcc('them@their-example.com');
            $ci->email->subject('Error on Samson\'s PRAP CI App');
            $ci->email->message('Severity: '.$severity.'  --> '.$message. ' '.$filepath.' '.$line);
            $ci->email->send();
        }


        parent::log_exception($severity, $message, $filepath, $line);
    }

}