<?php

defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2021-04-08
 */

class Course extends Admin_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Course_model');
        $this->load->helper('course');
        $this->load->library('form_validation');
        $this->load->helper('whatsapp/whatsapp');
        $this->load->library('whatsapp/wa');
    }

    public function index()
    {
        $q           = urldecode_fk($this->input->get('q', TRUE));
        $start       = intval($this->input->get('start'));
        $category_id = intval($this->input->get('category_id'));

        $config['base_url']  = build_pagination_url(Backend_URL . 'course', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'course', 'start');

        $config['per_page']          = 50;
        $config['page_query_string'] = TRUE;
        $config['total_rows']        = $this->Course_model->total_rows($category_id, $q);
        $courses                     = $this->Course_model->get_limit_data($config['per_page'], $start, $category_id, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'courses'     => $courses,
            'category_id' => $category_id,
            'q'           => $q,
            'pagination'  => $this->pagination->create_links(),
            'total_rows'  => $config['total_rows'],
            'start'       => $start,
        );
        $this->viewAdminContent('course/course/index', $data);
    }

    public function read($id)
    {
        $row = $this->Course_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'            => $row->id,
                'category_id'   => $row->category_id,
                'name'          => $row->name,
                'description'   => $row->description,
                'price'         => $row->price,
                'duration'      => $row->duration,
                'booking_limit' => $row->booking_limit,
                'status'        => $row->status,
                'dates'         => $this->Course_model->getDates($id),
            );

            $this->viewAdminContent('course/course/read', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Course Not Found</p>');
            redirect(site_url(Backend_URL . 'course'));
        }
    }

    public function create()
    {

        $data = array(
            'button'        => 'Create',
            'action'        => site_url(Backend_URL . 'course/create_action'),
            'id'            => set_value('id'),
            'category_id'   => set_value('category_id'),
            'name'          => set_value('name'),
            'description'   => set_value('description'),
            'price'         => set_value('price'),
            'duration'      => set_value('duration'),
            'booking_limit' => set_value('booking_limit'),
            'status'        => set_value('status', 'Active'),
            'dates'         => set_value('dates', [
                [
                    'start' => [
                        'date' => '',
                        'hh'   => '9',
                        'mm'   => '0',
                        'slot' => 'AM',
                    ],
                    'end'   => [
                        'date' => '',
                        'hh'   => '8',
                        'mm'   => '0',
                        'slot' => 'PM',
                    ]
                ], [
                    'start' => [
                        'date' => '',
                        'hh'   => '9',
                        'mm'   => '0',
                        'slot' => 'AM',
                    ],
                    'end'   => [
                        'date' => '',
                        'hh'   => '8',
                        'mm'   => '0',
                        'slot' => 'PM',
                    ]
                ],
            ]),
        );
        $this->viewAdminContent('course/course/create', $data);
    }

    public function create_action()
    {

        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'category_id'   => $this->input->post('category_id', TRUE),
                'name'          => $this->input->post('name', TRUE),
                'description'   => $this->input->post('description', TRUE),
                'price'         => $this->input->post('price', TRUE),
                'duration'      => $this->input->post('duration', TRUE),
                'booking_limit' => $this->input->post('booking_limit', TRUE),
                'status'        => $this->input->post('status', TRUE)
            );

            $course_id = $this->Course_model->insert($data);
            $this->insertDates($course_id);

            $wa_link_id = (int)$this->input->post('whatsapp_id');
            if ($wa_link_id) {
                $rel_tbl = Tools::_link_for('Course');
                Modules::run('whatsapp/_save_relation', $wa_link_id, $rel_tbl, $course_id);
            }

            $this->session->set_flashdata('message', '<p class="ajax_success">Course Added Successfully</p>');
            redirect(site_url(Backend_URL . 'course'));
        }
    }

    private function insertDates($course_id)
    {
        $batch = [];
        $dates = $this->input->post('dates');
        foreach ($dates as $date) {
            if (!empty($date['start'])) {
                $batch[] = [
                    'course_id'  => $course_id,
                    'start_date' => date("Y-m-d H:i:s", strtotime("{$date['start']['date']} {$date['start']['hh']}:{$date['start']['mm']}:00 {$date['start']['slot']}")),
                    'end_date'   => date("Y-m-d H:i:s", strtotime("{$date['end']['date']} {$date['end']['hh']}:{$date['end']['mm']}:00 {$date['end']['slot']}")),
                    'status'     => 1
                ];
            }
        }
        if (!empty($batch)) {
            $this->db->insert_batch('course_dates', $batch);
        }
    }

    public function update($id)
    {
        $row = $this->Course_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button'        => 'Update',
                'action'        => site_url(Backend_URL . 'course/update_action'),
                'id'            => set_value('id', $row->id),
                'category_id'   => set_value('category_id', $row->category_id),
                'name'          => set_value('name', $row->name),
                'description'   => set_value('description', $row->description),
                'price'         => set_value('price', $row->price),
                'duration'      => set_value('duration', $row->duration),
                'booking_limit' => set_value('booking_limit', $row->booking_limit),
                'status'        => set_value('status', $row->status),
                'dates'         => $this->Course_model->getDates($id),
            );

            $this->viewAdminContent('course/course/update', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Course Not Found</p>');
            redirect(site_url(Backend_URL . 'course'));
        }
    }

    public function delete_row()
    {
        ajaxAuthorized();
        $id = (int)$this->input->post('row_id');
        $this->db->where('id', $id);
        $this->db->delete('course_dates');
        echo ajaxRespond('OK', "Course Date id {$id} Delete");
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'category_id'   => $this->input->post('category_id', TRUE),
                'name'          => $this->input->post('name', TRUE),
                'description'   => $this->input->post('description', TRUE),
                'price'         => $this->input->post('price', TRUE),
                'duration'      => $this->input->post('duration', TRUE),
                'booking_limit' => $this->input->post('booking_limit', TRUE),
                'status'        => $this->input->post('status', TRUE),
            );

            $this->updateDates($id);
            $this->Course_model->update($id, $data);
            $this->session->set_flashdata('message', '<p class="ajax_success">Course Updated Successlly</p>');
            redirect(site_url(Backend_URL . 'course/update/' . $id));
        }
    }

    private function updateDates($course_id)
    {

        $update = $insert = [];
        $dates  = $this->input->post('dates');
        if (empty($dates)) {
            return false;
        }
        foreach ($dates as $date) {
            $id = (int)$date['id'];
            if ($id > 0) {
                $update[] = [
                    'id'         => $id,
                    'course_id'  => $course_id,
                    'start_date' => date("Y-m-d H:i:s", strtotime("{$date['start']['date']} {$date['start']['hh']}:{$date['start']['mm']}:00 {$date['start']['slot']}")),
                    'end_date'   => date("Y-m-d H:i:s", strtotime("{$date['end']['date']} {$date['end']['hh']}:{$date['end']['mm']}:00 {$date['end']['slot']}")),
                    //                    'start_date'    => date('Y-m-d H:i:s', strtotime($date['start'])),
                    //                    'end_date'      => date('Y-m-d H:i:s', strtotime($date['end'])),
                    'status'     => 1
                ];
            }
            if (!empty($date['start']) && $id == 0) {
                $insert[] = [
                    'course_id'  => $course_id,
                    'start_date' => date("Y-m-d H:i:s", strtotime("{$date['start']['date']} {$date['start']['hh']}:{$date['start']['mm']}:00 {$date['start']['slot']}")),
                    'end_date'   => date("Y-m-d H:i:s", strtotime("{$date['end']['date']} {$date['end']['hh']}:{$date['end']['mm']}:00 {$date['end']['slot']}")),
                    'status'     => 1
                ];
            }
        }
        if (!empty($update)) {
            $this->db->update_batch('course_dates', $update, 'id');
        }
        if (!empty($insert)) {
            $this->db->insert_batch('course_dates', $insert);
        }
    }

    public function delete($id)
    {
        $row = $this->Course_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'            => $row->id,
                'category_id'   => $row->category_id,
                'name'          => $row->name,
                'description'   => $row->description,
                'price'         => $row->price,
                'duration'      => $row->duration,
                'booking_limit' => $row->booking_limit,
                'status'        => $row->status,
            );
            $this->viewAdminContent('course/course/delete', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Course Not Found</p>');
            redirect(site_url(Backend_URL . 'course'));
        }
    }

    public function delete_action($id)
    {
        $row = $this->Course_model->get_by_id($id);

        if ($row) {
            $this->Course_model->delete($id);
            $this->session->set_flashdata('message', '<p class="ajax_success">Course Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'course'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Course Not Found</p>');
            redirect(site_url(Backend_URL . 'course'));
        }
    }

    public function _menu()
    {
        return buildMenuForMoudle([
            'module'   => 'Course',
            'icon'     => 'fa-hand-o-right',
            'href'     => 'course',
            'children' => [
                [
                    'title' => 'Course Booking List',
                    'icon'  => 'fa fa-calendar',
                    'href'  => 'course/booked'
                ], [
                    'title' => ' &nbsp; |_ Book Course',
                    'icon'  => 'fa fa-edit',
                    'href'  => 'course/booked/create'
                ], [
                    'title' => 'PLAB Part 2 Scenarios',
                    'icon'  => 'fa fa-calendar',
                    'href'  => 'course/booked/practice'
                ], [
                    'title' => ' &nbsp; |_ Book Practice',
                    'icon'  => 'fa fa-edit',
                    'href'  => 'course/booked/create_practice'
                ], [
                    'title' => 'Course Manager',
                    'icon'  => 'fa fa-puzzle-piece',
                    'href'  => 'course'
                ], [
                    'title' => ' &nbsp; |_ Add Course',
                    'icon'  => 'fa fa-plus',
                    'href'  => 'course/create'
                ], [
                    'title' => 'Practice Manager',
                    'icon'  => 'fa fa-puzzle-piece',
                    'href'  => 'course/practice_package'
                ], [
                    'title' => ' &nbsp; |_ Add Practice',
                    'icon'  => 'fa fa-plus',
                    'href'  => 'course/practice_package/create'
                ], [
                    'title' => 'Manage Category',
                    'icon'  => 'fa fa-bars',
                    'href'  => 'course/category'
                ], [
                    'title' => 'Payment Log',
                    'icon'  => 'fa fa-gbp',
                    'href'  => 'course/payment'
                ]
            ]
        ]);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('category_id', 'category id', 'trim|required|numeric');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('price', 'price', 'trim|required');
        $this->form_validation->set_rules('duration', 'duration', 'trim|required|numeric');
        $this->form_validation->set_rules('booking_limit', 'booking limit', 'trim|required|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function dateSlot($course_id)
    {
        echo getCourseDateSlotID(0, $course_id);
    }
}
