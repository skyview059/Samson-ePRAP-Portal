<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function doctorTabs($id, $active_tab)
{
    $html = '<ul class="tabsmenu">';
    $tabs = [
        'timeline' => 'Timeline',
        'update' => 'Update',
        'delete' => 'Delete',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "doctor/{$link}/{$id}\"";
        $html .= ($link == $active_tab ) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function recruitmentPosts($selected = ''){
   
    $ci = & get_instance();
    $posts = $ci->db->group_by('post')->get('recruitment_posts')->result();
    $row = '';
    foreach ($posts as $key => $val) {
        $row .= '<option value="' .$val->post . '"';
        $row .= ($selected == $val->id) ? ' selected' : '';
        $row .= '>' . $val->post . '</option>';
    }
    return $row;
}

function getDPRecruitmentManager($id=0, $label = '--Select Manager--') {
    $ci = & get_instance();

    $ci->db->select('u.id, CONCAT(u.first_name," ", u.last_name) as full_name');    
    $ci->db->where('u.role_id', 7 );
    $ci->db->from('users as u');    
    $users = $ci->db->get()->result();

    $options = "<option value='0'>{$label}</option>";
    foreach ($users as $user) {
        $options .= '<option value="' . $user->id . '" ';
        $options .= ($user->id == $id ) ? 'selected="selected"' : '';
        $options .= ">{$user->full_name}</option>";
    }
    return $options;
}


function getSpecialtySearch($string = '' ) {
    $ci = & get_instance();
       
    $selected = is_array($string) ? $string : explode(',', $string );
    $ci->db->select('id,name');
    $ci->db->order_by('name', 'ASC');
    $results = $ci->db->get('student_job_specialties')->result();
    
    $options = '<option value="0">Any</option>';    
    foreach ($results as $r) {                
        $options .= "<option value=\"{$r->id}\"";
        $options .= in_array($r->id, $selected) ? ' selected="selected"' : '';
        $options .= ">{$r->name}";
        $options .= '</option>';
    }
    $options .= '</table>';
    return $options;
}

function SpecialtyDuration( $start = 0, $stop = 12, $incr = 1, $sel = 0, $lbl = 'Months'){
    $option = '';
    for ($start; $start <= $stop; $start += $incr) {
        $option .= '<option value="' . sprintf('%02d', $start ) . '"';
        $option .= ( $sel == sprintf('%02d', $start )) ? ' selected' : '';
        $option .= '>';
        if($lbl == 'Months'){
            $option .=  sprintf('%02d', $start ) .' '. $lbl;
        } else {
            $option .=  sprintf('%0.1f', ($start / 12) ) .' '. $lbl;
//            $option .=  sprintf('%02d', ($start / 12) ) .' '. $lbl;
        }
        
        $option .= '</option>';
    }
    return $option;
}

function getStudentLastLogin( $student_id ){
    $ci = & get_instance();
    $ci->db->select('login_time');
    $ci->db->order_by('id', 'DESC');
    $ci->db->where('student_id', $student_id );
    $log = $ci->db->get('student_logs')->row();
    if($log){        
        return globalDateTimeFormat( $log->login_time ) . dayCount( $log->login_time );
    }
}

function dayCount($timestamp = null)
{
    $start_date = new DateTime($timestamp);
    $end_date   = new DateTime(date('Y-m-d H:i:s'));
    $diff       = $start_date->diff($end_date);
               
    $days   = $diff->days;
    $hours  = $diff->h;
    
    if($days >= 31 ){
        return FALSE;
    }    
    if($days >= 1 ){
        $str = "{$days} days ago";
    }    
    if($days == 0 && $hours >= 1 ){
        $str = "{$hours} hours ago";
    }
    if($days == 0 && $hours == 0 ){
        $str = "{$diff->i} min ago";
    }
    return " [{$str}]";
}
