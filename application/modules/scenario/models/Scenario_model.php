<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Scenario_model extends Fm_model
{
    public $table = 'scenarios';
    public $id = 'id';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id) {
        $this->db->select('s.*');
        $this->db->select('c.name as category_name');
        $this->db->where('s.id', $id);
        $this->db->from('scenarios as s');
        $this->db->join('exams as c', 's.exam_id=c.id', 'LEFT');
        return $this->db->get()->row();
    }

    function total_rows($id, $q = NULL)
    {
        $this->db->from('scenarios as s');
        $this->__search($id,$q);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $id=0, $q = NULL)
    {

        /* Code Off for Wrong Result */
        // $rel_qty = '(SELECT COUNT(*) FROM `scenario_relations` WHERE scenario_id = s.id)';
        // $exam_qty = '(SELECT COUNT(*) FROM `result_details` WHERE scenario_id = s.id)';
        // $this->db->select("{$rd_qty} as used");
        // $this->db->select("{$sr_qty} as examed");


        $this->db->select('s.id, s.reference_number, s.presentation, s.name, s.created_at, s.updated_at');
        $this->db->from('scenarios as s');

        /* Code Off for Long Loading Time */
        // $this->db->select('count(sr.scenario_id) as used');
        // $this->db->select('count(ex_rel.scenario_id) as examed');        
        // $this->db->join('scenario_relations as sr', 'sr.scenario_id=s.id', 'LEFT');
        // $this->db->join('result_details as ex_rel', 'ex_rel.scenario_id=s.id', 'LEFT');
        
//        $this->db->select('c.name as category_name');
//        $this->db->join('exams as c', 's.exam_id=c.id', 'LEFT');        
        $this->db->order_by('reference_number', $this->order);
        $this->__search($id,$q);        
        // $this->db->group_by('s.id');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function __search($id,$q = NULL)
    {
        $this->db->where('s.exam_id', $id );
        if ($q) {
            $this->db->group_start();            
            $this->db->like('s.name', $q);
            $this->db->or_like('s.candidate_instructions', $q);
            $this->db->or_like('s.reference_number', $q);
            $this->db->group_end();
        }
    }
    
    function get($id)
    {
        $this->db->select('s.id, s.reference_number, s.presentation, s.name, candidate_instructions');
        $this->db->from('scenarios as s');   
        $this->db->join('exam_schedules as sch', 'sch.exam_id=s.exam_id', 'LEFT');   
        
        $this->db->order_by('s.reference_number', 'ASC');        
        $this->db->where('sch.id', $id );        
        return $this->db->get()->result();
    }
    function find_exam_id($schedule_id)
    {
        $this->db->select('exam_id');                
        $this->db->where('id', $schedule_id );        
        $data = $this->db->get('exam_schedules')->row();
        return ($data) ? $data->exam_id : 0;        
    }
    
    function marked($id)
    {
        $this->db->select('scenario_id');
        $this->db->where('exam_schedule_id', $id );
        $marks    = $this->db->get('scenario_relations')->result();
        $selected = [];
        foreach ($marks as $mark){
            $selected[] = $mark->scenario_id;
        }
        return $selected;
    }
    
    function relation($id)
    {        
        $this->db->where('scenario_id', $id );
        return $this->db->count_all_results('scenario_relations');
        
    }

}