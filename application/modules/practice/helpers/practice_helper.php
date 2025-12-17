<?php defined('BASEPATH') OR exit('No direct script access allowed');

function practiceTabs($id, $active_tab)
{    
    $students = countPracticeStudent($id);
    
    $html = '<ul class="nav nav-tabs admintab hide_on_print">';
    $tabs = [                
        'student' => "<i class=\"fa fa-users\"></i> Students <sup class='large'>({$students})</sup>",
        'update' => ' <i class="fa fa-edit"></i> Update',
        'delete' => '<i class="fa fa-trash-o"></i> Delete',
        'cancel' => '<i class="fa fa-close"></i> Cancel',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "practice/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function countPracticeStudent($practice_schedule_id = 0) {    
    $ci = & get_instance();
    $ci->db->where('status','Enrolled');
    return$ci->db->where('practice_schedule_id', $practice_schedule_id)->count_all_results('practice_students');
}

function getPracticeName($id = 0) {
    $ci = & get_instance();
    $practice = $ci->db->where('id', $id)->get('practices')->row();
    if($practice){
        return $practice->name;
    } else {
        return "Unknown";
    }
}
function getPracticeDropDown($selected = 0)
{
    $ci = &get_instance();    
    $practices = $ci->db->get('practices')->result();
    $options = '';
    foreach ($practices as $p) {
        $options .= '<option value="' . $p->id . '" ';
        $options .= ($p->id == $selected) ? 'selected="selected"' : '';
        $options .= '>';
        $options .= ($p->name) ? $p->name : '--';
        $options .= '</option>';
    }
    return $options;

}

function getPracticeNameDropDownByCentre($centre_id = 0, $selected = 0, $adv = false )
{
    $ci = &get_instance();
    $ci->db->select('e.name, s.id, s.label, s.practice_id, s.exam_centre_id, s.datetime');
    $ci->db->from('practice_schedules as s');
    $ci->db->join('exams as e', 'e.id=s.practice_id', 'LEFT');
    $ci->db->where('exam_centre_id', $centre_id);
    $ci->db->where('practice_status', 'Active');        
    $ci->db->where('status', 'Unpublished');
    if($adv){
        $ci->db->where('datetime <=', date('Y-m-d 23:59:59')); 
        $ci->db->order_by('datetime', 'DESC');
    }    
    $practices = $ci->db->get()->result();
   
    $options = '<option value="0">-- Select Practice Name --</option>';
    foreach ($practices as $practice){
        $time = globalDateTimeFormat($practice->datetime);
        $options .= '<option value="'. $practice->id .'" ';
        $options .= ($practice->id == $selected) ? 'selected="selected"' : '';
        $options .= ">{$practice->name}, {$practice->label} ({$time})";
        $options .= '</option>';
    }
    return $options;
}

function practiceListTab($active = 'coming', $coming = 0, $past = 0)
{    
    $ci =& get_instance();
    $id = $ci->input->get('id');
    $html = '<ul class="hide_on_print nav nav-tabs admintab">';
    $tabs = [
        'coming' => "<i class=\"fa fa-clock-o\"></i> Upcoming Practice <sup class='large'>({$coming})</sup>",
        'past' => "<i class=\"fa fa-undo\"></i> Past Practice <sup class='large'>({$past})</sup>",        
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "practice?id={$id}&tab={$link}\"";
        $html .= ($link == $active) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}