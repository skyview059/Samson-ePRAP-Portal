<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends Fm_model
{
    public $table   = 'messages';
    public $id      = 'id';
    public $order   = 'DESC';
    private $student_id   = 0;

    function __construct()
    {
        parent::__construct();
        $this->student_id  = (int) getLoginStudentData('student_id');
    }

    // get total rows
    function total_rows($q = NULL, $from = 'user')
    {        
        $this->db->from($this->table);
        $this->__search($q,$from);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $from = 'user')
    {        
        $this->db->select("{$this->table}.id,subject,open_at,{$this->table}.status,opened_by");
        $this->db->select('CONCAT(fname, " ",mname," ",lname) AS student');
        $this->db->select('CONCAT(u.first_name, " ", u.last_name) AS teacher');
        $this->db->select('r.role_name AS label');
        $this->db->from($this->table);
        $this->db->join('students as s', "s.id={$this->table}.student_id", 'LEFT');
        $this->db->join('users as u', "u.id={$this->table}.user_id", 'LEFT');
        $this->db->join('roles as r', "r.id=u.role_id", 'LEFT');
          
        $this->__search($q,$from);
        $this->db->order_by($this->id, $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }
    
    function get_replys($id)
    {
        $this->db->select("{$this->table}.*");
        $this->db->select('CONCAT(fname, " ",mname," ",lname) AS student');
        $this->db->select('CONCAT(first_name, " ",last_name) AS admin');
        $this->db->from($this->table);
        $this->db->join('students as s',"s.id={$this->table}.student_id",'LEFT');
        $this->db->join('users as u',"u.id={$this->table}.user_id",'LEFT');
        $this->db->order_by('id', 'ASC');
        $this->db->where('parent_id', $id );
        return $this->db->get()->result();
    }
    
    private function __search($q,$from){
        
        if(!in_array($this->role_id, [1,2]) && $from == 'user'){
            $this->db->where('user_id', $this->user_id);
        }
        
        if($from == 'student')  { $this->db->where('student_id', $this->student_id); }
        $this->db->where('parent_id', '0');
        if ($q) {
            $this->db->group_start();
            $this->db->like('subject', $q);
            $this->db->or_like('body', $q);
            $this->db->group_end();
        }
    }

}
