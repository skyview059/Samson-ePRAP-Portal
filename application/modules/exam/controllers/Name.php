<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 12 Jun 2020 @03:13 pm
 */

class Name extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Name_model');        
        $this->load->library('form_validation');
    }

    public function index(){
        
        $start = intval($this->input->get('start'));
        
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'exam/name/', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'exam/name/', 'start');
        

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Name_model->total_rows();
        $names = $this->Name_model->get_limit_data($config['per_page'], $start);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'names' => $names,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('exam/name/index', $data);
    }

    public function create(){
        $data = array(
            'button' => 'Create',
            'action' => site_url( Backend_URL . 'exam/name/create_action'),
	    'id' => set_value('id'),
	    'name' => set_value('name')
	);
        $this->viewAdminContent('exam/name/create', $data);
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

            $this->Name_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Name Added Successfully</p>');
            redirect(site_url( Backend_URL. 'exam/name'));
        }
    }
    
    public function update($id){
        $row = $this->Name_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url( Backend_URL . 'exam/name/update_action'),
		'id' => set_value('id', $row->id),
		'name' => set_value('name', $row->name)
	    );
            $this->viewAdminContent('exam/name/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Name Not Found</p>');
            redirect(site_url( Backend_URL. 'exam/name'));
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

            $this->Name_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Name Updated Successlly</p>');
            redirect(site_url( Backend_URL. 'exam/name/'));
        }
    }
    
    public function delete($id){
        $row = $this->Name_model->get_by_id($id);

        if ($row) {
            $this->Name_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Name Deleted Successfully</p>');
            redirect(site_url( Backend_URL. 'exam/name'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Name Not Found</p>');
            redirect(site_url( Backend_URL. 'exam/name'));
        }
    }

    public function _rules(){
	$this->form_validation->set_rules('name', 'name', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    


}