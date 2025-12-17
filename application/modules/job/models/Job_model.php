<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Job_model extends Fm_model
{
    public $table = 'jobs';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q, $manage_all)
    {
        $this->__sql($q, $manage_all);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start, $q, $manage_all)
    {
        $this->db->order_by($this->id, $this->order);
        $this->__sql($q, $manage_all);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    private function __sql($q, $manage_all) {
        if ($q) {
            $this->db->like('id', $q);
            $this->db->or_like('post_title', $q);
            $this->db->or_like('description', $q);
            $this->db->or_like('location', $q);
            $this->db->or_like('deadline', $q);
            $this->db->or_like('skills', $q);
            $this->db->or_like('benefit', $q);
            $this->db->or_like('status', $q);
        }
        if($manage_all == false){
            $this->db->where('user_id', $this->user_id);
        }
    }

}