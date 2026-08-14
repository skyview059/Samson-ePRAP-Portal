
--- Date: 13th Aug, 2026 ---


ALTER TABLE `result_details` 
  CHANGE `technical_skills` `technical_skills` DECIMAL(2.1) NULL DEFAULT NULL COMMENT 'Data-gathering, technical and assessment skills (Quantitative Feedback )', 
  CHANGE `clinical_skills` `clinical_skills` DECIMAL(2,1) NULL DEFAULT NULL COMMENT 'Clinical Management Skills (Quantitative Feedback )', 
  CHANGE `interpersonal_skills` `interpersonal_skills` DECIMAL(2,1) NULL DEFAULT NULL COMMENT 'Interpersonal Skills (Quantitative Feedback )';

ALTER TABLE `exams`
  ADD `exam_type` ENUM('PLAB Part 2','SCA') NOT NULL DEFAULT 'PLAB Part 2' AFTER `name`;

ALTER TABLE `result_details` 
  CHANGE `overall_judgment` `overall_judgment` ENUM('Fail','Borderline','Pass','Very Good','Excellent','Bare Pass','Bare Fail') NULL DEFAULT NULL;


-- SCA qualitative feedback: statements ticked by the assessor for one assessed station.
-- Requires DB/sca_feedback_statements.sql (sca_feedback_domains + sca_feedback_statements) to be imported first.
-- No FK on `statement_id` on purpose: DB/sca_feedback_statements.sql re-creates its tables with DROP TABLE,
-- which a foreign key would block.
CREATE TABLE IF NOT EXISTS `result_detail_feedback_statements` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `result_detail_id` INT(11) NOT NULL,
  `statement_id` INT UNSIGNED NOT NULL COMMENT 'sca_feedback_statements.id',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_result_detail_statement` (`result_detail_id`,`statement_id`),
  KEY `idx_statement_id` (`statement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



ALTER TABLE `result_details` 
  ADD `welcomes_patient` ENUM('Yes','No') NULL DEFAULT NULL COMMENT 'For SCA' AFTER `starts_station_well`, 
  ADD `starts_with_open_end` ENUM('Yes','No') NULL DEFAULT NULL COMMENT 'For SCA' AFTER `welcomes_patient`;
