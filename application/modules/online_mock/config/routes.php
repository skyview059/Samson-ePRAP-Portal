<?php defined('BASEPATH') or exit('No direct script access allowed');

$route['admin/online_mock']                 = 'online_mock';
$route['admin/online_mock/create']          = 'online_mock/create';
$route['admin/online_mock/update/(:num)']   = 'online_mock/update/$1';
$route['admin/online_mock/scenario/(:num)'] = 'online_mock/scenario/$1';
$route['admin/online_mock/set_status']      = 'online_mock/set_status';

$route['admin/online_mock/print_candidate_inst/(:num)'] = 'online_mock/print_candidate_inst/$1';
$route['admin/online_mock/print_full_scenario/(:num)']  = 'online_mock/print_full_scenario/$1';

$route['admin/online_mock/student/(:num)']         = 'online_mock/student/$1';
$route['admin/online_mock/read/(:num)']            = 'online_mock/read/$1';
$route['admin/online_mock/delete/(:num)']          = 'online_mock/delete/$1';
$route['admin/online_mock/create_action']          = 'online_mock/create_action';
$route['admin/online_mock/update_action']          = 'online_mock/update_action';
$route['admin/online_mock/station_action']         = 'online_mock/station_action';
$route['admin/online_mock/exam_list_by_centre']    = 'online_mock/exam_list_by_centre';
$route['admin/online_mock/delete_action/(:num)']   = 'online_mock/delete_action/$1';
$route['admin/online_mock/publish/(:num)']         = 'online_mock/publish/$1';
$route['admin/online_mock/cancel/(:num)']          = 'online_mock/cancel/$1';
$route['admin/online_mock/cancel_action/(:num)']   = 'online_mock/cancel_action/$1';
$route['admin/online_mock/rollback_action/(:num)'] = 'online_mock/rollback_action/$1';

$route['admin/online_mock/get_assessor']  = 'online_mock/get_assessor';
$route['admin/online_mock/save_assessor'] = 'online_mock/save_assessor';

$route['admin/online_mock/get_student_exams/(:num)'] = 'online_mock/get_student_exams/$1';
$route['admin/online_mock/assign_exam_set_status']   = 'online_mock/assign_exam_set_status';

$route['admin/online_mock/save_scenario_time'] = 'online_mock/save_scenario_time';
$route['admin/online_mock/save_individual_scenario_time'] = 'online_mock/save_individual_scenario_time';