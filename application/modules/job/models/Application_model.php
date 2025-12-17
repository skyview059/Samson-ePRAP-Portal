<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Application_model extends Fm_model
{

    public $table = 'job_applications';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL, $manage_all)
    {
        $this->__sql($q, $manage_all);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $manage_all)
    {
        $this->__sql($q, $manage_all);
        $this->db->order_by($this->id, $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function __sql($q, $manage_all){
        $this->db->select('a.*, j.post_title as job_title, s.fname, s.mname, s.lname');
        if ($q) {
            $this->db->like('id', $q);
//            $this->db->or_like('job_id', $q);
//            $this->db->or_like('student_id', $q);
            $this->db->or_like('cover_letter', $q);
            $this->db->or_like('status', $q);
        }
        $this->db->from('job_applications as a');
        $this->db->join('jobs as j', 'j.id = a.job_id', 'LEFT');
        $this->db->join('students as s', 's.id = a.student_id', 'LEFT');
        if($manage_all == false){
            $this->db->where('j.user_id', $this->user_id);
        }
    }

    public function updateShortlist($id, $saveFields){
        $this->db->where('id', $id);
        if($this->db->update($this->table, $saveFields)){
            return true;
        }
        return false;
    }


}