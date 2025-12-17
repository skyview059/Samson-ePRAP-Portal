<?php defined('BASEPATH') or exit('No direct script access allowed');

// Student Portal
$route['profile']                       = 'student_portal/profile';
$route['profile/update']                = 'student_portal/profile_update';
$route['jobs']                          = 'student_portal/jobs';
$route['job-details/(:num)']            = 'student_portal/jobDetails/$1';
$route['job/apply/(:num)']              = 'student_portal/jobApply/$1';
$route['job/apply/action']              = 'student_portal/jobApplyAction';
$route['job-profile']                   = 'student_portal/job_profile';
$route['applied-jobs']                  = 'student_portal/applied_jobs';
$route['job-profile/update']            = 'student_portal/job_profile_update';
$route['exams']                         = 'student_portal/exams';
$route['change-password']               = 'student_portal/change_password';
$route['messages']                      = 'student_portal/messages';
$route['messages/open']                 = 'student_portal/message_new';
$route['messages/message_new_action']   = 'student_portal/message_new_action';
$route['messages/message_reply_action'] = 'student_portal/message_reply_action';
$route['message_view/(:num)']           = 'student_portal/message_view/$1';

$route['individual-learning-plan']  = 'student_portal/ind_learning_plan';
$route['personal-development-plan'] = 'student_portal/personal_dev_plan';


$route['results']                 = 'student_portal/results';
$route['results/(:num)']          = 'student_portal/result_view/$1';
$route['results/download/(:num)'] = 'student_portal/result_download/$1';

$route['understand']          = 'student_portal/understand';
$route['contact-us']          = 'student_portal/contact_us';
$route['whatsapp-group']      = 'student_portal/whatsapps';
$route['stage']               = 'student_portal/stage';
$route['upload_stage']        = 'student_portal/upload_stage';
$route['delete_stage/(:num)'] = 'student_portal/delete_stage/$1';
$route['docs']                = 'student_portal/docs';
$route['delete_doc/(:num)']   = 'student_portal/delete_doc/$1';

$route['booking']         = 'booking/index';
$route['booking/payment'] = 'booking/payment';

$route['practices'] = 'practices/index';

/*  Promo Codes */
$route['book-course']              = 'frontend/book_course';
$route['course-booking-action']    = 'booking_with_registration/index';
$route['purchase-practice-action'] = 'booking_with_registration/purchase_practice_action';
$route['course-booking-validate']  = 'booking_with_registration/process';
$route['check-promo-code']         = 'booking_with_registration/checkPromocode';
$route['guest-login']              = 'booking_with_registration/guestLogin';

$route['fp-webhook']       = 'frontend/firstpromoterWebhook';
$route['webhook-row-data'] = 'frontend/webhookData'; // this is for testing purpose


// Scenario Practice Routes
$route['scenario-practice']                                  = 'scenario_practice/index';
$route['scenario-practice/exam/explore/(:num)']              = 'scenario_practice/examExplore/$1';
$route['scenario-practice/exam/view/(:num)']                 = 'scenario_practice/exam/$1';
$route['scenario-practice/exam/practice/(:num)']             = 'scenario_practice/exam/$1';
$route['scenario-practice/exam/(:num)/item/(:num)']          = 'scenario_practice/itemDetails/$1/$2';
$route['scenario-practice/generate-practice-url']            = 'scenario_practice/generatePracticeURL';
$route['scenario-practice/change-practice-time']             = 'scenario_practice/changePracticeTime';
$route['scenario-practice/change-practice-roles']            = 'scenario_practice/changePracticeRoles';
$route['scenario-practice/exam/practice/(:num)/view/(:num)'] = 'scenario_practice/practiceDetails/$1/$2';
$route['scenario-practice/set-practice-status']              = 'scenario_practice/setPracticeStatus';
$route['scenario-practice/practice/summary/(:num)']          = 'scenario_practice/practiceSummaryDetails/$1';
$route['scenario-practice/set-status']                       = 'scenario_practice/setStatus';
$route['scenario-practice/review_action']                    = 'scenario_practice/review_action';


//$route['scenario-practice/exam/(:num)/summary/(:num)'] = 'scenario_practice/itemSummaryDetails/$1/$2';
//$route['scenario-practice/set-status-with-time']       = 'scenario_practice/setStatusWithTime';
//$route['scenario-practice/set-role-action']            = 'scenario_practice/setRoleAction';
//$route['scenario-practice/set-time-action']            = 'scenario_practice/setTimeAction';
//$route['scenario-practice/practice/(:num)']            = 'scenario_practice/practiceDetails/$1';


// Study Plan Routes
$route['study-plan']               = 'study_plan/index';
$route['study-plan/create']        = 'study_plan/create';
$route['study-plan/create_action'] = 'study_plan/create_action';
$route['study-plan/update/(:num)'] = 'study_plan/update/$1';
$route['study-plan/update_action'] = 'study_plan/update_action';
$route['study-plan/view/(:num)']   = 'study_plan/view/$1';
$route['study-plan/share/(:num)']  = 'study_plan/share/$1';
$route['study-plan/share_action']  = 'study_plan/share_action';
$route['study-plan/delete/(:num)'] = 'study_plan/delete/$1';

// Online Mock Routes
$route['mock']                           = 'mock/index';
$route['mock/exam-room/(:num)']          = 'mock/examRoom/$1';
$route['mock/exam-room/(:num)/practice'] = 'mock/examRoomPractice/$1';


// student-messages
$route['student-messages']                      = 'student_message/messages';
$route['student-messages/find-a-study-partner'] = 'student_message/find_a_study_partner';
$route['student-messages/students']             = 'student_message/students';
$route['student-messages/open']                 = 'student_message/message_new';
$route['student-messages/message_new_action']   = 'student_message/message_new_action';
$route['student-messages/message_reply_action'] = 'student_message/message_reply_action';
$route['student-messages/message_view/(:num)']  = 'student_message/message_view/$1';