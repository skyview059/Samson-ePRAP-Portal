<?php defined('BASEPATH') OR exit('No direct script access allowed');

function getByExam() {
    $ci =& get_instance();
    
    $sql = 'SELECT count(*) as qty FROM `student_exams` as se 
            INNER JOIN exam_schedules as es on es.id=se.exam_schedule_id
            WHERE es.exam_id = exams.id';
    
    $ci->db->select("({$sql}) as qty");     
    $ci->db->select('id,name');
    $exams = $ci->db->get('exams')->result();
        
    $html = '<option value="0">--All--</option>';
    foreach ($exams as $e) {        
        $html .= "<option value=\"{$e->id}\"";
        $html .= ">{$e->name}  ({$e->qty} Students)</option>";
    }
    return $html;
}
function getByCentre() {
    $ci =& get_instance();
    
    $sql = 'SELECT count(*) as qty FROM `students` WHERE exam_centre_id = exam_centres.id';    
    $ci->db->select("({$sql}) as qty");     
    $ci->db->select('id,name');
    $ci->db->where('name !=', '');
    $centers = $ci->db->get('exam_centres')->result();
        
    $html = '<option value="0">--All--</option>';
    foreach ($centers as $c) {        
        $html .= "<option value=\"{$c->id}\"";
        $html .= ">{$c->name}  ({$c->qty} Students)</option>";
    }
    return $html;
}

function getJobSpecialties() {
    $ci =& get_instance();
    
    $ci->db->select('id,name');
    $ci->db->order_by('name', 'ASC');
    $job_specialties = $ci->db->get('student_job_specialties')->result();
        
    $html = '<div id="specialty_ids">';
    foreach ($job_specialties as $s) {  
        $html .= '<label>';
        $html .= "<input name='job_specialty_ids[]' value='{$s->id}' class='specialty_ids' type='checkbox' >";
        $html .= " {$s->name}</label><br/>";
    }
    $html .= '</div>';
    return $html;
}

function send_type( $mock_exam_id = 0 ) {
    
    $last_30    = countStudent( date('Y-m-d', strtotime('-30 Days')), $mock_exam_id );
    $last_90    = countStudent( date('Y-m-d', strtotime('-90 Days')), $mock_exam_id );
    $total      = countStudent('All', $mock_exam_id );
          
    $types = [
        '30_days'           => "Last 30 Days ({$last_30} students)",
        '90_days'           => "Last 90 Days ({$last_90} students)",
        'all_student'       => "All Student ({$total} students)",
        'custom_dates'      => 'Custom Dates',        
        'custom_student'    => 'Custom Students',
    ];
    $html = '';
    foreach ($types as $key => $type) {
        $html .= "<option value='{$key}'";
        $html .= ">{$type}</option>";
    }
    return $html;
}

function countStudent($date_from = 'All', $exam_id = 0 ) {
    $ci =& get_instance();    
    $ci->db->from('students');           
    if($exam_id){ $ci->db->where('exam_id', $exam_id ); }    
    if($date_from != 'All'){ 
        $ci->db->where('created_at >=', $date_from );
    }
    return $ci->db->count_all_results();
}