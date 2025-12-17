<?php defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller']    = 'student_portal';

$route['admin']                 = 'dashboard';
$route['admin/calendar']        = 'dashboard/calendar';
$route['admin/calendar/schedule']        = 'dashboard/calendar/schedule';
$route['admin/db/get_student']  = 'dashboard/getPendingEnrolledStudents';
$route['admin/login']           = 'auth/login_form';
$route['admin/logout']          = 'auth/logout';

$route['404_override']          = 'frontend';
$route['404']                   = 'frontend/not_found_page';
$route['sign_up_action']        = 'frontend/sign_up_action';
$route['verify_email']          = 'frontend/verify_email';

$route['login']                 = 'frontend/login';
$route['ck-image-upload']       = 'frontend/ckImageUpload';
$route['logout']                = 'auth_student/logout';
$route['reset-password']        = 'auth_student/reset_password';
$route['sign-up']               = 'frontend/sign_up';
$route['translate_uri_dashes']  = FALSE;

$route['admin/clear_cache']     = 'ajax/clear_cache';
$route['admin/all_students']    = 'ajax/getStudents';

define('ModuleRoutePrefix', APPPATH . 'modules/');
define('ModuleRouteSuffix', '/config/routes.php');

require_once( 'routes-student.php' );

require_once(ModuleRoutePrefix . 'settings' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'users' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'profile' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'module' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'db_sync' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'email_templates' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'mailbox' . ModuleRouteSuffix);

require_once(ModuleRoutePrefix . 'scenario' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'exam' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'online_mock' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'centre' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'student' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'development_plan' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'personal_dev_plan' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'message' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'assess' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'progression' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'file' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'doctor' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'job_specialty' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'sms' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'job' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'mailer' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'whatsapp' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'course' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'practice' . ModuleRouteSuffix);
require_once(ModuleRoutePrefix . 'promocodes' . ModuleRouteSuffix);

//require_once(ModuleRoutePrefix . 'result' . ModuleRouteSuffix);
//require_once(ModuleRoutePrefix . 'exam_preparation' . ModuleRouteSuffix);
//require_once(ModuleRoutePrefix . 'examine' . ModuleRouteSuffix);
//require_once(ModuleRoutePrefix . 'company' . ModuleRouteSuffix);