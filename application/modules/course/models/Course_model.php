<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Course_model extends Fm_model {

    public $table = 'courses';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($category_id=0,$q = NULL)
    {
        $this->__sql($category_id,$q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $category_id =0, $q = NULL)
    {
        $this->db->select('count(*)');        
        $this->db->where('course_id', "{$this->table}.id", false );        
        $schedule = $this->db->get_compiled_select('course_dates');  
        
        $this->db->select('count(*)');        
        $this->db->where('course_id', "{$this->table}.id", false );        
        $this->db->where('status', 'Confirmed' );        
        $booked = $this->db->get_compiled_select('course_booked');                
        
        $this->db->select("{$this->table}.*, c.name as category");
        $this->db->select("({$schedule}) as schedule, ({$booked}) as booked");
        $this->db->join('course_categories as c', "c.id={$this->table}.category_id", 'LEFT');
        $this->__sql($category_id,$q);
        $this->db->order_by('category_id', 'ASC');        
        $this->db->order_by($this->id, 'ASC');        
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function __sql($category_id,$q){
        if($category_id) { $this->db->where('category_id', $category_id ); }
        if ($q) {
            $this->db->group_start();
            $this->db->like('courses.name', $q);
            $this->db->or_like('courses.description', $q);
            $this->db->group_end();
        }
    }
    
    function getDates($course_id){
        
        $this->db->select('count(*)');        
        $this->db->where('course_date_id', 'cd.id', false );        
        $sql = $this->db->get_compiled_select('course_booked');  
	                
        $this->db->select('*');
        $this->db->select('DATE_FORMAT(start_date, "%Y-%m-%d") as start_date');
        $this->db->select('DATE_FORMAT(start_date, "%h") as start_hh');
        $this->db->select('DATE_FORMAT(start_date, "%i") as start_mm');
        $this->db->select('DATE_FORMAT(start_date, "%p") as start_apm');
                
        $this->db->select('DATE_FORMAT(end_date, "%Y-%m-%d") as end_date');        
        $this->db->select('DATE_FORMAT(end_date, "%h") as end_hh');
        $this->db->select('DATE_FORMAT(end_date, "%i") as end_mm');
        $this->db->select('DATE_FORMAT(end_date, "%p") as end_apm');
                
        $this->db->select("({$sql}) as seat_booked");
        $this->db->order_by('start_date', 'ASC');
//        $this->db->order_by('id', 'ASC');
        $this->db->where('course_id', $course_id);
        return $this->db->get('course_dates as cd')->result();
    }
    
    function my_course_total_rows($student_id = 0 )
    {
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id', 'LEFT');
        $this->db->where('cp.student_id', $student_id);
        $this->db->where('cp.status', 'Paid');
        $this->db->where('cb.type', 'course');
        $this->db->where_in('cb.status', ['Confirmed', 'Cancelled']); 
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_my_courses($student_id, $limit=25, $start = 0)
    {       
        $today = date('Y-m-d');
        $this->db->select("cb.id, cb.course_id, cb.status, c.name as course, c.price, c.duration as course_duration, cc.name as category");
        $this->db->select('DATE_FORMAT(cd.start_date, "%d/%m/%Y %h:%i %p") as start_datetime');
        $this->db->select('DATE_FORMAT(cd.end_date, "%d/%m/%Y %h:%i %p") as end_datetime');                
        $this->db->select('TIMESTAMPDIFF(hour,cd.start_date, cd.end_date) as duration');        
        $this->db->select('DATE_FORMAT(cd.start_date, "%Y-%m-%d") as start_date');                
        $this->db->select('DATE_FORMAT(cd.end_date, "%Y-%m-%d") as end_date'); 
        
        $this->db->select("DATEDIFF(cd.start_date, '{$today}') as days_left");
        
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id','LEFT');
        $this->db->join('courses as c', 'c.id=cb.course_id', 'LEFT');
        $this->db->join('course_categories as cc', 'cc.id=c.category_id', 'LEFT');
        $this->db->join('course_dates as cd', 'cd.id=cb.course_date_id', 'LEFT');
        
        $this->db->where('cp.student_id', $student_id);
        $this->db->where('cp.status', 'Paid');
        $this->db->where('cb.type', 'course');
        $this->db->where_in('cb.status', ['Confirmed', 'Cancelled']); 
        $this->db->order_by('cp.id', 'DESC');        
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // get data with limit and search
    function get_my_practice_courses($student_id)
    {
        $this->db->select("cb.id, cb.course_id, cb.status, e.name as course, cp.total_pay, cb.expiry_date");
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id','LEFT');
        $this->db->join('exams as e', 'e.id = cb.course_id', 'LEFT');

        $this->db->where('cp.student_id', $student_id);
//        $this->db->where('cp.status', 'Paid');
        $this->db->where('cb.type', 'practice');
        $this->db->where_in('cb.status', ['Confirmed', 'Cancelled']);
        $this->db->order_by('cp.id', 'DESC');
        return $this->db->get()->result();
    }
}
