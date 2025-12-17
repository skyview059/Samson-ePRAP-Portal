<?php defined('BASEPATH') or exit('No direct script access allowed');

class Study_plan extends Frontend_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('scenario/scenario');
        $this->load->helper('exam/exam');
        if (empty($this->student_id)) {
            redirect(site_url('login'));
        }
    }

    public function index()
    {
        $page        = intval($this->input->get('page'));
        $target      = build_pagination_url('study-plan', 'page', true);
        $limit       = 25;
        $start       = startPointOfPagination($limit, $page);
        $total       = $this->totalRows();
        $study_plans = $this->getLimitData($limit, $start);

        $data = array(
            'study_plans' => $study_plans,
            'pagination'  => getPaginator($total, $page, $target, $limit),
            'total_rows'  => $total,
            'start'       => $start,
        );
        $this->viewMemberContent('study_plan/index', $data);
    }

    private function totalRows()
    {
        $this->planSql();
        return $this->db->count_all_results();
    }

    private function getLimitData($limit, $start)
    {
        $this->planSql();
        $this->db->order_by('ssp.start_date_time', 'asc');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    private function planSql()
    {
        $this->db->select('ssp.*, e.name as exam_name');
        $this->db->select('ss.name as subject_name');
        $this->db->select('st.name as topic_name');
//        $this->db->select('sti.custom_title as topic_item_name, sti.scenario_id as main_scenario_id');
        $this->db->where('ssp.student_id', $this->student_id);
        $this->db->from('scenario_study_plan as ssp');
        $this->db->join('exams as e', 'e.id = ssp.exam_id', 'left');
        $this->db->join('scenario_subjects as ss', 'ss.id = ssp.subject_id', 'left');
        $this->db->join('scenario_topics as st', 'st.id = ssp.topic_id', 'left');
//        $this->db->join('scenario_topics_items as sti', 'sti.id = ssp.topic_item_id', 'left');
    }

    public function create()
    {
        $exam_id    = $this->input->get('exam_id');
        $subject_id = $this->input->get('subject_id');
        $topic_id   = $this->input->get('topic_id');
        $data       = array(
            'button'          => 'Create',
            'id'              => set_value('id'),
            'exam_id'         => set_value('exam_id', $exam_id),
            'subject_id'      => set_value('subject_id', $subject_id),
            'topic_id'        => set_value('topic_id', $topic_id),
            'topic_item_ids'  => set_value('topic_item_ids'),
            'start_date'      => set_value('start_date'),
            'start_time'      => set_value('start_time'),
            'end_date'        => set_value('end_date'),
            'end_time'        => set_value('end_time'),
            'duration'        => set_value('duration', '00:00:00'),
            'action'          => site_url('study_plan/create_action'),
            'ref_id'          => set_value('ref_id'),
            'start_date_time' => set_value('start_date_time'),
            'end_date_time'   => set_value('end_date_time'),
            'zoom_link'       => set_value('zoom_link'),
        );
        $this->viewMemberContent('study_plan/create', $data);
    }

    public function create_action()
    {
        $this->form_validation->set_rules('exam_id', 'exam', 'required');
        $this->form_validation->set_rules('subject_id', 'subject', 'required');
        $this->form_validation->set_rules('start_date', 'start date', 'required');
        $this->form_validation->set_rules('start_time', 'start time', 'required');
        $this->form_validation->set_rules('end_date', 'end date', 'required');
        $this->form_validation->set_rules('end_time', 'end time', 'required');
        if ($this->form_validation->run() == false) {
            $this->create();
        } else {
            $data = array(
                'exam_id'         => intval($this->input->post('exam_id')),
                'student_id'      => $this->student_id,
                'subject_id'      => intval($this->input->post('subject_id')),
                'topic_id'        => intval($this->input->post('topic_id')),
                'topic_item_ids'  => implode(',', $this->input->post('topic_item_ids')),
                'start_date_time' => $this->input->post('start_date') . ' ' . $this->input->post('start_time'),
                'end_date_time'   => $this->input->post('end_date') . ' ' . $this->input->post('end_time'),
                'duration'        => $this->input->post('duration'),
                'zoom_link'       => $this->input->post('zoom_link'),
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s')
            );
            $this->db->insert('scenario_study_plan', $data);
            $this->session->set_flashdata('msgs', 'Study plan created successfully');
            redirect(site_url('study-plan'));
        }
    }

    public function update($id)
    {
        $row = $this->db->get_where('scenario_study_plan', array('id' => $id))->row();
        if ($row) {
            $exam_id    = $this->input->get('exam_id') ?: $row->exam_id;
            $subject_id = $this->input->get('subject_id') ?: $row->subject_id;
            $topic_id   = $this->input->get('topic_id') ?: $row->topic_id;
            $data       = array(
                'button'          => 'Update',
                'id'              => set_value('id', $row->id),
                'exam_id'         => set_value('exam_id', $exam_id),
                'subject_id'      => set_value('subject_id', $subject_id),
                'topic_id'        => set_value('topic_id', $topic_id),
                'topic_item_ids'  => set_value('topic_item_ids', $row->topic_item_ids),
                'start_date'      => set_value('start_date', date('Y-m-d', strtotime($row->start_date_time))),
                'start_time'      => set_value('start_time', date('H:i', strtotime($row->start_date_time))),
                'end_date'        => set_value('end_date', date('Y-m-d', strtotime($row->end_date_time))),
                'end_time'        => set_value('end_time', date('H:i', strtotime($row->end_date_time))),
                'duration'        => set_value('duration', $row->duration),
                'action'          => site_url('study_plan/update_action'),
                'start_date_time' => set_value('start_date_time', $row->start_date_time),
                'end_date_time'   => set_value('end_date_time', $row->end_date_time),
                'zoom_link'       => set_value('zoom_link', $row->zoom_link),
            );
            $this->viewMemberContent('study_plan/update', $data);
        } else {
            $this->session->set_flashdata('error', 'Record not found');
            redirect(site_url('study-plan'));
        }
    }

    public function update_action()
    {
        $this->form_validation->set_rules('exam_id', 'exam', 'required');
        $this->form_validation->set_rules('subject_id', 'subject', 'required');
        $this->form_validation->set_rules('start_date', 'start date', 'required');
        $this->form_validation->set_rules('start_time', 'start time', 'required');
        $this->form_validation->set_rules('end_date', 'end date', 'required');
        $this->form_validation->set_rules('end_time', 'end time', 'required');
        if ($this->form_validation->run() == false) {
            $this->update($this->input->post('id'));
        } else {
            $data = array(
                'exam_id'         => intval($this->input->post('exam_id')),
                'student_id'      => $this->student_id,
                'subject_id'      => intval($this->input->post('subject_id')),
                'topic_id'        => intval($this->input->post('topic_id')),
                'topic_item_ids'  => ($this->input->post('topic_item_ids')) ? implode(',', $this->input->post('topic_item_ids')) : 0,
                'start_date_time' => $this->input->post('start_date') . ' ' . $this->input->post('start_time'),
                'end_date_time'   => $this->input->post('end_date') . ' ' . $this->input->post('end_time'),
                'duration'        => $this->input->post('duration'),
                'zoom_link'       => $this->input->post('zoom_link'),
                'updated_at'      => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('scenario_study_plan', $data);
            $this->session->set_flashdata('msgs', 'Study plan updated successfully');
            redirect(site_url('study-plan'));
        }
    }

    public function view($id)
    {
        $study_plan = $this->getStudyPlanByID($id);
        if ($study_plan) {
            $data = array(
                'study_plan' => $study_plan,
                'topic_items' => $this->getTopicItems($study_plan->topic_item_ids),
                'share_logs' => $this->getStudyPlanShareLog($id)
            );
            $this->viewMemberContent('study_plan/view', $data);
        } else {
            $this->session->set_flashdata('error', 'Record not found');
            redirect(site_url('study-plan'));
        }
    }

    private function getTopicItems($topic_item_ids)
    {
        $this->db->select('sti.id, sti.scenario_id, sti.exam_id, s.name as scenario_name, s.presentation');
        $this->db->where_in('sti.id', explode(',', $topic_item_ids));
        $this->db->from('scenario_topics_items as sti');
        $this->db->join('scenarios as s', 's.id = sti.scenario_id', 'left');
        return $this->db->get()->result();
    }

    public function share($id)
    {
        $study_plan = $this->getStudyPlanByID($id);
        if ($study_plan) {
            $data = array(
                'study_plan' => $study_plan,
                'share_logs' => $this->getStudyPlanShareLog($id),
                'topic_items' => $this->getTopicItems($study_plan->topic_item_ids),
            );
            $this->viewMemberContent('study_plan/share', $data);
        } else {
            $this->session->set_flashdata('error', 'Record not found');
            redirect(site_url('study-plan'));
        }
    }

    public function share_action()
    {
        $this->load->helper('student/student');

        $id         = intval($this->input->post('study_plan_id'));
        $study_plan = $this->getStudyPlanByID($id);
        if ($study_plan) {
            $emails = $this->input->post('email');
            foreach ($emails as $email) {
                $data = array(
                    'email'         => $email,
                    'study_plan_id' => $study_plan->id,
                    'created_at'    => date('Y-m-d H:i:s')
                );
                $this->db->insert('scenario_study_plan_share', $data);
                $this->uodated_at_scenario_study_plan($study_plan->id);
            }

            // Send email
            $option = [
                'sender_id'       => $study_plan->student_id,
                'emails'          => $emails,
                'sender_name'     => getStudentName($study_plan->student_id),
                'exam_name'       => $study_plan->exam_name,
                'subject_name'    => $study_plan->subject_name,
                'topic_name'      => $study_plan->topic_name,
                'start_date_time' => globalDateTimeFormat($study_plan->start_date_time),
                'end_date_time'   => globalDateTimeFormat($study_plan->end_date_time),
                'duration'        => $study_plan->duration,
                'zoom_link'       => $study_plan->zoom_link
            ];
            Modules::run('mail/sendStudyPlanMail', $option);

            $this->session->set_flashdata('msgs', 'Study plan shared successfully');
            redirect(site_url('study-plan'));
        } else {
            $this->session->set_flashdata('error', 'Record not found');
            redirect(site_url('study-plan'));
        }
    }

    private function getStudyPlanByID($id)
    {
        $this->db->select('ssp.*, e.name as exam_name');
        $this->db->select('ss.name as subject_name');
        $this->db->select('st.name as topic_name');
        $this->db->where('ssp.student_id', $this->student_id);
        $this->db->where('ssp.id', $id);
        $this->db->from('scenario_study_plan as ssp');
        $this->db->join('exams as e', 'e.id = ssp.exam_id', 'left');
        $this->db->join('scenario_subjects as ss', 'ss.id = ssp.subject_id', 'left');
        $this->db->join('scenario_topics as st', 'st.id = ssp.topic_id', 'left');
        return $this->db->get()->row();
    }

    private function getStudyPlanShareLog($id)
    {
        $this->db->where('study_plan_id', $id);
        $this->db->from('scenario_study_plan_share');
        return $this->db->get()->result();
    }

    private function uodated_at_scenario_study_plan($id)
    {
        $this->db->where('id', $id);
        $this->db->update('scenario_study_plan', ['updated_at' => date('Y-m-d H:i:s')]);
    }

    public function delete($id)
    {
        // scenario_study_plan
        $this->db->where('id', $id);
        $this->db->where('student_id', $this->student_id);
        $this->db->delete('scenario_study_plan');

        //scenario_study_plan_share
        $this->db->where('study_plan_id', $id);
        $this->db->delete('scenario_study_plan_share');

        $this->session->set_flashdata('msgs', 'Study plan deleted successfully');
        redirect(site_url('study-plan'));
    }

}