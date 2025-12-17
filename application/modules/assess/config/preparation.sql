/**
 * Author:  Khairul Azam
 * Created: June 7, 2020
 * Initial Setup of this module
 */

INSERT INTO `modules` (`id`, `added_date`, `order`, `type`, `name`, `folder`, `description`, `status`) VALUES
(20, '2020-06-07', 1, 'Module', 'Exam Preparation', 'exam_preparation', '', 'Locked');

INSERT INTO `acls` (`id`, `module_id`, `permission_name`, `permission_key`, `order_id`) VALUES
(778, 20, 'Exam Preparation Details', 'exam_preparation/details', 0),
(774, 20, 'Exam Preparation', 'exam_preparation', 0);

INSERT INTO `role_permissions` (`id`, `role_id`, `acl_id`, `access`) VALUES
(13271, 1, 778, 1),
(13267, 1, 774, 1);

DELETE FROM `role_permissions` WHERE acl_id NOT IN (SELECT id FROM acls);
COMMIT;