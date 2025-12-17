<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/message']                 = 'message';
$route['admin/message/open']            = 'message/open';
$route['admin/message/open_action']     = 'message/open_action';
$route['admin/message/reply_action']    = 'message/reply_action';
$route['admin/message/view/(:num)']     = 'message/view/$1';
$route['admin/message/view_modal/(:num)']     = 'message/view_modal/$1';
$route['admin/message/multi_delete']    = 'message/multi_delete';
$route['admin/message/send_message_from_modal']    = 'message/send_message_from_modal';