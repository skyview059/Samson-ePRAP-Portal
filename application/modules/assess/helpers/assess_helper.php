<?php defined('BASEPATH') OR exit('No direct script access allowed');

function assessTabs($id, $active_tab) {
	$html = '<ul class="tabsmenu">';
	$tabs = [
                'read'   => 'Details',
                'update' => 'Update',
                'delete' => 'Delete',
            ];

	foreach ($tabs as $link=>$tab) {
		$html .= '<li><a href="' . Backend_URL ."assess/{$link}/{$id}\"";
		$html .= ($link == $active_tab ) ? ' class="active"' : '';
		$html .= '>'. $tab . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}

function getTodayExamScheduleDropDownByTeacher($selected = 0, $admin = false )
{
    $ci = &get_instance();
    $ci->db->select('sr.exam_schedule_id, es.datetime, es.exam_id, e.name,c.name as centre');
    $ci->db->from('scenario_relations as sr');
    $ci->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id=sr.id', 'left');
    $ci->db->join('exam_schedules as es', 'es.id=sr.exam_schedule_id', 'left');
    $ci->db->join('exams as e', 'e.id=es.exam_id', 'left');
    $ci->db->join('exam_centres as c', 'c.id=es.exam_centre_id', 'left');
    
    if($admin == false ){
        $ci->db->where('sta.assessor_id', getLoginUserData('user_id'));
        $ci->db->where('DATE(es.datetime)', date('Y-m-d') );
    }
    $ci->db->where('es.exam_status', 'Active');
    $ci->db->where('es.status', 'Unpublished');    
    $ci->db->group_by('sr.exam_schedule_id');
    $ci->db->order_by('c.id', 'ASC');
    
    $schedules = $ci->db->get()->result();
    $options = '';
    if(!$schedules){
        return '<option value="0">-No Exam Found!-</option>';
    }
    
    
    foreach ($schedules as $s ) {
        $time = globalDateTimeFormat($s->datetime);
        $options .= '<option value="' . $s->exam_schedule_id . '" ';
        $options .= ($s->exam_schedule_id == $selected) ? 'selected="selected"' : '';
        $options .= ">{$s->centre} > {$s->name} ({$time})</option>";
    }
    return $options;
}