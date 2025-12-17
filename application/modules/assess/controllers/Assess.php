<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/* Author: Khairul Azam
 * Date : 2020-06-11
 */

class Assess extends Admin_controller{
    function __construct(){
        parent::__construct();
        $this->load->model('Assess_model');
        $this->load->helper('assess');
        $this->load->library('form_validation');
    }

    public function index(){
        $this->viewAdminContent('assess/assess/index');
    }

    
    public function search_student() {
        $exam_schedule_id = urldecode_fk($this->input->get('exam_schedule_id', TRUE));
        $number_type = urldecode_fk($this->input->get('number_type', TRUE));
        $gmc        = urldecode_fk($this->input->get('gmc', TRUE));
        $students   = $this->Assess_model->searchStudent($number_type, $gmc);

        $student_id = ($students) ? $students->id : 0;
        //Get current exam information by student and current user
        $exam_info = $this->Assess_model->getExamScheduleInfoByStudent($exam_schedule_id, $student_id);

        if ($exam_info) {
            
            //Exam scenario status set
            if(!empty($exam_info->scenarios)){
                foreach ($exam_info->scenarios as $key => $scenario) {
                    //If duplicate check exam by student_id, exam_schedule_id, assessor_id & scenario_id
                    $result = $this->Assess_model->getDuplicateCheckExamResultData($student_id, $exam_info->id, $scenario->id);
                   
                    if($result){
//                        echo $result_details->assessor_id.'='.$this->user_id.'<br/>';
//                        dd($result_details->assessor_id);
                        
                        if($this->role_id == 2 ){
                            $scenario->status = $result->step;
                        } else {
                            $scenario->status = ($result->assessor_id != $this->user_id) ? 'not_assessor' : $result->step;                            
                        }      
                        $scenario->assessor = Tools::getAssessorByName($result->assessor_id);
                        
                    } else{
                        $scenario->status = 'initial_start';                        
                        $scenario->assessor = '--';
                    } 
                    
                }
            }
        
            
            //Set cookie data for student exam
            $cookie_data = json_encode([
                'student_id' => $student_id,
                'gmc_number' => $students->gmc_number,
                'exam_schedule_id' => $exam_info->id
            ]);

            $cookie = [
                'name' => 'exam_data',
                'value' => base64_encode($cookie_data),
                'expire' => 86500,
                'secure' => false
            ];

            $this->input->set_cookie($cookie);
        }
        
        $data = array(
            'students' => $students,
            'exam' => $exam_info,
            'exam_schedule_id' => $exam_schedule_id,
            'number_type' => $number_type,
            'gmc' => $gmc,
            'right_candidate' => set_value('right_candidate')
        );
        
//        dd( $data );
        
        $this->viewAdminContent('assess/assess/search_student', $data);
    }

    public function initial_approach($scenario_id) {
        $student_id = getStudentExamData('student_id');
        $exam_schedule_id = getStudentExamData('exam_schedule_id');
        $exam_info = $this->Assess_model->getExamScheduleInfoByStudent($exam_schedule_id, $student_id);

        if ($exam_info) {
            $scenrio_object = array_filter(
                $exam_info->scenarios, 
                function ($e) use (&$scenario_id) {
                    return $e->id == $scenario_id;
                }
            );

            if (empty($scenrio_object)) {
                $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
                redirect(site_url(Backend_URL . 'search_student'));
            }

            $exam_schedule_id = $exam_info->id;
            //If duplicate check exam by student_id, exam_schedule_id
            $result = $this->Assess_model->getExamResultByStudent($student_id, $exam_schedule_id);
            
            if(empty($result)){
                //IF student exam data empty,  Array prepared for initial data store for result table
                $result_array = [
                    'student_id' => $student_id,
                    'exam_schedule_id' => $exam_schedule_id,
                    'created_at' => date("Y-m-d H:i:s"),
                ];
                $result_id = $this->Assess_model->insertResult($result_array);
            }
            
            $result_id = ($result) ? $result->id : $result_id;
             
            //If duplicate check exam by student_id, exam_schedule_id, assessor_id & scenario_id
            $result_details = $this->Assess_model->getDuplicateCheckExamResultData($student_id, $exam_schedule_id, $scenario_id);
            
            if (empty($result_details)) {
                
                //Array prepared for initial data store for result details table
                $result_detail_array = [
                    'result_id' => $result_id,
                    'assessor_id' => $this->user_id,
                    'scenario_id' => $scenario_id,
                    'start_datetime' => date("Y-m-d H:i:s"),
                    'step' => 'Start',
                ];

                $result_detail_id = $this->Assess_model->insertResultDetails($result_detail_array);

                if (!$result_detail_id) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('msge', 'Initial exam data insertion failed!');
                    redirect(site_url(Backend_URL . 'assess/search_student'));
                }
            }
            
            $result_detail_id = ($result_details) ? $result_details->id : $result_detail_id;
            
            //Get Student Exam Details Data By result_detail_id
            $result_data = $this->Assess_model->getResultDetailsById($result_detail_id);
            
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/initial_approach_action'),
                'exam_info' => $exam_info,
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'patient_name' => set_value('patient_name', $result_data->patient),
                'greet_the_patient' => set_value('greet_the_patient', $result_data->greet_the_patient),
                'introduces_himself' => set_value('introduces_himself', $result_data->introduces_himself),
                'state_the_role' => set_value('state_the_role', $result_data->state_the_role),
                'name_preference' => set_value('name_preference', $result_data->name_preference),
                'starts_station_well' => set_value('starts_station_well', $result_data->starts_station_well),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
            );
            $this->viewAdminContent('assess/assess/initial_approach', $data);
            
        } else {
            
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/search_student'));
        }
    }

    public function initial_approach_action() {
        $this->_rules_initial_approach();
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/initial_approach/' . $result_details->scenario_id));
        }
         
        if ($this->form_validation->run() == FALSE) {
            $this->initial_approach($result_details->scenario_id);
        } else {
            $data = array(
                'patient' => $this->input->post('patient_name', TRUE),
                'greet_the_patient' => $this->input->post('greet_the_patient', TRUE),
                'introduces_himself' => $this->input->post('introduces_himself', TRUE),
                'state_the_role' => $this->input->post('state_the_role', TRUE),
                'name_preference' => $this->input->post('name_preference', TRUE),
                'starts_station_well' => $this->input->post('starts_station_well', TRUE),
                'step' => 'Initial Approach'
            );

            $this->Assess_model->updateResultDetails($result_detail_id, $data);
            $this->session->set_flashdata('msgs', 'Initial Approach Added Successfully');
            redirect(site_url(Backend_URL . 'assess/face/' . $result_detail_id));
        }
    }

   
    public function face($result_detail_id) {
        
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if ($result_details) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/face_action'),
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'face' => set_value('face', $result_details->face),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
            );
            $this->viewAdminContent('assess/assess/face', $data);
        } else {
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/initial_approach/' . $result_details->scenario_id));
        }
    }

    public function face_action() {

        $this->_rules_face();
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/initial_approach/' . $result_details->scenario_id));
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->face($result_detail_id);
        } else {
            $data = array(
                'face' => $this->input->post('face', TRUE),
                'step' => 'Face'
            );

            $this->Assess_model->updateResultDetails($result_detail_id, $data);
             
            $this->session->set_flashdata('msgs', 'Student Face Added Successfully');
            redirect(site_url(Backend_URL . 'assess/quantitative_feedback/' . $result_detail_id));
        }
    }

    public function quantitative_feedback($result_detail_id) {

        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if ($result_details) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/quantitative_feedback_action'),
                'result_details' => $result_details,
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'technical_skills' => set_value('technical_skills', $result_details->technical_skills),
                'clinical_skills' => set_value('clinical_skills', $result_details->clinical_skills),
                'interpersonal_skills' => set_value('interpersonal_skills', $result_details->interpersonal_skills),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
            );
            $this->viewAdminContent('assess/assess/quantitative_feedback', $data);
        } else {
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/face/' . $result_detail_id));
        }
       
    }

    public function quantitative_feedback_action() {
        $this->_quantitative_feedback();
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/quantitative_feedback/' . $result_detail_id));
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->quantitative_feedback($result_detail_id);
        } else {
            $data = array(
                'technical_skills' => $this->input->post('technical_skills', TRUE),
                'clinical_skills' => $this->input->post('clinical_skills', TRUE),
                'interpersonal_skills' => $this->input->post('interpersonal_skills', TRUE),
                'step' => 'Quantitative Feedback'
            );

            $this->Assess_model->updateResultDetails($result_detail_id, $data);
            $this->session->set_flashdata('msgs', 'Student Quantitative Feedback Added Successfully');
            redirect(site_url(Backend_URL . 'assess/qualitative_feedback/' . $result_detail_id));
        }
        
    }

    public function qualitative_feedback($result_detail_id) {
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if ($result_details) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/qualitative_feedback_action'),
                'result_details' => $result_details,
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'consultation' => set_value('consultation', $result_details->consultation),
                'issues' => set_value('issues', $result_details->issues),
                'diagnosis' => set_value('diagnosis', $result_details->diagnosis),
                'examination' => set_value('examination', $result_details->examination),
                'findings' => set_value('findings', $result_details->findings),
                'management' => set_value('management', $result_details->management),
                'rapport' => set_value('rapport', $result_details->rapport),
                'listening' => set_value('listening', $result_details->listening),
                'language' => set_value('language', $result_details->language),
                'time' => set_value('time', $result_details->time),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
                
            );
            $this->viewAdminContent('assess/assess/qualitative_feedback', $data);
        } else {
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/quantitative_feedback/' . $result_detail_id));
        }

    }

    public function qualitative_feedback_action() {
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/quantitative_feedback/' . $result_detail_id));
        }
        
        $consultation = !empty($this->input->post('consultation')) ? $this->input->post('consultation') : 0;
        $issues = !empty($this->input->post('issues')) ? $this->input->post('issues', TRUE) : 0;
        $diagnosis = !empty($this->input->post('diagnosis')) ? $this->input->post('diagnosis', TRUE) : 0;
        $examination = !empty($this->input->post('examination')) ? $this->input->post('examination', TRUE) : 0;
        $findings = !empty($this->input->post('findings')) ? $this->input->post('findings', TRUE) : 0;
        $management = !empty($this->input->post('management')) ? $this->input->post('management', TRUE) : 0;
        $rapport = !empty($this->input->post('rapport')) ? $this->input->post('rapport', TRUE) : 0;
        $listening = !empty($this->input->post('listening')) ? $this->input->post('listening', TRUE) : 0;
        $language = !empty($this->input->post('language')) ? $this->input->post('language', TRUE) : 0;
        $time = !empty($this->input->post('time')) ? $this->input->post('time', TRUE) : 0;


        $data = array(
            'consultation' => $consultation,
            'issues' => $issues,
            'diagnosis' => $diagnosis,
            'examination' => $examination,
            'findings' => $findings,
            'management' => $management,
            'rapport' => $rapport,
            'listening' => $listening,
            'language' => $language,
            'time' => $time,
            'step' => 'Qualitative Feedback'
        );

        $this->Assess_model->updateResultDetails($result_detail_id, $data);
        $this->session->set_flashdata('msgs', 'Student Qualitative Feedback Added Successfully');
        redirect(site_url(Backend_URL . 'assess/overall_judgment/' . $result_detail_id));

    }

    public function overall_judgment($result_detail_id) {
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if ($result_details) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/overall_judgment_action'),
                'result_details' => $result_details,
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'overall_judgment' => set_value('overall_judgment', $result_details->overall_judgment),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
            );
            $this->viewAdminContent('assess/assess/overall_judgment', $data);
        } else {
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/qualitative_feedback/' . $result_detail_id));
        }
        
    }

    public function overall_judgment_action() {
        $this->_rules_overall_judgment();
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/qualitative_feedback/' . $result_details->scenario_id));
        }
        
        if ($this->form_validation->run() == FALSE) {
            $this->overall_judgment($result_detail_id);
        } else {
            $data = array(
                'overall_judgment' => $this->input->post('overall_judgment', TRUE),
                'step' => 'Overall Judgment'
            );

            $this->Assess_model->updateResultDetails($result_detail_id, $data);
             
            $this->session->set_flashdata('msgs', 'Student Overall Judgment Added Successfully');
            redirect(site_url(Backend_URL . 'assess/comment/' . $result_detail_id));
        }

    }

    public function comment($result_detail_id) {
        
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
                        
        if ($result_details) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/comment_action'),
                'result_details' => $result_details,
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'comments' => set_value('comments', $result_details->examiner_comments),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
            );
            $this->viewAdminContent('assess/assess/comment', $data);
        } else {
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/overall_judgment/' . $result_detail_id));
        }
       
    }

    public function comment_action() {
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/comment/' . $result_detail_id));
        }
        
        $comments = !empty($this->input->post('comments')) ? $this->input->post('comments') : null;
        
        $data = array(
            'examiner_comments' => $comments,
            'step' => 'Examiner Comments'
        );

        $this->Assess_model->updateResultDetails($result_detail_id, $data);
        
        $this->session->set_flashdata('msgs', 'Examiners Comments Added Successfully');
        redirect(site_url(Backend_URL . 'assess/review/' . $result_detail_id));

    }

    public function review($result_detail_id) {
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if ($result_details) {
            $data = array(
                'button' => 'Update',
                'action' => site_url(Backend_URL . 'assess/review_action'),
                'result_details' => $result_details,
                'result_detail_id' => set_value('result_detail_id', $result_detail_id),
                'patient_name' => set_value('patient_name', $result_details->patient),
                'greet_the_patient' => set_value('greet_the_patient', $result_details->greet_the_patient),
                'introduces_himself' => set_value('introduces_himself', $result_details->introduces_himself),
                'state_the_role' => set_value('state_the_role', $result_details->state_the_role),
                'name_preference' => set_value('name_preference', $result_details->name_preference),
                'starts_station_well' => set_value('starts_station_well', $result_details->starts_station_well),
                'face' => set_value('face', $result_details->face),
                'technical_skills' => set_value('technical_skills', $result_details->technical_skills),
                'clinical_skills' => set_value('clinical_skills', $result_details->clinical_skills),
                'interpersonal_skills' => set_value('interpersonal_skills', $result_details->interpersonal_skills),
                'consultation' => set_value('consultation', $result_details->consultation),
                'issues' => set_value('issues', $result_details->issues),
                'diagnosis' => set_value('diagnosis', $result_details->diagnosis),
                'examination' => set_value('examination', $result_details->examination),
                'findings' => set_value('findings', $result_details->findings),
                'management' => set_value('management', $result_details->management),
                'rapport' => set_value('rapport', $result_details->rapport),
                'listening' => set_value('listening', $result_details->listening),
                'language' => set_value('language', $result_details->language),
                'time' => set_value('time', $result_details->time),
                'overall_judgment' => set_value('overall_judgment', $result_details->overall_judgment),
                'comments' => set_value('comments', $result_details->examiner_comments),
                'summery_std_scen' => Tools::getStudentNameByResultID( $result_detail_id )
            );
            $this->viewAdminContent('assess/assess/review', $data);
        } else {
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/review/' . $result_detail_id));
        }
        
        
    }

    public function review_action() {
        $this->_rules_review();
        $result_detail_id = (int) $this->input->post('result_detail_id');
        $result_details = $this->Assess_model->getResultDetailsById($result_detail_id);
        
        if(empty($result_details)){
            $this->session->set_flashdata('msge', 'The exam you are trying to access doesn\'t exists!!');
            redirect(site_url(Backend_URL . 'assess/comment/' . $result_detail_id));
        }
        
        if ($this->form_validation->run() == FALSE) {
            
            $this->session->set_flashdata('msge', 'Please, fill the required fields');
            $this->review($result_detail_id);
        }else {
            
            $consultation = !empty($this->input->post('consultation')) ? $this->input->post('consultation') : 0;
            $issues = !empty($this->input->post('issues')) ? $this->input->post('issues', TRUE) : 0;
            $diagnosis = !empty($this->input->post('diagnosis')) ? $this->input->post('diagnosis', TRUE) : 0;
            $examination = !empty($this->input->post('examination')) ? $this->input->post('examination', TRUE) : 0;
            $findings = !empty($this->input->post('findings')) ? $this->input->post('findings', TRUE) : 0;
            $management = !empty($this->input->post('management')) ? $this->input->post('management', TRUE) : 0;
            $rapport = !empty($this->input->post('rapport')) ? $this->input->post('rapport', TRUE) : 0;
            $listening = !empty($this->input->post('listening')) ? $this->input->post('listening', TRUE) : 0;
            $language = !empty($this->input->post('language')) ? $this->input->post('language', TRUE) : 0;
            $time = !empty($this->input->post('time')) ? $this->input->post('time', TRUE) : 0;

            $data = array(
                'patient' => $this->input->post('patient_name', TRUE),
                'greet_the_patient' => $this->input->post('greet_the_patient', TRUE),
                'introduces_himself' => $this->input->post('introduces_himself', TRUE),
                'state_the_role' => $this->input->post('state_the_role', TRUE),
                'name_preference' => $this->input->post('name_preference', TRUE),
                'starts_station_well' => $this->input->post('starts_station_well', TRUE),
                'face' => $this->input->post('face', TRUE),
                'technical_skills' => $this->input->post('technical_skills', TRUE),
                'clinical_skills' => $this->input->post('clinical_skills', TRUE),
                'interpersonal_skills' => $this->input->post('interpersonal_skills', TRUE),
                'consultation' => $consultation,
                'issues' => $issues,
                'diagnosis' => $diagnosis,
                'examination' => $examination,
                'findings' => $findings,
                'management' => $management,
                'rapport' => $rapport,
                'listening' => $listening,
                'language' => $language,
                'time' => $time,
                'overall_judgment' => $this->input->post('overall_judgment', TRUE),
                'examiner_comments' => $this->input->post('comments'),
                'end_datetime' => date("Y-m-d H:i:s"),
                'step' => 'Complete'
            );

            $review_update = $this->Assess_model->updateResultDetails($result_detail_id, $data);
            if($review_update){
                $this->session->set_flashdata('msgs', 'Examiners Has Been Successfully Submited');
                redirect(site_url(Backend_URL . 'assess/finished'));
            }else{
                $this->session->set_flashdata('msgs', 'Exam Review could\'t be updated');
                redirect(site_url(Backend_URL . 'assess/review/' . $result_detail_id));
            }

        }
        
        
    }
    
    public function finished() {
        $data = [
            'next_candidate_action' =>  site_url(Backend_URL . 'assess/search_student'), 
            'end_exam_action' =>  site_url(Backend_URL . 'assess/exam_is_over'), 
        ];
        $this->viewAdminContent('assess/assess/finished', $data);            
    }
    
    public function exam_is_over() {
        $this->viewAdminContent('assess/assess/exam_is_over');            
    }
    
     public function _rules_initial_approach() {
        $this->form_validation->set_rules('patient_name', 'name of the patient', 'trim|required');
        $this->form_validation->set_rules('greet_the_patient', 'greet the patient', 'trim|required');
        $this->form_validation->set_rules('introduces_himself', 'introduces himself', 'trim|required');
        $this->form_validation->set_rules('state_the_role', 'state the role', 'trim|required');
        $this->form_validation->set_rules('name_preference', 'checks patient’s name preference', 'trim|required');
        $this->form_validation->set_rules('starts_station_well', 'starts station well', 'trim|required');

        $this->form_validation->set_rules('result_detail_id', 'result_detail_id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }


    public function _rules_face() {
        $this->form_validation->set_rules('face', 'Face must be selected', 'trim|required', array('required' => 'Student Face Must Be Select'));
        $this->form_validation->set_rules('result_detail_id', 'Exam Not Found', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    
    
    public function _quantitative_feedback() {
        $this->form_validation->set_rules('technical_skills', 'Technical Skills', 'trim|required', array('required' => 'Data-gathering, technical and assessment skills is required!'));
        $this->form_validation->set_rules('clinical_skills', 'Clinical Skills', 'trim|required', array('required' => 'Clinical management skills is required'));
        $this->form_validation->set_rules('interpersonal_skills', 'Clinical Skills', 'trim|required', array('required' => 'Interpersonal skills is required'));
        $this->form_validation->set_rules('result_detail_id', 'Exam Not Found', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    
    public function _rules_overall_judgment() {
        $this->form_validation->set_rules('overall_judgment', 'overall judgment', 'trim|required', array('required' => 'Student Overall Judgment Must Be Selected!'));
        $this->form_validation->set_rules('result_detail_id', 'Exam Not Found', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    
    public function _rules_review() {
        
        $this->form_validation->set_rules('patient_name', 'name of the patient', 'trim|required');
        $this->form_validation->set_rules('greet_the_patient', 'greet the patient', 'trim|required');
        $this->form_validation->set_rules('introduces_himself', 'introduces himself', 'trim|required');
        $this->form_validation->set_rules('state_the_role', 'state the role', 'trim|required');
        $this->form_validation->set_rules('name_preference', 'checks patient’s name preference', 'trim|required');
        $this->form_validation->set_rules('starts_station_well', 'starts station well', 'trim|required');
        $this->form_validation->set_rules('face', 'Face must be selected', 'trim|required', array('required' => 'Student Face Must Be Selected!'));
        $this->form_validation->set_rules('technical_skills', 'Technical Skills', 'trim|required', array('required' => 'Data-gathering, technical and assessment skills is required!'));
        $this->form_validation->set_rules('clinical_skills', 'Clinical Skills', 'trim|required', array('required' => 'Clinical management skills is required'));
        $this->form_validation->set_rules('interpersonal_skills', 'Interpersonal Skills', 'trim|required', array('required' => 'Interpersonal skills is required'));
        $this->form_validation->set_rules('overall_judgment', 'Overall Judgment', 'trim|required', array('required' => 'Student Overall Judgment Must Be Selected!'));
        
        $this->form_validation->set_rules('result_detail_id', 'Exam Not Found', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
    
    public function student_list(){
        $es_id      = (int) $this->input->get('exam_schedule_id');
        
        $students   = $this->Assess_model->getStudentListByExam($es_id);
       
        //dd( $students );
        
        $data = array(
            'start' => 0,
            'students' => $students,
            'exam_schedule_id' => $es_id,
            'admin' => in_array($this->role_id, [1,2]) ? true : false,
            'today' => date('Y-m-d')
        );
        $this->viewAdminContent('assess/assess/exam_student_list', $data);   
    }
    public function review_assement(){
        $es_id      = (int) $this->input->get('es_id');
        $sid      = (int) $this->input->get('sid');
        
        $scenarios   = $this->Assess_model->getExamScenarios($es_id);
                
        $data = array(
            'start' => 0,
            'scenarios' => $scenarios,            
            'es_id' => $es_id,            
            'sid' => $sid,            
        );                
        $this->viewAdminContent('assess/assess/review_assement', $data);   
    }
    
    public function _menu(){        
        return buildMenuForMoudle([
            'module'    => 'Assessment',
            'icon'      => 'fa-clock-o',
            'href'      => 'assess',                    
            'children'  => [
                [
                    'title' => 'Result',
                    'icon'  => 'fa fa-bars',
                    'href'  => 'assess/result'
                ],[
                    'title' => 'Exam Preparation',
                    'icon'  => 'fa fa-plus',
                    'href'  => 'assess/preparation'
                ],[
                    'title' => 'Start Mock Exam',
                    'icon'  => 'fa-clock-o',
                    'href'  => 'assess/search_student'
                ],[
                    'title' => 'Generate Results',
                    'icon' => 'fa-circle-o',
                    'href' => 'assess/result/generate'
                ],[
                    'title' => 'Overall Student Results',
                    'icon' => 'fa-circle-o',
                    'href' => 'assess/result/overall_student_results'
                ],[
                    'title' => 'Re-Assess',
                    'icon'  => 'fa fa-random',
                    'href'  => 'assess/student_list'
                ],
//                [   'title' => 'Scenario List',
//                    'icon' => 'fa-circle-o',
//                    'href' => 'assess/result/scenario_list'
//                ],[
//                    'title' => 'Scenario Result',
//                    'icon' => 'fa-circle-o',
//                    'href' => 'assess/result/scenario'
//                ],[
//                    'title' => 'Scenario Min Pass Score',
//                    'icon' => 'fa-circle-o',
//                    'href' => 'assess/result/min_pass_score'
//                ],
            ]        
        ]);
    }
}