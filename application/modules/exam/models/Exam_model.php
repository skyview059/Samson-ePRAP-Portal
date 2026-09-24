<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Exam_model extends Fm_model
{

    public $table = 'exam_schedules';
    public $id = 'id';
    public $order = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id) {
        $this->db->select('e.*');        
        $this->db->select('cn.name as centre_name, cn.address as centre_address');        
        $this->db->select('c.name as course_name, c.id as exam_id');        
        $this->db->from('exam_schedules as e');
        $this->db->join('exams as c', 'e.exam_id=c.id', 'LEFT');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        $this->db->where('e.id', $id);
        $this->db->where('e.type', 'Offline');
        return $this->db->get()->row();
    }

    public function total_rows($id, $q = NULL)
    {
        $this->__search($id,$q);
        $this->db->from('exam_schedules as e');
        return $this->db->count_all_results();
    }

    // get data with limit and search
    public function get_limit_data($limit, $start = 0, $id=1,$tab='coming', $q = NULL)
    {        
        $this->db->select('e.*,cn.name as centre');
        $this->db->select('c.name as category_name');
        $this->db->from('exam_schedules as e');
        $this->db->join('exam_centres as cn', 'cn.id=e.exam_centre_id', 'LEFT');
        
        $this->__search($id,$tab,$q);
        if($tab =='coming'){
            $this->db->order_by('datetime', 'ASC');
        } elseif($tab == 'Canceled'){
            $this->db->order_by('datetime', 'DESC');
        } else {
            $this->db->order_by('datetime', 'DESC');
        }
        
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function __search($id,$tab,$q = NULL)
    {       
        $this->db->where('e.exam_id', $id);
        $this->db->where('e.type', 'Offline');
        
        if($tab =='coming'){
            $this->db->where('e.datetime >=', date('Y-m-d 00:00:00'));             
            $this->db->where('e.exam_status', 'Active');            
            
        } elseif( $tab == 'Canceled'){            
            $this->db->where('e.exam_status', 'Canceled');
            
        } else {
            $this->db->where('e.datetime <=', date('Y-m-d 00:00:00'));              
            $this->db->where('e.exam_status', 'Active');
        }
//        if ($q) {
//            $this->db->group_start();            
//            $this->db->like('c.name', $q);
//            $this->db->group_end();
//        }               
        $this->db->join('exams as c', 'e.exam_id=c.id', 'LEFT');
    }  

    public function get_students($exam_schedule_id){
        $this->db->select('s.*,se.id as student_exam_id, se.created_at as assign_at, se.status as exam_status, se.remarks, r.id as attendance');        
        $this->db->from('student_exam_enrollments as se');
        $this->db->join('students as s', 's.id=se.student_id ', 'LEFT');
        $this->db->join('results as r', 'r.student_id=s.id and r.exam_schedule_id=se.exam_schedule_id', 'LEFT');
        $this->db->where('se.exam_schedule_id', $exam_schedule_id);
        $this->db->order_by("FIELD(se.status, 'Enrolled', 'Cancelled')", '', FALSE);
        $this->db->order_by('se.created_at', 'DESC');
        return $this->db->get()->result();
    }
    
    public function get_scenarios($exam_schedule_id){
        $this->db->select('r.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        $this->db->from('scenario_relations as r');
        $this->db->join('scenarios as s', 'r.scenario_id = s.id', 'LEFT');
        $this->db->order_by('s.reference_number', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_assigned_assessors($scenario_rel_id){
        $this->db->select('r.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->select('sta.assessor_id, u.first_name, u.last_name, u.email');
        $this->db->from('scenario_relations as r');
        $this->db->join('scenarios as s', 'r.scenario_id = s.id', 'LEFT');
        $this->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id = r.id', 'LEFT');
        $this->db->join('users as u', 'u.id = sta.assessor_id', 'LEFT');
        $this->db->where('sta.scenario_rel_id', $scenario_rel_id);
        return $this->db->get()->result();
    }
    
    public function get_assessor_scenario_info($scenario_rel_id, $assessor_id){
        $this->db->select('r.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->select('sta.assessor_id, u.first_name, u.last_name, u.email');
        $this->db->from('scenario_relations as r');
        $this->db->join('scenarios as s', 'r.scenario_id = s.id', 'LEFT');
        $this->db->join('scenario_to_assessors as sta', 'sta.scenario_rel_id = r.id', 'LEFT');
        $this->db->join('users as u', 'u.id = sta.assessor_id', 'LEFT');
        $this->db->where('sta.scenario_rel_id', $scenario_rel_id);
        $this->db->where('sta.assessor_id', $assessor_id);
        return $this->db->get()->row();
    }
    
    
    public function marked($id)
    {
        $this->db->select('assessor_id');
        $this->db->where('scenario_rel_id', $id );
        $marks    = $this->db->get('scenario_to_assessors')->result();
        $selected = [2];
        foreach ($marks as $mark){
            $selected[] = $mark->assessor_id;
        }
        return $selected;
    }
    
    public function get_student_exam_by_id($student_exam_id){
        $this->db->select('s.*,se.id as student_exam_id, se.status, se.remarks, se.created_at as assign_at'); 
        $this->db->select('es.datetime, e.name as exam_name');
        $this->db->from('student_exam_enrollments as se');
        $this->db->join('students as s', 's.id=se.student_id ', 'LEFT');
        $this->db->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'LEFT');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'LEFT');
        $this->db->where('se.id', $student_exam_id);
        return $this->db->get()->row();
    }
    
    
    public function get_assigned_assessor_by_exam($exam_schedule_id){
        $this->db->select('sta.id, s.id as sid, s.reference_number, s.name, s.candidate_instructions as description, s.patient_information');
        $this->db->select('sta.assessor_id, u.first_name, u.last_name, u.email');
        $this->db->from('scenario_to_assessors as sta');
        $this->db->join('scenario_relations as sr', 'sr.id = sta.scenario_rel_id', 'LEFT');
        $this->db->join('scenarios as s', 'sr.scenario_id = s.id', 'LEFT');
        $this->db->join('users as u', 'u.id = sta.assessor_id', 'LEFT');
        $this->db->where('sr.exam_schedule_id', $exam_schedule_id);
        $this->db->group_by('sta.assessor_id');
        return $this->db->get()->result();
    }
    
    public function get_admin_users() {
        $this->db->from('users as u');
        $this->db->where('u.role_id', '2');
        return $this->db->get()->result();
    }
    
    public function qty( $tab = 'past', $exam_id = 1 )
    {        
                        
        if($tab =='coming'){
            $this->db->where('datetime >=', date('Y-m-d 00:00:00')); 
            $this->db->where('exam_status', 'Active');            
            
        } elseif( $tab == 'Canceled'){            
            $this->db->where('exam_status', 'Canceled');
            
        } else {
            $this->db->where('datetime <=', date('Y-m-d 00:00:00'));              
            $this->db->where('exam_status', 'Active');
        }
        
        $this->db->where('exam_id', $exam_id );
        return $this->db->count_all_results('exam_schedules');        
    }

    /**
     * Count every row, in every table, that is linked to an exam schedule.
     * Used by the delete preview so the admin can see what a delete would touch.
     * Order matters: the list is also the intended delete order (children first).
     */
    public function get_relational_data($exam_schedule_id)
    {
        $id = (int) $exam_schedule_id;

        $scenario_rel_ids = "SELECT id FROM scenario_relations WHERE exam_schedule_id = {$id}";
        $result_ids       = "SELECT id FROM results WHERE exam_schedule_id = {$id}";

        $relations = array(
            array(
                'label'     => 'Result Details',
                'table'     => 'result_details',
                'condition' => "result_id IN (results WHERE exam_schedule_id = {$id})",
                'count'     => $this->db->where("result_id IN ({$result_ids})", NULL, FALSE)->count_all_results('result_details'),
            ),
            array(
                'label'     => 'Results',
                'table'     => 'results',
                'condition' => "exam_schedule_id = {$id}",
                'count'     => $this->db->where('exam_schedule_id', $id)->count_all_results('results'),
            ),
            array(
                'label'     => 'Scenario Assessors',
                'table'     => 'scenario_to_assessors',
                'condition' => "scenario_rel_id IN (scenario_relations WHERE exam_schedule_id = {$id})",
                'count'     => $this->db->where("scenario_rel_id IN ({$scenario_rel_ids})", NULL, FALSE)->count_all_results('scenario_to_assessors'),
            ),
            array(
                'label'     => 'Scenarios',
                'table'     => 'scenario_relations',
                'condition' => "exam_schedule_id = {$id}",
                'count'     => $this->db->where('exam_schedule_id', $id)->count_all_results('scenario_relations'),
            ),
            array(
                'label'     => 'Student Enrollments (all statuses)',
                'table'     => 'student_exam_enrollments',
                'condition' => "exam_schedule_id = {$id}",
                'count'     => $this->db->where('exam_schedule_id', $id)->count_all_results('student_exam_enrollments'),
            ),
        );

        return $relations;
    }

    /**
     * Enrollment breakdown by status, e.g. ['Enrolled' => 3, 'Cancelled' => 1]
     */
    public function get_enrollment_status_counts($exam_schedule_id)
    {
        $rows = $this->db->select('status, COUNT(*) as total')
            ->where('exam_schedule_id', (int) $exam_schedule_id)
            ->group_by('status')
            ->get('student_exam_enrollments')
            ->result();

        $counts = array();
        foreach ($rows as $r) {
            $counts[$r->status] = (int) $r->total;
        }
        return $counts;
    }

    /**
     * Delete an exam schedule together with every related row (children first),
     * inside a single transaction. Same tables and order as get_relational_data().
     *
     * @return array|false  ['table' => rows deleted, ...] on success, FALSE on rollback
     */
    public function delete_with_relations($exam_schedule_id)
    {
        $id = (int) $exam_schedule_id;

        $scenario_rel_ids = "SELECT id FROM scenario_relations WHERE exam_schedule_id = {$id}";
        $result_ids       = "SELECT id FROM results WHERE exam_schedule_id = {$id}";

        $deleted = array();

        $this->db->trans_start();

        // 1. result_details -> results
        // MySQL forbids deleting from a table that the same statement selects from,
        // so materialise the parent ids first.
        $ids = array_column($this->db->query($result_ids)->result_array(), 'id');
        if ($ids) {
            $this->db->where_in('result_id', $ids)->delete('result_details');
        }
        $deleted['result_details'] = $ids ? $this->db->affected_rows() : 0;

        $this->db->where('exam_schedule_id', $id)->delete('results');
        $deleted['results'] = $this->db->affected_rows();

        // 2. scenario_to_assessors -> scenario_relations
        $ids = array_column($this->db->query($scenario_rel_ids)->result_array(), 'id');
        if ($ids) {
            $this->db->where_in('scenario_rel_id', $ids)->delete('scenario_to_assessors');
        }
        $deleted['scenario_to_assessors'] = $ids ? $this->db->affected_rows() : 0;

        $this->db->where('exam_schedule_id', $id)->delete('scenario_relations');
        $deleted['scenario_relations'] = $this->db->affected_rows();

        // 3. student_exam_enrollments
        $this->db->where('exam_schedule_id', $id)->delete('student_exam_enrollments');
        $deleted['student_exam_enrollments'] = $this->db->affected_rows();

        // 4. the exam schedule itself
        $this->db->where($this->id, $id)->delete($this->table);
        $deleted['exam_schedules'] = $this->db->affected_rows();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', "Exam schedule {$id} delete rolled back: " . json_encode($this->db->error()));
            return FALSE;
        }

        return $deleted;
    }
}
