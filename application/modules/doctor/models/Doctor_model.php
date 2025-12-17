<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_model extends Fm_model {

    public $table = 'students';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($type, $gender, $country_id, $ethnicity_id,$cl_id, $stage_id, $internship, $uk_status, $work_uk, $training_courses,$specialties,$expariense,$interested,$key) {
        $this->__search( $type, $gender,$country_id,$ethnicity_id, $cl_id, $stage_id, $internship, $uk_status, $work_uk, $training_courses,$specialties,$expariense,$interested,$key);
        $this->db->from($this->table);
        return $this->db->get()->num_rows();
    }

    // get data with limit and search
    function get_limit_data($limit, $start, $type, $gender, $country_id, $ethnicity_id,$cl_id, $stage_id, $internship, $uk_status, $work_uk, $training_courses,$specialties,$expariense,$interested,$key)
    {
        $this->db->select("{$this->table}.*,c.name as country,pc.name as present_country");
        $this->db->join('countries as c', "c.id={$this->table}.country_id",'LEFT');
        $this->db->join('countries as pc', "pc.id={$this->table}.present_country_id",'LEFT');                                
        $this->db->order_by($this->id, $this->order);
        $this->__search($type, $gender,$country_id,$ethnicity_id, $cl_id, $stage_id, $internship, $uk_status, $work_uk, $training_courses,$specialties,$expariense,$interested,$key);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    function __search( $type, $gender,$country_id,$ethnicity_id,$cl_id, $stage_id, $internship, $uk_status, $work_uk, $training_courses,$specialties,$expariense,$interested,$key){
        
        $this->db->where('students.status !=', 'Archive');
        $this->db->where('number_type', $type);
        if($gender){ $this->db->where('gender', $gender ); }
        if($key){ 
            $this->db->group_start();            
            $this->db->like('fname', $key );             
            $this->db->or_like('lname', $key );             
            $this->db->or_like("CONCAT(fname, ' ', lname)", $key );
            $this->db->or_like("CONCAT('+',phone_code, phone)", $key );
            $this->db->or_like("CONCAT('+',whatsapp_code, whatsapp)", $key );
            $this->db->or_like('email', $key );
            $this->db->or_like('gmc_number', $key );  
            $this->db->group_end();
        }
        if($country_id){  $this->db->where('country_id', $country_id); }
        if($cl_id){  $this->db->where('present_country_id', $cl_id); }
        if($ethnicity_id) { $this->db->where('ethnicity_id', $ethnicity_id); }
        
        if($stage_id){
            $this->db->join('student_progressions as sp', "sp.student_id={$this->table}.id",'LEFT');
            $this->db->group_by('sp.student_id');
            $this->db->where('progression_id', $stage_id);
        }

        /* Job Profile Search */
        $this->db->join('student_job_profile as sjp', "sjp.student_id={$this->table}.id",'LEFT');
        if($internship){ $this->db->where('sjp.internship', $internship); }
        
        //$specialties,$expariense,$interested
        if($specialties){ 
            $this->db->join('student_job_specialty_rel as sp_rel', "sp_rel.student_id={$this->table}.id",'LEFT');
            $this->db->where_in('sp_rel.specialty_id', $specialties);  
            
            if($expariense){ $this->db->where('sp_rel.experience', $expariense ); }
            $this->db->group_by('students.id');
        }
        
        if($interested){ 
            $this->db->group_start();
            $this->db->like('sjp.interest_ids', $interested); 
            $this->db->group_end();
        }
        
        if($work_uk){ $this->db->where('sjp.right_to_work', $work_uk); }

        if($uk_status){
            if($uk_status == 'Other'){
                $this->db->where('sjp.uk_status_other !=', '');
            } else {
                $this->db->where('sjp.uk_status', $uk_status);
            }
        }

        if($training_courses){
            if($training_courses == 'Other'){
                $this->db->where('sjp.training_courses_other !=', '');
            } else {
                $this->db->where('sjp.training_courses', $training_courses);
            }
        }                
    }
    
    function saveShortlist($saveData) {
        
        if ($this->db->insert('recruitment_shortlists', $saveData)) {
            return $this->db->insert_id();
        }
        return false;
    }
    
    public function updateShortlist($id, $saveFields){
        $this->db->where('id', $id);
        if($this->db->update('recruitment_shortlists', $saveFields)){
            return true;
        }
        return false;
    }
    
    function saveRecruitmentPost($saveData) {
        if ($this->db->insert('recruitment_posts', $saveData)) {
            return $this->db->where('id', $this->db->insert_id())->get('recruitment_posts')->row() ;
        }
        return false;
    }
    
    public function getPostByName($name) {
        return $this->db->where('post', trim_fk($name))->get('recruitment_posts')->row();        
    }
    
    public function getShortlistByStudent($student_id) {
        
        $this->db->select('rs.*, rp.post, rp.user_id');
        $this->db->join('recruitment_posts as rp', 'rp.id=rs.post_id');
        $shortlisted = $this->db->where('student_id', $student_id)->get('recruitment_shortlists as rs')->row();
        return $shortlisted;
        
    }
    
    function get_shortlisted_post()
    {
        if(in_array($this->role_id, [1,2] )){
            $this->db->where('rp.created_by_admin', 'Yes');
        } else {
            $this->db->where('rp.user_id', $this->user_id);
        }
        $this->db->select("rp.id,rp.post, count(rs.student_id) as total_candidate");
        $this->db->from('recruitment_shortlists as rs');
        $this->db->join('recruitment_posts as rp', "rp.id=rs.post_id", 'LEFT');
        $this->db->group_by('rs.post_id');
        
        $this->db->order_by('rp.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    function get_post_by_id($post_id) {
        $this->db->select('rp.id,post as title, DATE_FORMAT(rp.created_at, "%d/%m/%Y") as date');
        $this->db->select("u.first_name, u.last_name, u.email");
        $this->db->where('rp.id', $post_id);
        $this->db->join('users as u', 'u.id=rp.user_id', 'LEFT');        
        return $this->db->get('recruitment_posts rp')->row();
    }
    
    function total_shortlist($post_id,$status = ''){                
        $this->db->where('post_id', $post_id);        
        if($status){ $this->db->where('status', $status); }                
        return $this->db->count_all_results('recruitment_shortlists');
    }
    
    function get_shortlist_by_id($post_id,$limit,$start,$status = '') {
        $this->db->select('rs.*, rp.post,s.number_type,s.gmc_number,s.occupation,c.name as country,pc.name as present_country');
        $this->db->select('s.title,s.fname,s.mname,s.lname,s.gender,s.email,s.phone_code,s.phone,s.photo');
        $this->db->from('recruitment_shortlists as rs');
        $this->db->join('recruitment_posts as rp', "rp.id=rs.post_id", 'LEFT');
        $this->db->join('students as s', "s.id=rs.student_id", 'LEFT');
        $this->db->join('countries as c', "c.id=s.country_id",'LEFT');
        $this->db->join('countries as pc', "pc.id=s.present_country_id",'LEFT');
        $this->db->where('rs.post_id', $post_id);
        $this->db->limit($limit, $start );        
        if($status){ $this->db->where('rs.status', $status); }        
        $this->db->order_by('rs.id', 'ASC');
        return $this->db->get()->result();
    }
    
    function get_shortlist_report()
    {
        $this->db->select("rp.id, rp.post, rp.created_at, count(rs.student_id) as total_candidate");
        $this->db->select("u.first_name, u.last_name, u.email, rp.post");
        $this->db->from('recruitment_posts as rp');
        $this->db->join('recruitment_shortlists as rs', "rp.id=rs.post_id", 'LEFT');
        $this->db->join('users as u', "u.id=rp.user_id", 'LEFT');
        $this->db->group_by('rp.id');
        $this->db->order_by('rp.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    
    /*Recruitment Manager ID */
    function getShortlistByRID( $id ){
        $this->db->select('rs.id, DATE_FORMAT(rs.created_at, "%d/%m/%Y") as date, rs.post as title');        
        $this->db->from('recruitment_posts as rs');  
        $this->db->where('rs.user_id', $id );
        $this->db->order_by('rs.id', 'DESC');
        return $this->db->get()->result();
    }
    
    function createNewPostTitle( $data ){
        $this->db->insert('recruitment_posts', $data );
        return $this->db->insert_id();
    }

}
