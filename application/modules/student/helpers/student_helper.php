<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function studentTabs($id, $active_tab)
{
    $exam = Tools::countTableRows( $id,'student_exams' );
    $dev = Tools::countTableRows( $id,'student_development' );
    $pdev = Tools::countTableRows( $id,'personal_dev_plans' );
    $stage = Tools::countTableRows( $id,'student_progressions' );
    $file = Tools::countTableRows( $id,'files' );
    $mails = Tools::sentMail( $id,'messages' );
    
    $html = '<ul class="nav nav-tabs admintab">';
    $tabs = [
        'read'      => 'Details',
        'update'    => 'Update',
        'job_profile' => 'Job Profile',
        'exam'      => "Exam <sup class='large'>({$exam})</sup>",
        'plan'      => "ILP <sup class='large'>({$dev})</sup>",
        'plan_personal' => "PDP <sup class='large'>({$pdev})</sup>",
        'progress'  => "Stage <sup class='large'>({$stage})</sup>",
        'file'      => "File/Doc <sup class='large'>({$file})</sup>",
        'message'   => "Message <sup class='large'>({$mails})</sup>",
        'reset'     => 'Password',
        'delete'    => 'Delete',
    ];

    foreach ($tabs as $link => $tab) {
        $html .= '<li><a href="' . Backend_URL . "student/{$link}/{$id}\"";
        $html .= ($link == $active_tab) ? ' class="active"' : '';
        $html .= '>' . $tab . '</a></li>';
    }
    $html .= '<li><a href="' . Backend_URL . "student/login/{$id}\"' target='_blank'>";
    $html .= 'VAS</a></li>';   
    $html .= '</ul>';
    return $html;
}

function getStudentList($selected = 0) {
    $ci = & get_instance();
    $ci->db->select('id,fname,mname,lname,CONCAT(number_type, "-",gmc_number) AS number');
    $ci->db->where('status', 'Active');
    $students = $ci->db->get('students')->result();

    $options = '';
    foreach ($students as $stu) {
        $options .= '<option value="' . $stu->id . '" ';
        $options .= ($stu->id == $selected ) ? 'selected="selected"' : '';
        $options .= ">{$stu->fname} {$stu->mname} {$stu->lname} [{$stu->number}]</option>";
    }
    return $options;
}

function getStudentName($id = 0) {
    $ci = & get_instance();
    $ci->db->select('id,fname,mname,lname');
    $ci->db->where('id', $id);
    $student = $ci->db->get('students')->row();

    if($student){
        return "{$student->fname} {$student->mname} {$student->lname}";
    } else {
        return 'Student Not Found!';
    }
}

function studentStatus($status, $id){
    $html = '<div class="dropdown">';
    $html .= getstudentStatus( $status, $id );
    $html .= '<ul class="dropdown-menu">';
    $html .= "<li><a onclick=\"statusUpdate({$id}, 'Active');\"> <i class=\"fa fa-check\"></i> Active</a></li>";
    $html .= "<li><a onclick=\"statusUpdate({$id}, 'Inactive');\"> <i class=\"fa fa-ban\"></i> Inactive</a></li>";
    $html .= "<li><a onclick=\"statusUpdate({$id}, 'Pending');\"> <i class=\"fa fa-hourglass-1\"></i> Pending</a></li>";
    $html .= "<li><a onclick=\"statusUpdate({$id}, 'Archive');\"> <i class=\"fa fa-archive\"></i> Archive</a></li>";
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

function getstudentStatus( $status = 'Active', $id = 0, $action = true){
       
    switch ( $status ){
      
        case 'Active': 
            $class = 'btn-success';
            $icon = '<i class="fa fa-check-square-o"></i> ';
            break;            
        case 'Inactive':
            $class = 'btn-danger'; 
            $icon = '<i class="fa fa-ban" ></i> ';
            break;                             
        case 'Pending':
            $class = 'btn-info';
            $icon = '<i class="fa fa-hourglass-1"></i> ';
            break;              
         case 'Archive':
            $class = 'btn-danger'; 
            $icon = '<i class="fa fa-archive"></i> ';
            break; 
        default :
            $class = 'btn-default';
            $icon = '<i class="fa fa-info"></i> ';
    }  
    
    if($action){
        return '<button class="btn '. $class .' btn-xs" id="active_status_'. $id .'" type="button" data-toggle="dropdown">
            '. $icon . $status .' &nbsp; <i class="fa fa-angle-down"></i>
        </button>';
    } else {
        return "<span class=\"btn {$class} btn-xs\" type=\"button\">{$icon} {$status}</span>";
    }        
}

function studentIsVerified( $Verified ){
    if($Verified == 'Yes'){
        $Verified = '<b class="text-green">Yes</b>';
    } else {
        $Verified = '<b style="color:red;">No</b>';
    }
    
    
    return 'Verified: ' . $Verified;
}

function studentBlockedTab($status)
{        
    $html = '<ul class="tabsmenu">';
    $tabs = ['Cancelled','Blocked'];

    foreach ($tabs as $tab) {
        
        $qty = Tools::countBlockedOrCancelled( $tab );
        
        $html .= '<li><a href="' . Backend_URL . "student/cancelled?status={$tab}\"";
        $html .= ($tab == $status) ? ' class="active"' : '';
        $html .= ">{$tab} <sup class='large'>({$qty})</sup></a></li>";
    }    
    $html .= '</ul>';
    return $html;
}
