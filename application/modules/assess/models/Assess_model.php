<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class Assess_model extends Fm_model{

    public $table = 'results';
    public $id = 'id';
    public $order = 'DESC';

    function __construct(){
        parent::__construct();
    }    
    
    // get total rows
    function total_rows($q = NULL)
    {

        if ($q) { $this->db->like('id', $q);}
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        if ($q) { $this->db->like('id', $q);}
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    function searchStudent($number_type, $gmc = NULL)
    {        
        $this->db->order_by($this->id, $this->order);
        $this->db->where('number_type', $number_type);
        $this->db->where('gmc_number', $gmc);
        return $this->db->get('students')->row();
    }
    
    //Get exam information by student and current user id
    function getExamScheduleInfoByStudent($exam_schedule_id, $student_id) {
       
        $this->db->select('sr.exam_schedule_id, count(sr.scenario_id) as total_questions, group_concat(sr.scenario_id) AS scenario_ids');
        $this->db->from('scenario_relations as sr');
        $this->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id=sr.id', 'left');
        $this->db->where('sta.assessor_id', $this->user_id);
        $this->db->group_by('sr.exam_schedule_id');
        $exam_to_scenario_sql = $this->db->get_compiled_select();
        
        $this->db->select('es.id, se.student_id, es.exam_id, e.name as exam_name, es.datetime, ec.name as center_name, ec.address as center_address');
        $this->db->select('ets.total_questions, ets.scenario_ids');
        $this->db->from('exam_schedules as es');
        $this->db->join('student_exams as se', 'se.exam_schedule_id=es.id and se.student_id="'.$student_id.'" and se.status="Enrolled"', 'inner');
        $this->db->join("($exam_to_scenario_sql) as ets", 'ets.exam_schedule_id=es.id', 'inner');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left');
        $this->db->where('DATE(es.datetime)', "'".date('Y-m-d')."'", FALSE);
        $this->db->where('es.id', $exam_schedule_id);
        $exam_info = $this->db->get()->row();
    
        if($exam_info){
            $this->db->select('id,reference_number,exam_id,name,created_at,updated_at');
            $this->db->from('scenarios');
            $this->db->where_in('id', explode(',', $exam_info->scenario_ids));
            $this->db->order_by('reference_number');
            $scenarios = $this->db->get()->result();
            $exam_info->scenarios = $scenarios;
        }
        
        return $exam_info;
        
    }
    
    function insertResult($data) {
        if ($this->db->insert('results', $data)) {
                return $this->db->insert_id();
        }
        return false;
    }
    
    function insertResultDetails($data) {
        if ($this->db->insert('result_details', $data)) {
                return $this->db->insert_id();
        }
        return false;
    }
    
    function updateResultDetails($id, $saveFields) {
        $this->db->where('id', $id);
        if($this->db->update('result_details', $saveFields)){
                return true;
        }
        return false;
    }
    
    function getExamResultByStudent($student_id, $exam_schedule_id) {
        $this->db->select('r.*');
        $this->db->from('results as r');
        $this->db->where('r.student_id', $student_id);
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        return $this->db->get()->row();
    }
    
    function getDuplicateCheckExamResultData($student_id, $exam_schedule_id, $scenario_id) {
        $this->db->select('rd.*, r.student_id, r.exam_schedule_id');
        $this->db->from('result_details as rd');
        $this->db->join('results as r', 'r.id=rd.result_id', 'left');
        $this->db->where('r.student_id', $student_id);
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
//        $this->db->where('rd.assessor_id', $this->user_id);
        $this->db->where('rd.scenario_id', $scenario_id);
        return $this->db->get()->row();
    }
    
    function getResultDetailsById($result_detail_id) {
        $this->db->select('rd.*, r.student_id, r.exam_schedule_id, s.name as scenario_name');
        $this->db->from('result_details as rd');
        $this->db->join('results as r', 'r.id=rd.result_id', 'left');
        $this->db->join('scenarios as s', 's.id=rd.scenario_id', 'left');
        $this->db->where('rd.id', $result_detail_id);
        return $this->db->get()->row();
    }
    
    
    function getStudentListByExam($exam_schedule_id) {
        
        $students = $this->db
            ->from('students as s')
            ->join('student_exams as se', 'se.student_id=s.id', 'left')
            ->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'left')
            ->join('exams as e', 'e.id=es.exam_id', 'left')
            ->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left')
            ->select('s.id, s.photo, s.number_type, s.gmc_number,CONCAT(s.fname," ",s.lname) AS full_name,s.email, s.phone, s.created_at')
            ->select('se.status, se.remarks, e.name as exam_name, ec.name as centre_name, DATE_FORMAT(es.datetime, "%Y-%m-%d") as date')
            ->where('se.exam_schedule_id', $exam_schedule_id)
            ->where('se.status', 'Enrolled')
            ->order_by('s.gmc_number', 'ASC')
            ->get()->result();
        
        return $students;
    }
    
    function getExamScenarios($exam_schedule_id) {        
        $this->db->select('s.reference_number,s.name,s.id');
        $this->db->from('scenario_relations as sr');
        $this->db->join('scenarios as s', 's.id=sr.scenario_id', 'LEFT');            
        $this->db->where('exam_schedule_id', $exam_schedule_id);            
        return $this->db->get()->result();
    }

}