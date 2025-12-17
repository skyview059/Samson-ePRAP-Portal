<?php

/**
 * Description of SMS, for BulkSms.com
 * 
 * @author Khairul Azam
 * Date: 28th Sep 2020
 */
class Sms {
    //put your code here
    static protected $api_url    = 'https://api.bulksms.com/v1/messages?auto-unicode=true&longMessageMaxParts=30';
    static protected $api_user   = 'samsoncourses';
    static protected $api_pass   = 'Flick@2020';
    
    public static function OTP($digits = 4){
        return str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
    }

    public static function template( $OTP ){        
        $ci =& get_instance();
        $ci->db->select('template');
        $ci->db->where('slug','OTP');
        $result = $ci->db->get('email_templates')->row();
        if($result){
            return str_replace('%OTP%', $OTP, strip_tags_fk($result->template) );
        } else {
            return "Hi, {$OTP} is your login verification code.";
        }
    }

    static public function send($to,$msg){
        $post_sms = json_encode([['from' => 'ePRAP', 'to' => $to, 'body' => $msg]]);
       
        $ch     = curl_init();
        $headers = array(
            'Content-Type:application/json',
            'Authorization:Basic ' . base64_encode(self::$api_user . ':' . self::$api_pass )
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, self::$api_url );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_sms );
        // Allow cUrl functions 20 seconds to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // Wait 10 seconds while trying to connect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $output = array();
        $output['respond']  = curl_exec($ch);
        $curl_info          = curl_getinfo($ch);
        $output['http_status'] = $curl_info['http_code'];
        $output['error']    = curl_error($ch);
        curl_close($ch);
        
        self::saveDbLog($to,$msg,$output);
        self::saveTxtLog($output);
        return $output;
    }

    private static function saveDbLog($to,$msg,$output){
        $ci =& get_instance();        
        $data = [
            'phone'     => $to,
            'body'      => $msg,
            'type'      => self::isUnicode($msg),
            'qty'       => self::qty($msg),
            'respond'   => json_encode($output['respond']),
            'status'    => $output['http_status'],
            'timestamp' => date('Y-m-d H:i:s')
        ];        
        $ci->db->insert('sms_log',$data);
    }
    
    private static function saveTxtLog($respond) {
        $log_path = APPPATH . 'logs/sms_log.txt';
        $mail_log = date('Y-m-d H:i:s A') . ' | ' . json_encode($respond) . "\r\n";
        file_put_contents($log_path, $mail_log, FILE_APPEND);
    }
    
    private static function isUnicode($string){
        $str_length = mb_convert_encoding($string, 'UTF-8');

        if (strlen($string) != $str_length ){
            return 'UNICODE';
        } else {
            return 'TEXT';
        }
    }

    private static function qty($string){
        $str_length = mb_convert_encoding($string, 'UTF-8');
        if (strlen($string) != $str_length ){
            return ceil(mb_strlen( $string ) / 70);
        } else {
            return ceil(mb_strlen( $string ) / 160);
        }
    }
}
