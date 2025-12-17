<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/mailer']                          = 'mailer';
$route['admin/mailer/compose']                  = 'mailer/compose';
$route['admin/mailer/save_queue']               = 'mailer/save_queue';

$route['admin/mailer/last_registed_student']    = 'mailer/last_registed_student';
$route['admin/mailer/custom_date']              = 'mailer/custom_date';
$route['admin/mailer/all_student']              = 'mailer/all_student';
$route['admin/mailer/custom_student']           = 'mailer/custom_student';

$route['admin/mailer/get_students']             = 'mailer/get_students';

$route['admin/mailer/getStudents']              = 'mailer/getStudents';
$route['admin/mailer/update_template/(:num)']   = 'mailer/update_template/$1';

/* mail_queues */
$route['admin/mailer/queue']                    = 'mailer/queue';
$route['admin/mailer/queue/popup/(:num)']       = 'mailer/queue/popup/$1';
