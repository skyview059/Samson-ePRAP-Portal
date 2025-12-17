<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function getRecentStudents($limit = 10){
    $ci =& get_instance();
    $html = '';
    $students = $ci->db
            ->select('id,number_type,gmc_number,CONCAT(fname," ",lname) AS full_name, photo, gender, email,phone_code, phone,created_at')
            ->limit($limit)
            ->order_by('id', 'DESC')
            ->get('students')->result();
    
    if(!$students){
        $html .= '<tr>';
                $html .= '<td colspan="4">No Incoming Mail.</td>';
        $html .= '</tr>';
        return $html;
    }
    
    foreach ($students as $std) {
      $html .= '<tr>';
        $html .= '<td>'. getPhoto_v3( $std->photo, $std->gender, $std->full_name, 65, 65 ).'</td>';
        $html .= '<td>'. $std->full_name.' <br/> <a href="'.base_url('admin/student/read/'.$std->id).'">'. $std->email .'</a></td>';        
        $html .= "<td>+{$std->phone_code}{$std->phone}</td>";
        $html .= "<td>{$std->number_type}-{$std->gmc_number}</td>";
        $html .= "<td>". globalDateFormat($std->created_at) . "</td>";
      $html .= '</tr>';
    }         
    return $html;    
}

function getTodayExamCanceledStudents(){
    $ci =& get_instance();
    $html = '';
    $students = $ci->db
            ->from('students as s')
            ->join('student_exams as se', 'se.student_id=s.id', 'left')
            ->join('exam_schedules as es', 'es.id=se.exam_schedule_id', 'left')
            ->join('exams as e', 'e.id=es.exam_id', 'left')
            ->join('exam_centres as ec', 'ec.id=es.exam_centre_id', 'left')
            ->select('s.id,s.number_type, s.gmc_number,CONCAT(s.fname," ",s.lname) AS full_name,s.email, s.phone, s.created_at')
            ->select('se.status, se.remarks, e.name as exam_name, ec.name as centre_name, es.datetime')
            ->where('DATE(es.datetime)', date('Y-m-d') )
            ->where('se.status', 'Cancelled')
            ->order_by('es.datetime', 'ASC')
            ->order_by('se.student_id', 'DESC')
            ->get()->result();
        
    if(!$students){
        $html .= '<tr>';
                $html .= '<td class="text-center text-red" colspan="7">No Student found in today\'s Canceled list!</td>';
        $html .= '</tr>';
        return $html;
    }
    
    foreach ($students as $student) {
      $html .= '<tr>';
        $html .= '<td>'. $student->full_name.'</td>';
        $html .= '<td><a href="'.base_url('admin/student/read/'.$student->id).'">'. $student->email .'</a></td>';
        $html .= "<td>{$student->phone}</td>";
        $html .= "<td>{$student->number_type} {$student->gmc_number}</td>";
        $html .= "<td>{$student->exam_name}</td>";
        $html .= "<td>{$student->centre_name}</td>";
        $html .= "<td>". globalDateTimeFormat($student->datetime) . ' ['. dayLeftOfExam($student->datetime) . "]</td>";
      $html .= '</tr>';
//      if(!empty($student->remarks)){
//          $html .= "<tr><td colspan='7'><blockquote style='margin: 0 0 0px;font-size: 14.5px;'><p>{$student->remarks}</p></blockquote></td></tr>";
//      }
      
    }         
    return $html;    
}
