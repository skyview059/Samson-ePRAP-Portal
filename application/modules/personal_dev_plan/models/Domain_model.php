<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Domain_model extends Fm_model {

    public $table = 'personal_dev_plan_domains';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }
    
    function total_rows()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    function get_limit_data($limit, $start = 0)
    {
        $this->db->order_by('order_id', 'ASC');        
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function next_order_id()
    {
        $this->db->select('(MAX(order_id) + 1) as order_id');
        $next = $this->db->get($this->table)->row();
        return $next->order_id;
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

}
