<?php defined('BASEPATH') or exit('No direct script access allowed');

class Mock extends Frontend_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('scenario/scenario');
        $this->load->helper('exam/exam');
        $this->load->helper('acl');
    }

    public function index()
    {
        if (empty($this->student_id)) {
            redirect(site_url('login'));
        }

        $page         = intval($this->input->get('page'));
        $target       = build_pagination_url('mock', 'page', true);
        $limit        = 25;
        $start        = startPointOfPagination($limit, $page);
        $total        = $this->totalRows();
        $online_mocks = $this->getLimitData($limit, $start);
        $data = array(
            'online_mocks' => $online_mocks,
            'pagination'   => getPaginator($total, $page, $target, $limit),
            'total_rows'   => $total,
            'start'        => $start,
        );
        $this->viewMemberContent('mock/index', $data);
    }

    private function totalRows()
    {
        $this->planSql();
        return $this->db->count_all_results();
    }

    private function getLimitData($limit, $start)
    {
        $this->planSql();
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function planSql()
    {
        $this->db->select('se.id, se.exam_schedule_id, es.type, es.published_at, es.label, es.status, es.datetime, es.zoom_link');
        $this->db->select('e.name as exam_name');
        $this->db->where('se.student_id', $this->student_id);
        $this->db->where('es.type', 'Online');
//        $this->db->where('es.status', 'Published');
        $this->db->from('student_exams as se');
        $this->db->join('exam_schedules as es', 'es.id = se.exam_schedule_id', 'left');
        $this->db->join('exams as e', 'e.id = es.exam_id', 'left');
    }

    public function examRoom($exam_schedule_id)
    {
        if (checkPermission('online_mock', getLoginUserData('role_id')) === 0) {
            redirect('404');
        }

        $this->db->where('es.id', $exam_schedule_id);
        $this->db->from('exam_schedules as es');
        $exam_schedule = $this->db->get()->row();
        $data = array(
            'exam_schedule' => $exam_schedule
        );
        $this->viewMemberContent('mock/exam_room', $data);
    }

    public function examRoomPractice($exam_schedule_id)
    {
        if (checkPermission('online_mock', getLoginUserData('role_id')) === 0) {
            redirect('404');
        }
        
        $page   = intval($this->input->get('page'));
        $limit  = 1;
        $start  = startPointOfPagination($limit, $page);
        $target = build_pagination_url('mock/exam-room/' . $exam_schedule_id . '/practice', 'page', true);

        $total_rows = $this->practiceTotalRows($exam_schedule_id);
        $scenarios  = $this->practiceGetLimitData($limit, $start, $exam_schedule_id);
        $total_mock_time = $this->practiceTotalExamTime($exam_schedule_id);
        if(empty($scenarios)){
            $this->session->set_flashdata('msge', 'No practice found!');
            redirect(site_url('mock'));
        }

        $data = array(
            'scenarios'        => $scenarios,
            'pagination'       => getPaginator($total_rows, $page, $target, $limit),
            'start'            => $start,
            'display_none'     => 'none',
            'exam_schedule_id' => $exam_schedule_id,
            'total_mock_time'  => round($total_mock_time, 2),
            'total_rows' => $total_rows
        );
        $this->viewFrontContent('frontend/mock/scenario_details', $data);
    }

    private function practiceTotalRows($exam_schedule_id)
    {
        $this->practiceSql($exam_schedule_id);
        return $this->db->count_all_results();
    }

    private function practiceGetLimitData($limit, $start, $exam_schedule_id)
    {
        $this->practiceSql($exam_schedule_id);
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function practiceSql($exam_schedule_id)
    {
        $this->db->select('sr.id, sr.reading_time, sr.practice_time');
        $this->db->select('es.exam_id, es.label');
        $this->db->select('s.presentation, s.name as scenario_name, s.candidate_instructions, s.patient_information, s.examiner_information');
        $this->db->select('s.setup, s.exam_findings, s.approach, s.explanation');
        $this->db->where('sr.exam_schedule_id', $exam_schedule_id);
        $this->db->where('se.student_id', $this->student_id);
        $this->db->from('scenario_relations as sr');
        $this->db->join('scenarios as s', 's.id = sr.scenario_id', 'left');
        $this->db->join('exam_schedules as es', 'es.id = sr.exam_schedule_id', 'left');
        $this->db->join('student_exams as se', 'se.exam_schedule_id = sr.exam_schedule_id', 'left');
    }

    private function practiceTotalExamTime($exam_schedule_id){
        $this->db->select('SUM(sr.reading_time + sr.practice_time) as total_exam_time');
        $this->db->where('sr.exam_schedule_id', $exam_schedule_id);
        $this->db->from('scenario_relations as sr');
        return $this->db->get()->row()->total_exam_time;
    }

}