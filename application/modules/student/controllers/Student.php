<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-18
 */

class Student extends Admin_controller
{
    public $CI;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->model('Student_delete_model');
        $this->load->model('development_plan/Development_plan_model', 'Development_plan_model');
        $this->load->helper('student');
        $this->load->helper('assess/result');
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
    }

    public function index()
    {
        $q      = urldecode_fk(trim_fk($this->input->get('q', TRUE)));
        $type   = $this->input->get('type');
        $status = $this->input->get('status');

        $teacher_id         = (int)$this->input->get('tid');
        $exam_id            = (int)$this->input->get('eid');
        $country_origin_id  = (int)$this->input->get('ocid');
        $present_country_id = (int)$this->input->get('pcid');
        $centre_id          = (int)$this->input->get('cid');
        $exam_date          = $this->input->get('edt');

        $page   = intval($this->input->get('p'));
        $limit  = 25;
        $start  = startPointOfPagination($limit, $page);
        $target = build_pagination_url(Backend_URL . 'student', 'p', true);

        $total_rows = $this->Student_model->total_rows($type, $status, $teacher_id, $exam_id, $centre_id, $exam_date, $q, $country_origin_id, $present_country_id);
        $students   = $this->Student_model->get_limit_data($limit, $start, $type, $status, $teacher_id, $exam_id, $centre_id, $exam_date, $q, $country_origin_id, $present_country_id);

        $data = array(
            'students'   => $students,
            'q'          => $q,
            'pagination' => getPaginator($total_rows, $page, $target, $limit),
            'start'      => $start,
            'type'       => $type,
            'status'     => $status,
            'exam_id'    => $exam_id,
            'centre_id'  => $centre_id,
            'exam_date'  => $exam_date,
            'tid'        => $teacher_id,
            'ocid'       => $country_origin_id,
            'pcid'       => $present_country_id,
        );
        $this->viewAdminContent('student/student/index', $data);
    }

    public function read($id)
    {
        $row = $this->Student_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'                      => $row->id,
                'number_type'             => $row->number_type,
                'gmc_number'              => $row->gmc_number,
                'title'                   => $row->title,
                'fname'                   => $row->fname,
                'mname'                   => $row->mname,
                'lname'                   => $row->lname,
                'full_name'               => "{$row->title} {$row->fname} {$row->lname}",
                'email'                   => $row->email,
                'phone'                   => "+{$row->phone_code}{$row->phone}",
                'whatsapp'                => "+{$row->whatsapp_code}{$row->whatsapp}",
                'ethnicity'               => $row->ethnicity,
                'occupation'              => $row->occupation,
                'purpose_of_registration' => $row->purpose_of_registration,
                'address_line1'           => $row->address_line1,
                'address_line2'           => $row->address_line2,
                'postcode'                => $row->postcode,
                'country_of_origin'       => $row->country,
                'current_location'        => getCountryName($row->present_country_id),
                'gender'                  => $row->gender,
                'photo'                   => $row->photo,
                'exam_name'               => $row->exam_name,
                'exam_centre'             => $row->exam_centre,
                'exam_date'               => globalDateFormat($row->exam_date),
                'created_at'              => $row->created_at,
                'updated_at'              => $row->updated_at,
                'note'                    => $row->note,
            );
            $this->viewAdminContent('student/student/read', $data);
        } else {
            $this->session->set_flashdata('msge', 'Students Not Found');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function login($id)
    {
        $user = $this->Student_model->get_by_id($id);
        if ($user) {
            // Logout
            $cookie_logout = [
                'name'   => 'student_data',
                'value'  => false,
                'expire' => -84000,
                'secure' => false
            ];
            $this->input->set_cookie($cookie_logout);
            $this->session->unset_userdata('name');
            $this->session->unset_userdata('value');
            $this->session->unset_userdata('expire');
            $this->session->unset_userdata('secure');
            $this->session->unset_userdata('student_history');

            // Login
            $remember    = ($this->input->post('remember')) ? (60 * 60 * 24 * 7) : 0;
            $cookie_data = json_encode([
                'student_id'      => $user->id,
                'student_email'   => $user->email,
                'student_gmc'     => $user->gmc_number,
                'student_name'    => $user->fname . ' ' . $user->mname . ' ' . $user->lname,
                'student_history' => 0
            ]);
            $cookie      = [
                'name'   => 'student_data',
                'value'  => base64_encode($cookie_data),
                'expire' => $remember,
                'secure' => false
            ];

            $this->input->set_cookie($cookie);
            $this->session->set_userdata($cookie);

            redirect(site_url());
        } else {
            $this->session->set_flashdata('msge', 'Students Not Found');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function job_profile($id)
    {
        $row = $this->Student_model->get_by_id($id);

        if ($row) {
            $this->db->select('jp.*');
            $this->db->from('student_job_profile as jp');
            $this->db->where('jp.student_id', $id);
            $data = $this->db->get()->row();

            if ($data) {
                $data->id           = $id;
                $data->student_name = $this->Development_plan_model->getStudentName($id);
                $this->viewAdminContent('student/student/job_profile', $data);
            } else {
                $data['id']           = $id;
                $data['student_name'] = $this->Development_plan_model->getStudentName($id);
                $this->viewAdminContent('student/student/job_profile_not_setup', $data);
            }
        } else {
            $this->session->set_flashdata('msge', 'Students Not Found');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function message($id)
    {
        $page   = intval($this->input->get('p'));
        $limit  = 25;
        $start  = startPointOfPagination($limit, $page);
        $target = build_pagination_url(Backend_URL . "student/message/{$id}", 'p', true);
        $total  = $this->Student_model->total_mail($id);
        $mails  = $this->Student_model->get_mail_data($limit, $start, $id);
        $data   = array(
            'id'           => $id,
            'student_name' => $this->Development_plan_model->getStudentName($id),
            'pagination'   => getPaginator($total, $page, $target, $limit),
            'sl'           => $start + 1,
            'mails'        => $mails
        );
        $this->viewAdminContent('student/student/message', $data);
    }

    public function reset($id)
    {
        $row = $this->Student_model->get_by_id($id);
        if ($row) {
            $data = array(
                'student_name' => $this->Development_plan_model->getStudentName($id),
                'id'           => $row->id,
                'fname'        => $row->fname,
                'mname'        => $row->mname,
                'lname'        => $row->lname,
                'email'        => $row->email
            );
            $this->viewAdminContent('student/student/reset', $data);
        } else {
            $this->session->set_flashdata('msge', 'Students Not Found');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function reset_action()
    {
        $id    = $this->input->post('id', TRUE);
        $email = $this->input->post('email', TRUE);
        $pass  = trim_fk($this->input->post('new_pass', TRUE));

        $stren = password_strength($pass);
        if (!empty($stren)) {
            echo ajaxRespond('Fail', $stren);
            exit;
        }

        $data = array(
            'password'   => password_encription($pass),
            'updated_at' => date('Y-m-d H:i:s')
        );

        $this->Student_model->update($id, $data);

        $option = [
            'id'    => $id,
            'url'   => site_url('login'),
            'email' => $email,
            'pass'  => $pass,
        ];
        Modules::run('mail/pwd_reset_notification', $option);


        echo ajaxRespond('OK', '<p class="ajax_success">Students Password Changed Successfully</p>');

    }

    public function set_status()
    {
        $id     = intval($this->input->post('post_id'));
        $status = $this->input->post('status');
        $this->db->set('status', $status)->where('id', $id)->update('students');

        switch ($status) {
            case 'Active':
                $status = '<i class="fa fa-check"></i> Active';
                $class  = 'btn-success';
                break;
            case 'Inactive':
                $status = '<i class="fa fa-ban"></i> Inactive';
                $class  = 'btn-danger';
                break;
            case 'Pending':
                $status = '<i class="fa fa-hourglass-1" ></i> Pending';
                $class  = 'btn-info';
                break;
            case 'Archive':
                $status = '<i class="fa fa-ban"></i> Archive';
                $class  = 'btn-danger';
                break;
        }
        echo json_encode(['Status' => $status . ' &nbsp; <i class="fa fa-angle-down"></i>', 'Class' => $class]);

    }

    public function plan($id)
    {
        $plans = $this->Development_plan_model->get_by_student_id($id);

        $data = array(
            'id'           => $id,
            'student_name' => $this->Development_plan_model->getStudentName($id),
            'plans'        => $plans,
        );

        $this->viewAdminContent('student/student/plan', $data);

    }

    public function plan_personal($id)
    {
        $this->load->model('personal_dev_plan/Personal_dev_plan_model', 'Personal_dev_plan_model');
        $rows = $this->Personal_dev_plan_model->get_details($id);

        $data = array(
            'id'           => $id,
            'student_name' => $this->Personal_dev_plan_model->getStudentName($id),
            'plans'        => $rows,
        );
        $this->viewAdminContent('student/student/plan_personal', $data);
    }

    public function create()
    {
        $id   = (int)$this->input->get('id');
        $data = array(
            'button'                  => 'Create',
            'action'                  => site_url(Backend_URL . 'student/create_action'),
            'id'                      => set_value('id'),
            'number_type'             => set_value('number_type'),
            'gmc_number'              => set_value('gmc_number'),
            'title'                   => set_value('title'),
            'fname'                   => set_value('fname'),
            'mname'                   => set_value('mname'),
            'lname'                   => set_value('lname'),
            'email'                   => set_value('email'),
            'phone'                   => set_value('phone'),
            'phone_code'              => set_value('phone_code', 44),
            'whatsapp'                => set_value('whatsapp'),
            'whatsapp_code'           => set_value('whatsapp_code', 44),
            'password'                => set_value('password'),
            'ethnicity_id'            => set_value('ethnicity_id'),
            'occupation'              => set_value('occupation'),
            'purpose_of_registration' => set_value('purpose_of_registration'),
            'address_line1'           => set_value('address_line1'),
            'address_line2'           => set_value('address_line2'),
            'postcode'                => set_value('postcode'),
            'gender'                  => set_value('gender', 'Male'),
            'photo'                   => set_value('photo'),
            'exam_id'                 => set_value('exam_id', $id),
            'exam_centre_id'          => set_value('exam_centre_id'),
            'exam_date'               => set_value('exam_date'),
            'note'                    => set_value('note'),
        );
        $this->viewAdminContent('student/student/create', $data);
    }

    public function create_action()
    {
//        pp($this->input->post());
        $this->_rules_create();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $password = rand(11111111, 99999999);
            if ($_FILES['photo']['name']) {
                $photo = uploadPhoto($_FILES['photo'], 'uploads/student/' . date('Y/m/'), base64_encode(time()));
            } else {
                $photo = null;
            }
            $data = array(
                'number_type'             => $this->input->post('number_type', TRUE),
                'gmc_number'              => $this->input->post('gmc_number', TRUE),
                'title'                   => $this->input->post('title', TRUE),
                'fname'                   => $this->input->post('fname', TRUE),
                'mname'                   => $this->input->post('mname', TRUE),
                'lname'                   => $this->input->post('lname', TRUE),
                'email'                   => $this->input->post('email', TRUE),
                'phone_code'              => $this->input->post('phone_code', TRUE),
                'phone'                   => $this->input->post('phone', TRUE),
                'whatsapp_code'           => $this->input->post('whatsapp_code', TRUE),
                'whatsapp'                => $this->input->post('whatsapp', TRUE),
                'password'                => password_encription($password),
                'ethnicity_id'            => $this->input->post('ethnicity_id', TRUE),
                'occupation'              => $this->input->post('occupation', TRUE),
                'purpose_of_registration' => $this->input->post('purpose_of_registration', TRUE),
                'address_line1'           => $this->input->post('address_line1', TRUE),
                'address_line2'           => $this->input->post('address_line2', TRUE),
                'postcode'                => $this->input->post('postcode', TRUE),
                'gender'                  => $this->input->post('gender', TRUE),
                'photo'                   => $photo,
                'exam_id'                 => $this->input->post('exam_id', TRUE),
                'exam_centre_id'          => $this->input->post('exam_centre_id', TRUE),
                'exam_date'               => $this->input->post('exam_date', TRUE),
                'note'                    => $this->input->post('note', TRUE),
                'created_at'              => date('Y-m-d H:i:s'),
                'updated_at'              => date('Y-m-d H:i:s'),
            );
            $this->Student_model->insert($data);
            $student_id = $this->db->insert_id();

            $mail_data = [
                'id'        => $student_id,
                'full_name' => $this->input->post('fname', TRUE) . ' ' . $this->input->post('mname', TRUE) . ' ' . $this->input->post('lname', TRUE),
                'email'     => $this->input->post('email', TRUE),
                'password'  => $password,
            ];
            Modules::run('mail/onStudentRegistration', $mail_data);

            $this->session->set_flashdata('msgs', 'Student added & login information email send successfully');
            redirect(site_url(Backend_URL . 'student/exam/' . $student_id));
        }
    }

    public function update($id)
    {
        $row = $this->Student_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button'                  => 'Update',
                'action'                  => site_url(Backend_URL . 'student/update_action'),
                'id'                      => set_value('id', $row->id),
                'number_type'             => set_value('number_type', $row->number_type),
                'gmc_number'              => set_value('gmc_number', $row->gmc_number),
                'title'                   => set_value('title', $row->title),
                'fname'                   => set_value('fname', $row->fname),
                'mname'                   => set_value('mname', $row->mname),
                'lname'                   => set_value('lname', $row->lname),
                'email'                   => set_value('email', $row->email),
                'phone_code'              => set_value('phone_code', $row->phone_code),
                'phone'                   => set_value('phone', $row->phone),
                'whatsapp_code'           => set_value('whatsapp_code', $row->whatsapp_code),
                'whatsapp'                => set_value('whatsapp', $row->whatsapp),
                'password'                => set_value('password', $row->password),
                'ethnicity_id'            => set_value('ethnicity_id', $row->ethnicity_id),
                'occupation'              => set_value('occupation', $row->occupation),
                'purpose_of_registration' => set_value('purpose_of_registration', $row->purpose_of_registration),
                'address_line1'           => set_value('address_line1', $row->address_line1),
                'address_line2'           => set_value('address_line2', $row->address_line2),
                'postcode'                => set_value('postcode', $row->postcode),
                'country_id'              => set_value('country_id', $row->country_id),
                'present_country_id'      => set_value('present_country_id', $row->present_country_id),
                'gender'                  => set_value('gender', $row->gender),
                'status'                  => set_value('status', $row->status),
                'photo'                   => set_value('photo', $row->photo),
                'exam_id'                 => set_value('exam_id', $row->exam_id),
                'exam_centre_id'          => set_value('exam_centre_id', $row->exam_centre_id),
                'exam_date'               => set_value('exam_date', $row->exam_date),
                'note'                    => set_value('note', $row->note)
            );
            $this->viewAdminContent('student/student/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Students Not Found');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function update_action()
    {
        $this->_rules_update();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $gmc_number = $this->input->post('gmc_number', TRUE);
            if (empty($gmc_number)) {
                $gmc_number = null;
            }

            $data = array(
                'number_type'             => $this->input->post('number_type', TRUE),
                'gmc_number'              => $gmc_number,
                'title'                   => $this->input->post('title', TRUE),
                'fname'                   => $this->input->post('fname', TRUE),
                'mname'                   => $this->input->post('mname', TRUE),
                'lname'                   => $this->input->post('lname', TRUE),
                'phone_code'              => $this->input->post('phone_code', TRUE),
                'phone'                   => $this->input->post('phone', TRUE),
                'whatsapp_code'           => $this->input->post('whatsapp_code', TRUE),
                'whatsapp'                => $this->input->post('whatsapp', TRUE),
                'ethnicity_id'            => $this->input->post('ethnicity_id', TRUE),
                'occupation'              => $this->input->post('occupation', TRUE),
                'purpose_of_registration' => $this->input->post('purpose_of_registration', TRUE),
                'address_line1'           => $this->input->post('address_line1', TRUE),
                'address_line2'           => $this->input->post('address_line2', TRUE),
                'postcode'                => $this->input->post('postcode', TRUE),
                'country_id'              => (int)$this->input->post('country_id'),
                'present_country_id'      => (int)$this->input->post('present_country_id'),
                'gender'                  => $this->input->post('gender', TRUE),
                'status'                  => $this->input->post('status', TRUE),
                'exam_id'                 => (int)$this->input->post('exam_id'),
                'exam_centre_id'          => (int)$this->input->post('exam_centre_id'),
                'exam_date'               => $this->input->post('exam_date', TRUE),
                'note'                    => $this->input->post('note', TRUE),
                'updated_at'              => date('Y-m-d H:i:s')
            );

            if ($_FILES['photo']['name']) {
                removeImage($this->input->post('photo_old'));
                $data['photo'] = uploadPhoto($_FILES['photo'], 'uploads/student/' . date('Y/m/'), base64_encode(time()));
            }

            $this->Student_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Students updated successfully');
            redirect(site_url(Backend_URL . 'student/update/' . $id));
        }
    }

    public function exam($id)
    {
        $row = $this->Student_model->get_by_id($id);

        if ($row) {
            $data = array(
                'student_name'     => $this->Development_plan_model->getStudentName($id),
                'button'           => 'Update',
                'action'           => site_url(Backend_URL . 'student/exam_action'),
                'id'               => set_value('id', $row->id),
                'exam_centre_id'   => set_value('exam_centre_id'),
                'exam_schedule_id' => set_value('exam_schedule_id'),
                'exams'            => $this->getExamList($id),
                's'                => 0,
            );
//            dd( $data );

            $this->viewAdminContent('student/student/exam', $data);
        } else {
            $this->session->set_flashdata('msge', 'Student Not Found');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function exam_action()
    {
        $this->_rules_exam();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->exam($id);
        } else {

            $schedule_id = (int)$this->input->post('exam_schedule_id');
            // check already exist
            $this->db->where(['exam_schedule_id' => $schedule_id, 'student_id' => $id]);
            $isBooked = $this->db->count_all_results('student_exams');

            if ($isBooked) {
                $this->session->set_flashdata('msgw', 'He/She already booked for this Mock Exam');
                redirect(site_url(Backend_URL . 'student/exam/' . $id));
            }
            $name = getLoginUserData('name');
            $data = array(
                'exam_schedule_id' => $schedule_id,
                'student_id'       => $id,
                'status'           => 'Enrolled',
                'remarks'          => "Booked by {$name} from Student/Exam page",
                'created_at'       => date('Y-m-d H:i:s'),
            );

            $this->db->insert('student_exams', $data);
            $this->session->set_flashdata('msgs', 'Booked for Mock Exam Successfully');
            redirect(site_url(Backend_URL . 'student/exam/' . $id));
        }
    }

    public function _rules_exam()
    {
        $this->form_validation->set_rules('exam_centre_id', 'exam centre', 'trim|required');
        $this->form_validation->set_rules('exam_schedule_id', 'exam name', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    private function getExamList($id = 0)
    {
        $this->db->select('e.*, c.name as centre_name, c.address as centre_address, countries.name as country_name');
        $this->db->select('es.datetime,es.label,se.status, se.id as enroll_id, se.created_at as enrolled_at');
        $this->db->select('es.id as es_id, gmc_exam_dates');
        $this->db->where('se.student_id', $id);
        $this->db->from('student_exams as se');
        $this->db->join('exam_schedules as es', 'es.id = se.exam_schedule_id', 'LEFT');
        $this->db->join('exams as e', 'es.exam_id = e.id', 'LEFT');
        $this->db->join('exam_centres as c', 'c.id = es.exam_centre_id', 'LEFT');
        $this->db->join('countries', 'c.country_id = countries.id', 'LEFT');
        $this->db->order_by('se.id', 'DESC');
        return $this->db->get()->result();
    }

    public function delete($id)
    {
        $row  = $this->Student_model->get_by_id($id);
        $rels = $this->Student_model->coutn_all_links($id);
        if ($row) {
            $data = array(
                'student_name' => $this->Development_plan_model->getStudentName($id),
                'id'           => $row->id,
                'number_type'  => $row->number_type,
                'gmc_number'   => $row->gmc_number,
                'title'        => $row->title,
                'fname'        => $row->fname,
                'mname'        => $row->mname,
                'lname'        => $row->lname,
                'email'        => $row->email,
                'gender'       => $row->gender,
                'photo'        => $row->photo,
                'created_at'   => $row->created_at,
                'updated_at'   => $row->updated_at,
                't_rels'       => $rels['total'],
                'message'      => $rels,
            );
            $this->viewAdminContent('student/student/delete', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Students Not Found</p>');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function delete_action($id)
    {
        $row = $this->Student_model->get_by_id($id);
        if ($row) {
            $this->Student_delete_model->deleteStudent($id);

            $this->session->set_flashdata('message', '<p class="ajax_success">Students Deleted Successfully</p>');
            redirect(site_url(Backend_URL . 'student'));
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Students Not Found</p>');
            redirect(site_url(Backend_URL . 'student'));
        }
    }

    public function _menu()
    {
        return buildMenuForMoudle([
            'module'   => 'Student Database',
            'icon'     => 'fa-users',
            'href'     => 'student',
            'children' => [
                [
                    'title' => 'All Student',
                    'icon'  => 'fa fa-bars',
                    'href'  => 'student'
                ], [
                    'title' => ' |_ Add New',
                    'icon'  => 'fa fa-plus',
                    'href'  => 'student/create'
                ], [
                    'title' => 'Cancelled List',
                    'icon'  => 'fa fa-ban',
                    'href'  => 'student/cancelled'
                ], [
                    'title' => 'Logins Log',
                    'icon'  => 'fa fa-list',
                    'href'  => 'student/login_history'
                ], [
                    'title' => 'Logins Graph VIew',
                    'icon'  => 'fa fa-pie-chart',
                    'href'  => 'student/login_history/graph'
                ]
            ]
        ]);
    }

    public function cancelled()
    {
        $page   = (int)$this->input->get('p');
        $target = build_pagination_url(Backend_URL . 'student/cancelled', 'p', true);
        $limit  = 25;
        $start  = startPointOfPagination($limit, $page);

        $status   = ($this->input->get('status')) ? $this->input->get('status') : 'Cancelled';
        $total    = $this->Student_model->getTotalBlockedlist($status);
        $students = $this->Student_model->getBlockedlist($limit, $start, $status);

        $data = [
            'start'      => $start,
            'status'     => $status,
            'pagination' => getPaginator($total, $page, $target, $limit),
            'students'   => $students
        ];
        $this->viewAdminContent('student/student/cancelled', $data);
    }

    private function search_duplicate($email, $id, $column = 'email')
    {
        $this->db->where($column, $email);
        $this->db->where('id !=', $id);
        return $this->db->count_all_results('students');
    }

    public function _rules_update()
    {
        $id    = $this->input->post('id');
        $email = $this->input->post('email');
        if ($this->search_duplicate($email, $id, 'email')) {
            $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_unique_email',
                ['is_unique' => 'This email already in used']);
        }

        $this->form_validation->set_rules('gmc_number', 'GMC number', 'trim|callback_unique_student_number');

        $this->form_validation->set_rules('fname', 'fname', 'trim|required');
        $this->form_validation->set_rules('mname', 'mname', 'trim');
        $this->form_validation->set_rules('lname', 'lname', 'trim|required');

        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim');
        $this->form_validation->set_rules('ethnicity_id', 'ethnicity', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a ethnicity'
        ]);
        $this->form_validation->set_rules('country_id', 'country', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a country'
        ]);
        $this->form_validation->set_rules('phone_code', 'phone code', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select phone code'
        ]);
        $this->form_validation->set_rules('whatsapp_code', 'whatsapp code', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select whatsapp phone code'
        ]);
        $this->form_validation->set_rules('whatsapp', 'whatsapp', 'trim|required');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('photo', 'photo', 'trim');
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a exam'
        ]);
        $this->form_validation->set_rules('exam_centre_id', 'Exam Centre', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a exam centre'
        ]);
        $this->form_validation->set_rules('exam_date', 'Exam Date', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function _rules_create()
    {
        $this->form_validation->set_rules('gmc_number', 'GMC number', 'trim|required|min_length[7]|max_length[7]|callback_unique_student_number',
            [
                'min_length' => 'This GMC number must be a 7 digit',
                'max_length' => 'This GMC number must be a 7 digit',
            ]);
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_unique_email');

        $this->form_validation->set_rules('fname', 'first name', 'trim|required');
        $this->form_validation->set_rules('mname', 'middle name', 'trim');
        $this->form_validation->set_rules('lname', 'last name', 'trim|required');
        $this->form_validation->set_rules('phone', 'phone', 'trim|required');
        $this->form_validation->set_rules('ethnicity_id', 'ethnicity', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a ethnicity'
        ]);
        $this->form_validation->set_rules('phone_code', 'phone code', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select phone code'
        ]);
        $this->form_validation->set_rules('whatsapp_code', 'whatsapp code', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select whatsapp phone code'
        ]);
        $this->form_validation->set_rules('whatsapp', 'whatsapp', 'trim|required');
        $this->form_validation->set_rules('country_id', 'country', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a country'
        ]);
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a exam'
        ]);
        $this->form_validation->set_rules('exam_centre_id', 'Exam Centre', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a exam centre'
        ]);
        $this->form_validation->set_rules('exam_date', 'Exam Date', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function unique_student_number()
    {

        $id          = $this->input->post('id');
        $number_type = $this->input->post('number_type');// get number type
        $gmc_number  = $this->input->post('gmc_number'); // get student name
        $this->db->select('id');
        $this->db->from('students');
        $this->db->where('number_type', $number_type);
        $this->db->where('gmc_number', $gmc_number);
        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }
        $match = $this->db->get()->num_rows();
        if ($match) {
            $this->form_validation->set_message('unique_student_number', 'This ' . $number_type . ' number already in used');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function unique_email()
    {

        $id    = $this->input->post('id');
        $email = $this->input->post('email');// get email
        $this->db->select('id');
        $this->db->from('students');
        $this->db->where('email', $email);
        if (!empty($id)) {
            $this->db->where('id!=', $id);
        }
        $query = $this->db->get();
        $num   = $query->num_rows();
        if ($num > 0) {
            $this->form_validation->set_message('unique_email', 'This email already in used');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function get()
    {
        $id      = (int)$this->input->post('id');
        $exam_id = (int)$this->input->post('exam_id');
        $data    = [
            'exam_id'   => $exam_id,
            'exam_name' => Tools::getExamName($exam_id),
            'students'  => $this->Student_model->get($exam_id),
            'marked'    => $this->Student_model->marked($id)
        ];
        $this->load->view('student/student/get', $data);
    }

    public function save()
    {
        ajaxAuthorized();

        $exam_schedule_id = (int)$this->input->post('id', TRUE);
        $students         = $this->input->post('scenario', TRUE);

        if (empty($students)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Their is nothing to save... </p>');
            exit;
        }

        //Get already assigned student list
        $this->db->select('se.id, se.student_id, se.created_at as assign_at');
        $this->db->from('student_exams as se');
        $this->db->where('se.exam_schedule_id', $exam_schedule_id);
        $assigned_students = $this->db->get()->result();

        $assigned_student_arr     = [];
        $delete_assigned_students = [];
        foreach ($assigned_students as $student) {
            $assigned_student_arr[] = $student->student_id;

            if (!in_array($student->student_id, $students)) {
                $delete_assigned_students[] = $student->id;
            }
        }

        $data = [];
        $name = getLoginUserData('name');
        foreach ($students as $student_id) {
            if (!in_array($student_id, $assigned_student_arr)) {
                $data[] = [
                    'exam_schedule_id' => $exam_schedule_id,
                    'student_id'       => $student_id,
                    'status'           => 'Enrolled',
                    'remarks'          => "Booked by {$name} from Exam/Student page",
                    'created_at'       => date('Y-m-d H:i:s')
                ];
            }
        }

        $this->db->trans_start();
        if (!empty($delete_assigned_students)) {
            $this->db->where_in('id', $delete_assigned_students)->delete('student_exams');
        }

        if (!empty($data)) {
            $this->db->insert_batch('student_exams', $data);
        }

        $this->db->trans_complete();

        echo ajaxRespond('OK', '<p class="ajax_success">Student Assign Successfully</p>');
    }

    public function save_assignment()
    {
        ajaxAuthorized();

        $teacher_id = (int)$this->input->post('teacher_id', TRUE);
        $students   = $this->input->post('s_ids');
        if (empty($teacher_id)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please select teacher!. </p>');
            exit;
        }
        if (empty($students)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Their is nothing to save... </p>');
            exit;
        }
        $data = [];
        foreach ($students as $id) {
            $data[] = [
                'user_id'    => $teacher_id,
                'student_id' => $id,
                'timestamp'  => date('Y-m-d H:i:s')
            ];
        }

        $this->db->trans_start();
        $this->db->insert_batch('user_students_relation', $data);
        $this->db->trans_complete();

        echo ajaxRespond('OK', '<p class="ajax_success">Student Assign Successfully</p>');
    }

    public function progress($id)
    {

        $this->load->model('progression/Progression_model', 'Progression_model');
        $total    = $this->Student_model->get_progress_total($id);
        $progress = $this->Student_model->get_progress($id);

        $student             = $this->Student_model->get_by_id($id);
        $type                = $student->number_type; // GMC, GDC, NMC
        $getDropDownProgress = $this->Progression_model->getDropDownProgress($type);

        $data = array(
            'id'                  => $id,
            'student_name'        => $this->Development_plan_model->getStudentName($id),
            'progress'            => $progress,
            'total'               => $total,
            'type'                => $type,
            'getDropDownProgress' => $getDropDownProgress,
        );
        $this->viewAdminContent('student/student/progress', $data);
    }

    public function save_progress()
    {
        ajaxAuthorized();
        $file = null;
        if ($_FILES['file']['name']) {
            $path = 'uploads/certificate/' . date('Y/m/');
            $file = uploadFile($_FILES['file'], $path);
        }

        $student_id     = (int)$this->input->post('student_id');
        $progression_id = (int)$this->input->post('progression_id');

        if (!$progression_id) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Please select a progression</p>');
            exit;
        }

        if ($this->isAlreadyExist($student_id, $progression_id)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Progression already submited!</p>');
            exit;
        }

        $data = [
            'student_id'     => $student_id,
            'progression_id' => $progression_id,
            'completed'      => 'Yes',
            'file'           => $file,
            'timestamp'      => date('Y-m-d H:i:s'),
        ];
        $this->db->insert('student_progressions', $data);

        echo ajaxRespond('OK', '<p class="ajax_success">Progress Saved Successfully</p>');
    }


    public function progress_delete()
    {
        ajaxAuthorized();
        $id = (int)$this->input->post('id');

        $file = $this->db->select('file')->where('id', $id)->get('student_progressions')->row();
        if ($file) {
            $abs_path = dirname(BASEPATH) . "/{$file->file}";
            if ($file->file && file_exists($abs_path)) {
                removeImage($abs_path);
            }
        }
        $this->db->where('id', $id);
        $this->db->delete('student_progressions');

        echo ajaxRespond('OK', '<p class="ajax_success">Progress Deleted</p>');
    }

    private function isAlreadyExist($student_id, $progress_id)
    {
        $this->db->where('student_id', $student_id);
        $this->db->where('progression_id', $progress_id);
        return $this->db->count_all_results('student_progressions');
    }

    public function file($id)
    {

        $this->load->model('file/File_model', 'File_model');

        $files        = $this->File_model->get_by_student($id);
        $student_name = $this->Development_plan_model->getStudentName($id);

        $data = array(
            'files'        => $files,
            'student_name' => $student_name,
            'id'           => $id,
        );


        $this->viewAdminContent('student/student/file', $data);
    }

    public function file_upload()
    {
        ajaxAuthorized();

        $title = $this->input->post('title');

        if (empty($title)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Name is required!</p>');
            exit;
        }
        if (empty($_FILES['file']['name'])) {
            echo ajaxRespond('Fail', '<p class="ajax_error">File mus be selected!</p>');
            exit;
        }

        $file = null;
        if ($_FILES['file']['name']) {
            $path = 'uploads/files/' . date('Y/m/');
            $file = uploadFile($_FILES['file'], $path, 'file');
        }


        $data = [
            'student_id' => (int)$this->input->post('student_id'),
            'title'      => $title,
            'file'       => $file,
            'timestamp'  => date('Y-m-d H:i:s')
        ];

        $this->db->insert('files', $data);
        echo ajaxRespond('OK', '<p class="ajax_success">Document Saved Successfully</p>');
    }

    public function file_delete($id)
    {
        $this->load->model('file/File_model', 'File_model');
        $row = $this->File_model->get_by_id($id);

        if ($row) {
            $this->File_model->delete($id);
            removeImage($row->file);
            $this->session->set_flashdata('msgs', 'File Deleted Successfully');
        } else {
            $this->session->set_flashdata('msgs', 'File Not Found');
        }
        redirect(site_url(Backend_URL . "student/file/{$row->student_id}"));
    }
}