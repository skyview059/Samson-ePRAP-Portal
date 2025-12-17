<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getDropDownStudentList($id=0) {
    $ci = & get_instance();
    $ci->db->select('id, title, fname, mname, lname, gmc_number, email');
    $ci->db->select('CONCAT("+", phone_code,phone) as phone_no');
    $ci->db->select('CONCAT("+", whatsapp_code,whatsapp) as whatsapp_no');
    $ci->db->from('students');
    $ci->db->where('status', 'Active');
    $students = $ci->db->get()->result();
    $options = '<option value="0">--Select Student--</option>';
    foreach ($students as $stu) {
        $full_name = ($stu->title) ? $stu->title . ' ' : '';
        $full_name .= ($stu->fname) ? $stu->fname . ' ' : '';
        $full_name .= ($stu->mname) ? $stu->mname . ' ' : '';
        $full_name .= ($stu->lname) ?: '';

        $options .= '<option value="' . $stu->id . '" ';
        $options .= ($stu->id == $id ) ? 'selected="selected"' : '';
//        $options .= ">{$stu->full_name}, {$stu->email}, {$stu->gmc_number}, Ph: {$stu->phone_no}, Wa: {$stu->whatsapp_no}</option>";
        $options .= ">{$full_name}</option>";
    }
    return $options;
}


function countConversation( $id ){
    $ci = & get_instance();
    $ci->db->where('parent_id', $id );
    return $ci->db->count_all_results('student_messages') + 1;
}
function lastReplyStatus( $id ){
    $ci = & get_instance();
    $ci->db->select('status');
    $ci->db->where('parent_id', $id );
    $reply = $ci->db->get('student_messages')->row();
    return ($reply) ? $reply->status : 'Seen';    
}