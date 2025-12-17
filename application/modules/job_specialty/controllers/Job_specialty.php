<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 2020-09-04
 */

class Job_specialty extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Job_specialty_model');
        $this->load->helper('job_specialty');
        $this->load->library('form_validation');
    }

    public function index(){
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'job_specialty/', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'job_specialty/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Job_specialty_model->total_rows($q);
        $specialties = $this->Job_specialty_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'specialties' => $specialties,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('job_specialty/job_specialty/index', $data);
    }

    public function read($id){
        $row = $this->Job_specialty_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'name' => $row->name,
		'created_at' => $row->created_at,
		'updated_at' => $row->updated_at,
	    );
            $this->viewAdminContent('job_specialty/job_specialty/read', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job_specialty Not Found</p>');
            redirect(site_url( Backend_URL. 'job_specialty'));
        }
    }

    public function create(){
        $data = array(
            'button' => 'Create',
            'action' => site_url( Backend_URL . 'job_specialty/create_action'),
	    'id' => set_value('id'),
	    'name' => set_value('name'),
	    'created_at' => set_value('created_at'),
	    'updated_at' => set_value('updated_at'),
	);
        $this->viewAdminContent('job_specialty/job_specialty/create', $data);
    }
    
    public function create_action(){
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'name' => $this->input->post('name',TRUE),
		'created_at' => date('Y-m-d H:i:s'),
		'updated_at' => date('Y-m-d H:i:s'),
	    );

            $this->Job_specialty_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Job_specialty Added Successfully</p>');
            redirect(site_url( Backend_URL. 'job_specialty'));
        }
    }
    
    public function update($id){
        $row = $this->Job_specialty_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url( Backend_URL . 'job_specialty/update_action'),
		'id' => set_value('id', $row->id),
		'name' => set_value('name', $row->name),
		'created_at' => set_value('created_at', $row->created_at),
		'updated_at' => set_value('updated_at', $row->updated_at),
	    );
            $this->viewAdminContent('job_specialty/job_specialty/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job_specialty Not Found</p>');
            redirect(site_url( Backend_URL. 'job_specialty'));
        }
    }
    
    public function update_action(){
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update( $id );
        } else {
            $data = array(
		'name' => $this->input->post('name',TRUE),
                'updated_at' => date('Y-m-d H:i:s'),
	    );

            $this->Job_specialty_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Job_specialty Updated Successlly</p>');
            redirect(site_url( Backend_URL. 'job_specialty/update/'. $id ));
        }
    }

    public function delete($id){
        $row = $this->Job_specialty_model->get_by_id($id);

        if ($row) {
            $this->Job_specialty_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Job_specialty Deleted Successfully</p>');
            redirect(site_url( Backend_URL. 'job_specialty'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job_specialty Not Found</p>');
            redirect(site_url( Backend_URL. 'job_specialty'));
        }
    }
    

    public function _menu(){
        return add_main_menu('Job Specialty', 'admin/job_specialty', 'job_specialty', 'fa-hand-o-right');        
    }

    public function _rules(){
	$this->form_validation->set_rules('name', 'name', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    


}