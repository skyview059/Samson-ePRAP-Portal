<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Development_plan_model extends Fm_model
{
    public $table = 'student_development';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->__search($q);
        $this->db->group_by('d.student_id');
        return $this->db->get()->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->select("d.*");
        $this->db->select('CONCAT(s.fname," ", s.mname," ", s.lname) as "student_name"');
        $this->db->order_by($this->id, $this->order);
        $this->__search($q);

        $this->db->group_by('d.student_id');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function __search($q){
        if ($q) {         
            $this->db->group_start();           
            $this->db->where("s.fname LIKE '%{$q}%'", false, false);            
            $this->db->or_where("CONCAT(s.fname,' ', s.lname) LIKE '%{$q}%'", false, false);
            $this->db->or_where("CONCAT(s.fname,' ', s.mname,' ', s.lname) LIKE '%{$q}%'", false, false);
            $this->db->group_end();
        }
        $this->db->from("{$this->table} as d");
        $this->db->join('students as s', 's.id = d.student_id', 'LEFT');
    }

    function getStudentName($id) {        
        $this->db->select('CONCAT(s.fname," ", s.mname," ", s.lname) as name');
        $this->db->where('s.id', $id);
        $this->db->from('students as s');
        $student = $this->db->get()->row();
        if($student){
            return $student->name;
        } else {
            return 'Unknown Student';
        }
    }
    
    function get_by_id($id) {
        $this->db->select("d.*");
        $this->db->select('CONCAT(s.fname," ", s.mname," ", s.lname) as "student_name"');
        $this->db->where('d.id', $id);
        $this->db->from("{$this->table} as d");
        $this->db->join('students as s', 's.id = d.student_id', 'LEFT');
        return $this->db->get()->row();
    }

    function get_by_student_id($student_id) {
        $this->db->select("d.*");
        $this->db->select('CONCAT(s.fname," ", s.mname," ", s.lname) as "student_name"');
        $this->db->where('d.student_id', $student_id);
        $this->db->from("{$this->table} as d");
        $this->db->join('students as s', 's.id = d.student_id', 'LEFT');
        $this->db->order_by('id','DESC');
        return $this->db->get()->result();
    }

}