<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users_model extends Fm_model
{

    public $table = 'users';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL, $status = NULL, $role_id = 0)
    {
        $this->__search($role_id,$status,$q);
        $this->db->from($this->table);
        return $this->db->get()->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $status = NULL, $role_id = 0)
    {
        $this->db->order_by($this->id, $this->order);
        $this->__search($role_id,$status,$q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function __search($role_id,$status,$q){
        $this->db->where('role_id >=', 2 );
        if ($role_id != 0) { 
            $this->db->where('role_id', $role_id);
        } 
        if ($status) { $this->db->where('status', $status); }
        if ($q) {
            $this->db->group_start();
            $this->db->like('first_name', $q);
            $this->db->or_like('last_name', $q);
            $this->db->or_like("CONCAT(first_name,' ', last_name)", $q);
            $this->db->or_like('email', $q);	
            $this->db->or_like('mobile_number', $q);	
            $this->db->or_like('add_line1', $q);
            $this->db->or_like('add_line2', $q);
            $this->db->or_like('city', $q);
            $this->db->or_like('state', $q);
            $this->db->group_end();
        }        
    }
    
    
    function get_total_assign($id)
    {        
        $this->db->from('students as s');
        $this->db->join('user_students_relation as rel', 'rel.student_id = s.id', 'LEFT');
        $this->db->where('rel.user_id', $id );
        $this->db->where('s.status', 'Active' );        
        return $this->db->count_all_results();
    }
    
    function get_student($limit, $start, $id)
    {        
        $this->db->select('s.*');
        $this->db->from('students as s');
        $this->db->join('user_students_relation as rel', 'rel.student_id = s.id', 'LEFT');
        $this->db->where('rel.user_id', $id );
        $this->db->where('s.status', 'Active' );
        $this->db->limit($limit, $start);
        $this->db->order_by($this->id, $this->order);
        return $this->db->get()->result();
    }

}