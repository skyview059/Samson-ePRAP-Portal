ALTER TABLE `course_payments` CHANGE `admin_comments` `admin_comments` MEDIUMTEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL;



SELECT id, fname,lname, `email` FROM `students` WHERE email in( 
    'ejemenj@gmail.com', 'melimam0@gmail.com', 'stephenkumi64@yahoo.co.uk', 'enasaly91@gmail.com', 'jacobkimanimd@gmail.com'
);