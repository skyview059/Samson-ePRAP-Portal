<?php

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2021-04-08
 */

class Practice_package extends Admin_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Practice_package_model');
        $this->load->helper('course/practice_package');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q       = urldecode_fk($this->input->get('q', TRUE));
        $start   = intval($this->input->get('start'));
        $exam_id = intval($this->input->get('exam_id'));

        $config['base_url']  = build_pagination_url(Backend_URL . 'course/practice', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'course/practice', 'start');

        $config['per_page']          = 50;
        $config['page_query_string'] = TRUE;
        $config['total_rows']        = $this->Practice_package_model->total_rows($exam_id, $q);
        $packages                    = $this->Practice_package_model->get_limit_data($config['per_page'], $start, $exam_id, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'packages'   => $packages,
            'exam_id'    => $exam_id,
            'q'          => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start'      => $start,
        );
        $this->viewAdminContent('course/practice_package/index', $data);
    }

    public function read($id)
    {
        $row = $this->Practice_package_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'          => $row->id,
                'exam_id'     => $row->exam_id,
                'title'       => $row->title,
                'description' => $row->description,
                'price'       => $row->price,
                'duration'    => $row->duration,
                'scenario_type'  => $row->scenario_type,
                'status'      => $row->status
            );
            $this->viewAdminContent('course/practice_package/read', $data);
        } else {
            $this->session->set_flashdata('msge', 'Record Not Found');
            redirect(site_url(Backend_URL . 'course/practice_package'));
        }
    }

    public function create()
    {
        $data = array(
            'button'      => 'Create',
            'action'      => site_url(Backend_URL . 'course/practice_package/create_action'),
            'id'          => set_value('id'),
            'exam_id'     => set_value('exam_id'),
            'title'       => set_value('title'),
            'description' => set_value('description'),
            'price'       => set_value('price'),
            'duration'    => set_value('duration'),
            'scenario_type'      => set_value('scenario_type', 'Both'),
            'status'      => set_value('status', 'Active'),
        );
        $this->viewAdminContent('course/practice_package/create', $data);
    }

    public function create_action()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'exam_id'     => intval($this->input->post('exam_id', TRUE)),
                'title'       => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', TRUE),
                'price'       => $this->input->post('price', TRUE),
                'duration'    => $this->input->post('duration', TRUE),
                'scenario_type'      => $this->input->post('scenario_type', TRUE),
                'status'      => $this->input->post('status', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->Practice_package_model->insert($data);
            $this->session->set_flashdata('msgs', 'Practice Package Added Successfully');
            redirect(site_url(Backend_URL . 'course/practice_package'));
        }
    }

    public function update($id)
    {
        $row = $this->Practice_package_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button'        => 'Update',
                'action'        => site_url(Backend_URL . 'course/practice_package/update_action'),
                'id'            => set_value('id', $row->id),
                'exam_id'   => set_value('exam_id', $row->exam_id),
                'title'          => set_value('title', $row->title),
                'description'   => set_value('description', $row->description),
                'price'         => set_value('price', $row->price),
                'duration'      => set_value('duration', $row->duration),
                'scenario_type'      => set_value('scenario_type', $row->scenario_type),
                'status'        => set_value('status', $row->status)
            );
            $this->viewAdminContent('course/practice_package/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Record Not Found');
            redirect(site_url(Backend_URL . 'course/practice_package'));
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
                'exam_id'     => intval($this->input->post('exam_id', TRUE)),
                'title'       => $this->input->post('title', TRUE),
                'description' => $this->input->post('description', TRUE),
                'price'       => $this->input->post('price', TRUE),
                'duration'    => $this->input->post('duration', TRUE),
                'scenario_type'      => $this->input->post('scenario_type', TRUE),
                'status'      => $this->input->post('status', TRUE),
                'updated_at' => date('Y-m-d H:i:s')
            );

            $this->Practice_package_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Course Updated Successfully');
            redirect(site_url(Backend_URL . 'course/practice_package'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('exam_id', 'exam id', 'trim|required|numeric');
        $this->form_validation->set_rules('title', 'title', 'trim|required');
        $this->form_validation->set_rules('price', 'price', 'trim|required');
        $this->form_validation->set_rules('duration', 'duration', 'trim|required');
        $this->form_validation->set_rules('scenario_type', 'scenario_type', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}
