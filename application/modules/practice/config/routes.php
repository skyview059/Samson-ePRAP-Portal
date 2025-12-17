<?php defined('BASEPATH') or exit('No direct script access allowed');

$route['admin/practice']                         = 'practice';
$route['admin/practice/create']                  = 'practice/create';
$route['admin/practice/update/(:num)']           = 'practice/update/$1';
$route['admin/practice/student/(:num)']          = 'practice/student/$1';
$route['admin/practice/delete/(:num)']           = 'practice/delete/$1';
$route['admin/practice/create_action']           = 'practice/create_action';
$route['admin/practice/update_action']           = 'practice/update_action';
$route['admin/practice/practice_list_by_centre'] = 'practice/practice_list_by_centre';

$route['admin/practice/delete_action/(:num)'] = 'practice/delete_action/$1';
$route['admin/practice/publish/(:num)']       = 'practice/publish/$1';
$route['admin/practice/cancel/(:num)']        = 'practice/cancel/$1';
$route['admin/practice/cancel_action/(:num)'] = 'practice/cancel_action/$1';
$route['admin/practices/attendance']          = 'practice/attendance';
//
//$route['admin/practice/get_practice_students/(:num)']    = 'practice/get_practice_students/$1';
//$route['admin/practice/assign_practice_set_status']    = 'practice/assign_practice_set_status';