<?php defined('BASEPATH') or exit('No direct script access allowed');

$route['admin/scenario']                      = 'scenario';
$route['admin/scenario/create']               = 'scenario/create';
$route['admin/scenario/update/(:num)']        = 'scenario/update/$1';
$route['admin/scenario/read/(:num)']          = 'scenario/read/$1';
$route['admin/scenario/print/(:num)']         = 'scenario/single_print/$1';
$route['admin/scenario/delete/(:num)']        = 'scenario/delete/$1';
$route['admin/scenario/create_action']        = 'scenario/create_action';
$route['admin/scenario/update_action']        = 'scenario/update_action';
$route['admin/scenario/delete_action/(:num)'] = 'scenario/delete_action/$1';

$route['admin/scenario/get']                  = 'scenario/get';
$route['admin/scenario/save']                 = 'scenario/save';
$route['admin/scenario/save_assign_scenario'] = 'scenario/save_assign_scenario';

$route['admin/scenario/ajax_del_action']   = 'scenario/ajax_del_action';
$route['admin/scenario/ajax_batch_delete'] = 'scenario/ajax_batch_delete';

$route['admin/scenario/set_display_mode']      = 'scenario/set_display_mode';
$route['admin/scenario/ajax_set_display_mode'] = 'scenario/ajax_set_display_mode';


$route['admin/scenario/practice']                                = 'scenario/practice';
$route['admin/scenario/practice/view/(:num)']                    = 'scenario/practice/practice_view/$1';
$route['admin/scenario/practice/marking_criteria/(:num)']        = 'scenario/practice/marking_criteria/$1';
$route['admin/scenario/practice/marking_criteria_update_action'] = 'scenario/practice/marking_criteria_update_action';
$route['admin/scenario/practice/topic/edit']                     = 'scenario/practice/practice_topic_items_edit';
$route['admin/scenario/practice/topic_update_action']            = 'scenario/practice/topic_update_action';
$route['admin/scenario/practice/topic_item_add_action']          = 'scenario/practice/topic_item_add_action';
$route['admin/scenario/practice/topic_create_action']            = 'scenario/practice/topic_create_action';
$route['admin/scenario/practice/get_topic_rename_modal_data']    = 'scenario/practice/get_topic_rename_modal_data';
$route['admin/scenario/practice/topic_rename_action']            = 'scenario/practice/topic_rename_action';
$route['admin/scenario/practice/topic_subject_action']           = 'scenario/practice/topic_subject_action';
$route['admin/scenario/practice/topic_subject_update_action']    = 'scenario/practice/topic_subject_update_action';
$route['admin/scenario/practice/topic_save_order']               = 'scenario/practice/topic_save_order';
$route['admin/scenario/practice/topic_items_save_order']         = 'scenario/practice/topic_items_save_order';
$route['admin/scenario/practice/topic_delete']                   = 'scenario/practice/topic_delete';
$route['admin/scenario/practice/topic_item_delete']              = 'scenario/practice/topic_item_delete';
$route['admin/scenario/practice/scenario_subjects_save_order']   = 'scenario/practice/scenario_subjects_save_order';
$route['admin/scenario/practice/scenario_subject_delete']        = 'scenario/practice/scenario_subject_delete';
