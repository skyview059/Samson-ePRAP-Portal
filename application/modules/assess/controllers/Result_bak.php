<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* Author: Khairul Azam
 * Date : 11 Jun 2020 @10:28 am
 */

class Result extends Admin_controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Result_model');
        $this->load->helper('result');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $sch_id = urldecode_fk($this->input->get('id', TRUE));
        $start = intval($this->input->get('start'));

        $config['base_url'] = build_pagination_url(Backend_URL . 'result/', 'start');
        $config['first_url'] = build_pagination_url(Backend_URL . 'result/', 'start');

        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Result_model->total_rows($sch_id);
        $results = $this->Result_model->get_limit_data($config['per_page'], $start, $sch_id);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'results' => $results,
            'id' => $sch_id,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'exam_id' => 0,
            'exam_centre_id' => 0,
            'view_tree' => $this->getExamTree()
        );
        $this->viewAdminContent('assess/result/index', $data);
    }
    
    private function getExamTree(){
        
        $this->db->from('exam_schedules');        
        $schedules = $this->db->get()->result();
        
        $tree = '<ul>';
        foreach ($schedules as $sch ){
            $tree .= "<li>{$sch->exam_id}</li>";
        }
        $tree .= '</ul>';        
        return $tree;
    }

    public function details($result_id)
    {

        $results = $this->Result_model->get_result($result_id);

        $data = array(
            'results' => $results,
            'scenarios' => $results->details
        );                
        $this->viewAdminContent('assess/result/details', $data);
    }

    public function generate()
    {
        $exam_id = $this->input->get('exam_id', TRUE);
        $exam_centre_id = $this->input->get('exam_centre_id', TRUE);
        $exam_schedule_id = $this->input->get('exam_schedule_id', TRUE);
        $scenario_list = $this->Result_model->get_scenario_list_by_exam($exam_schedule_id);
        
        $data = array(
            'button' => 'Generate Result',
            'action' => site_url(Backend_URL . 'assess/result/generate'),
            'exam_id' => $exam_id,
            'exam_centre_id' => $exam_centre_id,
            'exam_schedule_id' => $exam_schedule_id,
            'scenario_list' => $scenario_list
        );
//        pp($scenario_list);
        $this->viewAdminContent('assess/result/generate', $data);
    }

    public function view_scores()
    {
        $exam_id = $this->input->get('exam_id', TRUE);
        $exam_centre_id = $this->input->get('exam_centre_id', TRUE);
        $exam_schedule_id = $this->input->get('exam_schedule_id', TRUE);
        $scenario_id = $this->input->get('scenario_id', TRUE);
        
        //Get average border line score by secnario and overall_judgment=Borderline
        $avg_borderline_score = getAverageBorderlineScoreByScenario($exam_schedule_id, $scenario_id);

        //Get exam scenario info by secnario 
        $exam_scenario = $this->Result_model->get_exam_info_by_scenario($exam_schedule_id, $scenario_id);
        if(empty($exam_scenario)){
           $this->session->set_flashdata('msge', 'The result you are trying to access doesn\'t exists!!');
           redirect(site_url(Backend_URL . 'assess/result/generate')); 
        }
        
        $exam_scenario->t_borderline_score = !empty($avg_borderline_score->t_borderline_score) ? $avg_borderline_score->t_borderline_score : 0;
        $exam_scenario->avg_borderline_score = !empty($avg_borderline_score->avg_borderline_score) ? $avg_borderline_score->avg_borderline_score : 0;
        $exam_scenario->pass_mark = $exam_scenario->avg_borderline_score + $exam_scenario->coefficient_mark;
        
        $results = $this->Result_model->get_scenario_wise_scores($exam_schedule_id, $scenario_id);
        
        //Get secnario list by exam schedule id
        $scenario_list = $this->Result_model->get_scenario_list_by_exam($exam_schedule_id);
        
        if($scenario_list){
            //Associative array prepare for scenario list
            $scenario_arr = [];
            foreach ($scenario_list as $scenario) {
                $scenario_arr[] = $scenario->scenario_id;
            }
           
            $current_scenario_key = array_search((string) $scenario_id,$scenario_arr,true);
            //This function use for next scenario id;
            $next_scenario_id = get_next($scenario_arr,$current_scenario_key);
            if(empty($next_scenario_id)){
               $next_scenario_id = current($scenario_arr); 
            }
        }
       
        $data = array(
            'button' => 'Scenario List',
            'exam_scenario' => $exam_scenario,
            'exam_id' => $exam_id,
            'exam_centre_id' => $exam_centre_id,
            'exam_schedule_id' => $exam_schedule_id,
            'scenario_id' => $scenario_id,
            'next_scenario_id' => $next_scenario_id,
            'results' => $results
        );
        $this->viewAdminContent('assess/result/view_scores', $data);
    }

    public function center_list_by_exam()
    {
        ajaxAuthorized();
        $html = getExamCentreDroDownByExam(intval($this->input->post('exam_id')));
        if ($html == '') {
            $html = '<option value="0">Exam center not found!</option>';
        }
        echo ajaxRespond('OK', $html);
    }

    public function generate_pass_mark()
    {
        ajaxAuthorized();
        $exam_schedule_id = $this->input->post('exam_schedule_id');
        $scenario_id = $this->input->post('scenario_id');
        $avg_borderline_score = $this->input->post('avg_borderline_score');
        $coefficient_mark = $this->input->post('coefficient_mark');
        $pass_mark = $avg_borderline_score + $coefficient_mark;
        $exam_scenario = $this->Result_model->get_exam_info_by_scenario($exam_schedule_id, $scenario_id);
        if (!$exam_scenario) {
            echo ajaxRespond('FAIL', 'The scenario you are trying to access doesn\'t exists!');
            exit;
        }

        if (empty($coefficient_mark)) {
            echo ajaxRespond('FAIL', 'Add Coefficient is Required!');
            exit;
        }
        
        if($coefficient_mark>12){
            echo ajaxRespond('FAIL', 'Add Coefficient of less than 12');
            exit;
        }

        $sql = 'UPDATE result_details AS rd JOIN results AS r ON rd.result_id = r.id SET rd.avg_borderline_score = "' . $avg_borderline_score . '", rd.coefficient_mark = "' . $coefficient_mark . '", rd.pass_mark = "' . $pass_mark . '" WHERE r.exam_schedule_id = "' . $exam_schedule_id . '" and rd.scenario_id = "' . $scenario_id . '"';
        $target = $this->db->query($sql);
        if ($target) {
            echo ajaxRespond('OK', 'Generate Pass Score Successfully!');
        } else {
            echo ajaxRespond('FAIL', 'Pass Score Could not Generate!');
        }
    }

    public function overall_student_results()
    {

        $results = [];
        $scenario_list = [];
        if ($this->input->post('submit')) {
            $exam_id = $this->input->post('exam_id', TRUE);
            $exam_schedule_id = $this->input->post('exam_schedule_id', TRUE);
            $scenario_list = $this->Result_model->get_scenario_list_by_exam($exam_schedule_id);
            $result_info = $this->Result_model->get_result_by_exam_schedule($exam_schedule_id);
            foreach ($result_info as $result) {
                $results[$result->student_id]['student_name'] = $result->fname . ' ' . $result->mname . ' ' . $result->lname;
                $results[$result->student_id]['gmc_number'] = $result->gmc_number;
                $results[$result->student_id]['scenario_list'][$result->scenario_id]['mark'] = intval($result->technical_skills) + intval($result->clinical_skills) + intval($result->interpersonal_skills);
                $results[$result->student_id]['scenario_list'][$result->scenario_id]['pass_mark'] = $result->pass_mark;
            }
        }//If form submit

        $data = array(
            'button' => 'Generate Result',
            'action' => site_url(Backend_URL . 'assess/result/overall_student_results'),
            'exam_id' => set_value('exam_id'),
            'exam_schedule_id' => set_value('exam_schedule_id'),
            'scenario_list' => $scenario_list,
            'results' => $results
        );
        $this->viewAdminContent('assess/result/overall_student_results', $data);
    }

    public function exam_schedule_by_exam()
    {
        ajaxAuthorized();
        $html = getExamScheduleDropDownByExam(intval($this->input->post('exam_id')));
        if ($html == '') {
            $html = '<option value="0">Exam date & time not found!</option>';
        }
        echo ajaxRespond('OK', $html);
    }

    public function download_pdf($result_id)
    {

        $results = $this->Result_model->get_result($result_id);
        $data = array(
            'results' => $results
        );

        $this->load->library('m_pdf');
        // Write some HTML code:
        $html = $this->load->view('result/result_pdf', $data, true);
        // Write some HTML code:
        $this->m_pdf->pdf->WriteHTML($html);

        // Output a PDF file directly to the browser
//        $this->m_pdf->pdf->Output();
        //Add 'D' parameter for download
        $this->m_pdf->pdf->Output($results->gmc_number . '-result.pdf', 'D');
    }

}

/*
public function scenario_list(){
    $data = array(
        'button' => 'Scenario List',
        'id' => set_value('id'),
    );
    $this->viewAdminContent('assess/result/scenario_list', $data);
}

public function scenario(){
    $data = array(
        'button' => 'Scenario List',
        'id' => set_value('id'),
    );
    $this->viewAdminContent('assess/result/scenario', $data);
}

public function min_pass_score(){
    $data = array(
        'button' => 'minimum pass score',
        'id' => set_value('id'),
    );
    $this->viewAdminContent('assess/result/min_pass_score', $data);
}
*/