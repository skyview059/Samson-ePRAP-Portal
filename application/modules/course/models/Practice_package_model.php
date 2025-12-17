<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Practice_package_model extends Fm_model
{

    public $table = 'practice_packages';
    public $id    = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($exam_id = 0, $q = NULL)
    {
        $this->__sql($exam_id, $q);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $exam_id = 0, $q = NULL)
    {
//        $this->db->select('count(*)');
//        $this->db->where('course_id', "{$this->table}.id", false);
//        $this->db->where('status', 'Confirmed');
//        $booked = $this->db->get_compiled_select('course_booked');

        $this->db->select("pp.*, e.name as practice_name");
//        $this->db->select("({$booked}) as booked");

        $this->__sql($exam_id, $q);
        $this->db->order_by('pp.exam_id', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function __sql($exam_id, $q)
    {
        if ($exam_id) {
            $this->db->where('exam_id', $exam_id);
        }
        if ($q) {
            $this->db->group_start();
            $this->db->like('pp.title', $q);
            $this->db->or_like('pp.description', $q);
            $this->db->group_end();
        }
        $this->db->from("{$this->table} as pp");
        $this->db->join('exams as e', "e.id = pp.exam_id", 'LEFT');
    }
}
