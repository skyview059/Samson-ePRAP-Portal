<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 2020-07-29
 */

class Progression extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Progression_model');
        $this->load->helper('progression');
        $this->load->library('form_validation');
    }

    public function index(){
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        $config['base_url'] = build_pagination_url( Backend_URL . 'progression/', 'start');
        $config['first_url'] = build_pagination_url( Backend_URL . 'progression/', 'start');

        $config['per_page'] = 100;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Progression_model->total_rows($q);
        $progressions = $this->Progression_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'progressions' => $progressions,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'order_id' => $this->Progression_model->next_order_id(),
            'find' => $this->Progression_model->qty(),
        );
        $this->viewAdminContent('progression/progression/index', $data);
    }    

    public function save_order(){
        ajaxAuthorized();
        $items      = $this->input->post('item');
        $reorder    = array();
        $order_id   = 0;
        foreach($items as $item){            
            $reorder[] = array(
                'id'     => $item,
                'order_id' => ++$order_id,
            );
        }

        $this->db->update_batch('progressions', $reorder, 'id');
        echo ajaxRespond('OK','<p class="ajax_success">Order Saved Successfully.</p>');
        
    }

    public function create(){
        $data = array(
            'button' => 'Create',
            'action' => site_url( Backend_URL . 'progression/create_action'),
	    'id' => set_value('id'),
	    'category' => set_value('category', 'GMC'),
	    'title' => set_value('title'),
	    'order_id' => set_value('order_id'),
	);
        $this->viewAdminContent('progression/progression/create', $data);
    }
    
    public function create_action(){
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'category' => $this->input->post('category',TRUE),
		'title' => $this->input->post('title',TRUE),
		'order_id' => $this->Progression_model->next_order_id(),
	    );

            $this->Progression_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Progression Added Successfully</p>');
            redirect(site_url( Backend_URL. 'progression'));
        }
    }
    
    public function update($id){
        $row = $this->Progression_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url( Backend_URL . 'progression/update_action'),
		'id' => set_value('id', $row->id),
		'order_id' => set_value('order_id', $row->order_id),
		'category' => set_value('category', $row->category),
		'title' => set_value('title', $row->title),		
	    );
            $this->viewAdminContent('progression/progression/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Progression Not Found</p>');
            redirect(site_url( Backend_URL. 'progression'));
        }
    }
    
    public function update_action(){
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update( $id );
        } else {
            $data = array(
		'category' => $this->input->post('category',TRUE),
		'title' => $this->input->post('title',TRUE)		
	    );

            $this->Progression_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Progression Updated Successlly</p>');
            redirect(site_url( Backend_URL. 'progression/update/'. $id ));
        }
    }

    public function delete($id){
        $row = $this->Progression_model->get_by_id($id);

        if ($row) {
            $this->Progression_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Progression Deleted Successfully</p>');
            redirect(site_url( Backend_URL. 'progression'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Progression Not Found</p>');
            redirect(site_url( Backend_URL. 'progression'));
        }
    }
    

    public function _menu(){
        return add_main_menu('Progression', 'admin/progression', 'progression', 'fa-hand-o-right');
//        return buildMenuForMoudle([
//            'module'    => 'Progression',
//            'icon'      => 'fa-hand-o-right',
//            'href'      => 'progression',                    
//            'children'  => [
//                [
//                    'title' => 'All Progression',
//                    'icon'  => 'fa fa-bars',
//                    'href'  => 'progression'
//                ],[
//                    'title' => ' |_ Add New',
//                    'icon'  => 'fa fa-plus',
//                    'href'  => 'progression/create'
//                ]
//            ]        
//        ]);
    }

    public function _rules(){
	$this->form_validation->set_rules('category', 'category', 'trim|required');
	$this->form_validation->set_rules('title', 'title', 'trim|required');	

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    


}