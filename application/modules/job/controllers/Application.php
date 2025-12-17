<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 02 Nov 2020 @09:43 am
 */

class Application extends Admin_controller
{
    protected $manage_all;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Application_model');
        $this->load->helper('application');
        $this->load->library('form_validation');

        $this->manage_all = checkPermission('job/application_manage_all', $this->role_id);
    }

    public function index()
    {
        $q = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = build_pagination_url(Backend_URL . 'job/application/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'job/application/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Application_model->total_rows($q, $this->manage_all);
        $applications = $this->Application_model->get_limit_data($config['per_page'], $start, $q, $this->manage_all);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'applications' => $applications,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('job/application/index', $data);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('cover_letter', 'cover letter', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function set_status()
    {
        ajaxAuthorized();
        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $updateData = [
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ];

        $result = $this->Application_model->updateShortlist($id, $updateData);
        if ($result) {
            echo ajaxRespond('OK', 'Status successfully updated!');
        } else {
            echo ajaxRespond('FAIL', 'Status Could not be updated!');
        }
    }


}