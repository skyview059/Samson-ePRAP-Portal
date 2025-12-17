/**
 * Author:  Khairul Azam
 * Created: May 1, 2022
 */

ALTER TABLE `countries` ADD INDEX(`parent_id`);
ALTER TABLE `countries` ADD INDEX(`type`);
ALTER TABLE `countries` ADD INDEX(`status`);


ALTER TABLE `courses` ADD INDEX(`category_id`);
ALTER TABLE `courses` ADD INDEX(`status`);

ALTER TABLE `course_booked` ADD INDEX(`course_id`);

ALTER TABLE `students` ADD INDEX(`number_type`);
ALTER TABLE `students` ADD INDEX(`gmc_number`);
ALTER TABLE `students` ADD INDEX(`email`);
ALTER TABLE `students` ADD INDEX(`country_id`);
ALTER TABLE `students` ADD INDEX(`present_country_id`);

ALTER TABLE `students` ADD INDEX(`gender`);
ALTER TABLE `students` ADD INDEX(`status`);
ALTER TABLE `students` ADD INDEX(`verified`);


ALTER TABLE `student_job_profile` ADD INDEX(`student_id`);
ALTER TABLE `student_job_profile` ADD INDEX(`job_interested`);

ALTER TABLE `student_job_specialty_rel` ADD INDEX(`student_id`, `specialty_id`);


ALTER TABLE `student_logs` ADD INDEX(`student_id`);

ALTER TABLE `course_dates` ADD INDEX(`course_id`);

ALTER TABLE `scenario_relations` ADD INDEX(`exam_schedule_id`, `scenario_id`);
ALTER TABLE `scenario_to_assessors` ADD INDEX(`scenario_rel_id`, `assessor_id`);
ALTER TABLE `course_payments` ADD INDEX(`student_id`);
ALTER TABLE `ethnicities` ADD INDEX(`category`);


ALTER TABLE `exam_schedules` ADD INDEX(`exam_status`);

ALTER TABLE `files` ADD INDEX(`status`);

ALTER TABLE `whatsapp_link_relations` ADD INDEX(`rel_table`, `rel_id`);
