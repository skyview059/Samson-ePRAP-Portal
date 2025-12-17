<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/whatsapp']                  = 'whatsapp';
$route['admin/whatsapp/create']           = 'whatsapp/create';
$route['admin/whatsapp/update/(:num)']    = 'whatsapp/update/$1';
$route['admin/whatsapp/create_action']    = 'whatsapp/create_action';
$route['admin/whatsapp/update_action']    = 'whatsapp/update_action';
$route['admin/whatsapp/delete/(:num)']    = 'whatsapp/delete/$1';

$route['admin/whatsapp/log/(:num)']       = 'whatsapp/log/$1';
$route['admin/whatsapp/send_link']        = 'whatsapp/send_link';
$route['admin/whatsapp/get_rel_id']       = 'whatsapp/get_rel_id';
$route['admin/whatsapp/get_link_data']       = 'whatsapp/get_link_data';
$route['admin/whatsapp/widget_action']    = 'whatsapp/widget_action';


$route['admin/whatsapp/country']          = 'whatsapp/country';
$route['admin/whatsapp/graph']            = 'whatsapp/graph';