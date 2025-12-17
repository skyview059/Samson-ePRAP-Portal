<?php defined('BASEPATH') OR exit('No direct script access allowed');

function centreTabs($id, $active_tab)
{
    $html = '<ul class="nav nav-tabs admintab">';
    $tabs = [
        'preview' => '<i class="fa fa-bars"></i> Preview',
        'update' => '<i class="fa fa-edit"></i> Update',
        'marge' => '<i class="fa fa-random"></i> Marge'
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "centre/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '</ul>';
    return $html;
}

function ExamCenterDroDown($selected = 0, $exam_id = 0)
{
    $ci = &get_instance();
    if($exam_id){ $ci->db->where('exam_id', $exam_id ); }
    
    $ci->db->select('exam_centres.*, c.name as country');
    $ci->db->join('countries as c', 'exam_centres.country_id=c.id','LEFT');
    $centres = $ci->db->get('exam_centres')->result();
    $options = '';
    foreach ($centres as $centre) {
        $options .= '<option value="' . $centre->id . '" ';
        $options .= ($centre->id == $selected) ? 'selected="selected"' : '';
        $options .= '>';
        $options .= ($centre->name) ? $centre->name : '--';
        $options .= " , {$centre->country}";
        $options .= '</option>';
    }
    return $options;
}

function countExamSchedule($id = 0) {
    $ci = & get_instance();
    return $ci->db->where('exam_centre_id', $id)->count_all_results('exam_schedules');
}
function getExamCentreName($id = 0) {
    $ci = & get_instance();
    $exam = $ci->db->where('id', $id)->get('exam_centres')->row();
    if($exam){
        return $exam->name;
    } else {
        return "Unknown";
    }
}