<?php defined('BASEPATH') OR exit('No direct script access allowed');

function resultTabs($id, $active_tab) {
	$html = '<ul class="tabsmenu">';
	$tabs = [
                'read'   => 'Details',
                'update' => 'Update',
                'delete' => 'Delete',
            ];

	foreach ($tabs as $link=>$tab) {
		$html .= '<li><a href="' . Backend_URL ."assess/result/{$link}/{$id}\"";
		$html .= ($link == $active_tab ) ? ' class="active"' : '';
		$html .= '>'. $tab . '</a></li>';
	}
	$html .= '</ul>';
	return $html;
}

function getExamCentreDroDownByExam($exam_id, $selected = 0)
{
    $ci = &get_instance();
    $ci->db->where('exam_id', $exam_id);
    $centres = $ci->db->get('exam_centres')->result();
    
    $options = '<option value="0">-- Select Exam Centre --</option>';
    foreach ($centres as $centre) {
        $options .= '<option value="' . $centre->id . '" ';
        $options .= ($centre->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $centre->name . '</option>';
    }
    return $options;
}

function getStudentExamResultByScenario($exam_schedule_id, $scenario_id) {
    
    $ci = &get_instance();
    $ci->db->select('count(r.student_id) as total_student, rd.scenario_id, rd.pass_mark');
    $ci->db->from('result_details as rd');
    $ci->db->join('results as r', 'r.id=rd.result_id', 'left');
    $ci->db->where('r.exam_schedule_id',$exam_schedule_id);
    $ci->db->where('rd.scenario_id',$scenario_id);
    $ci->db->group_by('rd.scenario_id');
    return $ci->db->get()->row();
    
}

function getExamScheduleDropDownByExam($exam_id, $selected = 0, $publish = false )
{
    $ci = &get_instance();
    $ci->db->select('es.*, e.name');
    $ci->db->from('exam_schedules as es');
    $ci->db->join('exams as e', 'e.id=es.exam_id', 'left');
    $ci->db->where('es.exam_id', $exam_id);
    if($publish) { $ci->db->where('es.status', 'Published'); }
    $exam_schedules = $ci->db->get()->result();
    
    if(!$exam_schedules){
        return '<option value="0">Exam date & time not found!</option>';
    }
    
    $options = '<option value="0">-- Select Exam Date and Time  --</option>';
    foreach ($exam_schedules as $schedule) {
        $options .= '<option value="' . $schedule->id . '" ';
        $options .= ($schedule->id == $selected) ? 'selected="selected"' : '';
        $options .= '>' . $schedule->name .'('.globalDateTimeFormat($schedule->datetime). ')</option>';
    }
    return $options;
}

function getAverageBorderlineScoreByScenario($exam_schedule_id, $scenario_id) {
    
    $ci = &get_instance();
    $ci->db->select('count(r.student_id) as total_student, rd.scenario_id, rd.coefficient_mark, rd.pass_mark');
    $ci->db->select('TRUNCATE(sum(technical_skills+clinical_skills+interpersonal_skills),2) as t_borderline_score');
    $ci->db->select('TRUNCATE((sum(technical_skills+clinical_skills+interpersonal_skills)/count(r.student_id)), 2) as avg_borderline_score');
    $ci->db->from('result_details as rd');
    $ci->db->join('results as r', 'r.id=rd.result_id', 'left');
    $ci->db->where('r.exam_schedule_id', $exam_schedule_id);
    $ci->db->where('rd.scenario_id', $scenario_id);
    $ci->db->where('rd.overall_judgment', 'Borderline');
    $ci->db->group_by('rd.scenario_id');
    return $ci->db->get()->row();
    
}

function showCross( $int ){    
    if($int) {
       return '<img src="' . site_url('assets/theme/images/cross.png') . '">';
    }
}

function get_next($array, $key) {
    $currentKey = key($array);
    while ($currentKey !== null && $currentKey != $key) {
        next($array);
        $currentKey = key($array);
    }
    return next($array);
}

function getFace( $str = 'Smiley'){    
    switch ($str) {
        case "Smiley":
            $img = 
                '<img  src="assets/admin/icons/smiley-selected.png" alt="Smiley" height="25">
                <img class="drinkcard-cc" src="assets/admin/icons/neutral-selected.png" alt="Neutral" height="25">                                    
                <img class="drinkcard-cc" src="assets/admin/icons/sad-selected.png" alt="Sad" height="25">
                ';            
            break;
        case "No Emotions":
            $img = 
                '<img class="drinkcard-cc" src="assets/admin/icons/smiley-selected.png" alt="Smiley" height="25">
                <img src="assets/admin/icons/neutral-selected.png" alt="Neutral" height="25">                                    
                <img class="drinkcard-cc" src="assets/admin/icons/sad-selected.png" alt="Sad" height="25">
                '; 
            break;
        case "Very Sad":
            $img = 
                '<img class="drinkcard-cc" src="assets/admin/icons/smiley-selected.png" alt="Smiley" height="25">
                <img class="drinkcard-cc" src="assets/admin/icons/neutral-selected.png" alt="Neutral" height="25">                                    
                <img src="assets/admin/icons/sad-selected.png" alt="Sad" height="25">
                '; 
            break;
        default :
            $img = 
                '<img class="drinkcard-cc" src="assets/admin/icons/smiley-selected.png" alt="Smiley" height="25">
                <img class="drinkcard-cc" src="assets/admin/icons/neutral-selected.png" alt="Neutral" height="25">                                    
                <img class="drinkcard-cc" src="assets/admin/icons/sad-selected.png" alt="Sad" height="25">
                '; 
            break;
    }
    return $img;
}