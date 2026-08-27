<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Result_model extends Fm_model {

    public $table = 'results';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($id)
    {

        if($id){ $this->db->where('exam_schedule_id', $id); } 
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $id = NULL)
    {

        $this->db->select('results.*, e.name as exam_name, ec.name as center_name, es.datetime as exam_datetime,  s.gmc_number, s.fname, s.mname, s.lname, s.email, s.phone');
        $this->db->join('students as s', 's.id=results.student_id', 'left');
        $this->db->join('exam_schedules as es', 'es.id=results.exam_schedule_id', 'left');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left');
        $this->db->order_by($this->id, $this->order);
        if($id){ $this->db->where('exam_schedule_id', $id); } 
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    function get_result($student_id,$es_id)
    {
        $this->db->select('r.*,e.name as exam_name, e.exam_type, ec.name as center_name, es.datetime, es.pass_station, es.pass_mark as schedule_pass_mark, es.passing_criteria, s.number_type, s.gmc_number, s.fname, s.mname, s.lname, s.email, s.phone');
        $this->db->from('results as r');
        $this->db->join('students as s', 's.id=r.student_id', 'left');
        $this->db->join('exam_schedules as es', 'es.id=r.exam_schedule_id', 'left');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left');
        $this->db->where('r.student_id', $student_id);
        $this->db->where('r.exam_schedule_id', $es_id);
        $result = $this->db->get()->row();

        $this->db->select('rd.*,s.name');
        $this->db->from('result_details as rd');
        $this->db->join('results as r', 'r.id=rd.result_id', 'left');
        $this->db->join('scenarios as s', 's.id=rd.scenario_id', 'left');
        $this->db->where('r.student_id', $student_id);
        $this->db->where('r.exam_schedule_id', $es_id);
        $this->db->order_by('s.reference_number', 'ASC');
        $result_details = $this->db->get()->result();

        // SCA: attach the ticked feedback statements to each assessed station
        if ($result && $result->exam_type == 'SCA' && $result_details) {
            $detail_ids = array();
            foreach ($result_details as $rd) {
                $detail_ids[] = $rd->id;
            }
            $statements = $this->get_feedback_statements_by_detail_ids($detail_ids);
            foreach ($result_details as $rd) {
                $rd->feedback_statements = isset($statements[$rd->id]) ? $statements[$rd->id] : array();
            }
        }

        $result->details = $result_details;
        return $result;
    }

    /**
     * Ticked SCA feedback statements for a set of result_detail ids,
     * grouped as [result_detail_id][domain_name][] = statement row.
     */
    function get_feedback_statements_by_detail_ids($detail_ids)
    {
        if (empty($detail_ids) OR ! $this->db->table_exists('result_detail_feedback_statements')) {
            return array();
        }

        $this->db->select('rdfs.result_detail_id, st.id as statement_id, d.id as domain_id, d.name as domain_name, d.sort_order as domain_sort_order, st.sl_no, st.subject, st.description');
        $this->db->from('result_detail_feedback_statements as rdfs');
        $this->db->join('sca_feedback_statements as st', 'st.id=rdfs.statement_id', 'inner');
        $this->db->join('sca_feedback_domains as d', 'd.id=st.domain_id', 'inner');
        $this->db->where_in('rdfs.result_detail_id', $detail_ids);
        $this->db->order_by('rdfs.result_detail_id', 'ASC');
        $this->db->order_by('d.sort_order', 'ASC');
        $this->db->order_by('st.sl_no', 'ASC');
        $rows = $this->db->get()->result();

        $grouped = array();
        foreach ($rows as $row) {
            $grouped[$row->result_detail_id][$row->domain_name][] = $row;
        }
        return $grouped;
    }

    function get_scenario_list_by_exam($exam_schedule_id)
    {

        $this->db->select('sr.*, s.reference_number, s.name');
        $this->db->from('scenario_relations as sr');
        $this->db->join('scenarios as s', 's.id=sr.scenario_id', 'left');
        $this->db->where('sr.exam_schedule_id', $exam_schedule_id);
        $this->db->order_by('s.reference_number');
        return $this->db->get()->result();
    }

    //get all student by exam_schedule_id, scenario_id
    function get_scenario_wise_scores($exam_schedule_id, $scenario_id)
    {
        $this->db->select('rd.*, s.gmc_number, s.fname, s.mname, s.lname, s.email, s.phone');
        $this->db->select('CONCAT(u.first_name, " ", u.last_name) AS ass_name, CONCAT("+",mobile_code, mobile_number) as ass_phone, u.email as ass_email');
        $this->db->from('result_details as rd');
        $this->db->join('results as r', 'r.id=rd.result_id', 'left');
        $this->db->join('students as s', 's.id=r.student_id', 'left');
        $this->db->join('users as u', 'u.id=rd.assessor_id', 'left');
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        $this->db->where('rd.scenario_id', $scenario_id);
        return $this->db->get()->result();
    }

    //get exam scenario info group by scenario_id
    function get_exam_info_by_scenario($exam_schedule_id, $scenario_id)
    {

        $this->db->select('r.*, rd.scenario_id, rd.coefficient_mark, rd.pass_mark, e.name as exam_name, es.datetime, s.reference_number, s.name as scenario_name');
        $this->db->from('results as r');
        $this->db->join('result_details as rd', 'r.id=rd.result_id', 'left');
        $this->db->join('scenarios as s', 's.id=rd.scenario_id', 'left');
        $this->db->join('exam_schedules as es', 'es.id=r.exam_schedule_id', 'left');
        $this->db->join('exams as e', 'e.id=es.exam_id', 'left');
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        $this->db->where('rd.scenario_id', $scenario_id);
        $this->db->group_by('rd.scenario_id');
        return $this->db->get()->row();
    }

    function get_result_by_exam_schedule($exam_schedule_id)
    {

        $this->db->select('rd.*, r.student_id, r.exam_schedule_id, s.gmc_number, s.fname, s.mname, s.lname, s.email, s.phone');
        $this->db->from('result_details as rd');
        $this->db->join('scenarios as sce', 'sce.id=rd.scenario_id', 'left');
        $this->db->join('results as r', 'r.id=rd.result_id', 'left');
        $this->db->join('students as s', 's.id=r.student_id', 'left');
        $this->db->where('r.exam_schedule_id', $exam_schedule_id);
        return $this->db->get()->result();
    }

}
