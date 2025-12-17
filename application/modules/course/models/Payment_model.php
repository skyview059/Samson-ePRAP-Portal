<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends Fm_model {

    public $table = 'course_payments';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($q = NULL)
    {

        if ($q) {
            $this->db->like('invoice_id', $q);
            $this->db->or_like('admin_comments', $q);
        }
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL, $status = null, $gateway = null, $purchased = null)
    {
        $now = DATE('Y-m-d H:i:s');
        $this->db->order_by($this->table.'.'.$this->id, $this->order);
        $this->db->select($this->table.'.*');
        $this->db->select("CONCAT(students.fname, ' ', IF(students.mname IS NULL or students.mname = '', '', CONCAT(students.mname, ' ')), students.lname) as full_name, students.email, students.phone_code, students.phone");
        $this->db->select("IF((purchased_at + INTERVAL 30 MINUTE) > '{$now}', 'No', 'Yes') as timeout");

        // Add the join clause
        $this->db->join('students', $this->table.'.student_id = students.id', 'left');

        $this->db->select('promocodes.code as promo_code');
        $this->db->join('promocodes', 'promocodes.code = course_payments.promo_code', 'left');

        $this->db->select('courses.name as course_name');
        $this->db->join('courses', 'courses.id = promocodes.course_id', 'left');
        $this->db->where('course_payments.status', $status ? $status : 'Paid');

        if ($gateway){
            $this->db->where('gateway', $gateway);
        }

        if ($purchased){
            $dates = explode(' to ', $purchased);

            if (count($dates) == 1) {
                $this->db->where('purchased_at', $dates[0]);
            } else {
                $this->db->where('purchased_at >=', $dates[0]);
                $this->db->where('purchased_at <=', $dates[1]);
            }
        }

        if ($q) {
            $this->db->group_start();
            $this->db->like('invoice_id', $q);
            $this->db->or_like('students.email', $q);
            $this->db->or_like('promo_code', $q);
            $this->db->or_like("CONCAT(students.fname, ' ', IF(students.mname IS NULL or students.mname = '', '', CONCAT(students.mname, ' ')), students.lname)", $q);
            $this->db->group_end();
        }

        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

}
