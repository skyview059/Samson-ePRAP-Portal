<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Feedback_domain_model extends Fm_model
{

    public $table = 'sca_feedback_domains';
    public $id = 'id';
    public $order = 'ASC';

    function __construct(){
        parent::__construct();
    }

    // all domains with statement count, ordered by sort_order
    function get_all_with_counts() {
        $this->db->select('d.*, (SELECT COUNT(*) FROM sca_feedback_statements s WHERE s.domain_id = d.id) AS statement_count', FALSE);
        $this->db->from($this->table . ' d');
        $this->db->order_by('d.sort_order', 'ASC');
        $this->db->order_by('d.id', 'ASC');
        return $this->db->get()->result();
    }
}
