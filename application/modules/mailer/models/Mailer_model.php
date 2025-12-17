<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Mailer_model extends Fm_model {
    
    function __construct() {
        parent::__construct();
    }
    
    function get_student($type,$exam_id=0,$centre_id=0,$till_date=null,$case=null,$date_from=null,$date_to=null,$student_ids = [] ) {
                        
        $this->db->select('id,title,fname,lname,email');
        $this->db->from('students');
        
        if(in_array($type, ['GMC','NMC','GDC'])){ $this->db->where('number_type', $type ); }   
        
        if($exam_id){ $this->db->where('exam_id', $exam_id ); }   
        if($centre_id){ $this->db->where('exam_centre_id', $centre_id ); }   
        
        if($case == '30_days') { $this->db->where('created_at >=', date('Y-m-d', strtotime('-30 Days'))); }
        if($case == '90_days') { $this->db->where('created_at >=', date('Y-m-d', strtotime('-90 Days'))); }
        if($case == 'custom_dates') { $this->db->where('created_at >=', date('Y-m-d', strtotime('-90 Days'))); }
        if($case == 'custom_student') { $this->db->where_in('id', $student_ids);}
        
        
        if($till_date) { $this->db->where('exam_date <=', $till_date ); }
                
        $this->db->order_by('id','ASC');
        return $this->db->get()->result();
    }
    
    function get_student_job_notification($type, $gender = 0,$contact_by_rm = 0, $job_profile = 0, $job_specialty_ids = []) {
        
        $student_ids = [];
        if(!empty($job_specialty_ids)){
            $this->db->select('student_id');
            $this->db->from('student_job_specialty_rel');
            $this->db->where_in('specialty_id', $job_specialty_ids);
            $this->db->group_by('student_id');
            $job_specialties = $this->db->get()->result_array();
            $student_ids = array_column($job_specialties, 'student_id');
        }
        
        $this->db->select('s.id,s.title,s.fname,s.lname,s.email');
        $this->db->from('students as s');
        if(!empty($job_profile)){
           $this->db->join('student_job_profile sjp', 'sjp.student_id = s.id', 'inner'); 
        }
        if(in_array($type, ['GMC','NMC','GDC'])){ $this->db->where('number_type', $type ); }   
        
        if($gender){ $this->db->where('s.gender', $gender ); }   
        if($contact_by_rm){ $this->db->where('s.contact_by_rm', $contact_by_rm ); }   
        if(!empty($student_ids)) { 
           
            $this->db->where_in('s.id', $student_ids);
        }
        
        $this->db->order_by('s.id','ASC');
        return $this->db->get()->result();
    }
}
