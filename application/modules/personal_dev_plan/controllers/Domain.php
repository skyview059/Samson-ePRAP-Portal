<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 28 Jul 2020 @10:11 am
 */

class Domain extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Domain_model');        
        $this->load->library('form_validation');
    }

    public function index()
    {
        $start = intval($this->input->get('start'));
        $config['base_url'] = build_pagination_url(Backend_URL . 'personal_dev_plan/domain/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'personal_dev_plan/domain/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Domain_model->total_rows();
        $domains = $this->Domain_model->get_limit_data($config['per_page'], $start);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'domains' => $domains,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'order_id' => $this->Domain_model->next_order_id(),
            'find' => $this->Domain_model->qty(),
        );
        $this->viewAdminContent('personal_dev_plan/domain/index', $data);
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'personal_dev_plan/domain/create_action'),
            'id' => set_value('id'),
            'domain' => set_value('domain'),
            'order_id' => set_value('order_id'),
        );
        $this->viewAdminContent('personal_dev_plan/domain/create', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'domain' => $this->input->post('domain', TRUE),
                'order_id' => (int) $this->input->post('order_id', TRUE),
            );

            $this->Domain_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Domain Added Successfully</p>');
            redirect(site_url(Backend_URL . 'personal_dev_plan/domain'));
        }
    }

    public function update($id)
    {
        $row = $this->Domain_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'personal_dev_plan/domain/update_action'),
                'id' => set_value('id', $row->id),
                'domain' => set_value('domain', $row->domain),
                'order_id' => set_value('order_id', $row->order_id),
            );
            $this->viewAdminContent('personal_dev_plan/domain/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Domain Not Found</p>');
            redirect(site_url(Backend_URL . 'personal_dev_plan/domain'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'domain' => $this->input->post('domain', TRUE),
            );

            $this->Domain_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Domain Updated Successlly</p>');
            redirect(site_url(Backend_URL . 'personal_dev_plan/domain/'));
        }
    }
    
    public function save_order()
    {
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

        $this->db->update_batch('personal_dev_plan_domains', $reorder, 'id');
        echo ajaxRespond('OK','<p class="ajax_success">Domain Order Saved Successfully.</p>');
        
    }

    public function delete($id)
    {
        $row = $this->Domain_model->get_by_id($id);

        if ($row) {
            $this->Domain_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Domain Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'personal_dev_plan/domain'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Domain Not Found</p>');
            redirect(site_url(Backend_URL . 'personal_dev_plan/domain'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('domain', 'domain', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
