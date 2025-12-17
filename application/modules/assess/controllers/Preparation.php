<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 11 Jun 2020 @10:28 am
 */

class Preparation extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Preparation_model');
        $this->load->model('scenario/Scenario_model', 'Scenario_model');        
        $this->load->library('form_validation');
    }
    
    public function index() {

        $exams = $this->Preparation_model->get_assessors_exam();
        foreach ($exams as $row) {
            $scenarios = $this->Preparation_model->get_scenarios_by_ids($row->scenario_ids);
            $row->scenarios = $scenarios;
        }

        $data = array(
            'exams' => $exams,
        );
        $this->viewAdminContent('assess/preparation/index', $data);
    }


    public function details($id) {
        $row = $this->Scenario_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'exam_id' => $row->exam_id,
                'category_name' => $row->category_name,
                'name' => $row->name,
                'candidate_instructions' => $row->candidate_instructions,
                'patient_information' => $row->patient_information,
                'reference_number' => $row->reference_number,
                'examiner_information' => $row->examiner_information,
                'setup' => $row->setup,
                'exam_findings' => $row->exam_findings,
                'approach' => $row->approach,
                'explanation' => $row->explanation,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            );
            $this->viewAdminContent('assess/preparation/details', $data);
        } else {
            $this->session->set_flashdata('message', '<p class="ajax_error">Exam preparation Not Found</p>');
            redirect(site_url(Backend_URL . 'assess/preparation'));
        }
    }
}