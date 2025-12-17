<?php
/**
 * Description of Admin_controller
 *
 * @author Kanny
 */

class Admin_controller extends MX_Controller {  

    protected $user_id;
    protected $role_id;
    public $SiteTitle;
    public $CompanyRegNo = false;

    public function __construct() {
        parent::__construct();        

        $this->load->library('user_agent');
        $this->load->helper('security');
        $this->load->helper('acl_helper');        
        $this->load->model('module/Acl_model', 'acls');
        $this->user_id  = (int) getLoginUserData('user_id');
        $this->role_id  = (int) getLoginUserData('role_id');
        $this->SiteTitle    = getSettingItem('SiteTitle');
        $this->CompanyRegNo = getSettingItem('CompanyRegistrationNo');
        
        if($this->isActiveAccount() == false && $this->user_id){
            redirect( base_url('auth/logout'), 'refresh' );   
        }
        if($this->user_id <= 0){
            redirect( site_url('admin/login'));
        }
        $this->set_admin_prefix( $this->uri->uri_string() );
        
//         $this->output->enable_profiler(TRUE);
    }

    private function isActiveAccount(){
        $this->db->cache_on();
        $this->db->select('status, password');
        $this->db->where('id', $this->user_id );
        $user = $this->db->get('users')->row();
        $this->db->cache_off();
        
        //Get password from user data
        $password = $this->security->xss_clean(trim_fk(getLoginUserData('password')));
        return ($user && $user->status == 'Active' && password_verify($password, $user->password)) ? true : false;
    }

    private function check_access( $string = 'dashboard'){
        // $backend_uri = 'admin'; // prefix no need to touch        
        $controller = empty($this->uri->segment(2)) ? $string : $this->uri->segment(2);       
        $method     = empty($this->uri->segment(3)) ? '' : '/'.$this->uri->segment(3);        
        $access_key = $controller . $method;        
        return $this->acls->checkPermission($access_key, $this->role_id);
    }
    
    private function set_admin_prefix( $string = '/'){
        if($this->uri->segment(1) != 'admin'){
            redirect( site_url('admin') .'/'. $string );  
        };
    }	         

    public function viewAdminContent($view, $data = []){
        if( $this->input->is_ajax_request() ){
            $this->load->view($view, $data);        
        } else {
            $this->load->view('backend/layout/header');
            $this->load->view('backend/layout/sidebar'); 
            if( $this->check_access( $view ) ){                
                $this->load->view($view, $data);    
            } else {
                $this->load->view('backend/restrict');    
            }
            $this->load->view('backend/layout/footer');
        }  				       
    }   
    
    protected function _get_course_names( $prefix = 'exam'){
        
        $this->db->cache_on();
        $this->db->select('id,name');
        $exams = $this->db->get('exams')->result();
        
        $btn = [];
        foreach($exams as $exam ){
            $qty = '';
//            if($prefix == 'exam'){
//                $count = Tools::getSchedules($exam->id);
//                $qty = "<sup>({$count})</sup>";
//            }
            $btn[] = [
//                'title' => "{$exam->name} {$qty}",
                'title' => "{$exam->name}",
                'icon' => 'fa fa-circle-o',
                'href' => "{$prefix}?id={$exam->id}"
            ];
        }
        return $btn;
    }
}