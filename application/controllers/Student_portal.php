<?php defined('BASEPATH') or exit('No direct script access allowed');

class Student_portal extends Frontend_controller
{
    // every thing coming form Frontend Controller

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');

        if (empty($this->student_id)) {
            redirect(site_url('login'));
        }
    }

    public function index()
    {
        $this->db->select('title,fname,lname,photo,gmc_number,number_type');
        $stu = $this->db->get_where('students', ['id' => $this->student_id])->row();

        $data = [
            'name'        => "{$stu->title} {$stu->fname} {$stu->lname}",
            'photo'       => $stu->photo,
            'gmc'         => $stu->gmc_number,
            'number_type' => $stu->number_type,
            //            'exams' => $this->upComingExam(),
            //            'sql' => $this->db->last_query()
        ];

//            dd( $data );
        $this->viewMemberContent('home', $data);
    }

    private function upComingExam()
    {

        $this->db->select('name');
        $this->db->where('id', 'es.exam_id', false, false);
        $sql = $this->db->get_compiled_select('exams');

        $this->db->select("es.id, ({$sql}) as name, es.datetime");
        $this->db->select('ec.name as centre, ec.address');
        $this->db->from('student_exams as se');
        $this->db->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'LEFT');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'LEFT');
        $this->db->where('se.student_id', $this->student_id);
        $this->db->where('es.datetime >=', date('Y-m-d 00:00:00'));
        $this->db->where('se.status', 'Enrolled');
        return $this->db->get()->result();
    }

    public function messages()
    {
        $this->load->model('message/Message_model', 'Message_model');
        $this->load->helper('message/message');
        $this->load->helper('student/student');

        $q        = null;
        $page     = intval($this->input->get('page'));
        $target   = build_pagination_url('messages', 'page', true);
        $limit    = 25;
        $start    = startPointOfPagination($limit, $page);
        $total    = $this->Message_model->total_rows($q, 'student');
        $messages = $this->Message_model->get_limit_data($limit, $start, $q, 'student');

//        dd( $messages );

        $data = array(
            'messages'   => $messages,
            'pagination' => getPaginator($total, $page, $target, $limit),
            'total_rows' => $total,
            'start'      => $start,
        );
        $this->viewMemberContent('messages', $data);
    }

    public function message_view($id)
    {
        $this->load->model('message/Message_model', 'Message_model');
        $this->load->helper('message/message');
        $this->load->helper('student/student');
        $row = $this->Message_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'        => $row->id,
                'parent_id' => $row->id,
                'user_id'   => $row->user_id,
                'subject'   => $row->subject,
                'body'      => $row->body,
                'status'    => $row->status,
                'open_at'   => globalDateTimeFormat($row->open_at),
                'opened_by' => $row->opened_by,
                'replys'    => $this->Message_model->get_replys($id)
            );

            if ($row->opened_by == 'Student') {
                $data['sender']   = getStudentName($row->student_id);
                $data['receiver'] = getUserNameByID($row->user_id);
            } else {
                $data['sender']   = getUserNameByID($row->user_id);
                $data['receiver'] = getStudentName($row->student_id);
            }
            $this->viewMemberContent('message_view', $data);
        } else {
            $this->session->set_flashdata('msge', 'Message Not Found');
            redirect(site_url(Backend_URL . 'messages'));
        }
    }

    public function message_new()
    {
        $this->load->helper('message/message');
        $id   = ($this->input->get('id')) ? (int)$this->input->get('id') : 0;
        $data = array(
            'user_id' => set_value('user_id', $id),
            'subject' => set_value('subject'),
            'message' => set_value('message')
        );
        $this->viewMemberContent('messages_new', $data);
    }

    public function message_new_action()
    {
        $this->isPost();
        $this->load->helper('message/message');
        $this->_rules_message_new();
        if ($this->form_validation->run() == FALSE) {
            $this->message_new();
        } else {
            $this->load->model('message/Message_model', 'Message_model');
            $data = array(
                'parent_id'  => 0,
                'student_id' => $this->student_id,
                'user_id'    => (int)$this->input->post('user_id'),
                'subject'    => $this->input->post('subject'),
                'body'       => $_POST['message'],
                'open_at'    => date('Y-m-d H:i:s'),
                'opened_by'  => 'Student',
            );
            $this->Message_model->insert($data);
            $this->session->set_flashdata('msgs', 'Message Opened Successfully');
            redirect(site_url('messages'));
        }
    }

    public function _rules_message_new()
    {
        $this->form_validation->set_rules('subject', 'subject', 'trim|required');
        $this->form_validation->set_rules('message', 'message', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function message_reply_action()
    {
        $this->isPost();
        $this->load->model('message/Message_model', 'Message_model');
        $id   = $this->input->post('parent_id');
        $data = array(
            'parent_id'  => (int)$this->input->post('parent_id'),
            'student_id' => $this->student_id,
            'user_id'    => (int)$this->input->post('user_id'),
            'subject'    => 'Inherited',
            'body'       => $_POST['message'],
            'open_at'    => date('Y-m-d H:i:s'),
            'opened_by'  => 'Student'
        );
        $this->Message_model->insert($data);
        $this->session->set_flashdata('msgs', 'Message Send Successfully');
        redirect(site_url("message_view/{$id}"));
    }

    public function profile()
    {
        $this->db->select('s.*, e.name as exam_name, ec.name as exam_centre, et.name as ethnicity_name, c.name as country_name');
        $this->db->select('pc.name as present_country_name, sc.questions as secret_question_1_name');
        $this->db->select('sc2.questions as secret_question_2_name');
        $this->db->join('exams as e', 'e.id=s.exam_id', 'LEFT');
        $this->db->join('exam_centres as ec', 'ec.id=s.exam_centre_id', 'LEFT');
        $this->db->join('ethnicities as et', 'et.id = s.ethnicity_id', 'LEFT');
        $this->db->join('countries as c', 'c.id = s.country_id', 'LEFT');
        $this->db->join('countries as pc', 'pc.id = s.present_country_id', 'LEFT');
        $this->db->join('secret_questions as sc', 'sc.id = s.secret_question_1', 'LEFT');
        $this->db->join('secret_questions as sc2', 'sc2.id = s.secret_question_2', 'LEFT');
        $data = $this->db->get_where('students as s', ['s.id' => $this->student_id])->row();

        $this->viewMemberContent('profile', $data);
    }


    public function profile_update()
    {
        $this->db->select('s.*, e.name as exam_name, ec.name as exam_centre');
        $this->db->join('exams as e', 'e.id=s.exam_id', 'LEFT');
        $this->db->join('exam_centres as ec', 'ec.id=s.exam_centre_id', 'LEFT');
        $stu = $this->db->get_where('students as s', ['s.id' => $this->student_id])->row();

        $data = array(
            'action'                  => site_url('student_portal/profile_update_action'),
            'id'                      => $stu->id,
            'number_type'             => $stu->number_type,
            'gmc_number'              => $stu->gmc_number,
            'exam_name'               => $stu->exam_name,
            'exam_centre'             => $stu->exam_centre,
            'exam_date'               => $stu->exam_date,
            'title'                   => set_value('title', $stu->title),
            'fname'                   => set_value('fname', $stu->fname),
            'mname'                   => set_value('mname', $stu->mname),
            'lname'                   => set_value('lname', $stu->lname),
            'email'                   => $stu->email,
            'phone_code'              => set_value('phone[code]', $stu->phone_code),
            'phone'                   => set_value('phone[number]', $stu->phone),
            'whatsapp_code'           => set_value('whatsapp[code]', $stu->whatsapp_code),
            'whatsapp'                => set_value('whatsapp[number]', $stu->whatsapp),
            'ethnicity_id'            => set_value('ethnicity_id', $stu->ethnicity_id),
            'occupation'              => set_value('occupation', $stu->occupation),
            'purpose_of_registration' => set_value('purpose_of_registration', $stu->purpose_of_registration),
            'address_line1'           => set_value('address_line1', $stu->address_line1),
            'address_line2'           => set_value('address_line2', $stu->address_line2),
            'postcode'                => set_value('postcode', $stu->postcode),
            'country_id'              => set_value('country_id', $stu->country_id),
            'present_country_id'      => set_value('present_country_id', $stu->present_country_id),
            'secret_question_1'       => set_value('secret_question_1', $stu->secret_question_1),
            'secret_question_2'       => set_value('secret_question_2', $stu->secret_question_2),
            'answer_1'                => set_value('answer_1', $stu->answer_1),
            'answer_2'                => set_value('answer_2', $stu->answer_2),
            'contact_by_rm'           => set_value('contact_by_rm', $stu->contact_by_rm),
            'gender'                  => set_value('gender', $stu->gender),
            'photo'                   => set_value('photo', $stu->photo)
        );
        $this->viewMemberContent('profile_update', $data);
    }

    public function profile_update_action()
    {
        $this->isPost();
        $this->_rules_profile_update();

        if ($this->form_validation->run() == FALSE) {
            $this->profile_update();
        } else {
            $phone    = $this->input->post('phone', TRUE);
            $whatsapp = $this->input->post('whatsapp', TRUE);

            $data = array(
                'title'              => $this->input->post('title', TRUE),
                'fname'              => $this->input->post('fname', TRUE),
                'mname'              => $this->input->post('mname', TRUE),
                'lname'              => $this->input->post('lname', TRUE),
                'phone_code'         => $phone['code'],
                'phone'              => $phone['number'],
                'whatsapp_code'      => $whatsapp['code'],
                'whatsapp'           => $whatsapp['number'],
                'ethnicity_id'       => $this->input->post('ethnicity_id', TRUE),
                'country_id'         => $this->input->post('country_id', TRUE),
                'present_country_id' => $this->input->post('present_country_id', TRUE),
                'occupation'         => $this->input->post('occupation', TRUE),
                'address_line1'      => $this->input->post('address_line1', TRUE),
                'address_line2'      => $this->input->post('address_line2', TRUE),
                'postcode'           => $this->input->post('postcode', TRUE),
                'gender'             => $this->input->post('gender', TRUE),
                'secret_question_1'  => $this->input->post('secret_question_1', TRUE),
                'answer_1'           => $this->input->post('answer_1', TRUE),
                'secret_question_2'  => $this->input->post('secret_question_2', TRUE),
                'answer_2'           => $this->input->post('answer_2', TRUE),
                'contact_by_rm'      => ($this->input->post('contact_by_rm')) ? 'Yes' : 'No',
                'updated_at'         => date('Y-m-d H:i:s')
            );


            if ($_FILES['photo']['name']) {
                removeImage($this->input->post('photo_old'));
                $data['photo'] = uploadPhoto($_FILES['photo'], 'uploads/student/' . date('Y/m/'), uniqid('file_'));
            }

            if (!empty($this->input->post("webcam_photo"))) {
                removeImage($this->input->post('photo_old'));

                $img         = $this->input->post("webcam_photo");
                $folder_path = 'uploads/student/' . date('Y/m/');
                if (!is_dir($folder_path)) {
                    mkdir($folder_path, 777);
                }
                $image_parts    = explode(";base64,", $img);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type     = $image_type_aux[1];

                $image_base64 = base64_decode_fk($image_parts[1]);
                $file_name    = uniqid('cam_') . '.png';

                $file_full_path = $folder_path . $file_name;
                file_put_contents($file_full_path, $image_base64);
                $data['photo'] = $file_full_path;
            }

            $this->db->where('id', $this->student_id)->update('students', $data);
            $this->session->set_flashdata('msgs', 'Profile updated successfully');
            redirect(site_url('profile'));
        }
    }

    public function _rules_profile_update()
    {
//        $gmc_number = $this->input->post('gmc_number');
//        if ($this->search_duplicate_student_gmc($gmc_number) > 1) {
//            $this->form_validation->set_rules('gmc_number', 'GMC number', 'trim|required|is_unique[students.gmc_number]',
//                ['is_unique' => 'This GMC number already in used']);
//        }

        $this->form_validation->set_rules('fname', 'fname', 'trim|required');
        $this->form_validation->set_rules('mname', 'mname', 'trim');
        $this->form_validation->set_rules('lname', 'lname', 'trim|required');

        $this->form_validation->set_rules('phone[code]', 'phone code', 'trim|required');
        $this->form_validation->set_rules('phone[number]', 'phone number', 'trim|required');

        $this->form_validation->set_rules('whatsapp[code]', 'whatsapp code', 'trim|required');
        $this->form_validation->set_rules('whatsapp[number]', 'whatsapp number', 'trim|required');

        $this->form_validation->set_rules('ethnicity_id', 'ethnicity', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a ethnicity'
        ]);

        $this->form_validation->set_rules('occupation', 'Occupation', 'trim|required');
        $this->form_validation->set_rules('address_line1', 'Address Line1', 'trim|required');
        $this->form_validation->set_rules('postcode', 'Postcode', 'trim|required');

        $this->form_validation->set_rules('country_id', 'country', 'trim|required|is_natural_no_zero', [
            'is_natural_no_zero' => 'Please select a country'
        ]);
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');

        $this->form_validation->set_rules('secret_question_1', 'Secret Question 1', 'trim|required');
        $this->form_validation->set_rules('answer_1', 'Answer 1', 'trim|required');
        $this->form_validation->set_rules('secret_question_2', 'Secret Question 2', 'trim|required');
        $this->form_validation->set_rules('answer_2', 'Answer 2', 'trim|required');

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function job_profile()
    {
        $this->db->from('student_job_profile');
        $this->db->where('student_id', $this->student_id);
        $data = $this->db->get()->row();

        if ($data) {
            $this->viewMemberContent('job_profile', $data);
        } else {
            $interest_ids = ($this->input->post('interest_ids')) ? $this->input->post('interest_ids') : array(0);
            $data         = [
                'id'                          => $this->student_id,
                'internship'                  => set_value('internship'),
                'internship_txt'              => set_value('internship_txt'),
                'specialty'                   => set_value('specialty'),
                'specialty_experience'        => set_value('specialty_experience'),
                'professional_qualifications' => set_value('professional_qualifications'),
                'interest_ids'                => set_value('interest_ids', implode(',', $interest_ids)),
                'uk_status'                   => set_value('uk_status'),
                'uk_status_other'             => set_value('uk_status_other'),
                'right_to_work'               => set_value('right_to_work'),
                'training_courses'            => set_value('training_courses'),
                'training_courses_other'      => set_value('training_courses_other'),
                'audit'                       => set_value('audit'),
                'research'                    => set_value('research'),
                'job_interested'              => set_value('job_interested'),
            ];
//            dd( $data );
            $this->viewMemberContent('job_profile_create', $data);
        }
    }

    public function job_profile_update()
    {

        $this->db->select('jp.*');
        $this->db->from('student_job_profile as jp');
        $this->db->where('jp.student_id', $this->student_id);
        $data = $this->db->get()->row();

        if (!$data) {
            redirect(site_url('job-profile'));
        }

        $this->db->from('student_job_specialty_rel');
        $this->db->where('student_id', $this->student_id);
        $data->specialty_rel = $this->db->get()->result_array();

        $this->viewMemberContent('job_profile_update', $data);
    }

    public function job_profile_update_action()
    {
        $this->isPost();
        $this->_rules_job_profile_update();

        $student_profile = $this->db->get_where('student_job_profile', ['student_id' => $this->student_id])->row();

        if ($this->form_validation->run() == FALSE) {
            if ($student_profile) {
                $this->job_profile_update();
            } else {
                $this->job_profile();
            }
        } else {
            $data = array(
                'internship'     => $this->input->post('internship', TRUE),
                'internship_txt' => $this->input->post('internship_txt', TRUE),
                'specialty'      => $this->input->post('specialty', TRUE),

                'interest_ids' => ($this->input->post('interest_ids', TRUE)) ? implode(',', $this->input->post('interest_ids', TRUE)) : null,

                'uk_status'                   => $this->input->post('uk_status', TRUE),
                'right_to_work'               => $this->input->post('right_to_work', TRUE),
                'professional_qualifications' => $this->input->post('professional_qualifications', TRUE),
                'training_courses'            => $this->input->post('training_courses', TRUE),
                'training_courses_other'      => $this->input->post('training_courses_other', TRUE),
                'uk_status_other'             => $this->input->post('uk_status_other', TRUE),
                'audit'                       => $this->input->post('audit', TRUE),
                'research'                    => $this->input->post('research', TRUE),
                'job_interested'              => $this->input->post('job_interested', TRUE),
                'updated_at'                  => date('Y-m-d H:i:s')
            );
            if ($student_profile) {
                $this->db->where('student_id', $this->student_id)->update('student_job_profile', $data);
            } else {
                $data['student_id'] = $this->student_id;
                $this->db->insert('student_job_profile', $data);
            }

            $specialties      = $this->input->post('specialties', true);
            $specialties_data = job_specilatiy_rel($specialties);
            if ($specialties_data) {
                $this->db->delete('student_job_specialty_rel', ['student_id' => $this->student_id]);
                $this->db->insert_batch('student_job_specialty_rel', $specialties_data);
            }

            $this->session->set_flashdata('msgs', 'Job Profile updated successfully');
            redirect(site_url('job-profile/update'));
        }
    }

    public function _rules_job_profile_update()
    {
        $this->form_validation->set_rules('internship', 'internship', 'trim|required');
        $this->form_validation->set_rules('internship_txt', 'internship_txt', 'trim');
        $this->form_validation->set_rules('specialty', 'specialty', 'trim|required');

        $this->form_validation->set_rules('uk_status', 'uk status', 'trim|required');
        $this->form_validation->set_rules('right_to_work', 'right to work', 'trim|required');
        $this->form_validation->set_rules('professional_qualifications', 'professional qualifications', 'trim|required');
        $this->form_validation->set_rules('training_courses', 'training courses', 'trim|required');
        $this->form_validation->set_rules('audit', 'audit', 'trim|required');
        $this->form_validation->set_rules('research', 'research', 'trim|required');
        $this->form_validation->set_rules('job_interested', 'which job are you interested', 'trim|required');
//        $this->form_validation->set_rules('specialty_experience', 'specialty experience', 'trim|required|is_natural_no_zero', [
//            'is_natural_no_zero' => 'Please select a specialty experience'
//        ]);

//        $this->form_validation->set_rules('ethnicity_id', 'ethnicity', 'trim|required|is_natural_no_zero', [
//            'is_natural_no_zero' => 'Please select a ethnicity'
//        ]);

        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function mock_exam_enrolled_summery()
    {
        //$id = $this->input->post('id');
        //$summery = Tools::enrolledStudentByMockExam($id);

        echo '<p>Confirm you enrollment.</p>';
        //echo $summery;
    }

    public function enrolled_confirm()
    {
        ajaxAuthorized();

        $es_id      = $this->input->post('id');
        $isEnrolled = Tools::isAlreadyEnrolled($es_id, $this->student_id);
        if ($isEnrolled) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Booking Fail. You already enrolled</p>');
            exit;
        }

        $data = [
            'exam_schedule_id' => $es_id,
            'student_id'       => $this->student_id,
            'status'           => 'Pending',
            'remarks'          => 'Enrolled by Student',
            'created_at'       => date('Y-m-d H:i:s')
        ];
        $this->db->insert('student_exams', $data);
        echo ajaxRespond('OK', '<p class="ajax_notice">Request sent. Awaiting for confirmation.</p>');
    }

    public function exams()
    {
        $exam_date = Tools::getStudentData($this->student_id, 'exam_date');
        $exam_id   = Tools::getStudentData($this->student_id, 'exam_id');
        $data      = [
//            'enrolled_exams'      => $this->upComingExam(),
'available_mock_exam' => ($exam_id && $exam_date) ? $this->availableMockExamForMe($exam_id, $exam_date) : [],
'sql'                 => $this->db->last_query()
        ];
        $this->viewMemberContent('exams', $data);
    }


    /* Depend on Student Exam Date */
    private function availableMockExamForMe($exam_id, $exam_date)
    {
        $this->db->select('name');
        $this->db->where('id', 'es.exam_id', false, false);
        $sql = $this->db->get_compiled_select('exams');

        $this->db->select('es.datetime, es.student_limit, es.label,es.gmc_exam_dates, es.zoom_link');
        $this->db->select('ec.name as centre, ec.address');
        $this->db->select("es.id, ({$sql}) as name");
        $this->db->from('exam_schedules as es');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'LEFT');
        $this->db->where('es.datetime >=', date('Y-m-d 00:00:00'));
        $this->db->where('es.exam_id', $exam_id);
        $this->db->where('es.exam_status', 'Active');
        $this->db->like('es.gmc_exam_dates', $exam_date);
        return $this->db->get()->result();
    }

    public function results()
    {
        $this->db->select('r.*, e.name as exam_name, ec.name as centre_name, ec.address');
//        $this->db->select('s.fname, s.mname, s.lname, s.email, s.phone, s.gmc_number');
        $this->db->select('es.datetime, es.published_at');
        $this->db->from('results as r');
//        $this->db->join('students as s', 's.id=r.student_id', 'left');
        $this->db->join('exam_schedules as es', 'es.id=r.exam_schedule_id', 'left');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left');

        $this->db->where('r.student_id', $this->student_id);
        $this->db->where('es.status', 'Published');
        $this->db->order_by('es.datetime', 'DESC');

        $data['start']   = 1;
        $data['results'] = $this->db->get()->result();

        $this->viewMemberContent('results', $data);
    }

    public function result_view($es_id)
    {

        $this->load->model('assess/Result_model', 'Result_model');
        $this->load->helper('assess/result');
        $student_id = $this->student_id;

        $results     = $this->Result_model->get_result($student_id, $es_id);
        $total_score = $total_pass_mark = $passed_station = 0;

        foreach ($results->details as $result) {
            $get_mark        = $result->technical_skills + $result->clinical_skills + $result->interpersonal_skills;
            $total_score     += $get_mark;
            $total_pass_mark += $result->pass_mark;

            if ($get_mark >= $result->pass_mark) {
                $passed_station += 1;
            }
        }

        $min_station          = $results->pass_station;
        $param_arr            = array('%PassStation%' => $results->pass_station, '%NameOfMockTest%' => $results->exam_name, '%YourScore%' => $total_score, '%PassedStations%' => $passed_station, '%MinPassMarkRequired%' => $total_pass_mark);
        $passing_criteria_str = strtr($results->passing_criteria, $param_arr);

        $data = array(
            'total_score'          => $total_score,
            'req_pass_mark'        => $total_pass_mark,
            'passed_station'       => $passed_station,
            'pass_or_fail'         => ($total_score >= $total_pass_mark && $passed_station >= $min_station) ? 'Pass' : 'Fail',
            'results'              => $results,
            'passing_criteria_str' => nl2br_fk($passing_criteria_str)
        );

        $this->viewMemberContent('result_view', $data);
    }

    public function understand()
    {
        $this->viewMemberContent('understand');
    }

    public function change_password()
    {
        $data = [];
        $this->viewMemberContent('change_password', $data);
    }


    public function change_password_action()
    {
        ajaxAuthorized();
        $old_pass = $this->input->post('old_pass');
        $new_pass = $this->input->post('new_pass');
        $con_pass = $this->input->post('con_pass');

        $error = password_strength($new_pass);
        if (!empty($error)) {
            echo ajaxRespond('Fail', $error);
            exit;
        }

        if (!$old_pass or !$new_pass or !$con_pass) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Required all fields</p>');
            exit;
        }
        if ($new_pass != $con_pass) {
            echo ajaxRespond('Fail', '<p class="ajax_error">Confirm Password Not Match</p>');
            exit;
        }

        $user    = $this->db->select('password')->get_where('students', ['id' => $this->student_id])->row();
        $db_pass = $user->password;
        $verify  = password_verify($old_pass, $db_pass);

        if ($verify == true) {
            $hass_pass = password_encription($new_pass);
            $this->db->update('students', ['password' => $hass_pass], ['id' => $this->student_id]);
            echo ajaxRespond('OK', '<p class="ajax_success">Password Reset Successfully</p>');
        } else {
            echo ajaxRespond('Fail', '<p class="ajax_error">Old Password not match, please try again.</p>');
        }
    }


    private function add_ind_learning_plan()
    {
        $data = array(
            'button'              => 'Create',
            'action'              => site_url('student_portal/add_ind_learning_plan_action'),
            'id'                  => set_value('id'),
            'student_id'          => set_value('student_id'),
            'aims'                => set_value('aims'),
            'goals'               => set_value('goals'),
            'date_of_achievement' => set_value('date_of_achievement'),
            'achievement'         => set_value('achievement'),
            'review_date'         => set_value('review_date'),
            'future_plan'         => set_value('future_plan'),
            'review'              => set_value('review')
        );

        $this->viewMemberContent('ind_learning_plan_add', $data);
    }

    public function add_ind_learning_plan_action()
    {
        $this->_learning_plan_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->add_ind_learning_plan();
        } else {
            $data = array(
                'student_id'          => $this->student_id,
                'aims'                => $this->input->post('aims', TRUE),
                'goals'               => $this->input->post('goals', TRUE),
                'date_of_achievement' => $this->input->post('date_of_achievement', TRUE),
                'achievement'         => $this->input->post('achievement', TRUE),
                'review_date'         => $this->input->post('review_date', TRUE),
                'future_plan'         => $this->input->post('future_plan', TRUE),
                'review'              => '',
                'created_at'          => date('Y-m-d H:i:s')
            );

            $this->load->model('development_plan/Development_plan_model', 'Development_plan_model');
            $this->Development_plan_model->insert($data);
            $this->session->set_flashdata('msgs', 'Individual Learning Plan Added Successfully');
            redirect(site_url('individual-learning-plan'));
        }
    }

    private function _learning_plan_rules()
    {
        $this->form_validation->set_rules('aims', 'aims', 'trim|required');
        $this->form_validation->set_rules('goals', 'goals', 'trim|required');
        $this->form_validation->set_rules('date_of_achievement', 'date of achievement', 'trim|required');
        $this->form_validation->set_rules('achievement', 'achievement', 'trim|required');
        $this->form_validation->set_rules('review_date', 'review date', 'trim|required');
        $this->form_validation->set_rules('future_plan', 'future plan', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function ind_learning_plan()
    {
        $id = $this->student_id;
        $this->load->model('development_plan/Development_plan_model', 'Development_plan_model');
        $plans = $this->Development_plan_model->get_by_student_id($id);

        $data = array(
            'id'           => $id,
            'student_name' => $this->Development_plan_model->getStudentName($id),
            'plans'        => $plans,
        );
        $this->viewMemberContent('ind_learning_plan', $data);

        /*
        $tab = $this->input->get('tab', true);
        if($tab == 'add'){
            $this->add_ind_learning_plan();
            
        } else {
            $id = $this->student_id;
            $this->load->model('development_plan/Development_plan_model', 'Development_plan_model');
            $plans = $this->Development_plan_model->get_by_student_id($id);

            $data = array(
                'id' => $id,
                'student_name' => $this->Development_plan_model->getStudentName($id),
                'plans' => $plans,
            );
            $this->viewMemberContent('ind_learning_plan', $data);
        }         
        */
    }


    public function personal_dev_plan()
    {
        $tab = $this->input->get('tab', true);
        if ($tab == 'setup') {
            $this->setup_personal_dev_plan();

        } elseif ($tab == 'update') {
            $this->update_personal_dev_plan();
        } else {
            $id = $this->student_id;
            $this->load->model('personal_dev_plan/Personal_dev_plan_model', 'Personal_dev_plan_model');
            $rows = $this->Personal_dev_plan_model->get_details($id);

            $data = array(
                'id'           => $id,
                'student_name' => $this->Personal_dev_plan_model->getStudentName($id),
                'plans'        => $rows,
            );
            $this->viewMemberContent('personal_dev_plan', $data);
        }


    }

    private function setup_personal_dev_plan()
    {
        $this->load->model('personal_dev_plan/Personal_dev_plan_model', 'Personal_dev_plan_model');

        $data = array(
            'student_id' => $this->student_id,
            'created_at' => date('Y-m-d H:i:s'),
            'action'     => site_url('student_portal/setup_personal_dev_plan_action'),
            'domains'    => $this->Personal_dev_plan_model->get_domains(),
        );
        $this->viewMemberContent('personal_dev_plan_setup', $data);
    }


    public function setup_personal_dev_plan_action()
    {
        $data = $this->input->post('plan');
        $this->db->insert_batch('personal_dev_plans', $data);
        Tools::fixPrimaryKey();
        $this->session->set_flashdata('msgs', 'Personal development plan Added Successfully');
        redirect(site_url('personal-development-plan'));
    }

    private function update_personal_dev_plan()
    {
        $this->load->model('personal_dev_plan/Personal_dev_plan_model', 'Personal_dev_plan_model');
        $plans = $this->Personal_dev_plan_model->get_details($this->student_id);

        $data = array(
            'button'       => 'Update',
            'action'       => site_url('student_portal/update_personal_dev_plan_action'),
            'id'           => set_value('id', $this->student_id),
            'created_at'   => set_value('created_at', date('Y-m-d H:i:s')),
            'student_id'   => set_value('student_id', $this->student_id),
            'student_name' => $this->Personal_dev_plan_model->getStudentName($this->student_id),
            'plans'        => $plans,
        );
        $this->viewMemberContent('personal_dev_plan_update', $data);
    }

    public function update_personal_dev_plan_action()
    {

        $this->db->where('student_id', $this->student_id)->delete('personal_dev_plans');
        $data = $this->input->post('plan');
        $this->db->insert_batch('personal_dev_plans', $data);
        Tools::fixPrimaryKey();

        $this->session->set_flashdata('msgs', 'Personal Development Plan Updated Successlly');
        redirect(site_url('personal-development-plan'));
    }

    //This function use for result PDF Download
    public function result_download($es_id)
    {

        $this->load->model('assess/Result_model', 'Result_model');
        $this->load->helper('assess/result');
        $student_id = $this->student_id;

        $results     = $this->Result_model->get_result($student_id, $es_id);
        $total_score = $total_pass_mark = $passed_station = 0;

        foreach ($results->details as $result) {
            $get_mark        = $result->technical_skills + $result->clinical_skills + $result->interpersonal_skills;
            $total_score     += $get_mark;
            $total_pass_mark += $result->pass_mark;

            if ($get_mark >= $result->pass_mark) {
                $passed_station += 1;
            }
        }
        if (!$results) {
            $this->session->set_flashdata('msgw', 'Result Not Found!');
            redirect(site_url('results'));
        }

        $min_station          = $results->pass_station;
        $param_arr            = array(
            '%PassStation%'         => $results->pass_station,
            '%NameOfMockTest%'      => $results->exam_name,
            '%YourScore%'           => $total_score,
            '%PassedStations%'      => $passed_station,
            '%MinPassMarkRequired%' => $total_pass_mark
        );
        $passing_criteria_str = strtr($results->passing_criteria, $param_arr);
        $data                 = array(
            'total_score'          => $total_score,
            'req_pass_mark'        => $total_pass_mark,
            'passed_station'       => $passed_station,
            'pass_or_fail'         => ($total_score >= $total_pass_mark && $passed_station >= $min_station) ? 'Pass' : 'Fail',
            'results'              => $results,
            's_id'                 => $student_id,
            'es_id'                => $es_id,
            'passing_criteria_str' => nl2br_fk($passing_criteria_str)
        );

        $this->load->library('m_pdf');
        // Write some HTML code:
        $html = $this->load->view('frontend/result_pdf', $data, true);
        // Write some HTML code:
        $this->m_pdf->pdf->AddPageByArray([
            'margin-left'   => 5,
            'margin-right'  => 5,
            'margin-top'    => 15,
            'margin-bottom' => 15,
        ]);
        $this->m_pdf->pdf->WriteHTML($html);


        // Output a PDF file directly to the browser
//        $this->m_pdf->pdf->Output();
        //Add 'D' parameter for download
        $this->m_pdf->pdf->Output($results->exam_name . ' result.pdf', 'D');

    }

    //This function use for result PDF Download
    public function result_download__xx($result_id)
    {

        //Get result information
        $this->db->select('r.*,e.name as exam_name, ec.name as center_name, es.datetime, es.status');
        $this->db->from('results as r');
        $this->db->join('exam_schedules as es', 'es.id=r.exam_schedule_id', 'left');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left');
        $this->db->where('r.id', $result_id);
        $this->db->where('es.status', 'Published');
        $results = $this->db->get()->row();

        //Get student result details
        $this->db->select('rd.*, s.name');
        $this->db->from('result_details as rd');
        $this->db->join('results as r', 'r.id=rd.result_id', 'left');
        $this->db->join('scenarios as s', 's.id=rd.scenario_id', 'left');
        $this->db->where('rd.result_id', $result_id);
        $this->db->where('r.student_id', $this->student_id);
        $result_details = $this->db->get()->result();
        if ($result_details) {
            $results->details = $result_details;
        }

        if (!$results) {
            $this->session->set_flashdata('msgw', 'Result Not Found!');
            redirect(site_url('results'));
        }

        $data = array(
            'results' => $results
        );

        $this->load->library('m_pdf');
        // Write some HTML code:
        $html = $this->load->view('frontend/result_pdf', $data, true);
        // Write some HTML code:
        $this->m_pdf->pdf->WriteHTML($html);

        // Output a PDF file directly to the browser
//        $this->m_pdf->pdf->Output();
        //Add 'D' parameter for download
        $this->m_pdf->pdf->Output($results->exam_name . ' result.pdf', 'D');

    }

    public function contact_us()
    {
        $this->viewMemberContent('contact-us');
    }

    public function whatsapps()
    {
        $data['links'] = [
            'Mock'     => Tools::getWhatsappLinks('Mock'),
            'Course'   => Tools::getWhatsappLinks('Course'),
            'Practice' => Tools::getWhatsappLinks('Practice'),
            'Country'  => Tools::getWhatsappLinks('Country'),
        ];
        $this->viewMemberContent('whats-app', $data);
    }

    public function stage()
    {
        //Get student data
        $stu = $this->db->get_where('students', ['id' => $this->student_id])->row();

        $this->db->select('p.*, sp.id as student_progression_id, sp.progression_id, sp.completed, sp.file, sp.timestamp');
        if (!empty($stu->number_type)) {
            $this->db->where('category', $stu->number_type); // GMC, NMC, GDC
        }
        $this->db->join('student_progressions as sp', "sp.progression_id=p.id and student_id={$this->student_id}", 'left');
        $progress = $this->db->get('progressions as p')->result();

        $data['sl']       = 0;
        $data['progress'] = $progress;

        $this->viewMemberContent('stage', $data);
    }

    public function upload_stage()
    {

        ajaxAuthorized();

        $post_data      = $this->input->post();
        $progression_id = $post_data['progression_id'];

        $this->db->select('id');
        $this->db->where('student_id', $this->student_id);
        $this->db->where('progression_id', $progression_id);
        $target = $this->db->get('student_progressions')->row();

        if (!empty($target)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">This Certificate Already Uploaded!</p>');
            exit;
        }

//        if(empty($_FILES['progression_file_'.$progression_id]['name'])){
//            echo ajaxRespond('Fail', '<p class="ajax_error">File mus be selected!</p>');
//            exit;
//        }

        $file = null;
        if ($_FILES['progression_file_' . $progression_id]['name']) {
            $path = 'uploads/certificate/' . date('Y/m/');
            $file = uploadFile($_FILES['progression_file_' . $progression_id], $path);
        }

        $data = [
            'student_id'     => $this->student_id,
            'progression_id' => $progression_id,
            'completed'      => !empty($post_data['completed_' . $progression_id]) ? $post_data['completed_' . $progression_id] : 'No',
            'file'           => $file,
            'timestamp'      => date('Y-m-d H:i:s')
        ];

        $this->db->insert('student_progressions', $data);
        echo ajaxRespond('OK', '<p class="ajax_success">Document Saved Successfully</p>');

    }

    public function delete_stage($id)
    {

        $this->db->where('id', $id);
        $row = $this->db->get('student_progressions')->row();

        if ($row) {
            removeImage($row->file);
            $this->db->where('id', $id);
            $this->db->delete('student_progressions');

            $this->session->set_flashdata('msgs', 'Certificate Deleted Successfully');
            redirect(site_url('stage'));
        } else {
            $this->session->set_flashdata('msgs', 'Certificate Not Found');
            redirect(site_url('stage'));
        }

    }

    public function docs()
    {
        $this->load->model('file/File_model', 'File_model');

        $q      = null;
        $page   = intval($this->input->get('page'));
        $target = build_pagination_url('docs', 'page', true);
        $limit  = 25;
        $start  = startPointOfPagination($limit, $page);
        $total  = $this->File_model->total_rows($q, 'student');
        $files  = $this->File_model->get_limit_data($limit, $start, $q, 'student');

        $data = array(
            'files'      => $files,
            'pagination' => getPaginator($total, $page, $target, $limit),
            'total'      => $total,
            'start'      => $start,
        );

        $this->viewMemberContent('docs', $data);
    }

    public function upload_doc()
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
            'student_id' => $this->student_id,
            'title'      => $title,
            'file'       => $file,
            'timestamp'  => date('Y-m-d H:i:s')
        ];

        $insert = $this->db->insert('files', $data);
        echo ajaxRespond('OK', '<p class="ajax_success">Document Saved Successfully</p>');

    }

    public function delete_doc($id)
    {
        $this->load->model('file/File_model', 'File_model');
        $row = $this->File_model->get_by_id($id);

        if ($row) {
            $this->File_model->delete($id);
            removeImage($row->file);
            $this->session->set_flashdata('msgs', 'File Deleted Successfully');
            redirect(site_url('docs'));
        } else {
            $this->session->set_flashdata('msgs', 'File Not Found');
            redirect(site_url('docs'));
        }
    }

    public function jobs()
    {
        $q       = $this->input->get('q');
        $job_for = $this->input->get('job_for');
        $page    = intval($this->input->get('page'));
        $target  = build_pagination_url('jobs', 'page', true);
        $limit   = 25;
        $start   = startPointOfPagination($limit, $page);
        $total   = $this->jobs_total_rows($q, $job_for);
        $jobs    = $this->jobs_get_limit_data($limit, $start, $q, $job_for);

        $data = array(
            'jobs'       => $jobs,
            'q'          => $q,
            'job_for'    => $job_for,
            'pagination' => getPaginator($total, $page, $target, $limit),
            'total_rows' => $total,
            'start'      => $start,
        );
        $this->viewMemberContent('jobs', $data);
    }

    private function jobs_total_rows($q, $job_for)
    {
        $this->jobs_sql($q, $job_for);
        return $this->db->count_all_results();
    }

    private function jobs_get_limit_data($limit, $start, $q, $job_for)
    {
        $this->jobs_sql($q, $job_for);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function jobs_sql($q, $job_for)
    {
        if ($q) {
            $this->db->like('id', $q);
            $this->db->or_like('post_title', $q);
            $this->db->or_like('description', $q);
            $this->db->or_like('skills', $q);
            $this->db->or_like('benefit', $q);
        }
        if ($job_for) {
            $this->db->where('job_for', $job_for);
        }
        $this->db->from('jobs');
    }

    public function applied_jobs()
    {
        $q      = $this->input->get('q');
        $page   = intval($this->input->get('page'));
        $target = build_pagination_url('applied-jobs', 'page', true);
        $limit  = 25;
        $start  = startPointOfPagination($limit, $page);
        $total  = $this->applied_jobs_total_rows($q);
        $jobs   = $this->applied_jobs_get_limit_data($limit, $start, $q);

        $data = array(
            'jobs'       => $jobs,
            'q'          => $q,
            'pagination' => getPaginator($total, $page, $target, $limit),
            'total_rows' => $total,
            'start'      => $start,
        );
        $this->viewMemberContent('applied_jobs', $data);
    }

    private function applied_jobs_total_rows($q)
    {
        $this->applied_jobs_sql($q);
        return $this->db->count_all_results();
    }

    private function applied_jobs_get_limit_data($limit, $start = 0, $q = null)
    {
        $this->applied_jobs_sql($q);
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function applied_jobs_sql($q)
    {
        $this->db->select('a.*, j.post_title');
        if ($q) {
            $this->db->like('id', $q);
//            $this->db->or_like('post_title', $q);
        }
        $this->db->from('job_applications as a');
        $this->db->join('jobs as j', 'j.id = a.job_id', 'LEFT');
    }

    public function jobDetails($id)
    {
        $this->db->where('id', $id);
        $this->db->where('status !=', 'Draft');
        $this->db->from('jobs');
        $job  = $this->db->get()->row();
        $data = array(
            'job' => $job
        );
        if ($job) {
            $this->viewMemberContent('job_details', $data);
        } else {
            $this->viewMemberContent('404');
        }
    }

    public function jobApply($id)
    {
        $this->db->where('id', $id);
        $this->db->where('status !=', 'Draft');
        $this->db->from('jobs');
        $job  = $this->db->get()->row();
        $data = array(
            'job'          => $job,
            'id'           => set_value('id'),
            'cover_letter' => set_value('cover_letter'),
        );
        if ($job) {
            $this->viewMemberContent('job_apply', $data);
        } else {
            $this->viewMemberContent('404');
        }
    }

    public function jobApplyAction()
    {
        $this->_jobApplyrules();

        $id = intval($this->input->post('id'));

        $application_exist = $this->db->where('student_id', $this->student_id)
            ->where('job_id', $id)
            ->count_all_results('job_applications');
        if ($application_exist) {
            $this->session->set_flashdata('msge', 'You already applied this job!');
            redirect(site_url('job/apply/' . $id));
        }

        if ($this->form_validation->run() == FALSE) {
            $this->jobApply($id);
        } else {
            $data = array(
                'job_id'       => $id,
                'student_id'   => $this->student_id,
                'cover_letter' => $this->input->post('cover_letter', TRUE),
                'status'       => 'Pending',
                'created_at'   => date("Y-m-d H:i:s")
            );

            $this->db->insert('job_applications', $data);
            $this->session->set_flashdata('msgs', 'Application Send Successfully!');
            redirect(site_url('jobs'));
        }
    }

    public function _jobApplyrules()
    {
        $this->form_validation->set_rules('cover_letter', 'cover letter', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}