<?php

defined('BASEPATH') or exit('No direct script access allowed');

function getDropDownStudentList($id = 0)
{
    $ci = &get_instance();
    $ci->db->select('id, title, fname, mname, lname, gmc_number, email');
    $ci->db->select('CONCAT("+", phone_code,phone) as phone_no');
    $ci->db->select('CONCAT("+", whatsapp_code,whatsapp) as whatsapp_no');
    $students = $ci->db->get('students')->result();
    $options  = '<option value="0">--Select Student--</option>';
    foreach ($students as $stu) {
        $full_name = ($stu->title) ? $stu->title . ' ' : '';
        $full_name .= ($stu->fname) ? $stu->fname . ' ' : '';
        $full_name .= ($stu->mname) ? $stu->mname . ' ' : '';
        $full_name .= ($stu->lname) ?: '';

        $options .= '<option value="' . $stu->id . '" ';
        $options .= ($stu->id == $id) ? 'selected="selected"' : '';
        $options .= ">{$full_name}, {$stu->email}, {$stu->gmc_number}, Ph: {$stu->phone_no}, Wa: {$stu->whatsapp_no}</option>";
    }
    return $options;
}

function getDropDownUserList4Std($id = 0, $label = '--Select--')
{
    $ci = &get_instance();

    $student_id = (int)getLoginStudentData('student_id');

    $ci->db->select('u.id, CONCAT(u.first_name," ", u.last_name) as full_name');
    $ci->db->select('r.role_name');
//    $ci->db->where_not_in('u.role_id', [1]);
//    $ci->db->where('u.role_id', 3);
    $ci->db->from('users as u');
    $ci->db->join('roles as r', 'u.role_id = r.id', 'LEFT');

    //SELECT user_id FROM `user_students_relation` WHERE student_id = 2
    $ci->db->join('user_students_relation as usr', 'usr.user_id = u.id', 'LEFT');
    $ci->db->where('usr.student_id', $student_id);
    $users = $ci->db->get()->result();

    $options = "<option value='0'>{$label}</option>";
    $options .= "<option value='2'>To Admin</option>";
    foreach ($users as $user) {
        $options .= '<option value="' . $user->id . '" ';
        $options .= ($user->id == $id) ? 'selected="selected"' : '';
        $options .= ">{$user->full_name} - {$user->role_name}</option>";
    }
    return $options;
}

function getDropDownUserList($id = 0, $label = '--Select Teacher--')
{
    $ci = &get_instance();

    $ci->db->select('u.id, CONCAT(u.first_name," ", u.last_name) as full_name');
    $ci->db->select('r.role_name');
    $ci->db->where_not_in('u.role_id', [1, 7]);
//    $ci->db->where('u.role_id', 2);
    $ci->db->from('users as u');
    $ci->db->join('roles as r', 'u.role_id = r.id', 'LEFT');
    $users = $ci->db->get()->result();

    $options = "<option value='0'>{$label}</option>";
    foreach ($users as $user) {
        $options .= '<option value="' . $user->id . '" ';
        $options .= ($user->id == $id) ? 'selected="selected"' : '';
        $options .= ">{$user->full_name} - {$user->role_name}</option>";
    }
    return $options;
}

function countConversation($id)
{
    $ci = &get_instance();
    $ci->db->where('parent_id', $id);
    return $ci->db->count_all_results('messages') + 1;
}

function lastReplyStatus($id)
{
    $ci = &get_instance();
    $ci->db->select('status');
    $ci->db->where('parent_id', $id);
    $reply = $ci->db->get('messages')->row();
    return ($reply) ? $reply->status : 'Seen';
}