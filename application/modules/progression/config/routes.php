<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/progression']                  = 'progression';
$route['admin/progression/create']           = 'progression/create';
$route['admin/progression/update/(:num)']    = 'progression/update/$1';
$route['admin/progression/delete/(:num)']    = 'progression/delete/$1';
$route['admin/progression/create_action']    = 'progression/create_action';
$route['admin/progression/update_action']    = 'progression/update_action';
$route['admin/progression/save_order']    = 'progression/save_order';
