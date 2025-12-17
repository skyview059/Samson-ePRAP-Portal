<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/development_plan']                  = 'development_plan';
$route['admin/development_plan/create']           = 'development_plan/create';
$route['admin/development_plan/update/(:num)']    = 'development_plan/update/$1';
$route['admin/development_plan/details/(:num)']   = 'development_plan/details/$1';
$route['admin/development_plan/delete/(:num)']    = 'development_plan/delete/$1';
$route['admin/development_plan/create_action']    = 'development_plan/create_action';
$route['admin/development_plan/update_action']    = 'development_plan/update_action';
$route['admin/development_plan/delete_action/(:num)']    = 'development_plan/delete_action/$1';
