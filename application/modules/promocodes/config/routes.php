<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/promocodes']                  = 'promocodes';
$route['admin/promocodes/create']           = 'promocodes/create';
$route['admin/promocodes/update/(:num)']    = 'promocodes/update/$1';
$route['admin/promocodes/read/(:num)']      = 'promocodes/read/$1';
$route['admin/promocodes/delete/(:num)']    = 'promocodes/delete/$1';
$route['admin/promocodes/create_action']    = 'promocodes/create_action';
$route['admin/promocodes/update_action']    = 'promocodes/update_action';
$route['admin/promocodes/delete_action/(:num)']    = 'promocodes/delete_action/$1';
