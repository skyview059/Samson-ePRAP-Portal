<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Promocodes_model extends Fm_model
{

    public $table = 'promocodes';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL, $status = 'active')
    {
        $this->whereStatus($status);
        if ($q) {
            $this->db->group_start();
            $this->db->like('id', $q);
            $this->db->or_like('amount', $q);
            $this->db->or_like('code', $q);
            $this->db->or_like('created_on', $q);
            $this->db->or_like('discount_type', $q);
            $this->db->or_like('end_date', $q);
            $this->db->or_like('start_date', $q);
            $this->db->or_like('status', $q);
            $this->db->or_like('updated_on', $q);
            $this->db->or_like('user_id', $q);
            $this->db->or_like('uses_limit', $q);
            $this->db->group_end();
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $status = 'active')
    {
        $this->whereStatus($status);
        $this->db->order_by($this->table . '.' . $this->id, $this->order);

        $this->db->limit($limit, $start);
        $this->db->select($this->table . '.*');
        $this->db->select("CONCAT(u.first_name,' ', u.last_name) as full_name");

        $this->db->join('users as u', "u.id={$this->table}.user_id", 'LEFT');

        if ($q) {
            $this->db->group_start();
            $this->db->like('amount', $q);
            $this->db->or_like('code', $q);
            $this->db->or_like('created_on', $q);
            $this->db->or_like('discount_type', $q);
            $this->db->or_like('end_date', $q);
            $this->db->or_like('start_date', $q);
            $this->db->or_like('promocodes.status', $q);
            $this->db->or_like('updated_on', $q);
            $this->db->or_like('user_id', $q);
            $this->db->or_like('uses_limit', $q);
            $this->db->group_end();
        }

        return $this->db->get($this->table)->result();
    }

    // active = still valid today or later, expired = validity ended before today
    private function whereStatus($status)
    {
        if ($status === 'expired') {
            $this->db->where("DATE({$this->table}.end_date) <", 'CURDATE()', FALSE);
        } else {
            $this->db->where("DATE({$this->table}.end_date) >=", 'CURDATE()', FALSE);
        }
    }
}