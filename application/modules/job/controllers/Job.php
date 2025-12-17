<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-10-14
 */

class Job extends Admin_controller
{
    protected $manage_all;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Job_model');
        $this->load->helper('job');
        $this->load->library('form_validation');

        $this->manage_all = checkPermission('job/manage_all', $this->role_id);
    }

    public function index()
    {
        $q = urldecode_fk(trim_fk($this->input->get('q', TRUE)));
        $start = intval($this->input->get('start'));

        $config['base_url'] = build_pagination_url(Backend_URL . 'job/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'job/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Job_model->total_rows($q, $this->manage_all);
        $jobs = $this->Job_model->get_limit_data($config['per_page'], $start, $q, $this->manage_all);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'jobs' => $jobs,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );
        $this->viewAdminContent('job/job/index', $data);
    }

    public function read($id)
    {
        $row = $this->Job_model->get_by_id($id);

        if ($row) {
            $data = array(
                'id' => $row->id,
                'user_id' => getUserNameByID($row->user_id),
                'post_title' => $row->post_title,
                'job_for' => $row->job_for,
                'description' => $row->description,
                'job_type' => $row->job_type,
                'salary_type' => $row->salary_type,
                'rate' => $row->rate,
                'location' => $row->location,
                'lat' => $row->lat,
                'lng' => $row->lng,
                'vacancy' => sprintf('%02d', $row->vacancy),
                'deadline' => globalDateFormat($row->deadline),
                'skills' => $row->skills,
                'benefit' => $row->benefit,
                'hit' => $row->hit,
                'status' => $row->status,
                'service_hour' => $row->service_hour,
                'featured' => $row->featured,
                'created_at' => globalDateTimeFormat($row->created_at),
                'updated_at' => globalDateTimeFormat($row->updated_at),
                'manage_all' => $this->manage_all
            );
            $this->viewAdminContent('job/job/read', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job Not Found</p>');
            redirect(site_url(Backend_URL . 'job'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url(Backend_URL . 'job/create_action'),
            'id' => set_value('id'),
            'user_id' => set_value('user_id'),
            'post_title' => set_value('post_title'),
            'job_for' => set_value('job_for', 'Doctor'),
            'description' => set_value('description'),
            'job_type' => set_value('job_type', 'Full Time'),
            'salary_type' => set_value('salary_type'),
            'rate' => set_value('rate'),
            'location' => set_value('location'),
            'lat' => set_value('lat'),
            'lng' => set_value('lng'),
            'vacancy' => set_value('vacancy', 1),
            'deadline' => set_value('deadline', date('Y-m-d', strtotime('+1 Months'))),
            'skills' => set_value('skills'),
            'benefit' => set_value('benefit'),
            'status' => set_value('status', 'Draft'),
            'service_hour' => set_value('service_hour'),
            'featured' => set_value('featured', 'No'),
            'manage_all' => $this->manage_all
        );
        $this->viewAdminContent('job/job/create', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {

            $data = array(
                'post_title' => $this->input->post('post_title', TRUE),
                'job_for' => $this->input->post('job_for', TRUE),
                'description' => $this->input->post('description', TRUE),
                'job_type' => $this->input->post('job_type', TRUE),
                'salary_type' => $this->input->post('salary_type', TRUE),
                'rate' => ($this->input->post('salary_type') != 'Negotiable') ? $this->input->post('rate', TRUE) : null,
                'location' => $this->input->post('location', TRUE),
                'lat' => $this->input->post('lat', TRUE),
                'lng' => $this->input->post('lng', TRUE),
                'vacancy' => $this->input->post('vacancy', TRUE),
                'deadline' => $this->input->post('deadline', TRUE),
                'skills' => $this->input->post('skills', TRUE),
                'benefit' => $this->input->post('benefit', TRUE),
                'status' => $this->input->post('status', TRUE),
                'service_hour' => $this->input->post('service_hour', TRUE),
                'featured' => $this->input->post('featured', TRUE),
                'created_at' => date("Y-m-d H:i:s")
            );

            if($this->manage_all){
                $data['featured'] = $this->input->post('featured', TRUE);
                $data['user_id'] = intval($this->input->post('user_id', TRUE));
            } else {
                $data['user_id'] = $this->user_id;
            }

            $this->Job_model->insert($data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Job Added Successfully</p>');
            redirect(site_url(Backend_URL . 'job'));
        }
    }

    public function update($id)
    {
        $row = $this->Job_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'job/update_action'),
                'id' => set_value('id', $row->id),
                'user_id' => set_value('user_id', $row->user_id),
                'post_title' => set_value('post_title', $row->post_title),
                'job_for' => set_value('job_for', $row->job_for),
                'description' => set_value('description', $row->description),
                'job_type' => set_value('job_type', $row->job_type),
                'salary_type' => set_value('salary_type', $row->salary_type),
                'rate' => set_value('rate', $row->rate),
                'location' => set_value('location', $row->location),
                'lat' => set_value('lat', $row->lat),
                'lng' => set_value('lng', $row->lng),
                'vacancy' => set_value('vacancy', $row->vacancy),
                'deadline' => set_value('deadline', $row->deadline),
                'skills' => set_value('skills', $row->skills),
                'benefit' => set_value('benefit', $row->benefit),
                'status' => set_value('status', $row->status),
                'service_hour' => set_value('service_hour', $row->service_hour),
                'featured' => set_value('featured', $row->featured),
                'manage_all' => $this->manage_all
            );
            $this->viewAdminContent('job/job/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job Not Found</p>');
            redirect(site_url(Backend_URL . 'job'));
        }
    }

    public function update_action()
    {
        $this->_rules();
        $id = intval($this->input->post('id', TRUE));

        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'post_title' => $this->input->post('post_title', TRUE),
                'job_for' => $this->input->post('job_for', TRUE),
                'description' => $this->input->post('description', TRUE),
                'job_type' => $this->input->post('job_type', TRUE),
                'salary_type' => $this->input->post('salary_type', TRUE),
                'rate' => ($this->input->post('salary_type') != 'Negotiable') ? $this->input->post('rate', TRUE) : null,
                'location' => $this->input->post('location', TRUE),
                'lat' => $this->input->post('lat', TRUE),
                'lng' => $this->input->post('lng', TRUE),
                'vacancy' => $this->input->post('vacancy', TRUE),
                'deadline' => $this->input->post('deadline', TRUE),
                'skills' => $this->input->post('skills', TRUE),
                'benefit' => $this->input->post('benefit', TRUE),
                'status' => $this->input->post('status', TRUE),
                'service_hour' => $this->input->post('service_hour', TRUE),
                'featured' => $this->input->post('featured', TRUE),
                'updated_at' => date("Y-m-d H:i:s")
            );

            if($this->manage_all){
                $data['featured'] = $this->input->post('featured', TRUE);
                $data['user_id'] = intval($this->input->post('user_id', TRUE));
            } else {
                $data['user_id'] = $this->user_id;
            }

            $this->Job_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Job Updated Successlly</p>');
            redirect(site_url(Backend_URL . 'job/update/' . $id));
        }
    }

    public function delete($id)
    {
        $row = $this->Job_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'user_id' => getUserNameByID($row->user_id),
                'post_title' => $row->post_title,
                'job_for' => $row->job_for,
                'description' => $row->description,
                'job_type' => $row->job_type,
                'salary_type' => $row->salary_type,
                'rate' => $row->rate,
                'location' => $row->location,
                'lat' => $row->lat,
                'lng' => $row->lng,
                'vacancy' => sprintf('%02d', $row->vacancy),
                'deadline' => globalDateFormat($row->deadline),
                'skills' => $row->skills,
                'benefit' => $row->benefit,
                'hit' => $row->hit,
                'status' => $row->status,
                'service_hour' => $row->service_hour,
                'featured' => $row->featured,
                'created_at' => globalDateTimeFormat($row->created_at),
                'updated_at' => globalDateTimeFormat($row->updated_at)
            );
            $this->viewAdminContent('job/job/delete', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job Not Found</p>');
            redirect(site_url(Backend_URL . 'job'));
        }
    }


    public function delete_action($id)
    {
        $row = $this->Job_model->get_by_id($id);

        if ($row) {
            $this->Job_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Job Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'job'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Job Not Found</p>');
            redirect(site_url(Backend_URL . 'job'));
        }
    }


    public function _menu()
    {
        // return add_main_menu('Job', 'job', 'job', 'fa-hand-o-right');
        return buildMenuForMoudle([
            'module' => 'Job',
            'icon' => 'fa-hand-o-right',
            'href' => 'job',
            'children' => [
                [
                    'title' => 'All Job',
                    'icon' => 'fa fa-bars',
                    'href' => 'job'
                ], [
                    'title' => ' |_ Add New',
                    'icon' => 'fa fa-plus',
                    'href' => 'job/create'
                ], [
                    'title' => 'Job Application',
                    'icon' => 'fa fa-list',
                    'href' => 'job/application'
                ]
            ]
        ]);
    }

    public function _rules()
    {
        if($this->manage_all){
            $this->form_validation->set_rules('user_id', 'recuitment manager', 'trim|required|is_natural_no_zero', [
                'is_natural_no_zero' => 'Recruitment manager must be selected!'
            ]);
        }

        $this->form_validation->set_rules('post_title', 'post title', 'trim|required');
        $this->form_validation->set_rules('job_for', 'job for', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('job_type', 'job type', 'trim|required');
        $this->form_validation->set_rules('salary_type', 'salary type', 'trim|required');
        if ($this->input->post('salary_type') != 'Negotiable') {
            $this->form_validation->set_rules('rate', 'salary/hourly rate', 'trim|required|numeric');
        }

        $this->form_validation->set_rules('location', 'location', 'trim|required');
        $this->form_validation->set_rules('vacancy', 'vacancy', 'trim|required');
        $this->form_validation->set_rules('deadline', 'deadline', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');
        $this->form_validation->set_rules('featured', 'featured', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}