<?php defined('BASEPATH') OR exit('No direct script access allowed');

function examTabs($id, $active_tab)
{
    $scenarios = countExamScenario($id);
    $students = countExamStudent($id);
    
    $html = '<ul class="nav nav-tabs admintab hide_on_print">';
    $tabs = [
        //'read' => '<i class="fa fa-bars"></i> Exam Details',
        
        'scenario' => "<i class=\"fa fa-book\"></i> Scenarios <sup class='large'>({$scenarios})</sup>",
        'student' => "<i class=\"fa fa-users\"></i> Students <sup class='large'>({$students})</sup>",
        'update' => ' <i class="fa fa-edit"></i> Update',
        'delete' => '<i class="fa fa-trash-o"></i> Delete',
        'cancel' => '<i class="fa fa-close"></i> Cancel/Rollback',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "exam/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function countExamScenario($exam_schedule_id = 0) {
    $ci = & get_instance();
    return $ci->db->where('exam_schedule_id', $exam_schedule_id)->count_all_results('scenario_relations');
}

function countExamStudent($exam_schedule_id = 0) {
    $ci = & get_instance();
    $ci->db->where('status','Enrolled');
    return$ci->db->where('exam_schedule_id', $exam_schedule_id)->count_all_results('student_exams');
}

function getExamName($id = 0) {
    $ci = & get_instance();
    $exam = $ci->db->select('id, name')->where('id', $id)->get('exams')->row();
    if($exam){
        return $exam->name;
    } else {
        return "Unknown";
    }
}

function getAssessorsName($id = 0) {
    $ci = & get_instance();
    
    $ci->db->select('u.first_name,u.last_name,r.role_name');
    $ci->db->from('scenario_to_assessors as as');
    $ci->db->join('users as u', 'u.id=as.assessor_id', 'LEFT');
    $ci->db->join('roles as r', 'r.id=u.role_id', 'LEFT');
    $assessors = $ci->db->where('scenario_rel_id', $id)->get()->result();
    if(!$assessors){        
        return "<span style='color:#999;'>Did not assign any assessor</span>";
    }
    
    $names = '';
    $names .= '<span style="color:#3c8dbc; font-weight:bold;">';
    $names .= '<i class="fa fa-user-md"></i>';
    
    foreach($assessors as $asses){
        $names .= " {$asses->first_name} {$asses->last_name} ({$asses->role_name}), ";  
    }    
    return rtrim_fk($names, ', ') . '</span>' ;
}

function getExamNameDropDownByCentre($centre_id = 0, $selected = 0, $adv = false )
{
    $ci = &get_instance();
    $ci->db->select('e.name, s.id, s.label, s.exam_id, s.exam_centre_id, s.datetime');
    $ci->db->from('exam_schedules as s');
    $ci->db->join('exams as e', 'e.id=s.exam_id', 'LEFT');
    $ci->db->where('exam_centre_id', $centre_id);
    $ci->db->where('exam_status', 'Active');
    $ci->db->where('s.status', 'Unpublished');
    if($adv){
        $ci->db->where('datetime <=', date('Y-m-d 23:59:59')); 
        $ci->db->order_by('datetime', 'DESC');
    }    
    $exams = $ci->db->get()->result();
   
    $options = '<option value="0">-- Select Exam Name --</option>';
    foreach ($exams as $exam){
        $time = globalDateTimeFormat($exam->datetime);
        $options .= '<option value="'. $exam->id .'" ';
        $options .= ($exam->id == $selected) ? 'selected="selected"' : '';
        $options .= ">{$exam->name}, {$exam->label} ({$time})";
        $options .= '</option>';
    }
    return $options;
}

function getExamCentreDropDown($selected = 0)
{
    $ci = &get_instance();
    $exams = $ci->db->get('exam_centres')->result();

    $options = '<option value="0">-- Select Centre Name --</option>';
    foreach ($exams as $exam){
        $options .= '<option value="'. $exam->id .'" ';
        $options .= ($exam->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $exam->name . '</option>';
    }
    return $options;
}

function getExamNameDropDown($selected = 0, $label = '-- Select Exam Name --')
{
    $ci = &get_instance();
    $exams = $ci->db->select('id, name')->get('exams')->result();

    $options = '<option value="">'.$label.'</option>';
    foreach ($exams as $exam){
        $options .= '<option value="'. $exam->id .'" ';
        $options .= ($exam->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $exam->name . '</option>';
    }
    return $options;
}

function ExamCourseDroDown($selected = 0, $label = '-- Select Exam --') {
    $ci = & get_instance();
    $categories = $ci->db->select('id, name')->get('exams')->result();
    $options = '<option value="0">'.$label.'</option>';
    foreach ($categories as $category) {
        $options .= '<option value="' . $category->id . '" ';
        $options .= ($category->id == $selected ) ? 'selected="selected"' : '';
        $options .= ">{$category->name}</option>";
    }
    return $options;
}

function examListTab($active = 'coming', $coming = 0, $past = 0, $cancelled = 0)
{    
    $ci =& get_instance();
    $id = $ci->input->get('id');
    $html = '<ul class="hide_on_print nav nav-tabs admintab">';
    $tabs = [
        'coming' => "<i class=\"fa fa-clock-o\"></i> Upcoming Mock <sup class='large'>({$coming})</sup>",
        'past' => "<i class=\"fa fa-undo\"></i> Past Mock <sup class='large'>({$past})</sup>",        
        'Canceled' => "<i class=\"fa fa-times\"></i> Canceled Mock <sup class='large'>({$cancelled})</sup>",        
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "exam?id={$id}&tab={$link}\"";
        $html .= ($link == $active) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}