<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-08-11
 */

class Doctor extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Doctor_model');
        $this->load->helper('doctor');
        $this->load->library('form_validation');
    }

    public function index()
    {

        $type = $this->input->get('type');
        if (empty($type)) {
            $type = 'GMC';
        }
        $gender = $this->input->get('gender');
        $country_id = (int)$this->input->get('country_id');
        $cl_id = (int)$this->input->get('cl_id');
        $ethnicity_id = (int)$this->input->get('ethnicity_id');
        $stage_id = (int)$this->input->get('stage_id');

        $internship = $this->input->get('internship'); // Yes or No
        $specialties = $this->input->get('specialties');
        $expariense = (int)$this->input->get('expariense');
        $interested = (int)$this->input->get('interested');

        $uk_status = $this->input->get('uk_status');
        $work_uk = $this->input->get('work_uk');
        $training_courses = $this->input->get('training_courses');
        $key = $this->input->get('q');


        $page = (int)$this->input->get('p');
        $target = build_pagination_url(Backend_URL . 'doctor', 'p', true);
        $limit = 35;
        $start = startPointOfPagination($limit, $page);

        $total_rows = $this->Doctor_model->total_rows($type, $gender, $country_id, $ethnicity_id, $cl_id,
            $stage_id, $internship, $uk_status, $work_uk, $training_courses, $specialties, $expariense, $interested,$key);
        $doctors = $this->Doctor_model->get_limit_data($limit, $start, $type, $gender, $country_id, $ethnicity_id, $cl_id,
            $stage_id, $internship, $uk_status, $work_uk, $training_courses, $specialties, $expariense, $interested,$key);        

        $data = array(
            'doctors' => $doctors,
            'pagination' => getPaginator($total_rows, $page, $target, $limit),
            'start' => $start,
            'type' => $type,
            'gender' => $gender,
            'country_id' => $country_id,
            'ethnicity_id' => $ethnicity_id,
            'cl_id' => $cl_id,
            'stage_id' => $stage_id,
            'internship' => $internship,
            'uk_status' => $uk_status,
            'work_uk' => $work_uk,
            'training_courses' => $training_courses,
            'specialties' => $specialties,
            'expariense' => $expariense,
            'interested' => $interested,
            'key' => $key,
        );
        if ($this->resticted($type)) {
            
            if (in_array($this->role_id, [1, 2])) {
                $this->viewAdminContent('doctor/doctor/index_admin', $data);
            } else {
                $this->viewAdminContent('doctor/doctor/index', $data);
            }
                        
        } else {
            $this->viewAdminContent('doctor/doctor/resticted');
        }
    }

    public function report()
    {

        $report = $this->Doctor_model->get_shortlist_report();        

        $data = array(
            'report' => $report,
            'start' => 0
        );
        $this->viewAdminContent('doctor/doctor/report', $data);
    }


    private function resticted($type)
    {
        if (empty($type)) {
            return false;
        }

        if (in_array($this->role_id, [1, 2])) {
            return true;
        } else {
            $allowed = getUserData($this->user_id, 'allowed');

            if (empty($allowed)) {
                $allowed = '["What"]';
            }

            $types = json_decode($allowed, true);
            return (in_array(strtoupper($type), $types)) ? true : false;
        }
    }

    public function timeline($id)
    {
        $row = $this->Doctor_model->get_by_id($id);
        $this->load->model('file/File_model', 'File_model');
        $this->load->model('student/Student_model', 'Student_model');

        if ($row) {
            $data = array(
                'id' => $row->id,
                'number_type' => $row->number_type,
                'gmc_number' => $row->gmc_number,
                'title' => $row->title,
                'fname' => $row->fname,
                'mname' => $row->mname,
                'lname' => $row->lname,
                'email' => $row->email,
                'phone_code' => $row->phone_code,
                'phone' => $row->phone,
                'whatsapp_code' => $row->whatsapp_code,
                'whatsapp' => $row->whatsapp,
                'password' => $row->password,
                'country_id' => getCountryName($row->country_id),
                'present_country_id' => getCountryName($row->present_country_id),
                'ethnicity_id' => $row->ethnicity_id,
                'gender' => $row->gender,
                'photo' => $row->photo,
                'status' => $row->status,
                'verified' => $row->verified,
                'exam_id' => $row->exam_id,
                'exam_centre_id' => $row->exam_centre_id,
                'exam_date' => $row->exam_date,
                'occupation' => $row->occupation,
                'purpose_of_registration' => $row->purpose_of_registration,
                'address_line1' => $row->address_line1,
                'address_line2' => $row->address_line2,
                'postcode' => $row->postcode,
                'created_at' => globalDateTimeFormat($row->created_at),
                'updated_at' => globalDateTimeFormat($row->updated_at),
            );

            $data['files'] = $this->File_model->get_by_student($id);
            $data['progress'] = $this->Student_model->get_progress($id);

            //Get Already shortlisted data
            $shortlisted = $this->Doctor_model->getShortlistByStudent($row->id);
            $data['shortlisted_id'] = !empty($shortlisted->id) ? $shortlisted->id : null;
            $data['shortlisted_status'] = !empty($shortlisted->status) ? $shortlisted->status : null;
            $data['job_position'] = !empty($shortlisted->post) ? $shortlisted->post : null;
            $data['remarks'] = !empty($shortlisted->remarks) ? $shortlisted->remarks : null;
            $data['manager_id'] = !empty($shortlisted->user_id) ? $shortlisted->user_id : 0;
            $data['role_id'] = $this->role_id;
            $data['job_profile'] = $this->StudentJobProfile($id);
            if ($this->resticted($row->number_type)) {
                $this->viewAdminContent('doctor/doctor/timeline', $data);
            } else {
                $this->viewAdminContent('doctor/doctor/resticted');
            }


        } else {
            $this->session->set_flashdata('msge', '<p class="ajax_error">Doctor Not Found</p>');
            redirect(site_url(Backend_URL . 'doctor'));
        }
    }

    private function StudentJobProfile($student_id){
        $this->db->select('jp.*');
        $this->db->from('student_job_profile as jp');
        $this->db->where('jp.student_id', $student_id);
        return $this->db->get()->row();
    }

    public function _menu()
    {
        $menus = [
            'module' => 'Recruitment Database',
            'icon' => 'fa-user-md',
            'href' => 'doctor',
            'children' => $this->_get_types()
        ];

        return buildMenuForMoudle($menus);
    }

    protected function _get_types()
    {

        if (in_array($this->role_id, [1, 2])) {
            $types = [
                'GMC' => 'Doctor',
                'GDC' => 'Dentist',
                'NMC' => 'Nurse',
            ];
        } else {
            $allowed = getUserData($this->user_id, 'allowed');
            $types = json_decode($allowed, true);
        }
        $btn[] = [
            'title' => 'Admin Report',
            'icon' => 'fa fa-bar-chart-o',
            'href' => 'doctor/report'
        ];
        foreach ($types as $key => $val ) {
            $btn[] = [
                'title' => "{$val} " . Tools::countStudent($key),
                'icon' => 'fa fa-circle-o',
                'href' => "doctor?type={$key}"
            ];
        }

        $btn[] = [
            'title' => 'Suggested/Shortlist',
            'icon' => 'fa fa-check-square-o',
            'href' => 'doctor/shortlisted'
        ];
        return $btn;
    }

    public function shortlisted()
    {

        $shortlisted = $this->Doctor_model->get_shortlisted_post();

        $data = array(
            'shortlisted' => $shortlisted,
            'start' => 0
        );
        $this->viewAdminContent('doctor/doctor/shortlisted', $data);
    }

    public function shortlist_details($post_id)
    {
        $status = $this->input->get('status');        
        $page   = intval($this->input->get('p'));
        $limit  = 25;
        $start  = startPointOfPagination($limit,$page);
        $target = build_pagination_url(Backend_URL . "doctor/shortlist/{$post_id}", 'p', true);
        
        $post       = $this->Doctor_model->get_post_by_id($post_id);        
        $total      = $this->Doctor_model->total_shortlist($post_id, $status);
        $canidates  = $this->Doctor_model->get_shortlist_by_id($post_id,$limit,$start, $status);
        
        
        $data = array(
            'status'        => $status,
            'post'          => $post,
            'canidates'     => $canidates,
            'pagination'    => getPaginator($total, $page, $target, $limit ),            
            'start'         => $start,
            'role_id'       => $this->role_id,
            'post_id'       => $post_id
        );
        
        $this->viewAdminContent('doctor/doctor/shortlist_details', $data);
    }

    public function shortlist_save()
    {
        ajaxAuthorized();

        $manager_id = $this->user_id;
        $created_by_admin = 'No';

        if (in_array($this->role_id, [1, 2])) {
            $manager_id = $this->input->post('manager_id');
            $created_by_admin = 'Yes';
        }

        $student_id = $this->input->post('student_id');
        $status = $this->input->post('status');
        $post_id = $this->input->post('post_id');
        $remarks = $this->input->post('remarks');

        if (!$status) {
            echo ajaxRespond('FAIL', 'Status must be selected!');
            exit;
        }

        if (empty($post_id)) {
            echo ajaxRespond('FAIL', 'Please select or create post!');
            exit;
        }

        //Get recruitment posts info by post title
        $post = $this->Doctor_model->getPostByName($post_id);
        if (empty($post)) {
            $post_data = [
                'user_id' => $manager_id,
                'post' => trim_fk($post_id),
                'created_by_admin' => $created_by_admin,
                'created_at' => date("Y-m-d H:i:s")
            ];

            $post = $this->Doctor_model->saveRecruitmentPost($post_data);

        }//If job position is empty

        $shortlisted = $this->Doctor_model->getShortlistByStudent($student_id);

        if ($shortlisted) {
            $updateData = [
                'status' => $status,
                'post_id' => $post->id,
                'remarks' => $remarks,
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $result = $this->Doctor_model->updateShortlist($shortlisted->id, $updateData);

            if ($result) {
                echo ajaxRespond('OK', 'The shortlisted candidate info updated!');
            } else {
                echo ajaxRespond('FAIL', 'The shortlisted candidate info Could not be updated!');
            }

        } else {
            $data = [
                'student_id' => $student_id,
                'status' => $status,
                'post_id' => $post->id,
                'remarks' => $remarks,
                'created_at' => date("Y-m-d H:i:s")
            ];

            $result = $this->Doctor_model->saveShortlist($data);

            if ($result) {
                echo ajaxRespond('OK', 'This candidate has been successfully shortlisted!');
            } else {
                echo ajaxRespond('FAIL', 'This candidate Could not be shortlisted!');
            }
        }
    }
    
    public function delete_post( $post_id )
    {        
        $sql = "DELETE recruitment_posts, recruitment_shortlists
                FROM recruitment_posts
                INNER JOIN recruitment_shortlists ON recruitment_shortlists.post_id = recruitment_posts.id                
                WHERE recruitment_posts.id = {$post_id}";
        
        $this->db->query( $sql );
        $rows = $this->db->affected_rows();
        
        $this->session->set_flashdata('msgs', "Post Deleted Successfully. Total {$rows} row(s) deleted");
        redirect(site_url(Backend_URL . 'doctor/report'));
    }
    
    public function delete_candidate()
    {
        ajaxAuthorized();
        $post_id    = (int)$this->input->post('post_id');
        $student_id = (int)$this->input->post('student_id');
        
        $this->db->where('post_id',$post_id);
        $this->db->where('student_id',$student_id);
        $this->db->delete('recruitment_shortlists');        
        $row = $this->db->affected_rows();
        if($row){
            echo ajaxRespond('OK', 'Removed');                    
        } else {
            echo ajaxRespond('Fail', 'Fail');        
        }

    }

    public function set_status()
    {
        ajaxAuthorized();
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $updateData = [
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $result = $this->Doctor_model->updateShortlist($id, $updateData);
        if ($result) {
            echo ajaxRespond('OK', 'Status successfully updated!');
        } else {
            echo ajaxRespond('FAIL', 'Status Could not be updated!');
        }
    }
    
    
    private function isAssigned( $student_ids = []){
        if(empty($student_ids)){
            return FALSE;
        }
        $this->db->select("student_id, rs.created_at, CONCAT( COALESCE(title, ''), ' ', COALESCE(fname, ''), ' ', COALESCE(lname)) AS full_name");
        $this->db->from('recruitment_shortlists as rs');
        $this->db->join('students as s', 's.id=rs.student_id', 'INNER');
        
//        $this->db->join('recruitment_posts as p', 'p.id=rs.post_id', 'INNER');
//        $this->db->where('p.user_id', $manager_id );
        
        $this->db->where_in('student_id', $student_ids );
        $this->db->group_by('student_id' );
        
        $students = $this->db->get()->result();
        
        if(!$students){
            return FALSE;
        }
        
        $student = [];
        foreach( $students as $s ){
            $student[] = $s->full_name;
        }
        return implode(', ', $student);
    }


    /* For Add in Suggesation list */
    public function get_shortlist(){
        $manager_id     = (int) $this->input->post('recruitment_manager_id');
        $student_ids    = $this->input->post('s_ids');
        
        if(empty($student_ids) or empty($manager_id)){
            die( 'Please select candidate & Recruitment Manager properly . <style type="text/css"> .modal-footer{ display:none;}</style>' );
        }
        
        $posts      = $this->Doctor_model->getShortlistByRID( $manager_id );
        $output     = '';        
        
        $rm_name    = getUserNameByID( $manager_id );
        $students   = $this->isAssigned( $student_ids );
        
        $style1      = '';
        $style2      = '';
        if($students){
            $style1  = 'display:none;';
            $output .= '<style type="text/css"> .modal-footer{ display:none;}</style>';
            $output .= "<p class='text-red'><strong>{$students}</strong> has already been referred to <strong>{$rm_name}</strong> are you sure, you want to refer to another recruitment manager?<p/>";
        } else {
            $style2  = 'display:none;';
            $output .= '<style type="text/css"> .modal-footer{ display:block;}</style>';
        }
        
        $output .= "<p class='text-bold show_hiddens' style='{$style1}'>Manager Name: {$rm_name} </p>";
        $output .= "<ul class='show_hiddens' style=\"list-style:none; padding-left:5px; {$style1} \">";
        
        $output .=  "<li><label class='radio-inline' style='width:100%;'><input type='radio' checked name='post_id' value='0'>";
        $output .=  '<input name="other" placeholder="Open New Job Post & Assign or Select From Bellow" type="text" class="form-control" /></label><br/> </li>';
                                
        foreach($posts as $post ){            
            $output .= "<li><label><input type='radio' name='post_id' value=\"{$post->id}\"> ";
            $output .= "{$post->title} ({$post->date})</label><br/></li>";
        }
        

        $output .= '</ul>';   
        
        $output .= "<div style=\"{$style2}\" class=\"text-center revers_hiddens\">";
        $output .= '<p><span class="btn btn-xs btn-danger" onclick="return no();">No, Cancel</span> &nbsp; &nbsp;';
        $output .= '<span class="btn btn-xs btn-success" onclick="return yes();">Yes, Continue</span></p>';
        $output .= '</div>';
        
        echo $output;
    }
    
    public function del_shortlist(){
        ajaxAuthorized();
        
        $post_id = (int) $this->input->post('id');
        
        
        $this->db->trans_start();
            $this->db->where('id', $post_id );
            $this->db->delete('recruitment_posts');
            $this->db->where('post_id', $post_id );
            $this->db->delete('recruitment_shortlists');
        $this->db->trans_complete();
        
        echo ajaxRespond('OK','Deleted');
    }
    
    /* Save Suggestion List for Recruitment Manger by Admin */
    public function save_suggestion(){
        ajaxAuthorized();                       
        $manager_id = (int)$this->input->post('recruitment_manager_id');
        $students   = $this->input->post('s_ids');
        $post_id    = (int) $this->input->post('post_id');
        
        $this->db->trans_start();
        if($post_id == 0){            
            $post_id = $this->Doctor_model->createNewPostTitle([
                'user_id' => $manager_id,
                'post' => $this->input->post('other'),
                'created_by_admin' => 'Yes',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
        
        $data = [];
        foreach ($students as $id ){
            
            $match = $this->isInPostList($post_id, $id);
            if($match == 0 ){
                $data[] = [
                    'post_id'       => $post_id,
                    'student_id'    => $id,
                    'status'        => 'Suggested',
                    'remarks'       => 'Suggested by Dr.Samson',
                    'created_at'    => date('Y-m-d H:i:s')
                ];
            }
        }
        if($data){
            $this->db->insert_batch('recruitment_shortlists', $data);
        }                
        $this->db->trans_complete();

        echo ajaxRespond('OK', '<p class="ajax_success">Added Suggestion List</p>');
    }
    
    private function isInPostList( $post_id, $student_id){
        $this->db->where('post_id', $post_id );
        $this->db->where('student_id', $student_id );
        return $this->db->count_all_results( 'recruitment_shortlists' );        
    }

    public function suggestion_action(){
        ajaxAuthorized();
       
        $manager_id = (int)$this->input->post('recruitment_manager_id');
        $students   = $this->input->post('s_ids');
        if(empty($manager_id)){
            echo ajaxRespond('Fail', '<p class="ajax_error">Please select recruitment manager... </p>');
            exit;
        }
        if(empty($students)){
            echo ajaxRespond('Fail', '<p class="ajax_error">Their is nothing to save... </p>');
            exit;
        }
        $data = [];
        foreach ($students as $id ){
            $data[] = [
                'user_id'       => $manager_id,
                'student_id'    => $id,
                'timestamp'     => date('Y-m-d H:i:s')
            ];
        }
        
        $this->db->trans_start();        
        $this->db->insert_batch('user_students_relation', $data);
        $this->db->trans_complete();

        echo ajaxRespond('OK', '<p class="ajax_success">Student Assign Successfully</p>');
    }
}