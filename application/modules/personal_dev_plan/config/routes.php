<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/personal_dev_plan']                   = 'personal_dev_plan';
$route['admin/personal_dev_plan/create']            = 'personal_dev_plan/create';
$route['admin/personal_dev_plan/update/(:num)']     = 'personal_dev_plan/update/$1';
$route['admin/personal_dev_plan/details/(:num)']    = 'personal_dev_plan/details/$1';
$route['admin/personal_dev_plan/delete/(:num)']     = 'personal_dev_plan/delete/$1';
$route['admin/personal_dev_plan/create_action']     = 'personal_dev_plan/create_action';
$route['admin/personal_dev_plan/update_action']     = 'personal_dev_plan/update_action';
$route['admin/personal_dev_plan/delete_action/(:num)']    = 'personal_dev_plan/delete_action/$1';

$route['admin/personal_dev_plan/fixPrimaryKey']     = 'personal_dev_plan/fixPrimaryKey';
$route['admin/personal_dev_plan/check']             = 'personal_dev_plan/check';

$route['admin/personal_dev_plan/domain']                  = 'personal_dev_plan/domain';
$route['admin/personal_dev_plan/domain/create']           = 'personal_dev_plan/domain/create';
$route['admin/personal_dev_plan/domain/create_action']    = 'personal_dev_plan/domain/create_action';
$route['admin/personal_dev_plan/domain/update/(:num)']    = 'personal_dev_plan/domain/update/$1';
$route['admin/personal_dev_plan/domain/update_action']    = 'personal_dev_plan/domain/update_action';
$route['admin/personal_dev_plan/domain/delete/(:num)']    = 'personal_dev_plan/domain/delete/$1';
$route['admin/personal_dev_plan/domain/save_order']       = 'personal_dev_plan/domain/save_order';