<?php defined('BASEPATH') or exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 2020-01-16
 */

class Scenario extends Admin_controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Scenario_model');
        $this->load->helper('scenario');
        $this->load->helper('exam/exam');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q    = urldecode_fk(trim_fk($this->input->get('q')));
        $page = intval($this->input->get('p'));
        $id   = intval($this->input->get('id'));

        $target = build_pagination_url(Backend_URL . 'scenario/', 'p', true);

        $limit = 25;
        $start = startPointOfPagination($limit, $page);

        $total_rows = $this->Scenario_model->total_rows($id, $q);
        $scenarios  = $this->Scenario_model->get_limit_data($limit, $start, $id, $q);

//        echo $this->db->last_query();


        $data = array(
            'scenarios'  => $scenarios,
            'q'          => $q,
            'pagination' => getPaginator($total_rows, $page, $target, $limit),
            'total_rows' => $total_rows,
            'start'      => $start,
            'id'         => $id,
        );
        $this->viewAdminContent('scenario/scenario/index', $data);
    }

    public function read($id)
    {
        $row = $this->Scenario_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'                     => $row->id,
                'exam_id'                => $row->exam_id,
                'category_name'          => $row->category_name,
                'presentation'                   => $row->presentation,
                'name'                   => $row->name,
                'candidate_instructions' => $row->candidate_instructions,
                'patient_information'    => $row->patient_information,
                'examiner_information'   => $row->examiner_information,
                'setup'             => $row->setup,
                'exam_findings'     => $row->exam_findings,
                'approach'          => $row->approach,
                'explanation'       => $row->explanation,

                'reference_number'       => $row->reference_number,
                'created_at'             => $row->created_at,
                'updated_at'             => $row->updated_at,
            );
            $this->viewAdminContent('scenario/scenario/read', $data);
        } else {
            $this->session->set_flashdata('msge', 'Scenario Not Found');
            redirect(site_url(Backend_URL . 'scenario'));
        }
    }

    public function single_print($id)
    {
        $row = $this->Scenario_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id'                     => $row->id,
                'exam_id'                => $row->exam_id,
                'category_name'          => $row->category_name,
                'presentation'                   => $row->presentation,
                'name'                   => $row->name,
                'reference_number'       => $row->reference_number,

                'candidate_instructions' => $row->candidate_instructions,
                'patient_information'    => $row->patient_information,
                'examiner_information'   => $row->examiner_information,
                
                'setup'         => $row->setup,
                'exam_findings' => $row->exam_findings,
                'approach'      => $row->approach,
                'explanation'   => $row->explanation,

                
                'created_at'             => $row->created_at,
                'updated_at'             => $row->updated_at,
            );
            if ($this->input->get('is') == 'candidate') {
                $this->load->view('scenario/scenario/print_candidate_instructions', $data);
            } else if ($this->input->get('is') == 'patient') {
                $this->load->view('scenario/scenario/print_patient_information', $data);
            } else if ($this->input->get('is') == 'examiner') {
                $this->load->view('scenario/scenario/print_examiner_information', $data);
            } else {
                $this->load->view('scenario/scenario/print_full_page', $data);
            }

        } else {
            $this->session->set_flashdata('msge', 'Scenario Not Found');
            redirect(site_url(Backend_URL . 'scenario'));
        }
    }

    public function create()
    {
        $exam_id = (int)$this->input->get('id');
        $data    = array(
            'button'                 => 'Create',
            'action'                 => site_url(Backend_URL . 'scenario/create_action'),
            'id'                     => set_value('id'),
            'exam_id'                => set_value('exam_id', $exam_id),
            'presentation'                   => set_value('presentation'),
            'name'                   => set_value('name'),
            'reference_number'       => set_value('reference_number'),
            'candidate_instructions' => set_value('candidate_instructions'),
            'patient_information'    => set_value('patient_information'),
            'examiner_information'   => set_value('examiner_information'),
            'setup'                  => set_value('setup'),
            'exam_findings'          => set_value('exam_findings'),
            'approach'               => set_value('approach'),
            'explanation'            => set_value('explanation'),
            
        );
        $this->viewAdminContent('scenario/scenario/create', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $exam_id = (int)$this->input->post('exam_id');
            $data    = array(
                'exam_id'                => $exam_id,
                'presentation'                   => $this->input->post('presentation', TRUE),
                'name'                   => $this->input->post('name', TRUE),
                'reference_number'       => $this->input->post('reference_number', TRUE),

                'candidate_instructions' => $this->input->post('candidate_instructions', TRUE),
                'patient_information'    => $this->input->post('patient_information', TRUE),
                'examiner_information'   => $this->input->post('examiner_information', TRUE),                

                'setup'                 => $this->input->post('setup'),
                'exam_findings'         => $this->input->post('exam_findings'),
                'approach'              => $this->input->post('approach'),
                'explanation'           => $this->input->post('explanation'),   

                'created_at'             => date('Y-m-d H:i:s'),
                'updated_at'             => date('Y-m-d H:i:s'),
            );

            $this->Scenario_model->insert($data);
            $this->session->set_flashdata('msgs', 'Scenario Added Successfully');
            redirect(site_url(Backend_URL . "scenario?id={$exam_id}"));
        }
    }

    public function update($id)
    {
        $scenario = $this->Scenario_model->get_by_id($id);

        if ($scenario) {
            $data = array(
                'button'                 => 'Update',
                'action'                 => site_url(Backend_URL . 'scenario/update_action'),
                'id'                     => set_value('id', $scenario->id),
                'exam_id'                => set_value('exam_id', $scenario->exam_id),
                'presentation'                   => set_value('presentation', $scenario->presentation),
                'name'                   => set_value('name', $scenario->name),
                'reference_number'       => set_value('reference_number', $scenario->reference_number),

                'candidate_instructions' => set_value('candidate_instructions', $scenario->candidate_instructions),
                'patient_information'    => set_value('patient_information', $scenario->patient_information),
                'examiner_information'   => set_value('examiner_information', $scenario->examiner_information),

                'setup'             => set_value('setup', $scenario->setup),
                'exam_findings'     => set_value('exam_findings', $scenario->exam_findings),
                'approach'          => set_value('approach', $scenario->approach),
                'explanation'       => set_value('explanation', $scenario->explanation)
            );
            $this->viewAdminContent('scenario/scenario/update', $data);
        } else {
            $this->session->set_flashdata('msge', 'Scenario Not Found');
            redirect(site_url(Backend_URL . 'scenario'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        $id = $this->input->post('id', TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->update($id);
        } else {
            $data = array(
                'exam_id'                => $this->input->post('exam_id', TRUE),
                'presentation'                   => $this->input->post('presentation', TRUE),
                'name'                   => $this->input->post('name', TRUE),
                'reference_number'       => $this->input->post('reference_number', TRUE),

                'candidate_instructions' => $this->input->post('candidate_instructions', TRUE),
                'patient_information'    => $this->input->post('patient_information', TRUE),
                'examiner_information'   => $this->input->post('examiner_information', TRUE),

                'setup'                 => $this->input->post('setup'),
                'exam_findings'         => $this->input->post('exam_findings'),
                'approach'              => $this->input->post('approach'),
                'explanation'           => $this->input->post('explanation'),                
                'updated_at'             => date('Y-m-d H:i:s'),
            );

            $this->Scenario_model->update($id, $data);
            $this->session->set_flashdata('msgs', 'Scenario Updated Successfully');
            redirect(site_url(Backend_URL . 'scenario/update/' . $id));
        }
    }

    public function delete($id)
    {
        $row = $this->Scenario_model->get_by_id($id);

        if ($row) {
            $data = array(
                'id'               => $row->id,
                'exam_id'          => $row->exam_id,
                'presentation'             => $row->presentation,
                'name'             => $row->name,
                'reference_number' => $row->reference_number,
                'relation'         => $this->Scenario_model->relation($id),
            );
            $this->viewAdminContent('scenario/scenario/delete', $data);
        } else {
            $this->session->set_flashdata('msge', 'Scenario Not Found');
            redirect(site_url(Backend_URL . 'scenario'));
        }
    }


    public function delete_action($id)
    {
        $row = $this->Scenario_model->get_by_id($id);

        if ($row) {
            $this->Scenario_model->delete($id);
            $this->session->set_flashdata('msgs', 'Scenario Deleted Successfully');
            redirect(site_url(Backend_URL . "scenario?id={$row->exam_id}"));
        } else {
            $this->session->set_flashdata('msge', 'Scenario Not Found');
            redirect(site_url(Backend_URL . 'scenario'));
        }
    }

    public function ajax_del_action()
    {
        $id  = (int)$this->input->post('id');
        $row = $this->Scenario_model->get_by_id($id);
        $del = FALSE;
        if ($row) {
            $this->Scenario_model->delete($id);
            $del = TRUE;
        }

        echo ($del) ? ajaxRespond('OK', 'Deleted') : ajaxRespond('Fail', 'Deleted');
    }

    public function ajax_batch_delete()
    {
        ajaxAuthorized();
        $id = $this->input->post('id');
        if (empty($id)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">No ID Selected</p>');
            exit;
        }

        $this->db->where_in('id', array_keys($id));
        $this->db->delete('scenarios');

        echo ajaxRespond('OK', '<p class="ajax_success">Selected Scenario Deleted Successfully</p>');
    }


    public function _menu()
    {
        $menus               = [
            'module'   => 'Scenario Bank',
            'icon'     => 'fa-home',
            'href'     => 'scenario',
            'children' => $this->_get_course_names('scenario')
        ];
        $menus['children'][] = [
            'title' => "Scenario Practice &nbsp; <span class='label label-success'>New</span>",
            'icon'  => 'fa fa-circle-o',
            'href'  => "scenario/practice"
        ];
        return buildMenuForMoudle($menus);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('exam_id', 'exam category', 'trim|required|numeric');
        $this->form_validation->set_rules('presentation', 'presentation', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
//        $this->form_validation->set_rules('candidate_instructions', 'candidate instructions', 'trim|required');
//        $this->form_validation->set_rules('patient_information', 'patient information', 'trim|required');
        $this->form_validation->set_rules('reference_number', 'reference number', 'trim');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function get()
    {
        $id                       = (int)$this->input->post('id');
        $data['exam_schedule_id'] = $id;
        $data['scenarios']        = $this->Scenario_model->get($id);
        $data['marked']           = $this->Scenario_model->marked($id);
        $data['exam_id']          = $this->Scenario_model->find_exam_id($id);
        $this->load->view('scenario/scenario/get', $data);
    }

    public function save()
    {
        ajaxAuthorized();

        $exam_schedule_id = (int)$this->input->post('id', TRUE);
        $scenarios        = $this->input->post('scenario', TRUE);
        if (empty($scenarios)) {
            echo ajaxRespond('Fail', '<p class="ajax_error">No scenario selected</p>');
            exit;
        }
        $data = [];
        foreach ($scenarios as $scenario_id) {
            $data[] = [
                'exam_schedule_id' => $exam_schedule_id,
                'scenario_id'      => $scenario_id,
                'created_at'       => date('Y-m-d H:i:s')
            ];
        }

        $this->db->trans_start();
        $this->db->where('exam_schedule_id', $exam_schedule_id)->delete('scenario_relations');
        $this->db->insert_batch('scenario_relations', $data);
        $this->db->trans_complete();

        echo ajaxRespond('OK', '<p class="ajax_success">Exam scenario updated successfully</p>');
    }

    public function save_assign_scenario()
    {
        ajaxAuthorized();

        $exam_schedule_id = (int)$this->input->post('exam_schedule_id', TRUE);
        $scenario_id      = (int)$this->input->post('scenario_id', TRUE);

        $this->db->from('scenario_relations as r');
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        $this->db->where('r.scenario_id', $scenario_id);
        $target = $this->db->get()->row();

        if ($target) {

            //Delete scenario_to_assessors data
            $this->db->where('scenario_rel_id', $target->id);
            $this->db->delete('scenario_to_assessors');

            //Delete scenario_relations data
            $this->db->where('exam_schedule_id', $exam_schedule_id);
            $this->db->where('scenario_id', $scenario_id);
            $result = $this->db->delete('scenario_relations');

        } else {
            $scheduleData = $this->db->select('reading_time, practice_time')->get_where('exam_schedules', ['id' => $exam_schedule_id])->row();
            $data   = [
                'exam_schedule_id' => $exam_schedule_id,
                'scenario_id'      => $scenario_id,
                'created_at'       => date('Y-m-d H:i:s')
            ];
            if($scheduleData->reading_time){
                $data['reading_time'] = $scheduleData->reading_time;
            }
            if($scheduleData->practice_time){
                $data['practice_time'] = $scheduleData->practice_time;
            }
            $result = $this->db->insert('scenario_relations', $data);
        }

        if ($result) {
            echo ajaxRespond('OK', '<p class="ajax_success">Exam scenario updated successfully</p>');
        } else {
            echo ajaxRespond('FAIL', '<p class="ajax_success">Exam scenario could not update.</p>');
        }


    }

}