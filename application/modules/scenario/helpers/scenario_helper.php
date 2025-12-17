<?php

defined('BASEPATH') or exit('No direct script access allowed');

function scenarioTabs($id, $active_tab)
{
    $html = '<ul class="nav nav-tabs admintab">';
    $tabs = [
        'read'   => 'Details',
        'update' => 'Update',
        'delete' => 'Delete',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "scenario/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function scenarioDelBtn($id, $used)
{
    if ($used) {
        return '<span class="btn btn-xs btn-danger disabled"> &nbsp;<i class="fa fa-lock"></i> &nbsp;</span>';
    } else {
        return '<span onclick="return delScen(' . $id . ');" class="btn btn-xs btn-danger"> &nbsp;<i class="fa fa-times"></i>&nbsp; </span>';
    }
}

function getScenarioGroupName($id)
{
    $ci  = &get_instance();
    $row = $ci->db->where('id', $id)->get('scenario_topics')->row();
    if ($row) {
        return $row->name;
    } else {
        return "Unknown";
    }
}

function getScenarioName($id)
{
    $ci  = &get_instance();
    $row = $ci->db->where('id', $id)->get('scenarios')->row();
    if ($row) {
        return $row->name;
    } else {
        return "Unknown";
    }
}

function scenarioStatus($id, $status = null, $practice_id = 0): string
{
    if ($status == 'Complete' && $practice_id) {
        $js_btn        = "onclick=\"setStatus({$id},'Incomplete');\"";
        $viewResultBtn = '<a href="' . site_url('scenario-practice/practice/summary/' . $practice_id) . '" class="btn btn-xs btn-link view_result">View Result</a>';
        $btn           = $viewResultBtn . '<span class="status_completed">Completed</span> <span ' . $js_btn . ' class="btn btn-xs btn-link undo_btn"><i class="fa fa-undo"></i> Undo</span>';
    } else {
        $js_btn = "onclick=\"setStatus({$id},'Complete');\"";
        $btn = '<span ' . $js_btn . ' class="btn btn-xs btn-link"><i class="fa fa-check-square-o"></i> Mark as Complete</span>';
    }
    return $btn;
}

function getScenarioSubjectsDropDown($exam_id, $selected = null, $label = 'Select Subject')
{
    $ci = &get_instance();
    if (!$exam_id) {
        return '<option value="">Select Exam First</option>';
    }
    $subjects = $ci->db->where('exam_id', $exam_id)->get('scenario_subjects')->result();
    $html     = '<option value="">' . $label . '</option>';
    foreach ($subjects as $subject) {
        $html .= '<option value="' . $subject->id . '"';
        $html .= ($selected == $subject->id) ? ' selected' : '';
        $html .= '>' . $subject->name . '</option>';
    }
    return $html;
}

function getScenarioTopicsDropDown($exam_id, $subject_id, $selected = null, $label = 'Select Topic')
{
    $ci = &get_instance();
    if (!$exam_id) {
        return '<option value="">Select Exam First</option>';
    }
    if (!$subject_id) {
        return '<option value="">Select Subject First</option>';
    }

    $ci->db->where('exam_id', $exam_id);
    $ci->db->where('subject_id', $subject_id);
    $topics = $ci->db->get('scenario_topics')->result();

    $html = '<option value="">' . $label . '</option>';
    foreach ($topics as $topic) {
        $html .= '<option value="' . $topic->id . '"';
        $html .= ($selected == $topic->id) ? ' selected' : '';
        $html .= '>' . $topic->name . '</option>';
    }
    return $html;
}

function getScenarioTopicItemsDropDownMultiple($exam_id, $subject_id, $topic_id, $selected = [], $label = 'Select Scenario')
{
    $ci = &get_instance();
    if (!$exam_id) {
        return '<option value="">Select Exam First</option>';
    }
    if (!$subject_id) {
        return '<option value="">Select Subject First</option>';
    }
    if (!$topic_id) {
        return '<option value="">Select Topic First</option>';
    }

    $ci->db->select('sti.*, s.name as scenario_name, s.presentation');
    $ci->db->where('sti.exam_id', $exam_id);
    $ci->db->where('sti.subject_id', $subject_id);
    $ci->db->where('sti.scenario_topic_id', $topic_id);
    $ci->db->from('scenario_topics_items as sti');
    $ci->db->join('scenarios as s', 's.id = sti.scenario_id', 'left');
    $topics = $ci->db->get()->result();

    if ($label == null) {
        $html = '';
    } else {
        $html = '<option value="">' . $label . '</option>';
    }
    foreach ($topics as $topic) {
        $html .= '<option value="' . $topic->id . '"';
        $html .= in_array($topic->id, explode(',', $selected)) ? ' selected' : '';
        $html .= '>' . $topic->presentation . '</option>';
    }
    return $html;
}

function getDateTimeColour($start_date_time)
{
    $date_time = strtotime($start_date_time);
    $now       = time();
    $diff      = $date_time - $now;
    if ($diff < 0) {
        return '<strong style="color: red">' . globalDateTimeFormat($start_date_time) . '</strong>';
    } elseif ($diff < (86400 * 2)) {
        return '<strong style="color: #ed9273;">' . globalDateTimeFormat($start_date_time) . '</strong>';
    } else {
        return '<strong style="color: green;">' . globalDateTimeFormat($start_date_time) . '</strong>';
    }
}

function examStatus($status, $id)
{
    $html = '<div class="dropdown">';
    $html .= getExamStatus($status, $id);
    $html .= '<ul class="dropdown-menu">';
    $html .= "<li><a onclick=\"examStatusUpdate({$id}, 'Active');\"> <i class=\"fa fa-check\"></i> Active</a></li>";
    $html .= "<li><a onclick=\"examStatusUpdate({$id}, 'Inactive');\"> <i class=\"fa fa-ban\"></i> Inactive</a></li>";
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

function getExamStatus($status = 'Active', $id = 0, $action = true)
{
    switch ($status) {
        case 'Active':
            $class = 'btn-success';
            $icon  = '<i class="fa fa-check-square-o"></i> ';
            break;
        case 'Inactive':
            $class = 'btn-danger';
            $icon  = '<i class="fa fa-ban" ></i> ';
            break;
        default :
            $class = 'btn-default';
            $icon  = '<i class="fa fa-info"></i> ';
    }
    if ($action) {
        return '<button class="btn ' . $class . ' btn-xs" id="exam_status_' . $id . '" type="button" data-toggle="dropdown">' . $icon . $status . ' &nbsp; <i class="fa fa-angle-down"></i></button>';
    } else {
        return "<span class=\"btn {$class} btn-xs\" type=\"button\">{$icon} {$status}</span>";
    }
}