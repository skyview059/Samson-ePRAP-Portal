<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2016-10-05
 */

class Users extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->helper('users');
//        $this->load->helper('company/company');
        $this->load->library('form_validation');       
    }
    
    public function index() {
        $q          = urldecode_fk($this->input->get('q', TRUE));
        $status     = urldecode_fk($this->input->get('status', TRUE));
        $role_id    = intval($this->input->get('role_id', TRUE));
        $start      = intval($this->input->get('start'));
        
        $config['base_url']     = build_pagination_url( Backend_URL . 'users/', 'start' );
        $config['first_url']    = build_pagination_url( Backend_URL . 'users/', 'start' );
        $config['per_page']     = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Users_model->total_rows($q, $status , $role_id);
        $users = $this->Users_model->get_limit_data($config['per_page'], $start, $q, $status , $role_id);
        
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'users_data' => $users,
            'q' => $q,
            'role_id' => $role_id,
            'status' => $status,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('users/users/index', $data);
    }

    public function student($id) {
        $limit  = 25;        
        $page   = $this->input->get('page');
        $target = build_pagination_url("admin/users/student/{$id}", 'page', true );
        
        $total      = $this->Users_model->get_total_assign($id);
        $start      = startPointOfPagination($limit,$page);        
        $pagination = getPaginator($total,$page,$target,$limit);
        
        $students   = $this->Users_model->get_student($limit, $start, $id);
        
        $data = [
            'id'        => $id,
            'start'     => $start,
            'pagination' => $pagination,
            'total'     => $total,
            'students'  => $students 
        ];
                    
        $this->viewAdminContent('users/users/student', $data);
        
    }
    
    public function profile($id) {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'role_id' => getRoleName($row->role_id),
                'full_name' => "{$row->first_name} {$row->last_name}",                
                'email' => $row->email,                
                'mobile_number' => $row->mobile_code . $row->mobile_number,
                'add_line1' => $row->add_line1,
                'add_line2' => $row->add_line2,
                'city' => $row->city,
                'state' => $row->state,
                'postcode' => $row->postcode,
                'country_id' => getCountryName($row->country_id),
                'created_at' => $row->created_at,
                'status' => $row->status,
            );


            $this->viewAdminContent('users/users/profile', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url( Backend_URL . 'users'));
        }
    }

          
    public function create() { 
        $data = array(
            'role_id'       => set_value('role_id', 2), 
            'occupation'    => set_value('occupation'),
            'first_name'    => set_value('first_name'),
            'last_name'     => set_value('last_name'),
            'your_email'    => set_value('your_email'),                
            'password'      => set_value('password', randomPassword( 12 )),                
            'code'          => set_value('mobile[code]', 44),
            'number'        => set_value('mobile[number]'),
            'add_line1'     => set_value('add_line1'),
            'add_line2'     => set_value('add_line2'),
            'city'          => set_value('city'),
            'state'         => set_value('state'),
            'postcode'      => set_value('postcode'),
            'country_id'    => set_value('country_id', 218),                           
            'status'        => set_value('status', 'Active'),
            'allowed'       => set_value('allowed', []),
        );

        $this->viewAdminContent('users/users/create', $data );
    }

    public function create_action() {  
        
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
                                                    
            $this->create();            
               
        } else {
            $role_id = intval($this->input->post('role_id', TRUE));
            $mobile = $this->input->post('mobile');
            $allowed = json_encode($this->input->post('allowed', TRUE));
            
            $data = array(
                'role_id'       => $role_id,                                
                'allowed'       => ($role_id==7) ? $allowed : null,
                'first_name'    => $this->input->post('first_name', TRUE),
                'last_name'     => $this->input->post('last_name', TRUE),
                'email'         => $this->input->post('your_email', TRUE),
                'password'      => password_encription(  $this->input->post('password', TRUE) ),
                'mobile_code'   => $mobile['code'],
                'mobile_number' => $mobile['number'],
                'add_line1'     => $this->input->post('add_line1', TRUE),
                'add_line2'     => $this->input->post('add_line2', TRUE),
                'city'          => $this->input->post('city', TRUE),
                'state'         => $this->input->post('state', TRUE),
                'postcode'      => $this->input->post('postcode', TRUE),
                'country_id'    => $this->input->post('country_id', TRUE),
                'created_at'    => date("Y-m-d H:i:s"),
                'status'        => $this->input->post('status', TRUE),
            );
            
            $id = $this->Users_model->insert($data);   
            $this->session->set_flashdata('msgs', 'User Registed Successfully');
            redirect(site_url("admin/users/update/{$id}"));
        }
    }

    

    public function update($id) {
        $row = $this->Users_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url( Backend_URL . 'users/update_action'),
                'id' => set_value('id', $row->id),
                'role_id' => set_value('role_id', $row->role_id), 
                'allowed' => json_decode( $row->allowed ? $row->allowed : '' ),
                'first_name' => set_value('first_name', $row->first_name),
                'last_name' => set_value('last_name', $row->last_name),
                'your_email' => set_value('your_email', $row->email),                                
                'code'      => set_value('mobile[code]', $row->mobile_code),
                'number'        => set_value('mobile[number]', $row->mobile_number),
                'add_line1' => set_value('add_line1', $row->add_line1),
                'add_line2' => set_value('add_line2', $row->add_line2),
                'city' => set_value('city', $row->city),
                'state' => set_value('state', $row->state),
                'postcode' => set_value('postcode', $row->postcode),
                'country_id' => set_value('country_id', $row->country_id),
                'created_at' => set_value('created_at', $row->created_at),                
                'status' => set_value('status', $row->status),
            );
            
            $this->viewAdminContent('users/users/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Record Not Found');
            redirect(site_url('users'));
        }
    }

    public function update_action() {
        $id = (int) $this->input->post('id', TRUE);
        $role_id = intval($this->input->post('role_id', TRUE));
        $mobile = $this->input->post('mobile');
        $allowed = json_encode( $this->input->post('allowed', TRUE) );       
        
        $data = array(
            'role_id'       => $role_id,                                
            'allowed'       => ($role_id==7) ? $allowed : null,
            'first_name'    => $this->input->post('first_name', TRUE),
            'last_name'     => $this->input->post('last_name', TRUE),
            'email'         => $this->input->post('your_email', TRUE),                
            'mobile_code'   => $mobile['code'],
            'mobile_number' => $mobile['number'],
            'add_line1'     => $this->input->post('add_line1', TRUE),
            'add_line2'     => $this->input->post('add_line2', TRUE),
            'city'          => $this->input->post('city', TRUE),
            'state'         => $this->input->post('state', TRUE),
            'postcode'      => $this->input->post('postcode', TRUE),
            'country_id'    => $this->input->post('country_id', TRUE)
        );
        $this->Users_model->update($id, $data);                                    
        $this->session->set_flashdata('msgs', 'User Update Successfully');
        redirect(site_url("admin/users/update/{$id}"));
       
    }

    public function freeze($id) {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = (array) $row;                       
            $this->viewAdminContent('users/users/freeze', $data);
        } else {
            $this->session->set_flashdata('msge', 'User Not Found');
            redirect(site_url('users'));
        }
    }
    
    public function setStatus(){
        $id         = $this->input->post('id');
        $status     = $this->input->post('status');        
        $this->db->set('status', $status);
        $this->db->where('id', $id);        
        $this->db->update('users');        
        if($status =='Inactive'){
            echo ajaxRespond('OK', "<p class='ajax_notice'>Account Freezed</p>");
        } else {
            echo ajaxRespond('OK', "<p class='ajax_success'>Account UnFreezed</p>");
        }                        
    }

    public function _rules(){         	
        $this->form_validation->set_rules('last_name', 'last name', 'trim|required');		
        $this->form_validation->set_rules('your_email', 'your email', 'trim|valid_email|required|is_unique[users.email]', 
                [ 'is_unique' => 'This email is already in use', 'valid_email' => 'Enter a valide email address']);
        
        $this->form_validation->set_rules('role_id', 'role_id', 'required');
        if($this->input->post('role_id')==7){
            $this->form_validation->set_rules('allowed[]', 'allowed', 'required');
        }
        $this->form_validation->set_rules('mobile[number]', 'mobile', 'required');
        $this->form_validation->set_rules('password', 'password field', 'required|min_length[6]|max_length[12]');        
        $this->form_validation->set_error_delimiters('<p class="text-red">', '</p>');	
    }

    public function image_upload($photo, $id = 0) {
        $handle = new upload($photo);
        if ($handle->uploaded) {
            $prefix                     = $id;
            $handle->file_new_name_body = 'user_photo';
            $handle->image_resize       = true;
            $handle->image_x            = 400;
            $handle->image_ratio_y      = true;
            $handle->allowed            = array(
                'image/jpeg', 
                'image/jpg', 
                'image/gif', 
                'image/png', 
                'image/bmp'
            );
            $handle->file_new_name_body = uniqid($prefix) . '_' . md5(microtime()) . '_' . time();           
            $handle->process( 'uploads/users_profile/');           
            $handle->processed;
            return $receipt_img = $handle->file_dst_name;
        }
    }
    
                   
    public function _menu() {
        return buildMenuForMoudle([
            'module' => 'User',
            'icon' => 'fa-users',
            'href' => 'users',
            'children' => [
                [
                    'title' => 'All User',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users'
                ],[
                    'title' => ' + Add New',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users/create'
                ],[
                    'title' => 'Role / ACL',
                    'icon' => 'fa fa-circle-o',
                    'href' => 'users/roles'
                ],[
                    'title' => 'Logins Log',
                    'icon' => 'fa fa-list',
                    'href' => 'users/login_history'
                ],[
                    'title' => 'Logins Graph VIew',
                    'icon' => 'fa fa-pie-chart',
                    'href' => 'users/login_history/graph'
                ]
            ]
        ]);
    }
               
    public function password( $id ){  
        
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'            => $row->id,
                'first_name'    => $row->first_name,
                'last_name'     => $row->last_name,
                'email'         => $row->email,
                'password'      => $row->password,
                'status'        => $row->status
            );
            $this->viewAdminContent('users/users/password', $data);
        } else {
            $this->session->set_flashdata('msge', 'Record Not Found');
            redirect( site_url( Backend_URL . 'users') );
        }        
    }
    
    public function reset_password(){
        ajaxAuthorized();
        $user_id  = intval( $this->input->post('user_id') );
        $new_pass = $this->input->post('new_pass');
        $con_pass = $this->input->post('con_pass');
                     
        $stren  = password_strength($new_pass);
        if(!empty($stren)){
            echo ajaxRespond('Fail', $stren );
            exit;
        }
        
        
        if ($new_pass != $con_pass) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Confirm Password Not Match</p>');                
            exit;
        }
     
        $hass_pass = password_encription( $new_pass ); 
        
        $this->db->set('password', $hass_pass);
        $this->db->where('id', $user_id);
        $this->db->update('users');
        echo ajaxRespond('OK', '<p class="ajax_success">Password Reset Successfully</p>');                  
    }
   
    public function set_status(){
        
        ajaxAuthorized();
                
        $id = $this->input->post('id', true );
        $status = $this->input->post('status', true );
        
        $data['status'] =  $status;
        $this->Users_model->update($id, $data);
                
        $returnStatus = expHeadStatus( $status );
        
        echo ajaxRespond('OK', $returnStatus );
    } 
    
}
