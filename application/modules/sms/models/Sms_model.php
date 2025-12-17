<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_model extends Fm_model {

    public $table = 'sms_log';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL)
    {
        $this->__search( $q );
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->order_by($this->id, $this->order);
        $this->__search( $q );
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function __search($q = NULL)
    {
        if ($q) {            
            $this->db->like('phone', $q);
            $this->db->or_like('body', $q);
            $this->db->or_like('status', $q);
        }
    }

}
