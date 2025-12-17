<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_dev_plan_model extends Fm_model {

    public $table = 'personal_dev_plans';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function get_domains()
    {        
        $this->db->from('personal_dev_plan_domains');
        $this->db->order_by('order_id','ASC');
        return $this->db->get()->result();
    }
    function get_details( $id )
    {        
        $this->db->from('personal_dev_plans as p');
        $this->db->join('personal_dev_plan_domains as d', 'p.domain_id=d.id', 'LEFT');
        $this->db->where('p.student_id', $id );
        $this->db->order_by('d.order_id','ASC');
        return $this->db->get()->result();
    }
    
    // get total rows
    function total_rows($q = NULL)
    {
        $this->__search($q);
        $this->db->group_by('pdp.student_id');
        return $this->db->get()->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL)
    {
//        $this->db->select("pdp.*");
        $this->db->select('pdp.created_at');
        $this->db->select('s.id, CONCAT(s.fname," ", s.mname," ", s.lname) as "student_name"');
        $this->db->order_by($this->id, $this->order);
        $this->__search($q);

        $this->db->group_by('pdp.student_id');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function __search($q){
        if ($q) {         
            $this->db->group_start();           
            $this->db->where("s.fname LIKE '%{$q}%'", false, false);            
            $this->db->or_where("CONCAT(s.fname,' ', s.lname) LIKE '%{$q}%'", false, false);
            $this->db->or_where("CONCAT(s.fname,' ', s.mname,' ', s.lname) LIKE '%{$q}%'", false, false);
            $this->db->group_end();
        }
        $this->db->from("{$this->table} as pdp");
        $this->db->join('students as s', "s.id = pdp.student_id", 'LEFT');
    }
    
    function getStudentName($id) {        
        $this->db->select('CONCAT(s.fname," ", s.mname," ", s.lname) as name');
        $this->db->where('s.id', $id);
        $this->db->from('students as s');
        $student = $this->db->get()->row();
        if($student){
            return $student->name;
        } else {
            return 'Unknown Student';
        }
    }


}
