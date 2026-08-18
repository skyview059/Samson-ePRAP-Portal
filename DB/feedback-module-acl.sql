-- Register the "feedback" module (SCA Feedback Domains & Statements manager)
-- and grant its permissions to the Developer role (role_id = 1).
-- Grant access to other roles via Admin > Module Manager > Access Control List.

INSERT INTO `modules` (`added_date`, `order`, `type`, `name`, `folder`, `description`, `status`)
VALUES (CURDATE(), 0, 'Module', 'SCA Feedback', 'feedback', 'SCA feedback domains & statements manager', 'Enable');

SET @module_id = LAST_INSERT_ID();

INSERT INTO `acls` (`module_id`, `permission_name`, `permission_key`, `order_id`) VALUES
(@module_id, 'Feedback', 'feedback', 0),
(@module_id, 'Feedback/domain save', 'feedback/domain_save', 0),
(@module_id, 'Feedback/domain delete', 'feedback/domain_delete', 0),
(@module_id, 'Feedback/statement save', 'feedback/statement_save', 0),
(@module_id, 'Feedback/statement delete', 'feedback/statement_delete', 0);

INSERT INTO `role_permissions` (`role_id`, `acl_id`, `access`)
SELECT 1, `id`, 1 FROM `acls` WHERE `module_id` = @module_id;
