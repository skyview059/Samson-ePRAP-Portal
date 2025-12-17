<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-16 application/modules/scenario/controllers/Scenario.php
 */

class Practice extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Scenario_model');
        $this->load->model('Practice_model');
        $this->load->helper('scenario');
        $this->load->helper('exam/exam');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['exams'] = $this->Practice_model->get_exam_list();
        $this->viewAdminContent('scenario/practice/index', $data);
    }

    public function practice_view($exam_id)
    {
        $subjects = $this->Practice_model->get_exam_subjects($exam_id);
//dd($subjects);
        $topics = [];
        foreach ($subjects as $subject) {
            $topics[$subject->id]['id']     = $subject->id;
            $topics[$subject->id]['name']   = $subject->name;
            $topics[$subject->id]['type']   = $subject->type;
            $topics[$subject->id]['topics'] = $this->Practice_model->get_exam_subject_topics($exam_id, $subject->id);
        }
        $data['exam_id']   = $exam_id;
        $data['exam_name'] = getExamName($exam_id);
        $data['topics']    = $topics;
        $this->viewAdminContent('scenario/practice/subjects_n_topics', $data);
    }

    public function marking_criteria($exam_id)
    {
        $data['exam_id']          = $exam_id;
        $data['exam_name']        = getExamName($exam_id);
        $data['action']           = site_url(Backend_URL . 'scenario/practice/marking_criteria_update_action');
        $data['marking_criteria'] = $this->db->get_where('exams', ['id' => $exam_id])->row()->marking_criteria;
        $this->viewAdminContent('scenario/practice/marking_criteria', $data);
    }

    public function marking_criteria_update_action()
    {
        $exam_id = intval($this->input->post('exam_id'));
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required');
        $this->form_validation->set_rules('marking_criteria', 'Marking Criteria', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->marking_criteria($exam_id);
        } else {
            $data = array(
                'marking_criteria' => $this->input->post('marking_criteria'),
                'updated_at'       => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $exam_id)->update('exams', $data);
            $this->session->set_flashdata('msgs', 'Marking Criteria Updated Successfully');
            redirect(site_url(Backend_URL . 'scenario/practice'));
        }
    }

    public function practice_topic_items_edit()
    {
        $exam_id    = intval($this->input->get('exam_id'));
        $topic_id   = intval($this->input->get('topic_id'));
        $subject_id = intval($this->input->get('subject_id'));

        $this->db->select('st.*, ss.name as subject_name, ex.name as exam_name');
        $this->db->where('st.id', $topic_id);
        $this->db->from('scenario_topics as st');
        $this->db->join('scenario_subjects as ss', 'ss.id = st.subject_id');
        $this->db->join('exams as ex', 'ex.id = st.exam_id');
        $row = $this->db->get()->row();
        if ($row) {
            $data['id']           = $topic_id;
            $data['exam_name']    = $row->exam_name;
            $data['subject_name'] = $row->subject_name;
            $data['topic_name']   = $row->name;
            $data['exam_id']      = $row->exam_id;
            $data['subject_id']   = $subject_id;
            $data['button']       = 'Update';

            $data['action']    = site_url(Backend_URL . 'scenario/practice/topic_update_action');
            $data['scenarios'] = $this->getScenarios($exam_id);
            $data['items']     = $this->getScenarioTopicItems($exam_id, $topic_id);
            $this->viewAdminContent('scenario/practice/topic_items', $data);
        } else {
            $this->session->set_flashdata('msge', 'Invalid topic ID');
            redirect(Backend_URL . 'scenario/practice/view/' . $exam_id);
        }
    }

    private function getScenarios($exam_id)
    {
        return $this->db->select('id, LPAD( reference_number, 3, "0") as ref_no, name, presentation')->get_where('scenarios', array('exam_id' => $exam_id))->result();
    }

    private function getScenarioTopicItems($exam_id, $topic_id)
    {
        return $this->db->select('sti.id, sc.name, sc.presentation, sc.reference_number as ref_no, sti.order')
            ->from('scenario_topics_items as sti')
            ->join('scenarios as sc', 'sc.id = sti.scenario_id')
            ->where('sti.scenario_topic_id', $topic_id)
            ->where('sc.exam_id', $exam_id)
            ->order_by('sti.order', 'ASC')
            ->get()->result();
    }

    public function topic_update_action()
    {
        $id         = intval($this->input->post('id', TRUE));
        $exam_id    = intval($this->input->post('exam_id', TRUE));
        $subject_id = intval($this->input->post('subject_id', TRUE));

        $data = array(
            'exam_id'    => $exam_id,
            'name'       => $this->input->post('name', TRUE),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id)->update('scenario_topics', $data);
        $this->session->set_flashdata('msgs', 'Scenario topic Updated Successfully');
        redirect(site_url(Backend_URL . 'scenario/practice/topic/edit?exam_id=' . $exam_id . '&topic_id=' . $id . '&subject_id=' . $subject_id));
    }

    public function topic_item_add_action()
    {
        $id          = intval($this->input->post('id', TRUE));
        $exam_id     = intval($this->input->post('exam_id', TRUE));
        $subject_id  = intval($this->input->post('subject_id', TRUE));
        $scenario_id = intval($this->input->post('scenario_id', TRUE));
        $data        = array(
            'exam_id'           => $exam_id,
            'scenario_topic_id' => $id,
            'scenario_id'       => $scenario_id,
            'subject_id'        => $subject_id,
            'order'             => 9999
        );
        $this->db->insert('scenario_topics_items', $data);

        $this->session->set_flashdata('msgs', 'Item Added Successfully!');
        redirect(site_url(Backend_URL . 'scenario/practice/topic/edit?exam_id=' . $exam_id . '&topic_id=' . $id . '&subject_id=' . $subject_id));
    }

    public function topic_create_action()
    {
        ajaxAuthorized();
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required');
        $this->form_validation->set_rules('subject_id', 'Subject', 'trim|required');
        $this->form_validation->set_rules('topic_name', 'Topic Name', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo ajaxRespond('FAIL', validation_errors());
        } else {
            $data = array(
                'exam_id'    => intval($this->input->post('exam_id')),
                'subject_id' => intval($this->input->post('subject_id')),
                'name'       => $this->input->post('topic_name'),
                'order'      => 0,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('scenario_topics', $data);
            echo ajaxRespond('OK', 'Topic has been created successfully.');
        }
    }

    public function topic_subject_action()
    {
        ajaxAuthorized();
        $this->form_validation->set_rules('exam_id', 'Exam', 'trim|required');
        $this->form_validation->set_rules('subject_name', 'Subject Name', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo ajaxRespond('FAIL', validation_errors());
        } else {
            $data = array(
                'exam_id'    => intval($this->input->post('exam_id')),
                'name'       => $this->input->post('subject_name'),
                'type'       => $this->input->post('type'),
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('scenario_subjects', $data);
            echo ajaxRespond('OK', 'Topic subject has been created successfully.');
        }
    }

    public function topic_subject_update_action()
    {
        ajaxAuthorized();
        $this->form_validation->set_rules('subject_id', 'Subject ID', 'trim|required');
        $this->form_validation->set_rules('exam_id', 'Exam ID', 'trim|required');
        $this->form_validation->set_rules('subject_name', 'Subject Name', 'trim|required');
        $this->form_validation->set_rules('subject_type', 'Subject Type', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo ajaxRespond('FAIL', validation_errors());
        } else {
            $data = array(
                'name'       => $this->input->post('subject_name'),
                'type'       => $this->input->post('subject_type'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            $this->db->where('id', $this->input->post('subject_id'))->update('scenario_subjects', $data);
            echo ajaxRespond('OK', 'Topic subject has been updated successfully.');
        }
    }

    public function scenario_subject_delete()
    {
        ajaxAuthorized();
        $id = intval($this->input->post('id'));
        $this->db->delete('scenario_subjects', ['id' => $id]);
        echo ajaxRespond('OK', 'Subject Deleted Successfully');
    }

    public function get_topic_rename_modal_data()
    {
        ajaxAuthorized();
        $topic_id  = intval($this->input->post('topic_id'));
        $topicData = $this->db->select('id, subject_id, name, exam_id')->get_where('scenario_topics', ['id' => $topic_id])->row();
        if ($topicData) {
            $data['topic_id']   = $topicData->id;
            $data['exam_id']    = $topicData->exam_id;
            $data['subject_id'] = $topicData->subject_id;
            $data['topic_name'] = $topicData->name;
            echo $this->load->view('scenario/practice/topic_rename_modal', $data);
        } else {
            echo 'Invalid Topic ID';
        }
    }

    public function topic_rename_action()
    {
        $data = array(
            'subject_id' => intval($this->input->post('subject_id')),
            'name'       => $this->input->post('topic_name'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $this->input->post('topic_id'))->update('scenario_topics', $data);

        $this->db->where('scenario_topic_id', intval($this->input->post('topic_id')))
            ->update('scenario_topics_items', [
                'subject_id' => intval($this->input->post('subject_id'))
            ]);
        $this->session->set_flashdata('msgs', 'Topic Updated Successfully');
        redirect(site_url(Backend_URL . 'scenario/practice/view/' . intval($this->input->post('exam_id'))));
    }

    public function scenario_subjects_save_order()
    {
        $items    = $this->input->post('item');
        $reorder  = [];
        $order_id = 0;
        foreach ($items as $item) {
            $reorder[] = [
                'id'    => $item,
                'order' => ++$order_id,
            ];
        }
        $this->db->update_batch('scenario_subjects', $reorder, 'id');
    }

    public function topic_save_order()
    {
        $items    = $this->input->post('subject-item');
        $reorder  = [];
        $order_id = 0;
        foreach ($items as $item) {
            $reorder[] = [
                'id'    => $item,
                'order' => ++$order_id,
            ];
        }
        $this->db->update_batch('scenario_topics', $reorder, 'id');
    }

    public function topic_items_save_order()
    {
        $items    = $this->input->post('item');
        $reorder  = [];
        $order_id = 0;
        foreach ($items as $item) {
            $reorder[] = [
                'id'    => $item,
                'order' => ++$order_id
            ];
        }
        $this->db->update_batch('scenario_topics_items', $reorder, 'id');
    }

    public function topic_item_delete()
    {
        ajaxAuthorized();
        $id = intval($this->input->post('id'));
        $this->db->delete('scenario_topics_items', ['id' => $id]);
        echo ajaxRespond('OK', 'Item Deleted Successfully');
    }

    public function topic_delete()
    {
        ajaxAuthorized();
        $id = intval($this->input->post('id'));
        $this->db->delete('scenario_topics', ['id' => $id]);
        $this->db->delete('scenario_topics_items', ['scenario_topic_id' => $id]);

        echo ajaxRespond('OK', 'topic Deleted Successfully');
    }
}