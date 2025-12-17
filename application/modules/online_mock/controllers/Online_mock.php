<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-17
 */

class Online_mock extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Online_mock_model');
        $this->load->helper('exam/exam');
        $this->load->helper('online_mock/online_mock');
        $this->load->helper('centre/centre');
        $this->load->library('form_validation');
        $this->load->helper('whatsapp/whatsapp');
        $this->load->library('whatsapp/wa');
    }

    public function index()
    {
        $q     = urldecode_fk($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        $id    = intval($this->input->get('id'));
        $tab   = ($this->input->get('tab'));
        if (empty($tab)) {
            $tab = 'coming';
        }

        $config['base_url']  = build_pagination_url(Backend_URL . 'online_mock/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'online_mock/', 'start');

        $config['per_page']          = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows']        = $this->Online_mock_model->total_rows($id, $tab, $q);
        $online_mocks                = $this->Online_mock_model->get_limit_data($config['per_page'], $start, $id, $tab, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'online_mocks' => $online_mocks,
            'q'            => $q,
            'pagination'   => $this->pagination->create_links(),
            'total_rows'   => $config['total_rows'],
            'start'        => $start,
            'id'           => $id,
            'tab'          => $tab,
            'coming'       => $this->Online_mock_model->qty('coming', $id),
            'past'         => $this->Online_mock_model->qty('past', $id),
            'canceled'     => $this->Online_mock_model->qty('Canceled', $id),
        );
        $this->viewAdminContent('online_mock/online_mock/index', $data);
    }

    public function student($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'             => $row->id,
                'exam_id'        => $row->exam_id,
                'course_name'    => $row->course_name,
                'datetime'       => $row->datetime,
                'created_at'     => $row->created_at,
                'centre_name'    => $row->centre_name,
                'centre_address' => $row->centre_address,
                'students'       => $this->Online_mock_model->get_students($id),
                'start'          => 0
            );
            $this->viewAdminContent('online_mock/online_mock/student', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function scenario($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $data = array(
                'exam_id'        => set_value('exam_id', $row->exam_id),
                'id'             => $row->id,
                'course_name'    => $row->course_name,
                'datetime'       => $row->datetime,
                'centre_name'    => $row->centre_name,
                'centre_address' => $row->centre_address,
                'reading_time'   => $row->reading_time,
                'practice_time'  => $row->practice_time,
            );

            $data['scenarios'] = $this->Online_mock_model->get_scenarios($id);
            $this->viewAdminContent('online_mock/online_mock/scenario', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock station not found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function print_candidate_inst($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $data = array(
                'exam_id'        => set_value('exam_id', $row->exam_id),
                'id'             => $row->id,
                'course_name'    => $row->course_name,
                'datetime'       => $row->datetime,
                'centre_name'    => $row->centre_name,
                'centre_address' => $row->centre_address,
                'sl'             => 0,
            );

            $data['scenarios'] = $this->Online_mock_model->get_scenarios($id);
            $this->load->view('online_mock/online_mock/print_candidate_inst', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock station not found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function print_full_scenario($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $data = array(
                'exam_id'        => set_value('exam_id', $row->exam_id),
                'id'             => $row->id,
                'course_name'    => $row->course_name,
                'datetime'       => $row->datetime,
                'centre_name'    => $row->centre_name,
                'centre_address' => $row->centre_address,
                'sl'             => 0,
            );

            $data['scenarios'] = $this->Online_mock_model->get_scenarios($id);
            $this->load->view('online_mock/online_mock/print_full_scenario', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock station not found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function get_assessor()
    {
        $id = $this->input->post('id');
        $this->db->select('u.id,first_name,last_name,r.role_name');
        $this->db->from('users as u');
        $this->db->join('roles as r', 'r.id=u.role_id', 'LEFT');
        $this->db->where_in('role_id', [2, 3, 4, 5]);
        $this->db->where('u.status', 'Active');
        $data['assessors'] = $this->db->get()->result();

        $data['marked'] = $this->Online_mock_model->marked($id);
        $this->viewAdminContent('online_mock/online_mock/get_assessor', $data);
    }

    public function save_assessor()
    {
        ajaxAuthorized();
        $_rel_id   = $this->input->post('scenario_rel_id');
        $assessors = $this->input->post('user_id');
        if (empty($assessors)) {
            echo ajaxRespond('FAIL', '<p class="ajax_success">At least one accessor must be assigned!</p>');
            exit;
        }
        //Get already assigned assessor and scenario information
        $previous_assessors = $this->Online_mock_model->get_assigned_assessors($_rel_id);

        $data = [];
        foreach ($assessors as $assessor_id) {
            $data[] = [
                'scenario_rel_id' => $_rel_id,
                'assessor_id'     => $assessor_id,
                'user_id'         => $this->user_id,
                'timestamp'       => date('Y-m-d H:i:s'),
            ];
        }
        $this->db->trans_start();
        $this->db->where('scenario_rel_id', $_rel_id);
        $this->db->delete('scenario_to_assessors');
        $this->db->insert_batch('scenario_to_assessors', $data);
        $this->db->trans_complete();

        //Email send unassigned assessor
        $pre_assessors = [];
        foreach ($previous_assessors as $previous_assessor) {
            $pre_assessors[] = $previous_assessor->assessor_id;
            if (array_key_exists($previous_assessor->assessor_id, $assessors)) {

            } else {
                //Email send to unassign assessor
                $option = [
                    'id'                     => $_rel_id,
                    'url'                    => site_url('scenario/read/' . $previous_assessor->sid),
                    'email'                  => $previous_assessor->email,
                    'full_name'              => $previous_assessor->first_name . ' ' . $previous_assessor->last_name,
                    'scenario_name'          => $previous_assessor->name,
                    'candidate_instructions' => $previous_assessor->description,
                    'patient_information'    => $previous_assessor->patient_information
                ];

                Modules::run('mail/assessorScenarioUnassigned', $option);
            }
        }

        //Get new assigned assessors list
        foreach ($assessors as $new_assessor_id) {
            //If previous assessor not assigned
            if (!in_array($new_assessor_id, $pre_assessors)) {
                $assessor_scenario = $this->Online_mock_model->get_assessor_scenario_info($_rel_id, $new_assessor_id);
                //Email send to assign assessor
                $option = [
                    'id'                     => $_rel_id,
                    'url'                    => site_url('scenario/read/' . $assessor_scenario->sid),
                    'email'                  => $assessor_scenario->email,
                    'full_name'              => $assessor_scenario->first_name . ' ' . $assessor_scenario->last_name,
                    'scenario_name'          => $assessor_scenario->name,
                    'candidate_instructions' => $assessor_scenario->description,
                    'patient_information'    => $assessor_scenario->patient_information
                ];

                Modules::run('mail/assessorScenarioAssigned', $option);
            }
        }

        echo ajaxRespond('OK', '<p class="ajax_success">Access Setup Compeleted</p>');
    }


    public function create()
    {
        $id   = $this->input->get('id');
        $data = array(
            'button'           => 'Create',
            'action'           => site_url(Backend_URL . 'online_mock/create_action'),
            'id'               => set_value('id'),
            'exam_id'          => set_value('exam_id', $id),
            'exam_centre_id'   => set_value('exam_centre_id'),
            'date'             => set_value('date', date('Y-m-d', strtotime('+1 Day'))),
            'hour'             => set_value('hour', date('h', strtotime('+1 month'))),
            'min'              => set_value('min', date('i', strtotime('+1 month'))),
            'am_pm'            => set_value('am_pm', date('a', strtotime('+1 month'))),
            'pass_station'     => set_value('pass_station', 10),
            'student_limit'    => set_value('student_limit', 10),
            'gmc_exam_dates'   => set_value('gmc_exam_dates'),
            'label'            => set_value('label'),
            'passing_criteria' => set_value('passing_criteria'),
            'zoom_link'        => set_value('zoom_link'),
            'created_at'       => set_value('created_at'),
            'updated_at'       => set_value('updated_at'),
        );
        $this->viewAdminContent('online_mock/online_mock/create', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $date_time = $this->input->post('date', TRUE);
            $date_time .= ' ' . $this->input->post('hour', TRUE);
            $date_time .= ':' . $this->input->post('min', TRUE);
            $date_time .= ' ' . $this->input->post('am_pm', TRUE);
            $datetime  = date('Y-m-d H:i:s', strtotime($date_time));
            $data      = array(
                'exam_id'          => $this->input->post('exam_id', TRUE),
                'exam_centre_id'   => $this->input->post('exam_centre_id', TRUE),
                'datetime'         => $datetime,
                'pass_station'     => $this->input->post('pass_station', TRUE),
                'passing_criteria' => $this->input->post('passing_criteria', TRUE),
                'zoom_link'        => $this->input->post('zoom_link', TRUE),

                'student_limit'  => $this->input->post('student_limit'),
                'gmc_exam_dates' => $this->input->post('gmc_exam_dates'),
                'label'          => $this->input->post('label'),
                'type'           => 'Online',
                'reading_time'   => '1.5',
                'practice_time'  => '8',
                'created_at'     => date('Y-m-d H:i:s')
            );

            $this->Online_mock_model->insert($data);
            $mock_id = $this->db->insert_id();

            $wa_link_id = (int)$this->input->post('whatsapp_id');
            if ($wa_link_id) {
                $rel_tbl = Tools::_link_for('Mock');
                Modules::run('whatsapp/_save_relation', $wa_link_id, $rel_tbl, $mock_id);
            }
            $this->session->set_flashdata('msgs', 'Online mock Added Successfully');
            redirect(site_url(Backend_URL . 'online_mock/scenario/' . $mock_id));
        }
    }

    public function update($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button'         => 'Update',
                'action'         => site_url(Backend_URL . 'online_mock/update_action'),
                'id'             => set_value('id', $row->id),
                'exam_id'        => set_value('exam_id', $row->exam_id),
                'exam_centre_id' => set_value('exam_centre_id', $row->exam_centre_id),

                'date'  => set_value('date', date('Y-m-d', strtotime($row->datetime))),
                'hour'  => set_value('hour', date('h', strtotime($row->datetime))),
                'min'   => set_value('min', date('i', strtotime($row->datetime))),
                'am_pm' => set_value('am_pm', date('a', strtotime($row->datetime))),

                'student_limit'  => set_value('student_limit', $row->student_limit),
                'gmc_exam_dates' => set_value('gmc_exam_dates', $row->gmc_exam_dates),
                'label'          => set_value('label', $row->label),

                'pass_station'     => set_value('pass_station', $row->pass_station),
                'passing_criteria' => set_value('passing_criteria', $row->passing_criteria),
                'zoom_link'        => set_value('zoom_link', $row->zoom_link),
                'created_at'       => set_value('created_at', $row->created_at),
                'updated_at'       => set_value('updated_at', $row->updated_at),
            );
            $this->viewAdminContent('online_mock/online_mock/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $date_time = $this->input->post('date', TRUE);
            $date_time .= ' ' . $this->input->post('hour', TRUE);
            $date_time .= ':' . $this->input->post('min', TRUE);
            $date_time .= ' ' . $this->input->post('am_pm', TRUE);
            $datetime  = date('Y-m-d H:i:s', strtotime($date_time));

            $data = array(
                'exam_id'          => (int)$this->input->post('exam_id', TRUE),
                'exam_centre_id'   => (int)$this->input->post('exam_centre_id', TRUE),
                'datetime'         => $datetime,
                'pass_station'     => $this->input->post('pass_station', TRUE),
                'passing_criteria' => $this->input->post('passing_criteria', TRUE),
                'zoom_link'        => $this->input->post('zoom_link', TRUE),
                'updated_at'       => date('Y-m-d H:i:s'),

                'student_limit'  => $this->input->post('student_limit'),
                'gmc_exam_dates' => $this->input->post('gmc_exam_dates'),
                'label'          => $this->input->post('label')
            );

            $this->Online_mock_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Online mock Updated Successfully');
            redirect(site_url(Backend_URL . 'online_mock/update/' . $id));
        }
    }

    public function delete($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $scenarios = countOnlineMockScenario($id);
            $students  = countOnlineMockStudent($id);
            $links     = $scenarios + $students;

            $data            = array(
                'id'             => $row->id,
                'course_name'    => $row->course_name,
                'centre_name'    => $row->centre_name,
                'centre_address' => $row->centre_address,
                'datetime'       => $row->datetime,
                'created_at'     => $row->created_at,
                'updated_at'     => $row->updated_at,
                'students'       => countOnlineMockStudent($id),
            );
            $data['warning'] = ($links) ? true : false;

            $this->viewAdminContent('online_mock/online_mock/delete', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }


    public function delete_action($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $red_id = $row->exam_id;
            $this->Online_mock_model->delete($id);
            $this->session->set_flashdata('msgs', 'Online mock Deleted Successfully');
            redirect(site_url(Backend_URL . "online_mock?id={$red_id}"));
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function publish($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $red_id = $row->exam_id;

            $students = $this->Online_mock_model->get_students($id);

            // updating the record
            if ($row->status == 'Published') {
                $updateData = array('status' => 'Unpublished', 'published_at' => date("Y-m-d H:i:s"));
            } else {
                $updateData = array('status' => 'Published', 'published_at' => date("Y-m-d H:i:s"));
            }

            $this->db->where('id', $id);
            $this->db->update('exam_schedules', $updateData);


            //Email send result published/unpublished
            foreach ($students as $student) {
                $option = [
                    'id'        => $student->id,
                    'url'       => site_url('results'),
                    'email'     => $student->email,
                    'full_name' => $student->title . ' ' . $student->fname . ' ' . $student->mname . ' ' . $student->lname,
                ];

                if ($row->status == 'Published') {
                    Modules::run('mail/resultUnpublished', $option);
                } else {
                    Modules::run('mail/resultPublished', $option);
                }

            }

            $this->session->set_flashdata('msgs', 'Online mock Published Successfully');
            redirect(site_url(Backend_URL . "online_mock?id={$red_id}"));
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }

    }

    public function _menu()
    {
        $menus = [
            'module'   => 'Online Mock',
            'icon'     => 'fa-list',
            'href'     => 'online_mock',
            'children' => $this->_get_course_names('online_mock')
        ];
        return buildMenuForMoudle($menus);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('exam_id', 'exam course', 'trim|required|numeric');
        $this->form_validation->set_rules('exam_centre_id', 'exam centre', 'trim|required|numeric');
        $this->form_validation->set_rules('date', 'date', 'trim|required');
        $this->form_validation->set_rules('hour', 'hour', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function exam_list_by_centre()
    {
        ajaxAuthorized();
        $center_id = (int)$this->input->post('centre_id');
        $html      = getOnlineMockNameDropDownByCentre($center_id, 0, true);
        if ($html == '') {
            $html = '<option value="0">Online mock not found!</option>';
        }
        echo ajaxRespond('OK', $html);
    }

    public function get_student_exams($student_exam_id)
    {

        $data = [
            'student_exam_id'   => $student_exam_id,
            'student_exam_info' => $this->Online_mock_model->get_student_exam_by_id($student_exam_id)
        ];
        $this->load->view('online_mock/online_mock/student_exam_info', $data);
    }

    public function assign_exam_set_status()
    {
        ajaxAuthorized();
        $id      = (int)$this->input->post('id', TRUE);
        $remarks = $this->input->post('remarks', TRUE);

        if (empty($id)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Their is nothing to save... </p>');
            exit;
        }

        if (empty($remarks)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Remarks is required! </p>');
            exit;
        }

        $this->db->trans_start();
        $updateData = array(
            'status'  => 'cancelled',
            'remarks' => $remarks,
        );
        $this->db->where('id', $id);
        $this->db->update('student_exams', $updateData);
        $this->db->trans_complete();

        echo ajaxRespond('OK', '<p class="ajax_success">Student Assign Online Mock Cancelled Successfully</p>');
    }

    public function cancel($id)
    {

        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'             => $row->id,
                'course_name'    => $row->course_name,
                'centre_name'    => $row->centre_name,
                'centre_address' => $row->centre_address,
                'datetime'       => $row->datetime,
                'gmc_exam_dates' => $row->gmc_exam_dates,
                'exam_status'    => $row->exam_status,
                'created_at'     => $row->created_at,
                'updated_at'     => $row->updated_at,
            );

            $this->viewAdminContent('online_mock/online_mock/cancel', $data);
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock?id=' . $id));
        }
    }

    public function cancel_action($id)
    {
        $row = $this->Online_mock_model->get_by_id($id);
        if ($row) {
            $red_id = $row->exam_id;

            //Get assigned assessors by exam information
            $assessors = $this->Online_mock_model->get_assigned_assessor_by_exam($id);
            //Get assigned students by exam information
            $students = $this->Online_mock_model->get_students($id);

            //Get admin information
            $admins = $this->Online_mock_model->get_admin_users();

            //Assessors email send exam Canceled
            foreach ($assessors as $assessor) {
                $option = [
                    'id'          => $assessor->id,
                    'email'       => $assessor->email,
                    'full_name'   => $assessor->first_name . ' ' . $assessor->last_name,
                    'exam_name'   => $row->course_name,
                    'datetime'    => $row->datetime,
                    'centre_name' => $row->centre_name
                ];
                Modules::run('mail/examCancel', $option);
            }

            //Student email send exam Canceled
            foreach ($students as $student) {
                $option = [
                    'id'          => $student->id,
                    'email'       => $student->email,
                    'full_name'   => $student->title . ' ' . $student->fname . ' ' . $student->lname,
                    'exam_name'   => $row->course_name,
                    'datetime'    => $row->datetime,
                    'centre_name' => $row->centre_name
                ];
                Modules::run('mail/examCancel', $option);
            }

            //Student email send exam Canceled
            foreach ($admins as $admin) {
                $option = [
                    'id'          => $admin->id,
                    'email'       => $admin->email,
                    'full_name'   => $admin->first_name . ' ' . $admin->last_name,
                    'exam_name'   => $row->course_name,
                    'datetime'    => $row->datetime,
                    'centre_name' => $row->centre_name
                ];
                Modules::run('mail/examCancel', $option);
            }

            // updating the record
            $updateData = array('exam_status' => 'Canceled', 'cancel_at' => date("Y-m-d H:i:s"));
            $this->db->where('id', $id);
            $this->db->update('exam_schedules', $updateData);

            $this->session->set_flashdata('msgs', 'Online mock Canceled Successfully');
            redirect(site_url(Backend_URL . "online_mock?id={$red_id}"));
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function rollback_action($id)
    {
        $exam = $this->Online_mock_model->get_by_id($id);
        if ($exam) {
            $this->db->set('exam_status', 'Active');
            $this->db->set('cancel_at', null);
            $this->db->where('id', $id);
            $this->db->update('exam_schedules');
            $this->session->set_flashdata('msgs', 'Online mock Rolled back Successfully');
            redirect(site_url(Backend_URL . "online_mock/scenario/{$id}"));
        } else {
            $this->session->set_flashdata('msge', 'Online mock Not Found');
            redirect(site_url(Backend_URL . 'online_mock'));
        }
    }

    public function set_status()
    {
        ajaxAuthorized();
        $id     = intval($this->input->post('post_id'));
        $status = $this->input->post('status');
        $this->db->set('status', $status)->where('id', $id)->update('exams');
        $class = '';
        switch ($status) {
            case 'Active':
                $status = '<i class="fa fa-check"></i> Active';
                $class  = 'btn-success';
                break;
            case 'Inactive':
                $status = '<i class="fa fa-ban"></i> Inactive';
                $class  = 'btn-danger';
                break;
        }
        echo json_encode(['Status' => $status . ' &nbsp; <i class="fa fa-angle-down"></i>', 'Class' => $class]);
    }

    public function save_scenario_time()
    {
        ajaxAuthorized();
        $id = $this->input->post('id');
        $this->db->set('reading_time', $this->input->post('reading_time'))
            ->set('practice_time', $this->input->post('practice_time'))
            ->where('id', $id)
            ->update('exam_schedules');
        echo ajaxRespond('OK', 'Time Save Successfully');
    }

    public function save_individual_scenario_time()
    {
        ajaxAuthorized();
        $id = $this->input->post('scenario_rel_id');
        $this->db->set('reading_time', $this->input->post('reading_time'))
            ->set('practice_time', $this->input->post('practice_time'))
            ->where('id', $id)
            ->update('scenario_relations');
        echo ajaxRespond('OK', 'Time Update Successfully');
    }
}