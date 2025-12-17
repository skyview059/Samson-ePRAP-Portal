<?php

/**
 * Description of Tools
 *
 * @author Khairul Azam
 * Developer, Flick Media Ltd
 * Date: 05th June 2020
 */
class Tools {
    
    private static $ci;

    function __construct()
    {
        self::$ci =& get_instance();
    }

    static public function getExam($selected = 0, $label = '--Select Exam--')
    {
        
        self::$ci->db->select('id,name');
        $exams = self::$ci->db->get('exams')->result();

        $options = "<option value='0'>{$label}</option>";
        foreach ($exams as $exam) {
            $options .= '<option value="' . $exam->id . '" ';
            $options .= ($exam->id == $selected) ? 'selected="selected"' : '';
            $options .= '>' . $exam->name . '</option>';
        }
        return $options;
    }
    static public function getExamName($exam_id = 0 )
    {        
        self::$ci->db->select('name');
        self::$ci->db->where('id', $exam_id );        
        $exam = self::$ci->db->get('exams')->row();
        return ($exam) ?  $exam->name : 'Unknown';
    }
    
    static public function getCentres($id = 0)
    {              
        self::$ci->db->where('exam_id', $id);
        return self::$ci->db->count_all_results('exam_centres');
    }
    static public function getSchedules($id = 0)
    {              
        self::$ci->db->where('exam_id', $id);
        return self::$ci->db->count_all_results('exam_schedules');
    }
    static public function getPractieSchedules($id = 0)
    {              
        self::$ci->db->where('practice_id', $id);
        return self::$ci->db->count_all_results('practice_schedules');
    }
    
    static function fixPrimaryKey(){        
        self::$ci->db->query( 'SET @newid=0;' );
        self::$ci->db->query( 'UPDATE `personal_dev_plans` SET id=(@newid:=@newid+1) ORDER BY id;' );
        $row = self::$ci->db->select( 'id')->order_by('id','DESC')->get('personal_dev_plans')->row();
        $max = ($row->id + 1);
        self::$ci->db->query( "ALTER TABLE `personal_dev_plans` AUTO_INCREMENT = {$max};" );        
    }
    
    static public function getStudentSingleScenarioExamStatus($es_id, $std_id, $scen_id )
    {
        $data['id'] = 0;
        $data['status'] = 'Pending';
        
        self::$ci->db->select('rd.id,rd.step');
        self::$ci->db->from('result_details as rd');
        self::$ci->db->join('results as r', 'r.id=rd.result_id', 'LEFT');        
        self::$ci->db->where('r.exam_schedule_id', $es_id );
        self::$ci->db->where('r.student_id', $std_id );
        self::$ci->db->where('rd.scenario_id', $scen_id );
        $status = self::$ci->db->get()->row();        
        if($status){
            $data['id'] = $status->id;
            $data['status'] = $status->step;
        }
        return (object) $data;
    }
    
    static public function getStudentNameByResultID( $result_details_id )
    {
        
        self::$ci->db->select('std.fname,std.lname,scen.name as scenario');
        self::$ci->db->from('result_details as rd');
        self::$ci->db->join('results as r', 'r.id=rd.result_id', 'LEFT');        
        self::$ci->db->join('students as std', 'std.id=r.student_id', 'LEFT');        
        self::$ci->db->join('scenarios as scen', 'scen.id=rd.scenario_id', 'LEFT');        
        self::$ci->db->where('rd.id', $result_details_id );
        $result = self::$ci->db->get()->row();        
        if($result){
            return "Student: {$result->fname} {$result->lname}  / Scenario: {$result->scenario}";
        } else {
            return 'Details Not Found';
        }
    }
    
    static public function getStudentNameByID( $student_id )
    {        
        self::$ci->db->select('s.fname,s.lname');
        self::$ci->db->from('students as s');    
        self::$ci->db->where('s.id', $student_id );
        $result = self::$ci->db->get()->row();        
        if($result){
            return "Student: {$result->fname} {$result->lname}";
        } else {
            return 'Details Not Found';
        }
    }
    
    static public function getStudentResultSummery( $student_id,$es_id )
    {
        
        self::$ci->db->select('rd.pass_mark,rd.technical_skills as ts,rd.clinical_skills as cs,rd.interpersonal_skills as is');
        self::$ci->db->from('result_details as rd');        
        self::$ci->db->join('results as r', 'r.id=rd.result_id', 'left');        
        self::$ci->db->where('r.student_id', $student_id);
        self::$ci->db->where('r.exam_schedule_id', $es_id);        
        $results = self::$ci->db->get()->result();
        
//        dd( $results );
        
        $your_score = $required_score = $passed_station = 0;        
        foreach ($results as $r) {
            $get_mark   = $r->ts + $r->cs + $r->is;
            $your_score += $get_mark;            
            $required_score += $r->pass_mark;
            
            if($get_mark >= $r->pass_mark ) {
                $passed_station += 1;
            }
        }
        $min_station = self::getPassStationRequired( $es_id ); // minimum pass station required need to make it dynamic        
        
        return array(
            'your_score'            => $your_score,
            'required_score'        => $required_score,
            'passed_station'        => $passed_station,
            'required_pass_station' => $min_station,
            'pass_fail_result'      => ($your_score >= $required_score && $passed_station >= $min_station ) ? 'Pass' : 'Fail',                                    
        );
    }
    
    static public function getAssessorByName( $id ){
        if(!$id){
            return '--';
        }
          
        self::$ci->db->select('first_name, last_name');
        $assessor = self::$ci->db->get_where('users', ['id' => $id ])->row();
        if ($assessor) {
            return $assessor->first_name . ' ' . $assessor->last_name;
        } else {
            return '--';
        }
    }
    
    static public function getPassStationRequired( $id ){                  
        self::$ci->db->select('pass_station');
        $exam = self::$ci->db->get_where('exam_schedules', ['id' => $id ])->row();
        if ($exam) {
            return $exam->pass_station;
        } else {
            return 0;
        }
    }
    
    static public function getAnnouncement( $type = 'GMC' ){                  
        $content = getSettingItem('AnnouncementFor');
                
        $json = json_decode( $content, true );
        if (empty($json)){ 
            return FALSE;
        }        
        
        $output = '';                            
        $output .= self::formatAnnouncement( $json['All'] );
        
        if($type == 'GMC'){
            $output .= self::formatAnnouncement( $json['Doctor'] );
        }
        
        if($type == 'NMC'){
            $output .= self::formatAnnouncement( $json['Nurse'] );
        }
        
        if($type == 'GDC'){
            $output .= self::formatAnnouncement( $json['Dentist'] );
        }                        
        return $output;
    }

    static private function formatAnnouncement( $text ){
        if(!empty($text)){                                    
            $html = '<div class="row"><div class="col-md-12 announcement">';
            $html .= '<i class="fa fa-bullhorn"></i> ';
            $html .= nl2br_fk($text);
            $html .= '</div></div>';
            return $html;
        }
    }
    
    static public function countStudent($type = 'GMC')
    {             
        self::$ci->db->cache_on(); 
        self::$ci->db->where('number_type', $type );
        $qty = self::$ci->db->count_all_results('students');
        self::$ci->db->cache_off();
        return ($qty) ? "({$qty})" : '';
    }
    
    static public function lastStageOfProress( $id ){                  
        self::$ci->db->select('p.title');        
        self::$ci->db->from('student_progressions as sp');
        self::$ci->db->join('progressions as p', 'p.id=sp.progression_id','LEFT');
        self::$ci->db->where('sp.student_id', $id );
        self::$ci->db->order_by('p.order_id', 'DESC' );
        $progress = self::$ci->db->get()->row();
        
        if ($progress) {
            return $progress->title;
        } else {
            return '--';
        }
    }
    static function getDropDownProgress($selected, $category = 'GMC')
    {        
        self::$ci->db->where('category', $category);
        $progress = self::$ci->db->get('progressions')->result();
        
        $option = '<option value="0">Any</option>';
        foreach($progress as $p){
            $option .= "<option value=\"{$p->id}\"";
            $option .= ($selected == $p->id ) ? ' selected' : '';
            $option .= ">{$p->title}</option>";
        }
        return $option;
    }
    
    static public function status($selected = ''){
    
        $status = [
            'Suggested' => 'Suggested',
            'Contacted Candidates' => 'Contacted Candidates',
            'Rejected' => 'Rejected',
            'Awaiting Interview' => 'Awaiting Interview',
            'Job Offered' => 'Job Offered',
            'Compliance Stage' => 'Compliance Stage',
            'Started Job' => 'Started Job',
            'Passed Probation' => 'Passed Probation',            
        ];
        $row = '';
        foreach ($status as $key => $val) {
            $row .= '<option value="' . htmlentities($key) . '"';
            $row .= ($selected == $key) ? ' selected' : '';
            $row .= '>' . $val . '</option>';
        }
        return $row;
    }

    static public function getJobFor($selected = ''){
        $status = [
            'Doctor' => 'Doctor',
            'Dentist' => 'Dentist',
            'Nurse' => 'Nurse',
        ];
        $row = '';
        foreach ($status as $key => $val) {
            $row .= '<option value="' . htmlentities($key) . '"';
            $row .= ($selected == $key) ? ' selected' : '';
            $row .= '>' . $val . '</option>';
        }
        return $row;
    }

    static public function getJobApplicationStatus($selected = ''){
        $status = [
            'Pending' => 'Pending',
            'Suggested' => 'Suggested',
            'Contacted Candidates' => 'Contacted Candidates',
            'Rejected' => 'Rejected',
            'Awaiting Interview' => 'Awaiting Interview',
            'Job Offered' => 'Job Offered',
            'Compliance Stage' => 'Compliance Stage',
            'Started Job' => 'Started Job',
            'Passed Probation' => 'Passed Probation'
        ];
        $row = '';
        foreach ($status as $key => $val) {
            $row .= '<option value="' . htmlentities($key) . '"';
            $row .= ($selected == $key) ? ' selected' : '';
            $row .= '>' . $val . '</option>';
        }
        return $row;
    }
    
    static public function getStudentData( $id, $column ){                  
           
        self::$ci->db->select($column);        
        self::$ci->db->from('students');        
        self::$ci->db->where('id', $id );
        
        $student = self::$ci->db->get()->row();
        
        if ($student) {
            return $student->$column;
        } else {
            return '--';
        }
    }
    
    static public function enrolledStudentByMockExam($id)
    {              
//        self::$ci->db->select('student_limit' );
//        self::$ci->db->from('exam_schedules' );        
//        self::$ci->db->where('id', $id );
//        $exam_plan = self::$ci->db->get()->row();
        
        self::$ci->db->where('exam_schedule_id', $id );
        self::$ci->db->where('status !=', 'Cancelled' );
        return self::$ci->db->count_all_results('student_exams');
                        
//        return "{$qty} Seat(s) Booked out of {$exam_plan->student_limit} Seats";
    }
    
    static public function isAlreadyEnrolled($es_id, $student_id )
    {                                
        self::$ci->db->select('status');
        self::$ci->db->where('exam_schedule_id', $es_id );
        self::$ci->db->where('student_id', $student_id );
        $enrolled = self::$ci->db->get('student_exams')->row();
        if($enrolled){
            return $enrolled->status;
        } else {
            return FALSE;
        }
    }
    
        
    static public function statusWiseCount( $post_id ){                  
           
        self::$ci->db->select('status, count(id) as total');        
        self::$ci->db->from('recruitment_shortlists');        
        self::$ci->db->where('post_id', $post_id );
        self::$ci->db->group_by('status');
        
        $status = self::$ci->db->get()->result();
        $html = '';
        foreach ($status as $sta) {
            $html .= "<a href='admin/doctor/shortlist/{$post_id}?status={$sta->status}' class='btn btn-primary btn-xs' style='margin-bottom:5px;'>";
            $html .= "{$sta->status} <span class='badge bg-white'>{$sta->total}</span></a>";
        }
        return $html;
    }
    
    static public function sentMail($id = 0)
    {                 
        self::$ci->db->where('student_id', $id );
        self::$ci->db->where('parent_id', '0' );
        return self::$ci->db->count_all_results( 'messages' );
    }
    
    
    static public function countTableRows( $student_id, $tbl ){
        self::$ci->db->where('student_id', $student_id );        
        if($tbl == 'student_exams'){ self::$ci->db->where('status', 'Enrolled'); }
        if($tbl == 'mails'){ self::$ci->db->where('receiver_id', 'Enrolled'); }        
        return self::$ci->db->count_all_results( $tbl );
    }
    
    static public function countBlockedOrCancelled( $status ){
        self::$ci->db->where('status', $status );
        return self::$ci->db->count_all_results( 'student_exams' );
    }
    
    static public function getWhatsappLinks( $link_for = 'Country'){
        
        $student_id = (int) getLoginStudentData('student_id');
        self::$ci->db->select('wl.id, wl.title,link,created_on');       
        self::$ci->db->from( 'whatsapp_link_relations as wlr' );
        
        self::$ci->db->where('wl.link_for',  $link_for );  
        self::$ci->db->where('wlr.rel_table', self::_link_for( $link_for ) );  
        self::$ci->db->join( 'whatsapp_links as wl', 'wl.id=wlr.wa_link_id', 'LEFT' ); 
        
        if($link_for == 'Mock'){
            $sql = "SELECT exam_schedule_id FROM `student_exams` WHERE `student_id` = '{$student_id}' AND status = 'Enrolled'";
            self::$ci->db->where_in('wlr.rel_id', $sql, FALSE );
        }
        
        if($link_for == 'Course'){
            $sql = "SELECT cb.course_id FROM `course_payments` as cp LEFT JOIN course_booked as cb on cb.course_payment_id=cp.id WHERE student_id = '{$student_id}'";            
            self::$ci->db->where_in('wlr.rel_id', $sql, FALSE );
        }

        if($link_for == 'Practice'){
            $sql = "SELECT `practice_schedule_id` FROM `practice_students` WHERE student_id = '{$student_id}'";
            self::$ci->db->where_in('wlr.rel_id', $sql, FALSE );
        }
        
        if($link_for == 'Country'){
            self::$ci->db->join( 'students as s', 's.present_country_id=wlr.rel_id', 'LEFT' );            
            self::$ci->db->where('s.id', $student_id );        
        }
        
        self::$ci->db->where('wl.status', 'Publish'); 
        $whatsapp = self::$ci->db->get()->result();        
        if($whatsapp){
            $link = '';
            foreach( $whatsapp as $wa ){
                $link .= "<p><i class='fa fa-whatsapp'></i> {$wa->title}";                
                $link .= " &nbsp; <a href='$wa->link' target=\"_blank\">{$wa->link}</a></p>";
            }
            return $link;
        } else {
            return '<p class="no-margin"><small><em>Not Group Found</em></small></p>';
        }
    }
    
    static function waJoiningURL($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return "<a href='{$url}' class='btn btn-link no-padding' target='_blank'>Link <i class='fa fa-external-link'></i> </a>";
        }
    }
    static function _link_for( $link_for ){
        switch ($link_for){
            case 'Mock':
                $rel_table = 'exam_schedules';
                break;
            case 'Course':
                $rel_table = 'courses';
                break;
            case 'Practice':
                $rel_table = 'practice_schedules';
                break;
            case 'Country':
                $rel_table = 'countries';
                break;
        }
        return $rel_table;
    }
    
    static function rel_table( $rel_table ){
        switch ($rel_table){
            case 'exam_schedules':
                $rel_table = 'Mock';
                break;
            case 'course_dates':
                $rel_table = 'Course';
                break;
            case 'practice_schedules':
                $rel_table = 'Practice';
                break;
            case 'countries':
                $rel_table = 'Country';
                break;
        }
        return $rel_table;
    }
}
