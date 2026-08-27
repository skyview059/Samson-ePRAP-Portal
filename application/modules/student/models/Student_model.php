<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Student_model extends Fm_model
{
    public $table = 'students';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get total rows
    function total_rows($type,$status,$teacher_id,$exam_id,$centre_id,$exam_date,$q, $country_origin_id,$present_country_id)
    {
        $this->__search($type,$status,$teacher_id,$exam_id,$centre_id,$exam_date,$q, $country_origin_id,$present_country_id);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start, $type,$status,$teacher_id, $exam_id,$centre_id,$exam_date, $q, $country_origin_id, $present_country_id)
    {
        $this->db->select('students.*');
        $this->db->select('(SELECT GROUP_CONCAT(e2.name ORDER BY e2.name SEPARATOR ", ")
                            FROM student_interested_exams sie2
                            JOIN exams e2 ON e2.id = sie2.exam_id
                            WHERE sie2.student_id = students.id) AS exam_names', false);
        $this->db->order_by($this->id, $this->order);
        $this->__search($type,$status,$teacher_id,$exam_id,$centre_id,$exam_date,$q, $country_origin_id, $present_country_id);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    
    
    function __search($type,$status,$teacher_id,$exam_id,$centre_id,$exam_date,$q, $country_origin_id, $present_country_id){
        if($type) { $this->db->where('number_type', $type); }
        if($status) { 
            $this->db->where('status', $status);
        } else {
            $this->db->where('status !=', 'Archive');
        }
        
        if($exam_id) {
            // Match on ANY interested exam (student_interested_exams), not just students.exam_id
            $this->db->where("EXISTS (SELECT 1 FROM student_interested_exams sie
                              WHERE sie.student_id = students.id AND sie.exam_id = " . (int)$exam_id . ")", null, false);
        }
        if($country_origin_id) { $this->db->where('country_id', $country_origin_id); }
        if($present_country_id) { $this->db->where('present_country_id', $present_country_id); }
        if($centre_id) { $this->db->where('exam_centre_id', $centre_id); }
        if($exam_date) { $this->db->where('exam_date', $exam_date); }

        if ($q) { 
            $this->db->group_start();
            $this->db->like('gmc_number', $q);
            $this->db->or_like('fname', $q);
            $this->db->or_like('mname', $q);
            $this->db->or_like('lname', $q);
            $this->db->or_like('email', $q);
            $this->db->or_like("CONCAT(fname, ' ', lname)", $q );
            $this->db->or_like("CONCAT('+',phone_code, phone)", $q );
            $this->db->or_like("CONCAT('+',whatsapp_code, whatsapp)", $q );
            $this->db->group_end();
        }        
                        
        /* Filter by Teacher */
        if($teacher_id){
            $this->db->join('user_students_relation as rel', 'rel.student_id = students.id', 'LEFT');
            $this->db->where('rel.user_id', $teacher_id );
        }
    }

    function get_by_id($id) {
        $this->db->select('s.*');
        $this->db->select('e.name as ethnicity, c.name as country');
        $this->db->select('exam.name as exam_name, ec.name as exam_centre');
        $this->db->where('s.id', $id);
        $this->db->from("{$this->table} as s");
        $this->db->join('ethnicities as e', 's.ethnicity_id = e.id', 'LEFT');
        $this->db->join('countries as c', 's.country_id = c.id', 'LEFT');
        $this->db->join('exams as exam', 'exam.id = s.exam_id', 'LEFT');
        $this->db->join('exam_centres as ec', 'ec.id = s.exam_centre_id', 'LEFT');
        return $this->db->get()->row();
    }

    /** Active students interested in the given exam (any of their interested exams). */
    function get($exam_id)
    {
        $this->db->select('s.id, s.number_type, s.gmc_number as gmc, CONCAT(s.fname, " ", s.mname, " ", s.lname) as full_name');
        $this->db->from('students as s');
        $this->db->join('student_interested_exams as sie', 'sie.student_id = s.id', 'INNER');
        $this->db->where('sie.exam_id', (int)$exam_id);
        $this->db->where('s.status', 'Active');
        $this->db->group_by('s.id');
        return $this->db->get()->result();
    }

    /** @return int[] exam ids from student_interested_exams, oldest first */
    function getInterestedExamIds($student_id)
    {
        $rows = $this->db->select('exam_id')
            ->where('student_id', (int)$student_id)
            ->order_by('id', 'ASC')
            ->get('student_interested_exams')->result();
        return array_map(fn($r) => (int)$r->exam_id, $rows);
    }

    /** Comma-joined exam names, e.g. "MRCGP SCA, PLAB/UKMLA Part 2" */
    function getInterestedExamNames($student_id)
    {
        $row = $this->db->select('GROUP_CONCAT(e.name ORDER BY e.name SEPARATOR ", ") AS names', false)
            ->from('student_interested_exams AS sie')
            ->join('exams AS e', 'e.id = sie.exam_id', 'LEFT')
            ->where('sie.student_id', (int)$student_id)
            ->get()->row();
        return $row ? (string)$row->names : '';
    }

    /**
     * Replace the student's interested-exam set.
     * @param  int[] $exam_ids
     * @return int|null first exam id (for the students.exam_id sync) or null when empty
     */
    function saveInterestedExams($student_id, array $exam_ids)
    {
        $student_id = (int)$student_id;
        $exam_ids   = array_values(array_unique(array_filter(array_map('intval', $exam_ids))));

        $this->db->trans_start();
        $this->db->delete('student_interested_exams', ['student_id' => $student_id]);
        if ($exam_ids) {
            $batch = array_map(fn($eid) => ['student_id' => $student_id, 'exam_id' => $eid], $exam_ids);
            $this->db->insert_batch('student_interested_exams', $batch);
        }
        $this->db->trans_complete();

        return $exam_ids[0] ?? null;
    }
    
    function marked($id)
    {
        $this->db->select('student_id');
        $this->db->where('exam_schedule_id', $id );
        $marks    = $this->db->get('student_exam_enrollments')->result();
        $selected = [];
        foreach ($marks as $mark){
            $selected[] = $mark->student_id;
        }
        return $selected;
    }

    /**
     * Server-side search for the "Book Student for Exam" modal.
     *
     * @param  int[]  $exam_ids          match students interested in ANY of these exams (student_interested_exams)
     * @param  int    $exam_schedule_id  only used to flag rows already enrolled in this schedule
     * @param  string $q                 keyword: name, email, student id, GMC number
     * @return array  ['total' => int, 'rows' => object[]]
     */
    function searchForExam(array $exam_ids, $exam_schedule_id, $q = '', $page = 1, $limit = 25)
    {
        $exam_ids = array_values(array_unique(array_filter(array_map('intval', $exam_ids))));
        if (!$exam_ids) {
            return ['total' => 0, 'rows' => []];
        }

        $build = function () use ($exam_ids, $q) {
            $this->db->from('students AS s');
            $this->db->where('s.status', 'Active');
            // EXISTS instead of JOIN so a student interested in several selected exams appears once
            $this->db->where(
                'EXISTS (SELECT 1 FROM student_interested_exams sie
                         WHERE sie.student_id = s.id AND sie.exam_id IN (' . implode(',', $exam_ids) . '))',
                null,
                false
            );
            if ($q !== '') {
                $this->db->group_start();
                $this->db->like('s.fname', $q);
                $this->db->or_like('s.mname', $q);
                $this->db->or_like('s.lname', $q);
                $this->db->or_like("CONCAT(s.fname, ' ', s.lname)", $q);
                $this->db->or_like('s.email', $q);
                $this->db->or_like('s.gmc_number', $q);
                // Accept the raw id (12) or the formatted one shown in the UI (S-00012, see studentID())
                if (preg_match('/^(?:S-?)?0*(\d+)$/i', $q, $m)) {
                    $this->db->or_where('s.id', (int)$m[1]);
                }
                $this->db->group_end();
            }
        };

        $build();
        $total = (int)$this->db->count_all_results();

        $build();
        $this->db->select(
            "s.id, s.number_type, s.gmc_number AS gmc, s.email,
             TRIM(CONCAT_WS(' ', s.fname, s.mname, s.lname)) AS full_name,
             (see.id IS NOT NULL) AS booked,
             (SELECT GROUP_CONCAT(e2.name ORDER BY e2.name SEPARATOR ', ')
                FROM student_interested_exams sie2
                JOIN exams e2 ON e2.id = sie2.exam_id
               WHERE sie2.student_id = s.id) AS exam_names",
            false
        );
        $this->db->join(
            'student_exam_enrollments AS see',
            'see.student_id = s.id AND see.exam_schedule_id = ' . (int)$exam_schedule_id,
            'LEFT'
        );
        $this->db->order_by('s.fname', 'ASC');
        $this->db->order_by('s.id', 'ASC');
        $this->db->limit((int)$limit, max(0, ((int)$page - 1) * (int)$limit));

        return ['total' => $total, 'rows' => $this->db->get()->result()];
    }

    function coutn_all_links($id)
    {
        $files                      = $this->db->where('student_id', $id )->count_all_results('files');
        $messages                   = $this->db->where('student_id', $id )->count_all_results('messages');
        $personal_dev_plans         = $this->db->where('student_id', $id )->count_all_results('personal_dev_plans');
        $recruitment_shortlists     = $this->db->where('student_id', $id )->count_all_results('recruitment_shortlists');
        $results                    = $this->db->where('student_id', $id )->count_all_results('results');
        $student_development        = $this->db->where('student_id', $id )->count_all_results('student_development');
        $student_exams              = $this->db->where('student_id', $id )->count_all_results('student_exam_enrollments');
        $student_job_profile        = $this->db->where('student_id', $id )->count_all_results('student_job_profile');
        $student_job_specialty_rel  = $this->db->where('student_id', $id )->count_all_results('student_job_specialty_rel');
        $student_logs               = $this->db->where('student_id', $id )->count_all_results('student_logs');
        $student_progressions       = $this->db->where('student_id', $id )->count_all_results('student_progressions');
        $user_students_relation     = $this->db->where('student_id', $id )->count_all_results('user_students_relation');
//        $students     = $this->db->where('student_id', $id )->count_all_results('students');

        return [
            'total' => ($files + $messages + $personal_dev_plans + $recruitment_shortlists + $results + $student_development + $student_exams
            + $student_job_profile + $student_job_specialty_rel + $student_logs + $student_progressions + $user_students_relation),
            'files'                 => $files,
            'messages'              => $messages,
            'personal_dev_plans'    => $personal_dev_plans,
            'recruitment_shortlists' => $recruitment_shortlists,
            'results'               => $results,
            'student_development'   => $student_development,
            'student_exam_enrollments'         => $student_exams,
            'student_job_profile'   => $student_job_profile,
            'student_job_specialty_rel' => $student_job_specialty_rel,
            'student_logs'          => $student_logs,
            'student_progressions'  => $student_progressions,
            'user_students_relation' => $user_students_relation,
        ];
    }
        
    function get_progress($id) {
        $this->db->select('sp.*,p.title');     
        $this->db->from("student_progressions as sp");
        $this->db->join('progressions as p', 'p.id=sp.progression_id','LEFT');
        $this->db->where('student_id', $id );
        return $this->db->get()->result();
    }
    function get_progress_total($id) {        
        $this->db->from("student_progressions as sp");
        $this->db->where('student_id', $id );
        return $this->db->get()->num_rows();
    }
    
    // get total rows
    function total_mail($id)
    {
        $this->db->where('parent_id', '0' );
        $this->db->where('student_id', $id );
        return $this->db->count_all_results('messages');
    }

    // get data with limit and search
    function get_mail_data($limit, $start, $id )
    {
        $this->db->select('id,student_id,user_id,subject,status,open_at,opened_by');
        $this->db->order_by('id', 'DESC');  
        $this->db->where('student_id', $id );
        $this->db->where('parent_id', '0' );
        $this->db->limit($limit, $start);
        return $this->db->get('messages')->result();
    }
    
    function getTotalBlockedlist( $status ){
        return $this->db->where('status', $status )->count_all_results('student_exam_enrollments');
    }
    
    function getBlockedlist( $limit, $start, $status ){
        return $this->db
                ->select('s.id as stu_id,se.id as enroll_id, s.number_type, s.photo, s.gender, s.gmc_number,s.title, CONCAT(s.fname," ", s.lname) as full_name,s.email, s.phone_code,s.phone,s.whatsapp_code,s.whatsapp, s.created_at')
                ->select('es.label, e.name as exam_name, ec.name as centre_name, es.datetime')
                ->select('se.status, se.remarks, es.created_at as enrolled_at')
                ->from('student_exam_enrollments as se')
                ->join('students as s', 'se.student_id=s.id', 'LEFT')
                ->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'LEFT')
                ->join('exams as e', 'e.id=es.exam_id', 'LEFT')
                ->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'LEFT')                
                ->where('se.status', $status )
                ->order_by('se.created_at', 'ASC')
                ->limit($limit, $start )
                ->get()->result();
    }
}