<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/student']                  = 'student';
$route['admin/student/create']           = 'student/create';
$route['admin/student/reset/(:num)']     = 'student/reset/$1';
$route['admin/student/reset_action']     = 'student/reset_action';

$route['admin/student/update/(:num)']    = 'student/update/$1';
$route['admin/student/exam/(:num)']      = 'student/exam/$1';
$route['admin/student/read/(:num)']      = 'student/read/$1';
$route['admin/student/login/(:num)']     = 'student/login/$1';
$route['admin/student/job_profile/(:num)'] = 'student/job_profile/$1';
$route['admin/student/plan/(:num)']      = 'student/plan/$1';
$route['admin/student/plan_personal/(:num)']      = 'student/plan_personal/$1';
$route['admin/student/delete/(:num)']    = 'student/delete/$1';
$route['admin/student/create_action']    = 'student/create_action';
$route['admin/student/update_action']    = 'student/update_action';
$route['admin/student/exam_action']      = 'student/exam_action';
$route['admin/student/delete_action/(:num)']    = 'student/delete_action/$1';
$route['admin/student/set_status']              = 'student/set_status';
$route['admin/student/get_student_exams']       = 'student/set_status';

$route['admin/student/get']             = 'student/get';
$route['admin/student/save']            = 'student/save';
$route['admin/student/save_assignment'] = 'student/save_assignment';
$route['admin/student/progress/(:num)'] = 'student/progress/$1';
$route['admin/student/save_progress']   = 'student/save_progress';
$route['admin/student/progress_delete'] = 'student/progress_delete';

$route['admin/student/file/(:num)']   = 'student/file/$1';
$route['admin/student/file_upload']   = 'student/file_upload';
$route['admin/student/file_delete/(:num)']   = 'student/file_delete/$1';

$route['admin/student/message/(:num)']  = 'student/message/$1';
$route['admin/student/ai']          = 'student/ai';
$route['admin/student/ai/test']     = 'student/ai/test';

$route['admin/student/cancelled']   = 'student/cancelled';


$route['admin/student/login_history']                 = 'student/login_history';
$route['admin/student/login_history/graph']           = 'student/login_history/graph';
$route['admin/student/login_history/delete']          = 'student/login_history/delete';
$route['admin/student/login_history/bulk_action']     = 'student/login_history/bulk_action';