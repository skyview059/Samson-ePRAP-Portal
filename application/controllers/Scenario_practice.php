<?php defined('BASEPATH') or exit('No direct script access allowed');

class Scenario_practice extends Frontend_controller
{
    // every thing coming form Frontend Controller

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('scenario/scenario');
        if (empty($this->student_id)) {
            redirect(site_url('login'));
        }
 
    }

    public function index()
    {
        $this->load->model('scenario/Practice_model', 'Practice_model');
        $data['exams'] = $this->Practice_model->get_exam_list(true);
        $this->viewMemberContent('scenario_practice/index', $data);
    }

    public function exam($exam_id)
    {
        // check purchase status
        $this->checkPurchaseStatus($exam_id);

        $exam = $this->db->select('id, name')->from('exams')->where('id', $exam_id)->get()->row();
        if (!$exam) {
            $this->session->set_flashdata('msge', 'Exam not found');
            redirect(site_url('scenario-practice'));
        }

        $search = $this->input->get('search');
        $data = [
            'exam'          => $exam,
            'scenarioItems' => $this->getScenarioGroupsAndGroupItems($exam_id, $search),
            'student_id'    => $this->student_id,
            'exam_id'       => $exam_id,
            'search'        => $search
        ];
        if ($this->uri->segment(3) === 'practice') {
            $this->viewMemberContent('scenario_practice/group_and_items_practice', $data);
        } else {
            $this->viewMemberContent('scenario_practice/group_and_items_view', $data);
        }
    }

    public function examExplore($exam_id)
    {
        // $purchaseStatus = scenarioPracticePurchaseStatus($this->student_id, $exam_id);
        // $purchaseStatus = true; // Free for all now
        $purchaseStatus = (object)['scenario_type' => 'Both']; // Free for all now

        $this->db->select('count(*)');
        $this->db->where('exam_id', 'exams.id', false);
        $subjects = $this->db->get_compiled_select('scenario_subjects');

        $this->db->select('count(*)');
        $this->db->where('exam_id', 'exams.id', false);
        $topics = $this->db->get_compiled_select('scenario_topics');

        $this->db->select('count(*)');
        $this->db->where('exam_id', 'exams.id', false);
        $scenarios = $this->db->get_compiled_select('scenario_topics_items');

        $this->db->select('id, name, status');
        $this->db->select("({$subjects}) as subjects");
        $this->db->select("({$topics}) as topics");
        $this->db->select("({$scenarios}) as scenarios");
        $this->db->where('id', $exam_id);
        $exam = $this->db->get('exams')->row();

        // practice_packages
        $this->db->select('*');
        $this->db->where('exam_id', $exam_id);
        $this->db->where('status', 'Active');
        $packages = $this->db->get('practice_packages')->result();

        if (!$exam) {
            $this->session->set_flashdata('msge', 'Exam not found');
            redirect(site_url('scenario-practice'));
        }

        $data = [
            'exam'           => $exam,
            'student_id'     => $this->student_id,
            'exam_id'        => $exam_id,
            'purchaseStatus' => $purchaseStatus,
            'packages'       => $packages
        ];
        $this->viewMemberContent('scenario_practice/exam_explore', $data);
    }

    private function getScenarioGroupsAndGroupItems($exam_id, $search = '')
    {
        // $purchaseStatus = scenarioPracticePurchaseStatus($this->student_id, $exam_id);
        // $purchaseStatus = true; // Free for all now
        $purchaseStatus = (object)['scenario_type' => 'Both']; // Free for all now

        $this->db->select('id as subject_id, name as subject_name, type as subject_type');
        $this->db->from('scenario_subjects');
        if($purchaseStatus->scenario_type != 'Both') {
            $this->db->where('type', $purchaseStatus->scenario_type);
        }
        $this->db->where('exam_id', $exam_id);
        $this->db->order_by('order', 'asc');
        $scenarioSubjects = $this->db->get()->result();

        foreach ($scenarioSubjects as $subject) {
            $this->db->select('id as topic_id, name as topic_name');
            $this->db->from('scenario_topics');
            $this->db->where('subject_id', $subject->subject_id);
            $this->db->where('exam_id', $exam_id);
            $this->db->order_by('order', 'asc');
            $subject->topics = $this->db->get()->result();

            foreach ($subject->topics as $topic) {
                $this->db->select('stis.status');
                $this->db->where('stis.scenario_topics_items_id', 'sgi.id', false);
                $this->db->where('stis.student_id', $this->student_id);
                $this->db->limit(1);
                $this->db->order_by('stis.id', 'desc');
                $status = $this->db->get_compiled_select('scenario_topics_items_students as stis');

                $this->db->select('stis.id as practice_id');
                $this->db->where('stis.scenario_topics_items_id', 'sgi.id', false);
                $this->db->where('stis.student_id', $this->student_id);
                $this->db->limit(1);
                $this->db->order_by('stis.id', 'desc');
                $practice_id = $this->db->get_compiled_select('scenario_topics_items_students as stis');

                // $this->db->select('sgi.id, sgi.scenario_id, s.name as scenario_name, s.presentation, s.display_mode');
                $this->db->select('sgi.id, sgi.scenario_id');
                $this->db->select("IF(s.display_mode = 'Presentation', s.presentation, s.name) as display_title");
                $this->db->select("IFNULL(({$status}), 'Incomplete') as status");
                $this->db->select("IFNULL(({$practice_id}), 0) as practice_id");
                $this->db->from('scenario_topics_items as sgi');
                $this->db->join('scenarios as s', 's.id = sgi.scenario_id');
                $this->db->where('sgi.scenario_topic_id', $topic->topic_id);
                $this->db->where('sgi.subject_id', $subject->subject_id);
                if (!empty($search)) {
                    $this->db->group_start();
                    $this->db->like('s.name', $search);
                    $this->db->or_like('s.presentation', $search);
                    // $this->db->or_like('s.patient_information', $search);
                    $this->db->group_end();
                }
                $this->db->order_by('sgi.order', 'asc');
                $topic->topic_items = $this->db->get()->result();
            }

            // Filter out topics with no items
            $subject->topics = array_filter($subject->topics, function($topic) {
                return !empty($topic->topic_items);
            });
        }
        
        // Filter out subjects with no topics
        $scenarioSubjects = array_filter($scenarioSubjects, function($subject) {
            return !empty($subject->topics);
        });

        return $scenarioSubjects;
    }

    public function setStatus()
    {
        ajaxAuthorized();
        $item_id = intval($this->input->post('item_id'));
        $status  = $this->input->post('status');

        $this->db->select('id');
        $this->db->where('scenario_topics_items_id', $item_id);
        $this->db->where('student_id', $this->student_id);
        $this->db->from('scenario_topics_items_students');
        $row = $this->db->get()->row();
        if ($row) {
            $this->db->where('id', $row->id);
            $this->db->update('scenario_topics_items_students', [
                'status' => $status,
            ]);
            $practice_id = $row->id;
        } else {
            $this->db->insert('scenario_topics_items_students', [
                'scenario_topics_items_id' => $item_id,
                'student_id'               => $this->student_id,
                'status'                   => $status,
            ]);
            $practice_id = $this->db->insert_id();
        }
        echo scenarioStatus($item_id, $status, $practice_id);
    }

    public function setPracticeStatus()
    {
        ajaxAuthorized();
        $practice_id = intval($this->input->post('practice_id'));
        $status      = $this->input->post('status');
        $this->db->where('id', $practice_id);
        $this->db->update('scenario_topics_items_students', [
            'status' => $status,
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Status updated successfully']);
    }

    private function markScheme($practice_id)
    {
        $this->load->helper('student/student');
        $result_details = $this->getResultDetailsById($practice_id);
        if ($result_details) {
            $data = array(
                'action'               => site_url('scenario-practice/review_action'),
                'result_details'       => $result_details,
                'practice_id'          => $practice_id,
                'face'                 => set_value('face', 'Smiley'),
                'technical_skills'     => set_value('technical_skills', 0),
                'clinical_skills'      => set_value('clinical_skills', 0),
                'interpersonal_skills' => set_value('interpersonal_skills', 0),
                'consultation'         => set_value('consultation', 0),
                'issues'               => set_value('issues', 0),
                'diagnosis'            => set_value('diagnosis', 0),
                'examination'          => set_value('examination', 0),
                'findings'             => set_value('findings', 0),
                'management'           => set_value('management', 0),
                'rapport'              => set_value('rapport', 0),
                'listening'            => set_value('listening', 0),
                'language'             => set_value('language', 0),
                'time'                 => set_value('time', 0),
                'student_name'         => getStudentName($result_details->student_id),
                'student_id'           => $result_details->student_id,
                'scenario_name'        => $result_details->scenario_name,
            );
            return $data;
        } else {
            return [];
        }
    }

    public function review_action()
    {
        ajaxAuthorized();
        $practice_id = $this->input->post('practice_id');
        $data        = [
            'patient'              => $this->input->post('patient_name'),
            'greet_the_patient'    => $this->input->post('greet_the_patient'),
            'introduces_himself'   => $this->input->post('introduces_himself'),
            'state_the_role'       => $this->input->post('state_the_role'),
            'name_preference'      => $this->input->post('name_preference'),
            'starts_station_well'  => $this->input->post('starts_station_well'),
            'face'                 => $this->input->post('face'),
            'technical_skills'     => $this->input->post('technical_skills'),
            'clinical_skills'      => $this->input->post('clinical_skills'),
            'interpersonal_skills' => $this->input->post('interpersonal_skills'),
            'consultation'         => intval($this->input->post('consultation')),
            'issues'               => intval($this->input->post('issues')),
            'diagnosis'            => intval($this->input->post('diagnosis')),
            'examination'          => intval($this->input->post('examination')),
            'findings'             => intval($this->input->post('findings')),
            'management'           => intval($this->input->post('management')),
            'rapport'              => intval($this->input->post('rapport')),
            'listening'            => intval($this->input->post('listening')),
            'language'             => intval($this->input->post('language')),
            'time'                 => intval($this->input->post('time')),
            'overall_judgment'     => $this->input->post('overall_judgment'),
            'examiner_comments'    => $this->input->post('comments'),
        ];

        $this->db->where('id', $practice_id)->update('scenario_topics_items_students', $data);

        echo ajaxRespond('OK', 'Review saved successfully');
    }

    private function getResultDetailsById($practice_id)
    {
        $this->db->select('stis.*, sti.scenario_id, s.name as scenario_name');
        $this->db->from('scenario_topics_items_students as stis');
        $this->db->join('scenario_topics_items as sti', 'sti.id = stis.scenario_topics_items_id', 'left');
        $this->db->join('scenarios as s', 's.id = sti.scenario_id', 'left');
        $this->db->where('stis.id', $practice_id);
        return $this->db->get()->row();
    }

    public function practiceSummaryDetails($practice_id)
    {
        $this->load->helper('assess/result');
        $pass_station     = 3;
        $pass_mark        = 3;
        $passing_criteria = 'You have passed %PassedStations% out of %PassStation% stations in %NameOfMockTest%. Your score is %YourScore%. The minimum pass mark required is %MinPassMarkRequired%.';

        $this->db->select('stis.*, sti.scenario_id, s.name as scenario_name, s.exam_id, e.name as exam_name');
        $this->db->select('CONCAT(st.title, " ", st.fname, " ", st.mname, " ", st.lname) as student_name');
        $this->db->where('stis.id', $practice_id);
        $this->db->join('scenario_topics_items as sti', 'sti.id = stis.scenario_topics_items_id', 'left');
        $this->db->join('scenarios as s', 's.id = sti.scenario_id', 'left');
        $this->db->join('exams as e', 'e.id = s.exam_id', 'left');
        $this->db->join('students as st', 'st.id = stis.student_id', 'left');
        $summaryData = $this->db->get('scenario_topics_items_students as stis')->row();

        $total_score = $total_pass_mark = $passed_station = 0;

        $get_mark        = $summaryData->technical_skills + $summaryData->clinical_skills + $summaryData->interpersonal_skills;
        $total_score     += $get_mark;
        $total_pass_mark += $pass_mark;

        if ($get_mark >= $summaryData->pass_mark) {
            $passed_station += 1;
        }

        $min_station          = $pass_station;
        $param_arr            = array(
            '%PassStation%'         => $pass_station,
            '%NameOfMockTest%'      => $summaryData->exam_name,
            '%YourScore%'           => $total_score,
            '%PassedStations%'      => $passed_station,
            '%MinPassMarkRequired%' => $total_pass_mark
        );
        $passing_criteria_str = strtr($passing_criteria, $param_arr);

        $data = [
            'display_none'         => 'display:none;',
            'total_score'          => $total_score,
            'req_pass_mark'        => $total_pass_mark,
            'passed_station'       => $passed_station,
            'pass_or_fail'         => ($total_score >= $total_pass_mark && $passed_station >= $min_station) ? 'Pass' : 'Fail',
            'results'              => $summaryData,
            's_id'                 => $summaryData->student_id,
            'es_id'                => $summaryData->exam_id,
            'passing_criteria_str' => nl2br_fk($passing_criteria_str)
        ];

        $this->viewFrontContent('frontend/scenario_practice/item_details_summary', $data);
    }

    public function generatePracticeURL()
    {
        ajaxAuthorized();
        $exam_id        = intval($this->input->post('exam_id'));
        $item_id        = intval($this->input->post('item_id'));
        $practice_id    = intval($this->input->post('practice_id'));
        $practice_roles = $this->input->post('practice_roles');
        $practice_time  = $this->input->post('practice_time');

        if ($practice_id) {
            // update practice roles and time
            $practiceData = $this->db->select('id, student_id, candidate_id, patient_id, examiner_id, exercise_time_json')
                ->from('scenario_topics_items_students')
                ->where('id', $practice_id)->get()->row();
            if ($practiceData->candidate_id == 0 || $practiceData->patient_id == 0 || $practiceData->examiner_id == 0) {
                $this->db->where('id', $practice_id);
                $this->db->update('scenario_topics_items_students', [
                    'candidate_id' => $practice_roles['candidate_id'],
                    'patient_id'   => $practice_roles['patient_id'],
                    'examiner_id'  => $practice_roles['examiner_id'],
                ]);
            }
            if ($practiceData->exercise_time_json == '') {
                $this->db->where('id', $practice_id);
                $this->db->update('scenario_topics_items_students', [
                    'exercise_time_json' => json_encode([
                        'readingTime'  => $practice_time['reading_time'],
                        'practiceTime' => $practice_time['practice_time'],
                        'feedbackTime' => $practice_time['feedback_time'],
                        'totalTime'    => $practice_time['total_time']
                    ]),
                ]);
            }
        } else {
            // insert new practice
            $data = [
                'scenario_topics_items_id' => $item_id,
                'student_id'               => $this->student_id,
                'candidate_id'             => $practice_roles['candidate_id'],
                'patient_id'               => $practice_roles['patient_id'],
                'examiner_id'              => $practice_roles['examiner_id'],
                'status'                   => 'Incomplete',
                'exercise_time_json'       => json_encode([
                    'readingTime'  => $practice_time['reading_time'],
                    'practiceTime' => $practice_time['practice_time'],
                    'feedbackTime' => $practice_time['feedback_time'],
                    'totalTime'    => $practice_time['total_time']
                ]),
            ];
            $this->db->insert('scenario_topics_items_students', $data);
            $practice_id = $this->db->insert_id();
        }
        echo json_encode([
            'status'  => 'success',
            'message' => 'Practice URL generated successfully',
            'url'     => site_url('scenario-practice/exam/practice/' . $exam_id . '/view/' . $practice_id),
        ]);
    }

    public function setRoleAction()
    {
        ajaxAuthorized();

        $item_id      = intval($this->input->post('item_id'));
        $exam_id      = intval($this->input->post('exam_id'));
        $candidate_id = intval($this->input->post('candidate_id'));
        $patient_id   = intval($this->input->post('patient_id'));
        $examiner_id  = intval($this->input->post('examiner_id'));

        if ($item_id == 0 || $exam_id == 0 || $candidate_id == 0 || $patient_id == 0 || $examiner_id == 0) {
            ajaxRespond('ERROR', 'Please select all fields');
            return false;
        }

        $this->db->where('scenario_topics_items_id', $item_id);
        $this->db->where('student_id', $this->student_id);
        $this->db->delete('scenario_topics_items_students');

        $data = [
            'scenario_topics_items_id' => $item_id,
            'student_id'               => $this->student_id,
            'candidate_id'             => $candidate_id,
            'patient_id'               => $patient_id,
            'examiner_id'              => $examiner_id,
            'status'                   => 'Incomplete',
        ];
        $this->db->insert('scenario_topics_items_students', $data);
        $insert_id = $this->db->insert_id();
        // json response
        echo json_encode([
            'status'      => 'success',
            'message'     => 'Role set successfully',
            'practice_id' => $insert_id
        ]);
        return true;
    }

    public function setTimeAction()
    {
        $practice_id   = intval($this->input->post('practice_id'));
        $exercise_time = [
            'readingTime'  => $this->input->post('reading_time'),
            'practiceTime' => $this->input->post('practice_time'),
            'feedbackTime' => $this->input->post('feedback_time'),
            'totalTime'    => $this->input->post('total_time')
        ];

        $this->db->where('id', $practice_id);
        $this->db->update('scenario_topics_items_students', [
            'exercise_time_json' => json_encode($exercise_time),
        ]);

        $this->session->set_flashdata('msgs', 'Time set successfully');
        redirect(site_url('scenario-practice/practice/' . $practice_id));
    }

    public function changePracticeTime()
    {
        ajaxAuthorized();
        $practice_id   = intval($this->input->post('practice_id'));
        $exercise_time = [
            'readingTime'  => $this->input->post('reading_time'),
            'practiceTime' => $this->input->post('practice_time'),
            'feedbackTime' => $this->input->post('feedback_time'),
            'totalTime'    => $this->input->post('total_time')
        ];

        $this->db->where('id', $practice_id);
        $this->db->update('scenario_topics_items_students', [
            'exercise_time_json' => json_encode($exercise_time),
        ]);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Time set successfully',
        ]);
    }

    public function changePracticeRoles()
    {
        ajaxAuthorized();
        $practice_id  = intval($this->input->post('practice_id'));
        $candidate_id = intval($this->input->post('candidate_id'));
        $patient_id   = intval($this->input->post('patient_id'));
        $examiner_id  = intval($this->input->post('examiner_id'));

        $this->db->where('id', $practice_id);
        $this->db->update('scenario_topics_items_students', [
            'candidate_id' => $candidate_id,
            'patient_id'   => $patient_id,
            'examiner_id'  => $examiner_id,
        ]);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Roles set successfully',
        ]);
    }

    public function practiceDetails($exam_id, $practice_id)
    {
        // check purchase status
        $this->checkPurchaseStatus($exam_id);

        $this->db->select('p.id, p.candidate_id, p.patient_id, p.examiner_id, p.scenario_topics_items_id, p.status');
        $this->db->select('p.exercise_time_json');
        $this->db->select('sti.scenario_id, sti.exam_id');
        $this->db->select('e.name as exam_name, e.marking_criteria');
        $this->db->select('s.name as scenario_name, s.presentation, s.candidate_instructions, s.patient_information, s.examiner_information');
        $this->db->select('s.setup, s.exam_findings, s.approach, s.explanation');
        $this->db->where('p.id', $practice_id);
        $this->db->from('scenario_topics_items_students as p');
        $this->db->join('scenario_topics_items as sti', 'sti.id = p.scenario_topics_items_id', 'left');
        $this->db->join('scenarios as s', 's.id = sti.scenario_id', 'left');
        $this->db->join('exams as e', 'e.id = sti.exam_id', 'left');
        $practice = $this->db->get()->row();

        if (!$practice) {
            $this->session->set_flashdata('msge', 'Practice not found');
            redirect(site_url('scenario-practice'));
        }

        if (!in_array($this->student_id, [$practice->candidate_id, $practice->patient_id, $practice->examiner_id])) {
            $this->session->set_flashdata('msge', 'You are not allowed to access this page');
            redirect(site_url('scenario-practice'));
        }

        $timeData   = json_decode($practice->exercise_time_json);
        $data_array = [
            'practice'      => $practice,
            'display_none'  => 'display:none;',
            'student_id'    => $this->student_id,
            'markScheme'    => $this->markScheme($practice_id),
            'reading_time'  => $timeData->readingTime ?? '0',
            'practice_time' => $timeData->practiceTime ?? '0',
            'feedback_time' => $timeData->feedbackTime ?? '0',
            'total_time'    => $timeData->totalTime ?? '0',
        ];
        $this->viewFrontContent('frontend/scenario_practice/practice_details', $data_array);
    }

    public function itemDetails($exam_id, $item_id)
    {
        // check purchase status
        $this->checkPurchaseStatus($exam_id);


        $this->db->select('i.*');
        $this->db->select('e.name as exam_name, e.marking_criteria');
        $this->db->select('s.name as scenario_name, s.presentation, s.candidate_instructions, s.patient_information, s.examiner_information, s.setup, s.exam_findings, s.approach, s.explanation');
        $this->db->where('i.id', $item_id);
        $this->db->from('scenario_topics_items as i');
        $this->db->join('exams as e', 'e.id = i.exam_id', 'left');
        $this->db->join('scenarios as s', 's.id = i.scenario_id', 'left');
        $item = $this->db->get()->row();

        if (!$item) {
            $this->session->set_flashdata('msge', 'Item not found');
            redirect(site_url('scenario-practice'));
        }

        $data_array = [
            'practice'     => $item,
            'display_none' => 'display:none;',
            'student_id'   => $this->student_id,
            'markScheme'   => $this->markScheme(0),
            'practice_id'  => 0,
            'exam_id'      => $exam_id,
        ];
        $this->viewFrontContent('frontend/scenario_practice/item_details', $data_array);
    }

    private function checkPurchaseStatus($exam_id)
    {
        /*
        $purchaseStatus = scenarioPracticePurchaseStatus($this->student_id, $exam_id);
        if ($purchaseStatus) {
            return $purchaseStatus;
        } else {
            $this->session->set_flashdata('msge', 'Please purchase this exam to view it');
            redirect(site_url('scenario-practice'));
        }
        */
        return true;
    }

}