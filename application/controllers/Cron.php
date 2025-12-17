<?php

/**
 * @author Khairul Azam
 * Date: 26th June 2020
 */
class Cron extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $today = date('Y-m-d');
        $this->db->set('status', 'Inactive');
        $this->db->where('status', 'Active');
        $this->db->where('exam_date <=', $today);
        $this->db->update('students');
        
        $qty = $this->db->affected_rows();
        
        echo "{$qty} Student(s) Account Inactivated";
    }
    
    function test()
    {
        $this->load->library('Sms');
//        Sms::send('+8801713900423', 'Hello Kanny');
//        Sms::send('+8801713900423', 'শুভ সকাল');
    }
    
    function json(){
        $str = '{"server_response":"[ {\n  \"id\" : \"892738913280466944\",\n  \"type\" : \"SENT\",\n  \"from\" : \"BulksmsCoUk\",\n  \"to\" : \"8801713900423\",\n  \"body\" : \"Hello Kanny\",\n  \"encoding\" : \"TEXT\",\n  \"protocolId\" : 0,\n  \"messageClass\" : 0,\n  \"submission\" : {\n    \"id\" : \"2-00000000001756545360\",\n    \"date\" : \"2020-09-29T11:45:43Z\"\n  },\n  \"status\" : {\n    \"id\" : \"ACCEPTED.null\",\n    \"type\" : \"ACCEPTED\",\n    \"subtype\" : null\n  },\n  \"relatedSentMessageId\" : null,\n  \"userSuppliedId\" : null,\n  \"numberOfParts\" : null,\n  \"creditCost\" : null\n} ]","http_status":201,"error":""}';
        
        $str = '{"server_response":"[ {\n  \"id\" : \"892740555551809536\",\n  \"type\" : \"SENT\",\n  \"from\" : \"BulksmsCoUk\",\n  \"to\" : \"8801713900423\",\n  \"body\" : \"\u09b6\u09c1\u09ad \u09b8\u0995\u09be\u09b2\",\n  \"encoding\" : \"UNICODE\",\n  \"protocolId\" : 0,\n  \"messageClass\" : 0,\n  \"submission\" : {\n    \"id\" : \"2-00000000001756550731\",\n    \"date\" : \"2020-09-29T11:52:14Z\"\n  },\n  \"status\" : {\n    \"id\" : \"ACCEPTED.null\",\n    \"type\" : \"ACCEPTED\",\n    \"subtype\" : null\n  },\n  \"relatedSentMessageId\" : null,\n  \"userSuppliedId\" : null,\n  \"numberOfParts\" : null,\n  \"creditCost\" : null\n} ]","http_status":201,"error":""}';
        $array = json_decode($str, true);        
        dd( $array );
    }

}
