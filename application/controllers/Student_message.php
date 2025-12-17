<?php defined('BASEPATH') or exit('No direct script access allowed');

class Student_message extends Frontend_controller
{
    // every thing coming form Frontend Controller

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');

        if (empty($this->student_id)) {
            redirect(site_url('login'));
        }

        $this->load->model('message/Student_message_model', 'Student_message_model');
        $this->load->helper('message/student_message');
        $this->load->helper('student/student');
        $this->load->helper('exam/exam');
    }

    public function find_a_study_partner()
    {
        $exams = $this->db->select('id, name')->where('status', 'Active')->order_by('name', 'asc')->get('exams')->result();

        $data = array(
            'exams' => $exams
        );

        $this->viewMemberContent('student_message/exam_list', $data);
    }

    public function students()
    {
        $q      = urldecode_fk(trim_fk($this->input->get('q', TRUE)));
        $page   = intval($this->input->get('p'));
        $exam_id = intval($this->input->get('exam_id'));
        $limit  = 25;
        $start  = startPointOfPagination($limit, $page);
        $target = build_pagination_url('student-messages/students', 'p', true);

        $this->__student_sql($q, $exam_id);
        $total_rows = $this->db->count_all_results();

        $this->__student_sql($q, $exam_id);
        $this->db->order_by('s.fname', 'asc');
        $this->db->limit($limit, $start);
        $students = $this->db->get()->result();

        $data = array(
            'students'   => $students,
            'q'          => $q,
            'exam_id'   => $exam_id,
            'pagination' => getPaginator($total_rows, $page, $target, $limit),
            'start'      => $start
        );

        $this->viewMemberContent('student_message/students', $data);
    }

    private function __student_sql($q, $exam_id)
    {
        $this->db->select('s.id, s.title, s.fname, s.mname, s.lname, s.gmc_number, s.email, s.photo, s.gender');
        $this->db->select('e.name as exam_name, s.exam_date');
        $this->db->select('ec.name as exam_centre_name');
        $this->db->where('s.status', 'Active');
        $this->db->from('students as s');
        $this->db->join('exams as e', 'e.id = s.exam_id', 'LEFT');
        $this->db->join('exam_centres as ec', 'ec.id = s.exam_centre_id', 'LEFT');

        $student_id = (int)$q;
        if ($student_id > 0) {
            $this->db->where('s.id', $student_id);
        } else if($q) {
            $this->db->group_start();
            $this->db->like('fname', $q);
            $this->db->or_like('mname', $q);
            $this->db->or_like('lname', $q);
            $this->db->or_like("CONCAT(fname, ' ', lname)", $q );
            $this->db->group_end();
        }
        if($exam_id > 0) {
            $this->db->where('s.exam_id', $exam_id);
        }
    }


    public function messages()
    {
        $q        = null;
        $page     = intval($this->input->get('page'));
        $target   = build_pagination_url('messages', 'page', true);
        $limit    = 5000;
        $start    = startPointOfPagination($limit, $page);
        $total    = $this->Student_message_model->total_rows($q);
        $messages = $this->Student_message_model->get_limit_data($limit, $start, $q);

        $data = array(
            'messages'   => $messages,
            'pagination' => getPaginator($total, $page, $target, $limit),
            'total_rows' => $total,
            'start'      => $start,
            'login_id'   => $this->student_id
        );
        $this->viewMemberContent('student_message/messages', $data);
    }

    public function message_view($id)
    {
        $messages = $this->Student_message_model->get_limit_data(5000, 0, '');
        $row = $this->Student_message_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'              => $row->id,
                'parent_id'       => $row->id,
                'from_student_id' => $row->from_student_id,
                'subject'         => $row->subject,
                'from_student'    => $row->from_student,
                'to_student'      => $row->to_student,
                'to_student_id'   => $row->to_student_id,
                'body'            => $row->body,
                'status'          => $row->status,
                'open_at'         => globalDateTimeFormat($row->open_at),
                'replys'          => $this->Student_message_model->get_replys($id),
                'login_id'        => $this->student_id,
                'messages'        => $messages
            );
            $this->viewMemberContent('student_message/message_view', $data);
        } else {
            $this->session->set_flashdata('msge', 'Message Not Found');
            redirect(site_url(Backend_URL . 'messages'));
        }
    }

    public function message_new()
    {
        $id   = ($this->input->get('id')) ? (int)$this->input->get('id') : 0;
        $data = array(
            'to_student_id' => set_value('to_student_id', $id),
            'subject'       => set_value('subject'),
            'message'       => set_value('message')
        );
        $this->viewMemberContent('student_message/messages_new', $data);
    }

    public function message_new_action()
    {
        $this->isPost();

        $this->_rules_message_new();
        if ($this->form_validation->run() == FALSE) {
            $this->message_new();
        } else {
            $data = array(
                'parent_id'       => 0,
                'from_student_id' => $this->student_id,
                'to_student_id'   => (int)$this->input->post('to_student_id'),
                'subject'         => $this->input->post('subject'),
                'body'            => $_POST['message'],
                'open_at'         => date('Y-m-d H:i:s')
            );
            $this->Student_message_model->insert($data);
            $this->session->set_flashdata('msgs', 'Message Opened Successfully');
            redirect(site_url('student-messages'));
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

        $id   = $this->input->post('parent_id');
        $data = array(
            'parent_id'       => (int)$this->input->post('parent_id'),
            'from_student_id' => $this->student_id,
            'to_student_id'   => (int)$this->input->post('to_student_id'),
            'subject'         => 'Inherited',
            'body'            => $_POST['message'],
            'open_at'         => date('Y-m-d H:i:s')
        );
        $this->Student_message_model->insert($data);
        $this->session->set_flashdata('msgs', 'Message Send Successfully');
        redirect(site_url("student-messages/message_view/{$id}"));
    }

}