<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends Fm_model {

    public $table = 'files';
    public $id = 'id';
    public $order = 'DESC';
    public $student_id = 0;

    function __construct()
    {
        parent::__construct();
        $this->student_id  = (int) getLoginStudentData('student_id');
    }

    // get total rows
    function total_rows($q = NULL, $from = 'user')
    {

        if ($q) { $this->db->like('title', $q); }
        if($from == 'student')  { $this->db->where('student_id', $this->student_id); }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL,  $from = 'user')
    {
        $this->db->select('f.*, CONCAT(s.title," ", s.fname," ", s.lname) AS full_name, s.id as stu_id');
        $this->db->from("{$this->table} as f");
        $this->db->join('students as s', 's.id=f.student_id','LEFT');        
        $this->db->order_by($this->id, $this->order);
        if($from == 'student')  { $this->db->where('f.student_id', $this->student_id); }
        if ($q) { $this->db->like('f.title', $q); }
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }
    
    
    function get_by_student ($id = 0 )
    {        
        $this->db->from($this->table);
        $this->db->order_by($this->id, $this->order );
        $this->db->where('student_id', $id  );        
        return $this->db->get()->result();
    }

}
