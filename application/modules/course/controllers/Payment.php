<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 22 Apr 2021 @01:34 pm
 */

class Payment extends Admin_controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Payment_model');        
        $this->load->library('form_validation');
    }

    public function index() {
        $q = urldecode_fk($this->input->get('q', TRUE));
        $status = urldecode_fk($this->input->get('status', TRUE));
        $gateway = urldecode_fk($this->input->get('gateway', TRUE));
        $purchased = urldecode_fk($this->input->get('purchased', TRUE));

        $start = intval($this->input->get('start'));


        $config['base_url'] = build_pagination_url(Backend_URL . 'course/payment/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'course/payment/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Payment_model->total_rows($q);
        $payments = $this->Payment_model->get_limit_data($config['per_page'], $start, $q, $status, $gateway, $purchased);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'payments'  => $payments,
            'q'         => $q,
            'status'    => $status,
            'gateway'   => $gateway,
            'purchased'     => $purchased,
            'pagination'    => $this->pagination->create_links(),
            'total_rows'    => $config['total_rows'],
            'start' => $start,
        );

        $this->viewAdminContent('course/payment/index', $data);
    }

    public function create() {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'course/payment/create_action'),
            'id' => set_value('id'),
            'purchased_at' => set_value('purchased_at'),
            'total_items' => set_value('total_items'),
            'total_pay' => set_value('total_pay'),
            'invoice_id' => set_value('invoice_id'),
            'gateway' => set_value('gateway'),
            'admin_comments' => set_value('admin_comments'),
            'status' => set_value('status'),
            'is_deleted' => set_value('is_deleted'),
        );
        $this->viewAdminContent('course/payment/create', $data);
    }

    public function create_action() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'purchased_at' => $this->input->post('purchased_at', TRUE),
                'total_items' => $this->input->post('total_items', TRUE),
                'total_pay' => $this->input->post('total_pay', TRUE),
                'invoice_id' => $this->input->post('invoice_id', TRUE),
                'gateway' => $this->input->post('gateway', TRUE),
                'admin_comments' => $this->input->post('admin_comments', TRUE),
                'status' => $this->input->post('status', TRUE),
                'is_deleted' => $this->input->post('is_deleted', TRUE),
            );

            $this->Payment_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Payment Added Successfully</p>');
            redirect(site_url(Backend_URL . 'course/payment'));
        }
    }

    public function update($id) {
        $row = $this->Payment_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'course/payment/update_action'),
                'id' => set_value('id', $row->id),
                'purchased_at' => set_value('purchased_at', $row->purchased_at),
                'total_items' => set_value('total_items', $row->total_items),
                'total_pay' => set_value('total_pay', $row->total_pay),
                'invoice_id' => set_value('invoice_id', $row->invoice_id),
                'gateway' => set_value('gateway', $row->gateway),
                'admin_comments' => set_value('admin_comments', $row->admin_comments),
                'status' => set_value('status', $row->status),
                'is_deleted' => set_value('is_deleted', $row->is_deleted),
            );
            $this->viewAdminContent('course/payment/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Payment Not Found</p>');
            redirect(site_url(Backend_URL . 'course/payment'));
        }
    }

    public function update_action() {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'purchased_at' => $this->input->post('purchased_at', TRUE),
                'total_items' => $this->input->post('total_items', TRUE),
                'total_pay' => $this->input->post('total_pay', TRUE),
                'invoice_id' => $this->input->post('invoice_id', TRUE),
                'gateway' => $this->input->post('gateway', TRUE),
                'admin_comments' => $this->input->post('admin_comments', TRUE),
                'status' => $this->input->post('status', TRUE),
                'is_deleted' => $this->input->post('is_deleted', TRUE),
            );

            $this->Payment_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Payment Updated Successlly</p>');
            redirect(site_url(Backend_URL . 'course/payment/'));
        }
    }

    public function delete($id) {
        $row = $this->Payment_model->get_by_id($id);

        if ($row) {
            $this->Payment_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Payment Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'course/payment'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Payment Not Found</p>');
            redirect(site_url(Backend_URL . 'course/payment'));
        }
    }

    public function _rules() {
        $this->form_validation->set_rules('purchased_at', 'purchased at', 'trim|required');
        $this->form_validation->set_rules('total_items', 'total items', 'trim|required|is_natural_no_zero');
        $this->form_validation->set_rules('total_pay', 'total pay', 'trim|required');
        $this->form_validation->set_rules('invoice_id', 'invoice id', 'trim|required');
        $this->form_validation->set_rules('gateway', 'gateway', 'trim|required');
        $this->form_validation->set_rules('admin_comments', 'admin comments', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        $this->form_validation->set_rules('is_deleted', 'is deleted', 'trim|required|is_natural_no_zero');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}
