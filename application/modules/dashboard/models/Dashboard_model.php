<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends Fm_model {

    function __construct()
    {
        parent::__construct();
    }

    function total_pending_enrolled(){
        return $this->db->where('status', 'Pending')->count_all_results('student_exams');
    }
    function get_pending_enrolled_list( $limit, $start ){
        return $this->db
                ->select('s.id as stu_id,se.id as enroll_id, s.number_type, s.photo, s.gender, s.gmc_number,s.title, CONCAT(s.fname," ",s.lname) AS full_name,s.email, CONCAT(s.phone_code,s.phone) as phone, s.created_at')
                ->select('se.status, se.remarks, es.label, e.name as exam_name, ec.name as centre_name, es.datetime')
                ->from('student_exams as se')
                ->join('students as s', 'se.student_id=s.id', 'LEFT')
                ->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'LEFT')
                ->join('exams as e', 'e.id=es.exam_id', 'LEFT')
                ->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'LEFT')                
                ->where('se.status', 'Pending')
                ->order_by('se.created_at', 'ASC')
                ->limit($limit, $start )
                ->get()->result();
    }
}
