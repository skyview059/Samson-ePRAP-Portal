<?php

defined('BASEPATH') or exit('No direct script access allowed');

function sidebar_links($tab = '')
{
    $menus = [
        ''                          => '<i class="fa fa-dashboard"></i> Dashboard',
        'booking'                   => '<i class="fa fa-check-square-o"></i> Booking',
//        'whatsapp-group'            => '<i class="fa fa-whatsapp"></i> Whatsapp Group',
        'scenario-practice'         => '<i class="fa fa-users"></i> PLAB Part 2 Scenarios <span class="label label-success">NEW</span>',
        'study-plan'                => '<i class="fa fa-bookmark"></i> Study Plan <span class="label label-success">NEW</span>',
        'mock'                      => '<i class="fa fa-list"></i> Online Mock <span class="label label-success">NEW</span>',
        'practices'                 => '<i class="fa fa-book"></i> Practice Booking',
        'profile'                   => '<i class="fa fa-user"></i> Profile',
        'jobs'                      => '<i class="fa fa-list"></i> Job Board',
        'applied-jobs'              => '<i class="fa fa-list"></i> Applied Jobs',
        'job-profile'               => '<i class="fa fa-briefcase"></i> Job Profile',
        'stage'                     => '<i class="fa fa-signal"></i> Update Stage',
        'docs'                      => '<i class="fa fa-file"></i> Documents',
        'individual-learning-plan'  => '<i class="fa fa-folder-open-o"></i> Individual Learning Plan',
        'personal-development-plan' => '<i class="fa fa-desktop"></i> Personal Development Plan',
        'exams'                     => '<i class="fa fa-book"></i> Mock Exam <i class="fa fa-clock-o"></i>',
        'results'                   => '<i class="fa fa-wpforms"></i> Results',
        'understand'                => '<i class="fa fa-graduation-cap"></i> Understanding Your Results',
//        'messages'                  => '<i class="fa fa-comments-o"></i> Admin Messages',
        'student-messages'                  => '<i class="fa fa-comments-o"></i> Student Messages',
        'change-password'           => '<i class="fa fa-lock"></i> Change Password',
        'contact-us'                => '<i class="fa fa-envelope"></i> Contact us'
    ];

    $html = '<nav class="navbar navbar-default">';
    $html .= '<div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>';
    $html .= '<div class="sidebar collapse navbar-collapse" id="myNavbar">';
    $html .= '<ul class="nav navbar-nav">';
    foreach ($menus as $link => $menu) {
        $html .= '<li';
        $html .= ($link == $tab) ? ' class="active"' : '';
        $html .= "><a href=\"{$link}\">{$menu}</a></li>";
    }
    $html .= '<li><a href="'.site_url('book-course').'" target="_blank"> <i class="fa fa-user-md"></i> Subscription <i class="fa fa-external-link"></i></a></li>';
    $html .= '<li><a href="https://www.samsoncourses.com/" target="_blank"> <i class="fa fa-globe"></i> Samson Courses Website <i class="fa fa-external-link"></i></a></li>';
    $html .= '<li><a href="' . site_url("logout") . '"> <i class="fa fa-power-off"></i> Logout</a></li>';
    $html .= '</ul></div></nav>';
    return $html;
}

function getStageOfProgess()
{

    $student_id = (int)getLoginStudentData('student_id');

    $ci =& get_instance();
    $ci->db->select('p.title');
    $ci->db->from('student_progressions as sp');
    $ci->db->join('progressions as p', 'sp.progression_id=p.id', 'LEFT');

    $ci->db->where('sp.student_id', $student_id);
    $ci->db->where('sp.completed', 'Yes');
    $ci->db->order_by('p.order_id', 'DESC');

    $progress = $ci->db->get()->row();
    if (!$progress) {
        return '<p><em>Please <a href="stage">click here</a> to set your stage of progression</em></p>';
    }
    $html = '<div class="div done">';
//    $html .= '<i class="fa fa-check-square"></i>&nbsp';
    $html .= '<i class="fa fa-hand-o-right"></i>&nbsp';
//    $html .= '<i class="fa fa-caret-right"></i>&nbsp';
//    $html .= '<i class="fa fa-chevron-right"></i>&nbsp';
    $html .= $progress->title;
    $html .= '</div>';
    return $html;

    //echo $ci->db->last_query();

//    $html = '';
//    foreach($progress as $p ){
//        $html .= '<li class="done">';
//        $html .= '<i class="fa fa-check-square"></i>&nbsp';
//        $html .= $progress->title;
//        $html .= '</li>';
//    }
//    return $html;
}

function passOrFailColor($str = 'Pass')
{
    if ($str == 'Pass') {
        return '<b style="color:green;text-weight:bold;">Pass</b>';
    } else {
        return '<b style="color:red;text-weight:bold;">Fail</b>';
    }
}

function multiDateFormat($str)
{
    if (empty($str)) {
        return '--';
    }
    $arr   = explode(',', $str);
    $qty   = count($arr);
    $loop  = $qty - 2;
    $first = [];
    for ($i = 0; $i <= $loop; $i++) {
        $first[] = date('dS M', strtotime($arr[$i]));
    }
    $output = implode(', ', $first);
    $second = ' And ' . date('dS M Y', strtotime($arr[$qty - 1]));
    return $output . $second;

}

function getPhoto_v2($photo, $name, $width = 80, $height = 80, $zc = 2)
{
    $filename = dirname(BASEPATH) . "/{$photo}";
    if ($photo && file_exists($filename)) {
        $src = base_url($photo);
        $img = base_url("timthumb.php?src={$src}&h={$height}&w={$width}&zc={$zc}");
        return "<img src='{$img}' class='img-cercle' title='{$name}'/>";
    } else {
        return name2imgView($name, $width, $height);
    }
}

function name2imgView($name)
{
    $arr   = explode(' ', strtoupper($name));
    $short = substr_fk($arr[0], 0, 1) . substr_fk($arr[1], 0, 1);
    return "<div class='name2img'>{$short}</div>";
}

function getPhoto_v3($photo, $gender, $name = 'Student', $width = 80, $height = 80, $zc = 2, $id = false)
{
    $filename = dirname(BASEPATH) . "/{$photo}";
    if ($photo && file_exists($filename)) {
        $src = base_url($photo);
//        $img = base_url("timthumb.php?src={$src}&h={$height}&w={$width}&zc={$zc}");
        $img = $src;
    } else {
        $str = strtolower_fk($gender);
        if (empty($str)) {
            $str = 'male';
        }
        $img = "uploads/{$str}.png";
    }
    $new_id = ($id == true) ? "id='results'" : '';
    return "<img {$new_id} src='{$img}' title='{$name}'  style=\"width:{$width}px; height:{$height}px;\" class='img-cercle' />";
}

function name2imgViewV3($name, $width = 60, $height = 60)
{
    $arr   = explode(' ', strtoupper($name));
    $short = substr_fk($arr[0], 0, 1) . substr_fk($arr[1], 0, 1);
    $style = '';
    if ($width !== 60 and $height !== 60) {
        $style = "style=\"width:{$width}px;height:{$height}px\"";
    }
    return "<div class='name2img-v3' {$style}><span>{$short}</span></div>";
}

function getStudentProcessBar()
{
    $student_id = getLoginStudentData('student_id');
    $ci         =& get_instance();
    $stage      = $ci->db->where('student_id', $student_id)->count_all_results('student_progressions');
    $files      = $ci->db->where('student_id', $student_id)->count_all_results('files');
    $job_pro    = $ci->db->where('student_id', $student_id)->count_all_results('student_job_profile');
    $mock       = $ci->db->where('student_id', $student_id)->where('status', 'Enrolled')->count_all_results('student_exams');

    $html = '<div class="row">
                <div class="col-md-12">
                    <div class="progressbar-wrapper">
                        <ul class="progressbar">
                            <li class="active"><i class="fa fa-user" aria-hidden="true"></i>Profile</li>';

    if ($files > 0) {
        $html .= '<li class="active document"><i class="fa fa-file-text" aria-hidden="true"></i>Documents</li>';
    } else {
        $html .= '<li class="document"><a href="docs"><i class="fa fa-file-text" aria-hidden="true"></i>Documents</a></li>';
    }
    if ($stage > 0) {
        $html .= '<li class="active progresa"><i class="fa fa-signal" aria-hidden="true"></i>Stage of Progression</li>';
    } else {
        $html .= '<li class="progresa"><a href="stage"><i class="fa fa-signal" aria-hidden="true"></i>Stage of Progression</a></li>';
    }

    if ($job_pro > 0) {
        $html .= '<li class="active jobprofile"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>Job Profile</li>';
    } else {
        $html .= '<li class="jobprofile"><a href="job-profile"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i>Job Profile</a></li>';

    }

    if ($mock > 0) {
        $html .= '<li class="active mockbooking"><i class="fa fa-check-circle-o" aria-hidden="true"></i>Mock Booking</li>';
    } else {
        $html .= '<li class="mockbooking"><a href="exams"><i class="fa fa-check-circle-o" aria-hidden="true"></i>Mock Booking</a></li>';
    }

    $html .= '</ul>
                    </div>
                    
                </div>
                
            </div> ';
    return $html;
}

function job_specilatiy_rel($specialties)
{
    $data = [];
    foreach ($specialties as $opt) {
        if (array_key_exists('specialty_id', $opt)) {
            $data[] = $opt;
        }
    }
    return $data;
}

function openBySwitch($data)
{
    if ($data->opened_by == 'Student') {
        $data = [
            'sender'   => "{$data->student} (Student)",
            'receiver' => "{$data->teacher} ({$data->label})",
        ];
    } else {
        $data = [
            'sender'   => "{$data->teacher} ({$data->label})",
            'receiver' => "{$data->student} (Student)",
        ];
    }

    return $data;
}

function isEditLocked($id, $status, $url = 'delete_doc')
{
    $btn = '<span id="sbtn-' . $id . '">';
    if ($status == 'Locked') {
        $btn .= '<span class="btn btn-danger btn-xs disabled" title="Admin Locked File">';
        $btn .= '<i class="fa fa-lock"></i> Locked </span>';
    } else {
        $link = site_url("{$url}/{$id}");
        $btn  .= "<a href=\"{$link}\" class='btn btn-danger btn-xs' onclick=\"return confirm('Confirm Delete');\">
            <i class='fa fa-times'></i> 
            Delete
        </a>";
    }
    $btn .= '</span>';
    return $btn;
}

function nameOfDocuments()
{
    $options = [
        'Passport'                       => 'Passport',
        'Date of entry into the UK'      => 'Date of entry into the UK (stamp page)',
        'PLAB 2 Course Payment'          => 'PLAB 2 Course Payment',
        'Registration form'              => 'Registration form (optional)',
        'GMC online booking screen shot' => 'GMC online booking screen shot',
        'Other'                          => 'Other',
    ];
    $html    = '';
    foreach ($options as $label => $name) {
        $html .= "<option value='{$label}'>{$name}</option>";
    }
    return $html;
}

function GBP($number)
{
    return '&pound;' . number_format_fk($number, 2);
}

function showBookingDates($course, $course_payment_id)
{

    $json  = $course['dates'];
    $id    = $course['id'];
    $dates = json_decode($json);

    if (empty($dates)) {
        return '<p><small>No Date Found to Enroll<small></p>';
    }

    $isSelected = (isset($course['isSelected']) && $course['isSelected'] > 0) ? '' : 'hidden';
    $checkbox   = "<div id='slot_{$id}' class='slots {$isSelected}'>";

    foreach ($dates as $date) {
        $checked = '';
        if (!$date->qty) {
            if ($course_payment_id) {
                $checked = ($date->id == $date->inCart) ? ' checked="checked"' : '';
            }

            $checkbox .= '<label>';
            $checkbox .= " <input type='radio' class='ckbx' 
                            name='slot_id[{$id}]' 
                            id='date_{$id}_$date->id' 
                            value=\"{$date->id}\" {$checked} />  &nbsp;";

        } else {
            $checkbox .= '<label class="text-red">';
        }


//        $checkbox .= "{$date->s_date} ({$date->AvailableSeat})";
        $checkbox .= date('d M y H:i A', strtotime($date->start_date)) . " ({$date->AvailableSeat})";

        $checkbox .= "</label>";
    }
    $checkbox .= '</div>';

    return $checkbox;
}

function courseNavTab($active)
{
    $tabs = [
        'booking'         => '<i class="fa fa-check-square-o"></i> My Course(s)',
        'booking/course'  => '<i class="fa fa-anchor"></i> Book Course',
        'booking/payment' => '<i class="fa fa-gbp"></i> Payment Log',
    ];

    $nav = '<ul class="nav nav-tabs">';
    foreach ($tabs as $url => $tab) {
        $nav .= '<li';
        $nav .= ($url == $active) ? ' class="active"' : '';
        $nav .= '><a href="' . site_url($url) . '">';
        $nav .= $tab;
        $nav .= '</a>';
        $nav .= '</li>';
    }
    $nav .= '</ul>';
    return $nav;
}

function practiceNavTab($active)
{
    $tabs = [
        'practices'        => '<i class="fa fa-check-square-o"></i> Book Practice',
        'practices/booked' => '<i class="fa fa-anchor"></i> My Practice',
    ];

    $nav = '<ul class="nav nav-tabs">';
    foreach ($tabs as $url => $tab) {
        $nav .= '<li';
        $nav .= ($url == $active) ? ' class="active"' : '';
        $nav .= '><a href="' . site_url($url) . '">';
        $nav .= $tab;
        $nav .= '</a>';
        $nav .= '</li>';
    }
    $nav .= '</ul>';
    return $nav;
}

function viewOrPayInvoice($ID, $Status, $Timeout)
{
    $btn = '';
    if ($Status == 'Paid') {
        $btn .= '<span id="' . $ID . '" class="view-invoice btn btn-xs btn-primary">';
        $btn .= '<i class="fa fa-bars"></i>';
        $btn .= ' View Invoice';
        $btn .= '</span>';
        return $btn;
    }

    if ($Timeout == 'No') {
        $btn .= '<a href="booking/checkout/' . $ID . '" class="btn btn-xs btn-primary">';
        $btn .= '<i class="fa fa-play"></i>';
        $btn .= ' Pay Now';
        $btn .= '</a>';
    }

    if ($Timeout == 'Yes') {
        $btn .= '<span class="btn btn-xs btn-danger">';
        $btn .= '<i class="fa fa-ban"></i>';
        $btn .= ' Payment Timeout';
        $btn .= '</span>';
    }

    return $btn;
}

function getInvoiceCourses($payment_id)
{
    $ci =& get_instance();

    $ci->db->select('c.name as course');
    $ci->db->from('course_booked as cb');
    $ci->db->join('courses as c', 'c.id=cb.course_id');
    $ci->db->where('course_payment_id', $payment_id);
    $ci->db->where('cb.type', 'course');
    $courses = $ci->db->get()->result();
    if ($courses) {
        $html = '';
        foreach ($courses as $c) {
            $html .= $c->course . '<br>';
        }
        echo '<strong>Courses Selected: </strong>' .$html;
    } else {
        $ci->db->select('e.name');
        $ci->db->from('course_booked as cb');
        $ci->db->join('exams as e', 'e.id=cb.course_id');
        $ci->db->where('course_payment_id', $payment_id);
        $ci->db->where('cb.type', 'practice');
        $practice = $ci->db->get()->row();
        if($practice){
            echo '<strong>Practice Selected: </strong>' .$practice->name;
        } else {
            echo '<strong>Course/Practice Selected: </strong>' .'N/A';
        }
    }
}

function findOutPayoutTime($purchased_on, $limit = 30)
{
    $time_limit   = time();
    $time_start   = strtotime($purchased_on);
    $limit_in_sec = (($limit * 60) - ($time_limit - $time_start));
    return $limit_in_sec;
}

function refundPercentage($days_left)
{
    if ($days_left >= 28) {
        return '100';
    } elseif ($days_left >= 21) {
        return '50';
    } elseif ($days_left >= 14) {
        return '25';
    } else {
        return '0';
    }
}

function refundAmount($price, $days_left)
{
    $percentage = refundPercentage($days_left);
    if ($percentage > 0) {
        return round(($price * ($percentage / 100)), 2);
    } else {
        return 0;
    }
}

function timeToSec($time)
{
    $totalSeconds = $time * 60;
    $minutes      = str_pad(intval($totalSeconds / 60), 2, '0', STR_PAD_LEFT);
    $seconds      = str_pad(intval($totalSeconds % 60), 2, '0', STR_PAD_LEFT);
    return "{$minutes}:{$seconds}";
}

function scenarioPracticePurchaseStatus($student_id, $course_id)
{
    $ci =& get_instance();
    $ci->db->select('cb.id, pp.title as package_name, pp.scenario_type');
    $ci->db->where('cp.student_id', $student_id);
    $ci->db->where('cb.course_id', $course_id);
    $ci->db->where('cb.type', 'practice');
    $ci->db->where('cb.status', 'Confirmed');
    $ci->db->where('cb.expiry_date >=', date('Y-m-d'));
    $ci->db->from('course_booked as cb');
    $ci->db->join('course_payments as cp', 'cp.id=cb.course_payment_id', 'LEFT');
    $ci->db->join('practice_packages as pp', 'pp.id=cb.practice_package_id', 'LEFT');
    return $ci->db->get()->row();
//    return $ci->db->count_all_results();
}

function getPackageScenarioTypeName($type)
{
    // "PLAB" refers to Professional and Linguistic Assessments Board
    switch ($type) {
        case 'New':
            return 'PLAB 2 new scenario';
        case 'Old':
            return 'PLAB 2 scenario bank';
        case 'Both':
            return 'Complete PLAB2 scenario bank';
        default:
            return 'N/A';
    }
}