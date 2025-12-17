<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Centre_model extends Fm_model {

    public $table = 'exam_centres';
    public $id = 'id';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($exam_id)
    {        
        $this->db->from('exam_centres as s');
        $this->db->where('s.exam_id', $exam_id );
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data__bak($limit, $start = 0, $exam_id=0)
    {
        $this->db->select('c.id,s.datetime, c.name as centre, c.address, con.name as country');
        $this->db->from('exam_schedules as s');
        $this->db->join('exam_centres as c', 'c.id=s.exam_centre_id', 'LEFT');  
        $this->db->join('countries as con', 'con.id=c.country_id', 'LEFT');  
        $this->db->where('s.exam_id', $exam_id );
        $this->db->order_by('s.datetime', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }
    
    
    function get_limit_data($limit, $start = 0, $exam_id=0, $q = NULL)
    {
        $this->db->select('count(*)');        
	$this->db->where('exam_centre_id', "{$this->table}.id", false );        
	$students = $this->db->get_compiled_select('students');  
        
        
        $this->db->select("{$this->table}.*,c.name as country");
        $this->db->select("({$students}) as students");
        $this->db->from($this->table);
        $this->db->join('countries as c', "c.id={$this->table}.country_id", 'LEFT');        
        $this->db->order_by($this->id, $this->order);
        $this->__search($exam_id,$q);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function __search($exam_id, $q){
//        $this->db->join('exam_schedules as e', "e.exam_centre_id={$this->table}.id", 'LEFT');
        $this->db->where('exam_id', $exam_id );
        if ($q) {     
            $this->db->group_start();
            $this->db->like("{$this->table}.name", $q);
            $this->db->or_like("{$this->table}.address", $q);
            $this->db->group_end();
        }
    }
    
    
    function get_exam_schedule($id)
    {
        $this->db->select('e.*,cn.name as centre');
        $this->db->select('c.name as exam_name');
        $this->db->from('exam_schedules as e');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        $this->db->join('exams as c', 'c.id=e.exam_id', 'LEFT');
               
        $this->db->where('e.exam_centre_id', $id );
        return $this->db->get()->result();
    }
}
