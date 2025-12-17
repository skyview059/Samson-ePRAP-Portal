<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 2020-04-27
 */

class Personal_dev_plan extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Personal_dev_plan_model');
        $this->load->helper('personal_dev_plan');
        $this->load->library('form_validation');
    }

    public function index(){
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'personal_dev_plan/', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'personal_dev_plan/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Personal_dev_plan_model->total_rows($q);
        $personal_dev_plans = $this->Personal_dev_plan_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'p_dev_plans' => $personal_dev_plans,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        
        $this->viewAdminContent('personal_dev_plan/personal_dev_plan/index', $data);
    }

    public function details($id){
        $rows = $this->Personal_dev_plan_model->get_details($id);       
        
        if ($rows) {
            $data = array(
		'id' => $id,
                'student_name' => $this->Personal_dev_plan_model->getStudentName($id),		
		'plans' => $rows,
	    );
            $this->viewAdminContent('personal_dev_plan/personal_dev_plan/details', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Personal Development Plan Not Found</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan'));
        }
    }
    
    public function create(){
        $id = (int) $this->input->get('id');
        $data = array(
            'id' => set_value('id'),
            'button' => 'Create',
            'student_id' => set_value('student_id', $id ),
            'locked' => ($id) ?  'disabled="disabled" ' : '',
            'continue' => (!$id) ?  'disabled="disabled" ' : '',
            'hidden' => (!$id) ?  'hidden ' : '',
            'notice_hidden' => (!$id) ?  '' : 'hidden',
            'created_at' => date('Y-m-d H:i:s'),
            'action' => site_url( Backend_URL . 'personal_dev_plan/create_action'),	    	   
	    'domains' => $this->Personal_dev_plan_model->get_domains(),
	);
        $this->viewAdminContent('personal_dev_plan/personal_dev_plan/create', $data);
    }
    
    public function create_action(){
                        
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = $this->input->post('plan');            
            $this->db->insert_batch('personal_dev_plans', $data);
            Tools::fixPrimaryKey();
            $this->session->set_flashdata('message', '<p class="ajax_success">Personal Development Plan Added Successfully</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan'));
        }
    }
    
    public function update($id){
                
        $plans = $this->Personal_dev_plan_model->get_details($id);        
        if ($plans) {
            $data = array(
                'button' => 'Update',
                'action' => site_url( Backend_URL . 'personal_dev_plan/update_action'),
		'id' => set_value('id', $id ),
		'created_at' => set_value('created_at', date('Y-m-d H:i:s') ),
		'student_id' => set_value('student_id', $id),
		'student_name' => $this->Personal_dev_plan_model->getStudentName($id),
		'plans' => $plans,
	    );
            $this->viewAdminContent('personal_dev_plan/personal_dev_plan/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Personal Development Plan Not Found</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan'));
        }
    }
    
    public function update_action(){
        
        $this->_rules();

        $student_id = (int) $this->input->post('student_id'); 
        if ($this->form_validation->run() == FALSE) {
            $this->update( $student_id );
        } else {

            $this->db->where('student_id', $student_id)->delete('personal_dev_plans');
            $data = $this->input->post('plan');            
            $this->db->insert_batch('personal_dev_plans', $data);
            Tools::fixPrimaryKey();
            
            $this->session->set_flashdata('message', '<p class="ajax_success">Personal Development Plan Updated Successlly</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan/update/'. $student_id  ));
        }
    }

    public function delete($id){
        $row = $this->Personal_dev_plan_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'student_id' => $row->student_id,
		'domain_id' => $row->domain_id,
		'specific_development' => $row->specific_development,
		'theses_development' => $row->theses_development,
		'i_have_achieved' => $row->i_have_achieved,
		'timescale' => $row->timescale,
		'evaluation_and_outcome' => $row->evaluation_and_outcome,
		'created_at' => $row->created_at,
	    );
            $this->viewAdminContent('personal_dev_plan/personal_dev_plan/delete', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Personal Development Plan Not Found</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan'));
        }
    }

    public function delete_action($id){
        $row = $this->Personal_dev_plan_model->get_by_id($id);

        if ($row) {
            $this->Personal_dev_plan_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Personal Development Plan Deleted Successfully</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Personal Development Plan Not Found</p>');
            redirect(site_url( Backend_URL. 'personal_dev_plan'));
        }
    }
    
    public function _menu(){       
        return buildMenuForMoudle([
            'module'    => 'Personal Dev. Plan',
            'icon'      => 'fa-bar-chart-o',
            'href'      => 'personal_dev_plan',                    
            'children'  => [
                [
                    'title' => 'All Plan',
                    'icon'  => 'fa fa-bars',
                    'href'  => 'personal_dev_plan'
                ],[
                    'title' => ' |_ Add New',
                    'icon'  => 'fa fa-plus',
                    'href'  => 'personal_dev_plan/create'
                ],[
                    'title' => 'Manage Domains',
                    'icon'  => 'fa fa-bullseye',
                    'href'  => 'personal_dev_plan/domain'
                ]
            ]        
        ]);
    }

    public function _rules(){
	$this->form_validation->set_rules('student_id', 'student id', 'trim');
	$this->form_validation->set_rules('domain_id', 'domain id', 'trim');
	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    
    public function check(){        
        $id = (int) $this->input->post('id');        
        $this->db->where('student_id', $id);
        echo $this->db->count_all_results('personal_dev_plans');        
    }
}