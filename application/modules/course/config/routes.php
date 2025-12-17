<?php defined('BASEPATH') or exit('No direct script access allowed');

$route['admin/course']        = 'course';
$route['admin/course/create'] = 'course/create';

$route['admin/course/update/(:num)']        = 'course/update/$1';
$route['admin/course/read/(:num)']          = 'course/read/$1';
$route['admin/course/delete/(:num)']        = 'course/delete/$1';
$route['admin/course/create_action']        = 'course/create_action';
$route['admin/course/update_action']        = 'course/update_action';
$route['admin/course/delete_action/(:num)'] = 'course/delete_action/$1';
$route['admin/course/delete_row']           = 'course/delete_row/';

$route['admin/course/payment']                      = 'course/payment';
$route['admin/course/payment/create']               = 'course/payment/create';
$route['admin/course/payment/update/(:num)']        = 'course/payment/update/$1';
$route['admin/course/payment/read/(:num)']          = 'course/payment/read/$1';
$route['admin/course/payment/delete/(:num)']        = 'course/payment/delete/$1';
$route['admin/course/payment/create_action']        = 'course/payment/create_action';
$route['admin/course/payment/update_action']        = 'course/payment/update_action';
$route['admin/course/payment/delete_action/(:num)'] = 'course/payment/delete_action/$1';

$route['admin/course/category']                      = 'course/category';
$route['admin/course/category/create']               = 'course/category/create';
$route['admin/course/category/update/(:num)']        = 'course/category/update/$1';
$route['admin/course/category/read/(:num)']          = 'course/category/read/$1';
$route['admin/course/category/delete/(:num)']        = 'course/category/delete/$1';
$route['admin/course/category/create_action']        = 'course/category/create_action';
$route['admin/course/category/update_action']        = 'course/category/update_action';
$route['admin/course/category/delete_action/(:num)'] = 'course/category/delete_action/$1';

$route['admin/course/booked']                     = 'course/booked';
$route['admin/course/booked/related_data/(:num)'] = 'course/booked/related_data/$1';
$route['admin/course/booked/create']              = 'course/booked/create';
$route['admin/course/booked/create_action']       = 'course/booked/create_action';
$route['admin/course/booked/cancel']              = 'course/booked/cancel';
$route['admin/course/booked/save_manual_payment'] = 'course/booked/save_manual_payment';

// practice booking
$route['admin/course/booked/practice']               = 'course/booked/practice';
$route['admin/course/booked/create_practice']        = 'course/booked/create_practice';
$route['admin/course/booked/create_practice_action'] = 'course/booked/create_practice_action';

$route['admin/course/booked/admin_note']      = 'course/booked/admin_note';
$route['admin/course/booked/reschedule']      = 'course/booked/reschedule';
$route['admin/course/booked/reschedule_save'] = 'course/booked/reschedule_save';
$route['admin/course/booked/attendance']      = 'course/booked/attendance';
$route['admin/course/date/slot/(:num)']       = 'course/course/dateSlot/$1';

// Practice Manager
$route['admin/course/practice_package']               = 'course/practice_package';
$route['admin/course/practice_package/create']        = 'course/practice_package/create';
$route['admin/course/practice_package/create_action'] = 'course/practice_package/create_action';
$route['admin/course/practice_package/read/(:num)'] = 'course/practice_package/read/$1';
$route['admin/course/practice_package/update/(:num)'] = 'course/practice_package/update/$1';
$route['admin/course/practice_package/update_action'] = 'course/practice_package/update_action';

