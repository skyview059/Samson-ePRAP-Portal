<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Feedback_statement_model extends Fm_model
{

    public $table = 'sca_feedback_statements';
    public $id = 'id';
    public $order = 'ASC';

    function __construct(){
        parent::__construct();
    }

    // all statements ordered by domain then statement number
    function get_all_ordered() {
        $this->db->order_by('domain_id', 'ASC');
        $this->db->order_by('sl_no', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // check duplicate (domain_id, sl_no) pair, excluding a given id on update
    function sl_no_exists($domain_id, $sl_no, $except_id = 0) {
        $this->db->where('domain_id', $domain_id);
        $this->db->where('sl_no', $sl_no);
        if ($except_id > 0) {
            $this->db->where('id !=', $except_id);
        }
        $this->db->from($this->table);
        return $this->db->count_all_results() > 0;
    }
}
