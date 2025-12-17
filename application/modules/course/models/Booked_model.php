<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Booked_model extends Fm_model {

    public $table = 'course_booked';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    function total_rows($category_id, $course_id, $status, $course_dates_id)
    {        
        $this->db->from("{$this->table} as cb");
        $this->db->join('courses as c', 'c.id=cb.course_id', 'LEFT');
        $this->db->join('course_categories as cc', 'c.category_id=cc.id', 'LEFT');
        $this->__sql($category_id, $course_id, $status, $course_dates_id);
        return $this->db->get()->num_rows();
    }

    function get_limit_data($limit, $start, $category_id, $course_id, $status, $course_dates_id)
    {
        $this->db->select('cb.*');
        $this->db->select('c.name as course, c.price,c.booking_limit');
        
        $this->db->select('cp.id as payment_id, cp.total_pay as paid, cp.gateway as gateway');
        $this->db->select('DATE_FORMAT(cp.purchased_at, "%d-%b-%Y") as booked_on'); 
        
        $this->db->select("s.number_type, s.gmc_number as gmc, CONCAT(s.fname, ' ', IF(s.mname IS NULL or s.mname = '', '', CONCAT(s.mname, ' ')), s.lname) as full_name");
        $this->db->select('s.email,s.phone_code,s.phone');
        $this->db->select('DATE_FORMAT(cd.start_date, "%d/%m/%Y %h:%i %p") as start_date');
        $this->db->select('DATE_FORMAT(cd.end_date, "%d/%b/%Y %h:%i %p") as end_date');
        
        $today = date('Y-m-d');
        $this->db->select("DATEDIFF(cd.start_date, '{$today}') as days_left");
        
        $this->db->join('course_dates as cd', 'cd.id=cb.course_date_id', 'LEFT');
        $this->db->join('courses as c', 'c.id=cb.course_id', 'LEFT');
        
        // $this->db->select('cc.name as category');
        // $this->db->join('course_categories as cc', 'c.category_id=cc.id', 'LEFT');
        
        $this->db->join('course_payments as cp', 'cp.id=cb.course_payment_id', 'LEFT');
        $this->db->join('students as s', 's.id=cp.student_id', 'LEFT');
        
        $this->__sql($category_id, $course_id, $status, $course_dates_id);
        
        $this->db->order_by('cb.id', $this->order);       
        $this->db->limit($limit, $start);
        return $this->db->get('course_booked as cb')->result();        
    }
    
    function __sql($category_id, $course_id, $status, $course_dates_id){
        if($category_id){ $this->db->where('c.category_id', $category_id ); }
        if($course_id){ $this->db->where('cb.course_id', $course_id ); }
        if($status){ $this->db->where('cb.status', $status ); }        
        if($course_dates_id){ $this->db->where('cb.course_date_id', $course_dates_id ); }
        $this->db->where('cb.type', 'course');
    }
    
    function getReschedule($booking_id){
        $this->db->select('b.id, c.booking_limit, course_id, course_date_id, admin_remark, p.total_pay' );
        $this->db->from('course_booked as b');
        $this->db->join('course_payments as p', 'p.id=b.course_id', 'LEFT');
        $this->db->join('courses as c', 'c.id=b.course_id', 'LEFT');
        $this->db->where('b.id', $booking_id );

        return $this->db->get()->row();
    }
    
    function getStudentEmail($booking_id){        
        $this->db->select('s.email,s.lname,s.id');
        $this->db->from('course_booked as b');
        $this->db->join('course_payments as p', 'p.id=b.course_payment_id', 'LEFT');
        $this->db->join('students as s', 's.id=p.student_id', 'LEFT');
        $this->db->where('b.id', $booking_id );
        return $this->db->get()->row();
    }
    
    function getCourseDate($course_date_id){        
        $this->db->select('DATE_FORMAT(start_date, "%d/%m/%Y %h:%i %p") as start');
        $this->db->select('DATE_FORMAT(end_date, "%d/%m/%Y %h:%i %p") as end');         
        $this->db->where('id', $course_date_id  );
        $date = $this->db->get('course_dates')->row();
        if($date){
            return "{$date->start} ~ {$date->end}";
        } else {
            return '--';
        }        
    }
    
    public function getBookingCancelationMail( $id ){
        $today = date('Y-m-d');
//        $this->db->select('*');
        $this->db->select('cc.name as c_category');
        $this->db->select('c.name as c_name, cb.booked_price as c_booked_price, c.duration as c_duration');
        $this->db->select('DATE_FORMAT(cd.start_date, "%d/%m/%Y %h:%i %p") as start_date');
        $this->db->select('DATE_FORMAT(cd.end_date, "%d/%m/%Y %h:%i %p") as end_date'); 
        $this->db->select("DATEDIFF(cd.start_date, '{$today}') as c_days_left");
        
        $this->db->select('invoice_id,refund_amount,student_remark');
        $this->db->select('s.email,s.fname');        
        
        $this->db->from('course_booked as cb');
        $this->db->join('course_payments as cp','cp.id=cb.course_payment_id', 'LEFT');
        
        $this->db->join('courses as c','cb.course_id=c.id', 'LEFT');
        $this->db->join('course_dates as cd','cb.course_date_id=cd.id', 'LEFT');
        $this->db->join('course_categories as cc','cc.id=c.category_id', 'LEFT');
        $this->db->join('students as s','s.id=cp.student_id', 'LEFT');
        
        $this->db->where('cb.id', $id );
        $this->db->where('cb.status', 'Cancelled' );
        return $this->db->get()->row();        
    }


    function total_rows_practice($practice_id, $status, $gateway)
    {
        $this->__sql_practice($practice_id, $status, $gateway);
        return $this->db->get()->num_rows();
    }

    function get_limit_data_practice($limit, $start, $practice_id, $status, $gateway)
    {
        $this->__sql_practice($practice_id, $status, $gateway);
        $this->db->order_by('cb.id', $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function __sql_practice($practice_id, $status, $gateway){
        $this->db->select('cb.*, e.name');
        $this->db->select('cp.id as payment_id, cp.total_pay as paid, cp.gateway as gateway');
        $this->db->select('DATE_FORMAT(cp.purchased_at, "%d-%b-%Y") as booked_on');

        $this->db->select("s.number_type, s.gmc_number as gmc, CONCAT(s.fname, ' ', IF(s.mname IS NULL or s.mname = '', '', CONCAT(s.mname, ' ')), s.lname) as full_name");
        $this->db->select('s.email,s.phone_code,s.phone');

        $this->db->join('exams as e', 'e.id = cb.course_id', 'LEFT');
        $this->db->join('course_payments as cp', 'cp.id = cb.course_payment_id', 'LEFT');
        $this->db->join('students as s', 's.id=cp.student_id', 'LEFT');
        $this->db->from('course_booked as cb');

        if($practice_id){ $this->db->where('cb.course_id', $practice_id ); }
        if($status){ $this->db->where('cb.status', $status ); }
        if($gateway){ $this->db->where('cp.gateway', $gateway ); }
        $this->db->where('cb.type', 'practice');
    }
}
