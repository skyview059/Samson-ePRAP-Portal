<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['admin/assess/result']                       = 'assess/result';
$route['admin/assess/result/details/(:num)/(:num)'] = 'assess/result/details/$1/$2';
$route['admin/assess/result/generate']              = 'assess/result/generate';
$route['admin/assess/result/scenario_list']         = 'assess/result/scenario_list';
$route['admin/assess/result/scenario']              = 'assess/result/scenario';
$route['admin/assess/result/min_pass_score']        = 'assess/result/min_pass_score';
$route['admin/assess/result/center_list_by_exam']   = 'assess/result/center_list_by_exam';
$route['admin/assess/result/view_scores']           = 'assess/result/view_scores';
$route['admin/assess/result/generate_pass_mark']    = 'assess/result/generate_pass_mark';
$route['admin/assess/result/overall_student_results'] = 'assess/result/overall_student_results';
$route['admin/assess/result/exam_schedule_by_exam']   = 'assess/result/exam_schedule_by_exam';
$route['admin/assess/result/download/(:num)/(:num)']  = 'assess/result/download_pdf/$1/$2';

$route['admin/assess/preparation']                  = 'assess/preparation';
$route['admin/assess/preparation/details/(:num)']   = 'assess/preparation/details/$1';

$route['admin/assess']                  = 'assess';
$route['admin/assess/search_student']   = 'assess/search_student';
$route['admin/assess/initial_approach/(:num)'] = 'assess/initial_approach/$1';
$route['admin/assess/initial_approach_action'] = 'assess/initial_approach_action';
$route['admin/assess/face/(:num)'] = 'assess/face/$1';
$route['admin/assess/face_action'] = 'assess/face_action';
$route['admin/assess/quantitative_feedback/(:num)'] = 'assess/quantitative_feedback/$1';
$route['admin/assess/quantitative_feedback_action'] = 'assess/quantitative_feedback_action';
$route['admin/assess/qualitative_feedback/(:num)'] = 'assess/qualitative_feedback/$1';
$route['admin/assess/qualitative_feedback_action'] = 'assess/qualitative_feedback_action';
$route['admin/assess/overall_judgment/(:num)'] = 'assess/overall_judgment/$1';
$route['admin/assess/overall_judgment_action'] = 'assess/overall_judgment_action';
$route['admin/assess/comment/(:num)'] = 'assess/comment/$1';
$route['admin/assess/comment_action'] = 'assess/comment_action';
$route['admin/assess/review/(:num)'] = 'assess/review/$1';
$route['admin/assess/review_action'] = 'assess/review_action';
$route['admin/assess/finished'] = 'assess/finished';
$route['admin/assess/exam_is_over'] = 'assess/exam_is_over';
$route['admin/assess/student_list'] = 'assess/student_list';
$route['admin/assess/review_assement'] = 'assess/review_assement';
