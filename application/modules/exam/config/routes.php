<?php defined('BASEPATH') or exit('No direct script access allowed');

$route['admin/exam']                 = 'exam';
$route['admin/exam/create']          = 'exam/create';
$route['admin/exam/update/(:num)']   = 'exam/update/$1';
$route['admin/exam/scenario/(:num)'] = 'exam/scenario/$1';
$route['admin/exam/set_status']      = 'exam/set_status';

$route['admin/exam/print_candidate_inst/(:num)'] = 'exam/print_candidate_inst/$1';
$route['admin/exam/print_full_scenario/(:num)']  = 'exam/print_full_scenario/$1';

$route['admin/exam/student/(:num)']         = 'exam/student/$1';
$route['admin/exam/student_export_csv/(:num)']         = 'exam/student_export_csv/$1';
$route['admin/exam/read/(:num)']            = 'exam/read/$1';
$route['admin/exam/delete/(:num)']          = 'exam/delete/$1';
$route['admin/exam/create_action']          = 'exam/create_action';
$route['admin/exam/update_action']          = 'exam/update_action';
$route['admin/exam/station_action']         = 'exam/station_action';
$route['admin/exam/exam_list_by_centre']    = 'exam/exam_list_by_centre';
$route['admin/exam/delete_action/(:num)']   = 'exam/delete_action/$1';
$route['admin/exam/publish/(:num)']         = 'exam/publish/$1';
$route['admin/exam/cancel/(:num)']          = 'exam/cancel/$1';
$route['admin/exam/cancel_action/(:num)']   = 'exam/cancel_action/$1';
$route['admin/exam/rollback_action/(:num)'] = 'exam/rollback_action/$1';

$route['admin/exam/get_assessor']  = 'exam/get_assessor';
$route['admin/exam/save_assessor'] = 'exam/save_assessor';

$route['admin/exam/name']                     = 'exam/name';
$route['admin/exam/name/create']              = 'exam/name/create';
$route['admin/exam/name/create_action']       = 'exam/name/create_action';
$route['admin/exam/name/update/(:num)']       = 'exam/name/update/$1';
$route['admin/exam/name/update_action']       = 'exam/name/update_action';
$route['admin/exam/name/delete/(:num)']       = 'exam/name/delete/$1';
$route['admin/exam/get_student_exams/(:num)'] = 'exam/get_student_exams/$1';
$route['admin/exam/assign_exam_set_status']   = 'exam/assign_exam_set_status';