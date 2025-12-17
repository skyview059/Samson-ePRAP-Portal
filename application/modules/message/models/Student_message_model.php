<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Student_message_model extends Fm_model
{
    public $table   = 'student_messages';
    public $id      = 'id';
    public $order   = 'DESC';
    private $student_id   = 0;

    function __construct()
    {
        parent::__construct();
        $this->student_id  = (int) getLoginStudentData('student_id');
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->__search($q);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {        
        $this->db->select("m.*");
        $this->db->select('CONCAT(fs.fname, " ",fs.mname," ",fs.lname) AS from_student, fs.photo as from_student_photo');
        $this->db->select('CONCAT(ts.fname, " ",ts.mname," ",ts.lname) AS to_student, ts.photo as to_student_photo');

        $this->db->join('students as fs', "fs.id=m.from_student_id", 'LEFT');
        $this->db->join('students as ts', "ts.id=m.to_student_id", 'LEFT');

        $this->__search($q);
        $this->db->order_by('m.id', $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function get_by_id($id)
    {
        $this->db->select("m.*");
        $this->db->select('CONCAT(fs.fname, " ",fs.mname," ",fs.lname) AS from_student');
        $this->db->select('CONCAT(ts.fname, " ",ts.mname," ",ts.lname) AS to_student');
        $this->db->from('student_messages as m');
        $this->db->join('students as fs', "fs.id=m.from_student_id", 'LEFT');
        $this->db->join('students as ts', "ts.id=m.to_student_id", 'LEFT');
        $this->db->where('m.id', $id);
        return $this->db->get()->row();
    }
    
    function get_replys($id)
    {
        $this->db->select("m.*");
        $this->db->select('CONCAT(fs.fname, " ",fs.mname," ",fs.lname) AS from_student');
        $this->db->select('CONCAT(ts.fname, " ",ts.mname," ",ts.lname) AS to_student');
        $this->db->from('student_messages as m');
        $this->db->join('students as fs', "fs.id=m.from_student_id", 'LEFT');
        $this->db->join('students as ts', "ts.id=m.to_student_id", 'LEFT');
        $this->db->order_by('m.id', 'ASC');
        $this->db->where('m.parent_id', $id );
        return $this->db->get()->result();
    }
    
    private function __search($q){
        $this->db->from('student_messages as m');
        $this->db->group_start();
        $this->db->where('m.to_student_id', $this->student_id);
        $this->db->or_where('m.from_student_id', $this->student_id);
        $this->db->group_end();

        $this->db->where('m.parent_id', '0');
        if ($q) {
            $this->db->group_start();
            $this->db->like('m.subject', $q);
            $this->db->or_like('m.body', $q);
            $this->db->group_end();
        }
    }

}
