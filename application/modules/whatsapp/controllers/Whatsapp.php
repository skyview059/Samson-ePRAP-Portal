<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2021-03-30
 */

class Whatsapp extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Whatsapp_model');
        $this->load->helper('whatsapp');
        $this->load->library('form_validation');
        $this->load->library('whatsapp/wa');
    }

    public function index()
    {
        $q     = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url']          = build_pagination_url(Backend_URL . 'whatsapp/', 'start');
        $config['first_url']         = build_pagination_url(Backend_URL . 'whatsapp/', 'start');
        $config['per_page']          = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows']        = $this->Whatsapp_model->total_rows($q);
        $whatsapps                   = $this->Whatsapp_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'whatsapps'  => $whatsapps,
            'q'          => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start'      => $start,
        );
        $this->viewAdminContent('whatsapp/whatsapp/index', $data);
    }

    public function create()
    {
        $data = array(
            'button'    => 'Create',
            'action'    => site_url(Backend_URL . 'whatsapp/create_action'),
            'id'        => set_value('id'),
            'title'     => set_value('title'),
            'link_type' => set_value('link_type', 'Whatsapp'),
            'link_for'  => set_value('link_for', 'Mock'),
            'rel_id'    => set_value('rel_id', '0'),
            'link'      => set_value('link'),
            'status'    => set_value('status', 'Publish'),
        );
        $this->viewAdminContent('whatsapp/whatsapp/create', $data);
    }

    public function create_action()
    {
        $link_for = $this->input->post('link_for', TRUE);
        $rel_tbl  = Tools::_link_for($link_for);
        $rel_id   = (int)$this->input->post('rel_id');

        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'user_id'     => $this->user_id,
                'title'       => $this->input->post('title', TRUE),
                'link_type'   => $this->input->post('link_type', TRUE),
                'link_for'    => $link_for,
                'link'        => $this->input->post('link', TRUE),
                'status'      => $this->input->post('status', TRUE),
                'created_on'  => date('Y-m-d H:i:s'),
                'modified_on' => date('Y-m-d H:i:s'),
            );

            $wa_link_id = $this->Whatsapp_model->insert($data);
            $this->_save_relation($wa_link_id, $rel_tbl, $rel_id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Whatsapp Added Successfully</p>');
            redirect(site_url(Backend_URL . 'whatsapp'));
        }
    }

    public function widget_action()
    {
        ajaxAuthorized();
        $link_for = $this->input->post('link_for', TRUE);
        $data     = array(
            'user_id'     => $this->user_id,
            'title'       => $this->input->post('title', TRUE),
            'link_for'    => $link_for,
            'link'        => $this->input->post('link', TRUE),
            'status'      => 'Publish',
            'created_on'  => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s'),
        );
        $id       = $this->Whatsapp_model->insert($data);

        if ($id) {
            echo ajaxRespond('OK', getDropDownWhatsapp($link_for, $id));
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_success">Fail</p>');
        }
    }

    public function _save_relation($wa_link_id, $rel_tbl, $rel_id, $update = false)
    {
        if ($update == true) {
            $this->db->where('wa_link_id', $wa_link_id);
            $this->db->delete('whatsapp_link_relations');
        }

        $data = array(
            'wa_link_id' => $wa_link_id,
            'rel_table'  => $rel_tbl,
            'rel_id'     => $rel_id,
            'timestamp'  => date('Y-m-d H:i:s'),
        );
        $this->db->insert('whatsapp_link_relations', $data);
    }

    public function update($id)
    {
        $row = $this->Whatsapp_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button'    => 'Update',
                'action'    => site_url(Backend_URL . 'whatsapp/update_action'),
                'id'        => set_value('id', $row->id),
                'title'     => set_value('title', $row->title),
                'link_type' => set_value('link_type', $row->link_type),
                'link_for'  => set_value('link_for', $row->link_for),
                'link'      => set_value('link', $row->link),
                'status'    => set_value('status', $row->status),
                'rel_id'    => set_value('rel_id', $row->rel_id),
                'rel_table' => set_value('rel_table', Tools::rel_table($row->rel_table)),
            );
            $this->viewAdminContent('whatsapp/whatsapp/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Whatsapp Not Found</p>');
            redirect(site_url(Backend_URL . 'whatsapp'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = (int)$this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'title'       => $this->input->post('title', TRUE),
                'link_type'   => $this->input->post('link_type', TRUE),
                'link_for'    => $this->input->post('link_for', TRUE),
                'link'        => $this->input->post('link', TRUE),
                'status'      => $this->input->post('status', TRUE),
                'modified_on' => date('Y-m-d H:i:s'),
            );

            $rel_tbl = $this->input->post('link_for');
            $rel_id  = (int)$this->input->post('rel_id');
            $this->Whatsapp_model->update($id, $data);
            $this->_save_relation($id, $rel_tbl, $rel_id, true);

            $this->session->set_flashdata('message', '<p class="ajax_success">Whatsapp Updated Successlly</p>');
            redirect(site_url(Backend_URL . 'whatsapp/update/' . $id));
        }
    }

    public function log($id)
    {
        $logs = $this->Whatsapp_model->getLinkSentLog($id);
        $data = [
            'sl'   => 0,
            'id'   => $id,
            'logs' => $logs,
        ];
        $this->viewAdminContent('whatsapp/whatsapp/log', $data);
    }

    public function delete($id)
    {
        ajaxAuthorized();
        $row = $this->Whatsapp_model->get_by_id($id);

        if ($row) {
            $this->Whatsapp_model->delete($id);
            echo ajaxRespond('OK', 'Whatsapp Deleted Successfully');
        } else {
            echo ajaxRespond('Fail', 'Whatsapp Not Found');
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('title', 'title', 'trim|required');
        $this->form_validation->set_rules('link_for', 'link_for', 'trim|required');
        $this->form_validation->set_rules('link', 'link', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function get_link_data()
    {
        ajaxAuthorized();

        $link_id = $this->input->post('link_id');
        $whatsapp   = $this->Whatsapp_model->get_by_id($link_id);
        echo ajaxRespond('OK', $whatsapp);
    }

    public function send_link()
    {
        ajaxAuthorized();
        $students = $this->input->post('students');
        if (empty($students)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please Select Student to Send Link</p>');
            exit;
        }
        $bcc_emails = $this->Whatsapp_model->getStudentEmails($students);
        $data = [
            'students'  => $bcc_emails,
            'mail_body'  => $this->input->post('mail_body')
        ];

        Modules::run('mail/sendLinkMail', $data);
        echo ajaxRespond('OK', 'Link Sent to Student');
    }

    function saveLinkSentLog($whatsapp_id, $students)
    {
        $save      = [];
        $timestamp = date('Y-m-d H:i:s');
        foreach ($students as $s) {
            $save[] = [
                'wa_link_id' => $whatsapp_id,
                'student_id' => $s,
                'timestamp'  => $timestamp,
            ];
        }
        if (!empty($save)) {
            $this->db->insert_batch('whatsapp_link_sent', $save);
        }
    }

    function get_rel_id()
    {
        $link_for = $this->input->post('link_for');
        $rel_id   = (int)$this->input->post('rel_id');
        if ($link_for === 'Mock') {
            echo wa::getMocks($rel_id);
        } elseif ($link_for === 'Course') {
            echo wa::getCourses($rel_id);
        } elseif ($link_for === 'Practice') {
            echo wa::getPractices($rel_id);
        } elseif ($link_for === 'Country') {
            echo wa::getCountries($rel_id);
        } else {
            echo wa::getMocks($rel_id);
        }
    }
}