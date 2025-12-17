<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/centre']                  = 'centre';
$route['admin/centre/create']           = 'centre/create';
$route['admin/centre/update/(:num)']    = 'centre/update/$1';
$route['admin/centre/preview/(:num)']   = 'centre/preview/$1';
$route['admin/centre/delete/(:num)']    = 'centre/delete/$1';
$route['admin/centre/create_action']    = 'centre/create_action';
$route['admin/centre/update_action']    = 'centre/update_action';
$route['admin/centre/delete_action/(:num)']    = 'centre/delete_action/$1';

$route['admin/centre/marge/(:num)']    = 'centre/marge/$1';
$route['admin/centre/marge_action']    = 'centre/marge_action';