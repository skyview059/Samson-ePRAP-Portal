<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Practice_model extends Fm_model
{

    public $table = 'practice_schedules';
    public $id = 'id';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    function get_by_id($id) {
        $this->db->select('e.*');        
        $this->db->select('cn.name as centre_name, cn.address as centre_address');        
        $this->db->select('p.name as practice_name, p.id as practice_id');        
        $this->db->from('practice_schedules as e');
        $this->db->join('practices as p', 'e.practice_id=p.id', 'LEFT');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        $this->db->where('e.id', $id);
        return $this->db->get()->row();
    }

    function total_rows($id, $tab='coming')
    {
        $this->__search($id,$tab);
        $this->db->from('practice_schedules as e');
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $id=1,$tab='coming')
    {
        $this->db->select('e.*,cn.name as centre');
        $this->db->select('p.name as category_name');
        $this->db->from('practice_schedules as e');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        
        $this->__search($id,$tab);
        if($tab =='coming'){
            $this->db->order_by('datetime', 'ASC');
        } else {
            $this->db->order_by('datetime', 'DESC');
        }
        
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    function __search($id,$tab)
    {       
        $this->db->where('e.practice_id', $id);
        
        if($tab =='coming'){
            $this->db->where('e.datetime >=', date('Y-m-d 00:00:00'));        
        } else {
            $this->db->where('e.datetime <=', date('Y-m-d 23:59:59'));        
        }             
        $this->db->join('practices as p', 'e.practice_id=p.id', 'LEFT');
    }  

    public function getEnrolledStudents($practice_schedule_id, $status = false ){
        $this->db->select('s.email,s.id as student_id,s.fname,s.mname,s.lname,s.photo,s.gender,s.number_type');        
        $this->db->select('s.gmc_number,phone_code,phone,whatsapp_code,whatsapp');        
        $this->db->select('pb.id as practice_booked_id, pb.created_at,pb.remarks,pb.status,pb.practice_schedule_id, pb.attendance');        
        $this->db->from('practice_students as pb'); 
        $this->db->join('students as s', 's.id=pb.student_id ', 'INNER');   
        if($status) { $this->db->where('pb.status', 'Enrolled'); }
        $this->db->where('pb.practice_schedule_id', $practice_schedule_id);
        $this->db->group_by('pb.student_id');
        $this->db->order_by('pb.status', 'ASC');
        return $this->db->get()->result();
    }
    public function getScheduledPractice($practice_schedule_id){
        $today = date('Y-m-d');
        $this->db->select("DATEDIFF(ps.datetime, '{$today}') as c_days_left"); 
        $this->db->select('p.name as p_category, ps.label as p_label');
        $this->db->select('DATE_FORMAT(ps.datetime, "%d/%m/%Y %h:%i %p") as schedule');
        
        $this->db->from('practice_schedules as ps');                 
        $this->db->join('practices as p','p.id=ps.practice_id', 'LEFT');        
        
        $this->db->where('ps.id', $practice_schedule_id);
        return $this->db->get()->row();
    }
    
    public function get_student_practice_by_id($student_practice_id){
        $this->db->select('s.*,ps.id as student_practice_id, ps.status, ps.remarks, ps.created_at as assign_at'); 
        $this->db->select('es.datetime, p.name as practice_name');
        $this->db->from('practice_students as ps');
        $this->db->join('students as s', 's.id=ps.student_id ', 'LEFT');
        $this->db->join('practice_schedules as es', 'es.id=ps.practice_schedule_id', 'LEFT');
        $this->db->join('practices as p', 'p.id=ps.practice_schedule_id', 'LEFT');
        $this->db->where('ps.id', $student_practice_id);
        return $this->db->get()->row();
    }
    
    function qty( $count = 'past', $practice_id = 1 )
    {        
        if($count =='coming'){
            $this->db->where('datetime >=', date('Y-m-d 00:00:00') );        
        } else {
            $this->db->where('datetime <', date('Y-m-d 23:59:59'));        
        }
        $this->db->where('practice_id', $practice_id );
        return $this->db->count_all_results('practice_schedules');        
    }
    
    
    function getCancelationMail( $id ){        
        $today = date('Y-m-d');
//        $this->db->select('*');  
        $this->db->select('p.name as p_category, ps.label as p_label');
        $this->db->select('DATE_FORMAT(ps.datetime, "%d/%m/%Y %h:%i %p") as schedule');
        $this->db->select("DATEDIFF(ps.datetime, '{$today}') as c_days_left");
        $this->db->select('s.email,s.fname,s.id as student_id');        
        
        $this->db->from('practice_students as pb');                
        $this->db->join('practice_schedules as ps','ps.id=pb.practice_schedule_id', 'LEFT');        
        $this->db->join('practices as p','p.id=ps.practice_id', 'LEFT');
        
        $this->db->join('students as s','s.id=pb.student_id', 'LEFT');        
        $this->db->where('pb.id', $id );
        return $this->db->get()->row();        
    }
}