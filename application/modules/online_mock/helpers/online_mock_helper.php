<?php defined('BASEPATH') OR exit('No direct script access allowed');

function onlineMockTabs($id, $active_tab)
{
    $scenarios = countOnlineMockScenario($id);
    $students = countOnlineMockStudent($id);
    
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
        $html .= '<li><a href="' . Backend_URL . "online_mock/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function countOnlineMockScenario($exam_schedule_id = 0) {
    $ci = & get_instance();
    return $ci->db->where('exam_schedule_id', $exam_schedule_id)->count_all_results('scenario_relations');
}

function countOnlineMockStudent($exam_schedule_id = 0) {
    $ci = & get_instance();
    $ci->db->where('status','Enrolled');
    return$ci->db->where('exam_schedule_id', $exam_schedule_id)->count_all_results('student_exams');
}

function onlineMockListTab($active = 'coming', $coming = 0, $past = 0, $cancelled = 0)
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
        $html .= '<li><a href="' . Backend_URL . "online_mock?id={$id}&tab={$link}\"";
        $html .= ($link == $active) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}