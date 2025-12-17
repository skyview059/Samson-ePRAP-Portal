<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Student_delete_model extends Fm_model
{
    public $table = 'students';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    public function deleteStudent($student_id = 0){
//        dd($this->count_all_links($student_id));
//        dd($student_id);
        $this->deleteFiles($student_id);
        $this->deleteResults($student_id);

        $this->db->delete('messages', ['student_id' => $student_id]);
        $this->db->delete('personal_dev_plans', ['student_id' => $student_id]);
        $this->db->delete('recruitment_shortlists', ['student_id' => $student_id]);
        $this->db->delete('student_development', ['student_id' => $student_id]);
        $this->db->delete('student_exams', ['student_id' => $student_id]);
        $this->db->delete('student_job_profile', ['student_id' => $student_id]);
        $this->db->delete('student_job_specialty_rel', ['student_id' => $student_id]);
        $this->db->delete('student_logs', ['student_id' => $student_id]);
        $this->db->delete('student_progressions', ['student_id' => $student_id]);
        $this->db->delete('user_students_relation', ['student_id' => $student_id]);

        // Delete Student
        $this->deleteStudentTbl($student_id);
    }

    private function deleteStudentTbl($student_id){
        $this->db->where('id', $student_id);
        $row = $this->db->get('students')->row();
        removeFile($row->photo);

        $this->db->delete('students', ['id' => $student_id]);
    }

    private function deleteResults($student_id){
        $this->db->where('student_id', $student_id);
        $results = $this->db->get('results')->result();
        foreach ($results as $result){
            $this->db->delete('result_details', ['result_id' => $result->id]);
        }
        $this->db->delete('results', ['student_id' => $student_id]);
    }

    private function deleteFiles($student_id){
        $this->db->where('student_id', $student_id);
        $files = $this->db->get('files')->result();
        foreach ($files as $file){
            removeFile($file->file);
            $this->db->delete('files', ['id' => $file->id]);
        }
    }
    
    private function count_all_links($id)
    {
        $files                      = $this->db->where('student_id', $id )->count_all_results('files');
        $messages                   = $this->db->where('student_id', $id )->count_all_results('messages');
        $personal_dev_plans         = $this->db->where('student_id', $id )->count_all_results('personal_dev_plans');
        $recruitment_shortlists     = $this->db->where('student_id', $id )->count_all_results('recruitment_shortlists');
        $results                    = $this->db->where('student_id', $id )->count_all_results('results');
        $student_development        = $this->db->where('student_id', $id )->count_all_results('student_development');
        $student_exams              = $this->db->where('student_id', $id )->count_all_results('student_exams');
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
            'student_exams'         => $student_exams,
            'student_job_profile'   => $student_job_profile,
            'student_job_specialty_rel' => $student_job_specialty_rel,
            'student_logs'          => $student_logs,
            'student_progressions'  => $student_progressions,
            'user_students_relation' => $user_students_relation,
        ];
    }
}