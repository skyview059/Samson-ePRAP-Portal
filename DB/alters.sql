ALTER TABLE `course_payments` CHANGE `admin_comments` `admin_comments` MEDIUMTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL;



SELECT id, fname,lname, `email` FROM `students` WHERE email in( 
    'ejemenj@gmail.com', 'melimam0@gmail.com', 'stephenkumi64@yahoo.co.uk', 'enasaly91@gmail.com', 'jacobkimanimd@gmail.com'
);



ALTER TABLE `students` ADD INDEX(`email`);
ALTER TABLE `students` ADD INDEX(`gmc_number`);


ALTER TABLE `students` ADD INDEX(`whatsapp_code`, `whatsapp`);
ALTER TABLE `students` ADD INDEX(`phone_code`, `phone`);



-- Attachment records created by uploadAttachment() / ck-image-upload
CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `path` varchar(100) DEFAULT NULL,
  `size` int(11) DEFAULT 0,
  `type` varchar(120) DEFAULT NULL,
  `uploaded_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
