<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Online_mock_model extends Fm_model
{
    public $table = 'exam_schedules';
    public $id = 'id';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id) {
        $this->db->select('e.*');        
        $this->db->select('cn.name as centre_name, cn.address as centre_address');        
        $this->db->select('c.name as course_name, c.id as exam_id');        
        $this->db->from('exam_schedules as e');
        $this->db->join('exams as c', 'e.exam_id=c.id', 'LEFT');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        $this->db->where('e.id', $id);
        $this->db->where('e.type', 'Online');
        return $this->db->get()->row();
    }

    function total_rows($id, $q = NULL)
    {
        $this->__search($id,$q);
        $this->db->from('exam_schedules as e');
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $id=1,$tab='coming', $q = NULL)
    {
        $this->db->select('e.*, cn.name as centre');
        $this->db->select('c.name as category_name');
        $this->db->from('exam_schedules as e');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        
        $this->__search($id,$tab,$q);
        if($tab =='coming'){
            $this->db->order_by('datetime', 'ASC');
        } elseif($tab == 'Canceled'){
            $this->db->order_by('datetime', 'DESC');
        } else {
            $this->db->order_by('datetime', 'DESC');
        }
        
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function __search($id,$tab,$q = NULL)
    {       
        $this->db->where('e.exam_id', $id);
        $this->db->where('e.type', 'Online');

        if($tab =='coming'){
            $this->db->where('e.datetime >=', date('Y-m-d 00:00:00'));             
            $this->db->where('e.exam_status', 'Active');            
            
        } elseif( $tab == 'Canceled'){            
            $this->db->where('e.exam_status', 'Canceled');
            
        } else {
            $this->db->where('e.datetime <=', date('Y-m-d 00:00:00'));              
            $this->db->where('e.exam_status', 'Active');
        }
//        if ($q) {
//            $this->db->group_start();            
//            $this->db->like('c.name', $q);
//            $this->db->group_end();
//        }               
        $this->db->join('exams as c', 'e.exam_id=c.id', 'LEFT');
    }  

    public function get_students($exam_schedule_id){
        $this->db->select('s.*, se.id as student_exam_id, se.created_at as assign_at, se.status as exam_status, se.remarks, r.id as attendance');
        $this->db->from('student_exams as se');
        $this->db->join('students as s', 's.id=se.student_id ', 'LEFT');
        $this->db->join('results as r', 'r.student_id=s.id and r.exam_schedule_id=se.exam_schedule_id', 'LEFT');
        $this->db->where('se.status', 'Enrolled');
        $this->db->where('se.exam_schedule_id', $exam_schedule_id);
        return $this->db->get()->result();
    }
    
    public function get_scenarios($exam_schedule_id){
        $this->db->select('r.id, r.reading_time, r.practice_time');
        $this->db->select('s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        $this->db->from('scenario_relations as r');
        $this->db->join('scenarios as s', 'r.scenario_id = s.id', 'LEFT');
        $this->db->order_by('s.reference_number', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_assigned_assessors($scenario_rel_id){
        $this->db->select('r.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->select('sta.assessor_id, u.first_name, u.last_name, u.email');
        $this->db->from('scenario_relations as r');
        $this->db->join('scenarios as s', 'r.scenario_id = s.id', 'LEFT');
        $this->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id = r.id', 'LEFT');
        $this->db->join('users as u', 'u.id = sta.assessor_id', 'LEFT');
        $this->db->where('sta.scenario_rel_id', $scenario_rel_id);
        return $this->db->get()->result();
    }
    
    public function get_assessor_scenario_info($scenario_rel_id, $assessor_id){
        $this->db->select('r.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->select('sta.assessor_id, u.first_name, u.last_name, u.email');
        $this->db->from('scenario_relations as r');
        $this->db->join('scenarios as s', 'r.scenario_id = s.id', 'LEFT');
        $this->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id = r.id', 'LEFT');
        $this->db->join('users as u', 'u.id = sta.assessor_id', 'LEFT');
        $this->db->where('sta.scenario_rel_id', $scenario_rel_id);
        $this->db->where('sta.assessor_id', $assessor_id);
        return $this->db->get()->row();
    }
    
    function marked($id)
    {
        $this->db->select('assessor_id');
        $this->db->where('scenario_rel_id', $id );
        $marks    = $this->db->get('scenario_to_assessors')->result();
        $selected = [2];
        foreach ($marks as $mark){
            $selected[] = $mark->assessor_id;
        }
        return $selected;
    }
    
    public function get_student_exam_by_id($student_exam_id){
        $this->db->select('s.*,se.id as student_exam_id, se.status, se.remarks, se.created_at as assign_at'); 
        $this->db->select('es.datetime, e.name as exam_name');
        $this->db->from('student_exams as se');
        $this->db->join('students as s', 's.id=se.student_id ', 'LEFT');
        $this->db->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'LEFT');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'LEFT');
        $this->db->where('se.id', $student_exam_id);
        return $this->db->get()->row();
    }

    public function get_assigned_assessor_by_exam($exam_schedule_id){
        $this->db->select('sta.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->select('sta.assessor_id, u.first_name, u.last_name, u.email');
        $this->db->from('scenario_to_assessors as sta');
        $this->db->join('scenario_relations as sr', 'sr.id = sta.scenario_rel_id', 'LEFT');
        $this->db->join('scenarios as s', 'sr.scenario_id = s.id', 'LEFT');
        $this->db->join('users as u', 'u.id = sta.assessor_id', 'LEFT');
        $this->db->where('sr.exam_schedule_id', $exam_schedule_id);
        $this->db->group_by('sta.assessor_id');
        return $this->db->get()->result();
    }
    
    public function get_admin_users() {
        $this->db->from('users as u');
        $this->db->where('u.role_id', '2');
        return $this->db->get()->result();
    }
    
    function qty( $tab = 'past', $exam_id = 1 )
    {
        if($tab =='coming'){
            $this->db->where('datetime >=', date('Y-m-d 00:00:00')); 
            $this->db->where('exam_status', 'Active');            
            
        } elseif( $tab == 'Canceled'){            
            $this->db->where('exam_status', 'Canceled');
            
        } else {
            $this->db->where('datetime <=', date('Y-m-d 00:00:00'));              
            $this->db->where('exam_status', 'Active');
        }
        
        $this->db->where('exam_id', $exam_id );
        $this->db->where('type', 'Online');
        return $this->db->count_all_results('exam_schedules');        
    }
}