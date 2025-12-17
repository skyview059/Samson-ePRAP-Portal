<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/doctor']                  = 'doctor';
$route['admin/doctor/timeline/(:num)']  = 'doctor/timeline/$1';
$route['admin/doctor/shortlist_save']   = 'doctor/shortlist_save';
$route['admin/doctor/shortlisted']      = 'doctor/shortlisted';
$route['admin/doctor/shortlist/(:num)'] = 'doctor/shortlist_details/$1';
$route['admin/doctor/set_status']       = 'doctor/set_status';
$route['admin/doctor/report']           = 'doctor/report';

$route['admin/doctor/suggestion_action']    = 'doctor/suggestion_action';

$route['admin/doctor/get_shortlist']    = 'doctor/get_shortlist';
$route['admin/doctor/del_shortlist']    = 'doctor/del_shortlist';
$route['admin/doctor/save_suggestion']  = 'doctor/save_suggestion';

$route['admin/doctor/delete_post/(:num)']   = 'doctor/delete_post/$1';
$route['admin/doctor/delete_candidate']     = 'doctor/delete_candidate';
