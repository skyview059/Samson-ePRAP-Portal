<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Practice_model extends Fm_model
{
    public $table = '';
    public $id    = 'id';
    public $order = 'ASC';

    function __construct()
    {
        parent::__construct();
    }

    function get_exam_list($frontend = false)
    {
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
        if ($frontend){
            $this->db->where('status', 'Active');
        }
        return $this->db->get('exams')->result();
    }

    function get_topic_scenarios($exam_id){
        $this->db->select('count(*)');
        $this->db->where('exam_id', 'scenario_topics.exam_id', false);
        $this->db->where('scenario_topic_id', 'scenario_topics.id', false);
        $scenarios = $this->db->get_compiled_select('scenario_topics_items');

        $this->db->select('id, name, created_at');
        $this->db->select("({$scenarios}) as scenarios");
        $this->db->where('exam_id', $exam_id);
        $this->db->order_by('order', 'ASC');
        return $this->db->get('scenario_topics')->result();
    }

    function get_exam_subjects($exam_id){
        $this->db->select('ss.id, ss.name, ss.type, ss.created_at');
        $this->db->from('scenario_subjects as ss');
        $this->db->where('ss.exam_id', $exam_id);
        $this->db->order_by('ss.order', 'ASC');
        return $this->db->get()->result();
    }

    function get_exam_subject_topics($exam_id,$subject_id){
//        $this->db->cache_on();
        $this->db->select('count(*)');
        $this->db->where('exam_id', 'scenario_topics.exam_id', false);
        $this->db->where('scenario_topic_id', 'scenario_topics.id', false);
        $scenarios = $this->db->get_compiled_select('scenario_topics_items');

        $this->db->select('id, name, created_at');
        $this->db->select("({$scenarios}) as scenarios");
        $this->db->where('exam_id', $exam_id);
        $this->db->where('subject_id', $subject_id);
        $this->db->order_by('order', 'ASC');
        $topics = $this->db->get('scenario_topics')->result();
//        $this->db->cache_off();
        return $topics;
    }
}