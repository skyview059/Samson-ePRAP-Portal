<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Name_model extends Fm_model {

    public $table = 'exams';
    public $id    = 'id';
    public $order = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    // get total rows
    public function total_rows()
    {       
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    public function get_limit_data($limit, $start = 0)
    {
        $_students = $this->db->select('count(*)')
                        ->where('exam_id', 'exams.id', false)
                        ->get_compiled_select('students'); 

        $_schedules = $this->db->select('count(*)')
                        ->where('exam_id', 'exams.id', false)
                        ->get_compiled_select('exam_schedules'); 

        $_centres = $this->db->select('count(*)')
                        ->where('exam_id', 'exams.id', false)
                        ->get_compiled_select('exam_centres'); 
        
        $this->db->select('id,name,exam_type,status,created_at,updated_at');
        $this->db->select("({$_students}) as student_qty");
        $this->db->select("({$_schedules}) as schedule_qty");
        $this->db->select("({$_centres}) as centre_qty");
        $this->db->order_by($this->id, $this->order);        
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

}
