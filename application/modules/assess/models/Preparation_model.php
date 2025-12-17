<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Preparation_model extends Fm_model{

    public $table = 'scenario_to_assessors';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    
    //Get exam schedules list assessors wise
    public function get_assessors_exam() {
        $current_date = date("Y-m-d H:i:s", strtotime('-12 Hours'));
        $expire_date = date('Y-m-d H:i:s', strtotime($current_date. ' + 7 days'));
        
        $this->db->select('sr.exam_schedule_id, count(sr.scenario_id) as total_questions, group_concat(sr.scenario_id) AS scenario_ids');
        $this->db->from('scenario_relations as sr');
        $this->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id=sr.id', 'left');
        $this->db->where('sta.assessor_id', $this->user_id);
        $this->db->group_by('sr.exam_schedule_id');
        $exam_to_scenario_sql = $this->db->get_compiled_select();
        
        $this->db->select('es.id, es.exam_id, e.name as exam_name, es.datetime, ec.name as center_name, ec.address as center_address');
        $this->db->select('ets.total_questions, ets.scenario_ids');
        $this->db->from('exam_schedules as es');
        $this->db->join("($exam_to_scenario_sql) as ets", 'ets.exam_schedule_id=es.id', 'inner');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left');
        $this->db->where('es.datetime >= ', $current_date);
        $this->db->where('es.datetime <= ', $expire_date);
        return $this->db->get()->result();
        
    }
    
    public function get_scenarios_by_ids($ids) {
        $this->db->from('scenarios');
        $this->db->where_in('id', explode(',', $ids));
        $this->db->order_by('reference_number');
        return $this->db->get()->result();
    }

}