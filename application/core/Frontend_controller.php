<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Frontend_controller extends MX_Controller {

    public $student_id;

    public function __construct() {
        parent::__construct();
        $this->student_id = intval(getLoginStudentData('student_id'));
        
        if($this->isAccountCheck() == false && $this->student_id){
            redirect( site_url('logout'));   
        }
//        $this->output->enable_profiler(TRUE);
    }

    public function index() {
        http_response_code(404);
//        $this->viewFrontContent('frontend/login');
        $this->viewFrontContent('frontend/404');
    }

    public function not_found_page() {
        $this->viewFrontContent('frontend/404');
    }

    public function login(){
        if($this->student_id){
            redirect(site_url());
        }

        $this->viewFrontContent('frontend/login');
    }        
    
    public function viewFrontContent($view, $data = []) {
        $this->load->view('frontend/header', $data);
        $this->load->view($view, $data);
        $this->load->view('frontend/footer');
    }
    
    protected function viewMemberContent($view, $data = [])
    {
        $active_menu = $this->uri->segment(1);
        $this->load->view('frontend/header', ['display_none' => '']  );
//        $this->load->view('frontend/top_menu', ['active' => $active_tab]);
        $this->load->view('frontend/sidebar', ['active' => $active_menu]);
        $this->load->view('frontend/' . $view, $data);
        $this->load->view('frontend/footer', ['display_none' => ''] );
    }

    public function isPost(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        } else {
            redirect('404');
        }
    }
    
    private function isAccountCheck(){
        
        $admin_id     = (int) getLoginUserData('user_id');
        if( $admin_id ){
            return true;
        }
                
        $this->db->select('status, password');
        $this->db->where('id', $this->student_id );
        $student = $this->db->get('students')->row();
        if($student){
            //Get password from student data
            $password = getLoginStudentData('password');            
            return (password_verify($password, $student->password)) ? true : false;
        }
        return false;
        
    }
}