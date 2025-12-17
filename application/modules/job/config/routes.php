<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/job']                  = 'job';
$route['admin/job/create']           = 'job/create';
$route['admin/job/update/(:num)']    = 'job/update/$1';
$route['admin/job/read/(:num)']      = 'job/read/$1';
$route['admin/job/delete/(:num)']    = 'job/delete/$1';
$route['admin/job/create_action']    = 'job/create_action';
$route['admin/job/update_action']    = 'job/update_action';
$route['admin/job/delete_action/(:num)']    = 'job/delete_action/$1';

$route['admin/job/application']     = 'job/application';
$route['admin/job/application/delete_action/(:num)']    = 'job/application/delete_action/$1';
$route['admin/job/application/set_status']    = 'job/application/set_status';