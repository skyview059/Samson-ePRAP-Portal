<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Queue_model extends Fm_model {

    public $table = 'mail_queues';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL)
    {        
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->select('id,send_to,subject,created_on,status,sent_at');
        $this->db->order_by($this->id, $this->order);        
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

}