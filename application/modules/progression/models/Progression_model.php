<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Progression_model extends Fm_model {

    public $table = 'progressions';
    public $id = 'id';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        if ($q) {           
            $this->db->like('category', $q);
            $this->db->or_like('title', $q);            
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by('order_id', 'ASC');
        if ($q) {           
            $this->db->like('category', $q);
            $this->db->or_like('title', $q);            
        }
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function next_order_id()
    {
        $this->db->select('MAX(order_id) as order_id');
        $next = $this->db->get($this->table)->row();
        return $next->order_id + 1;
    }

    function qty()
    {
        $this->db->select('count(id) as qty, domain_id');
        $this->db->group_by('domain_id');
        $results = $this->db->get('personal_dev_plans')->result();  
        $arr = [];
        foreach($results as $result ){
            $arr[$result->domain_id] = $result->qty;
        }
        return $arr;
    }
    
    function getDropDownProgress($category = 'GMC')
    {        
        $this->db->where('category', $category);
        $progress = $this->db->get($this->table)->result();
        
        $option = '<option value="0">--Select--</option>';
        foreach($progress as $p){
            $option .= "<option value=\"$p->id\">";
            $option .= $p->title;
            $option .= '</option>';
        }
        return $option;
    }
}
