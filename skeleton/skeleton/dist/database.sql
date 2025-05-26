SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activities
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}activities`;
CREATE TABLE `{DBPREFIX}activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `module` varchar(50) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for entities
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}entities`;
CREATE TABLE `{DBPREFIX}entities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `owner_id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `type` enum('user','group','object') NOT NULL,
  `subtype` varchar(50) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `privacy` tinyint(1) NOT NULL DEFAULT 2,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` int(11) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(11) unsigned NOT NULL DEFAULT 0,
  `deleted_at` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of entities
-- ----------------------------
INSERT INTO `{DBPREFIX}entities` VALUES ('1', '0', '0', 'user', 'owner', '{USERNAME}', 'english', '2', '1', '0', '{TIME}', '0', '0');

-- ----------------------------
-- Entities Delete Trigger
-- ----------------------------
DROP TRIGGER IF EXISTS after_entity_delete;

DELIMITER $$

DROP TRIGGER IF EXISTS `after_entity_delete`$$
CREATE TRIGGER `after_entity_delete`
AFTER DELETE ON `{DBPREFIX}entities`
FOR EACH ROW
BEGIN
  UPDATE `{DBPREFIX}entities`
  SET
    `parent_id` = IF(`parent_id` = OLD.`id`, 0, `parent_id`),
    `owner_id` = IF(`owner_id` = OLD.`id`, 0, `owner_id`)
  WHERE `parent_id` = OLD.`id` OR `owner_id` = OLD.`id`;
END$$

DELIMITER ;

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}groups`;
CREATE TABLE `{DBPREFIX}groups` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for metadata
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}metadata`;
CREATE TABLE `{DBPREFIX}metadata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` bigint(20) unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for objects
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}objects`;
CREATE TABLE `{DBPREFIX}objects` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}options`;
CREATE TABLE `{DBPREFIX}options` (
  `name` varchar(200) NOT NULL,
  `value` longtext NOT NULL,
  `tab` varchar(50) NULL DEFAULT NULL,
  `field_type` varchar(50) NULL DEFAULT 'text',
  `options` varchar(255) NULL DEFAULT NULL,
  `required` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `{DBPREFIX}options` VALUES ('active_modules', 'a:0:{}', 'modules', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('admin_email', 'admin@example.com', 'email', 'email', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('alert_login_failed', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('alert_login_success', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('allow_multi_session', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('allow_oauth', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('allow_quick_login', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('allow_registration', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('allow_remember', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('allowed_types', 'gif|png|jpeg|jpg|pdf|doc|docx|xls|pptx|zip|mp4|txt', 'upload', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('contact_email', 'contact@example.com', 'email', 'email', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('date_format', 'd/m/Y', 'datetime', 'dropdown', 'a:5:{s:5:"Y-m-d";s:5:"Y-m-d";s:5:"d-m-Y";s:5:"d-m-Y";s:5:"d/m/Y";s:5:"d/m/Y";s:5:"Y/m/d";s:5:"Y/m/d";s:5:"m/d/Y";s:5:"m/d/Y";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('demo_mode', 'false', 'general', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('discord_auth', 'false', 'discord', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('email_activation', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('enable_profiler', 'false', 'general', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('facebook_app_id', '', 'facebook', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('facebook_auth', 'false', 'facebook', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('facebook_pixel_id', '', 'facebook', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('github_auth', 'false', 'github', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('google_analytics_id', '', 'google', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('google_auth', 'false', 'google', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('google_site_verification', '', 'google', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('google_tagmanager_id', '', 'google', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('image_watermark', 'false', 'upload', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('linkedin_auth', 'false', 'linkedin', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('login_fail_allowed_attempts', '4', 'users', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('login_fail_allowed_lockouts', '4', 'users', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('login_fail_enabled', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('login_fail_long_lockout', '6', 'users', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('login_fail_short_lockout', '20', 'users', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('login_type', 'both', 'users', 'dropdown', 'a:3:{s:4:\"both\";s:9:\"lang:both\";s:8:\"username\";s:13:\"lang:username\";s:5:\"email\";s:18:\"lang:email_address\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('mail_protocol', 'mail', 'email', 'dropdown', 'a:3:{s:4:\"mail\";s:4:\"Mail\";s:4:\"smtp\";s:4:\"SMTP\";s:8:\"sendmail\";s:8:\"Sendmail\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('manual_activation', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('max_height', '0', 'upload', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('max_size', '0', 'upload', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('max_width', '0', 'upload', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('min_height', '0', 'upload', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('min_width', '0', 'upload', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('offline_access_level', '30', 'general', 'number', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('per_page', '10', 'general', 'dropdown', 'a:4:{i:10;i:10;i:20;i:20;i:30;i:30;i:50;i:50;}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('recaptcha_private_key', '', 'captcha', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('recaptcha_site_key', '', 'captcha', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('sendmail_path', '/usr/sbin/sendmail', 'email', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('server_email', 'noreply@example.com', 'email', 'email', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('site_author', 'Kader Bouyakoub', 'general', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('site_background_color', 'ffffff', 'manifest', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('site_description', 'The Power to Build, The Freedom to Create', 'general', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('site_favicon', '', 'general', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('site_keywords', 'codeigniter, skeleton, algeria, ianhub, kader', 'general', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('site_name', 'Skeleton', 'general', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('site_offline', 'false', 'general', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('site_short_name', 'CSK', 'manifest', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('site_theme_color', '134d78', 'manifest', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('smtp_crypto', 'none', 'email', 'dropdown', 'a:3:{s:4:\"none\";s:9:\"lang:none\";s:3:\"ssl\";s:3:\"SSL\";s:3:\"tls\";s:3:\"TLS\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('smtp_host', '', 'email', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('smtp_pass', '', 'email', 'password', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('smtp_port', '', 'email', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('smtp_user', '', 'email', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('theme', 'default', 'theme', 'text', NULL, '1');
INSERT INTO `{DBPREFIX}options` VALUES ('time_format', 'H:i', 'datetime', 'dropdown', 'a:3:{s:5:"g:i a";s:5:"g:i a";s:5:"g:i A";s:5:"g:i A";s:3:"H:i";s:3:"H:i";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('time_reference', 'UTC', 'datetime', 'dropdown', '', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('upload_year_month', 'true', 'upload', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('uploads_path', 'content/uploads', 'upload', 'text', NULL, '0');
INSERT INTO `{DBPREFIX}options` VALUES ('use_captcha', 'false', 'captcha', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('use_gravatar', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('use_manifest', 'false', 'manifest', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `{DBPREFIX}options` VALUES ('use_recaptcha', 'false', 'captcha', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');

-- ----------------------------
-- Table structure for relations
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}relations`;
CREATE TABLE `{DBPREFIX}relations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid_from` bigint(20) unsigned NOT NULL DEFAULT 0,
  `guid_to` bigint(20) unsigned NOT NULL DEFAULT 0,
  `relation` varchar(255) NOT NULL,
  `params` longtext DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}sessions`;
CREATE TABLE `{DBPREFIX}sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}tokens`;
CREATE TABLE `{DBPREFIX}tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT 0,
  `access_token` varchar(255) NOT NULL,
  `access_expires_at` int UNSIGNED NOT NULL DEFAULT 0,
  `refresh_token` varchar(255) NOT NULL,
  `refresh_expires_at` int UNSIGNED NOT NULL DEFAULT 0,
  `user_agent` text NULL,
  `ip_address` varchar(100) NULL DEFAULT NULL,
  `revoked` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `revoked_at` int UNSIGNED NOT NULL DEFAULT 0,
  `last_used_at` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` int UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` int UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for translations
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}translations`;
CREATE TABLE `{DBPREFIX}translations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` bigint(20) unsigned NOT NULL DEFAULT 0,
  `idiom` varchar(50) NOT NULL DEFAULT 'english',
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}users`;
CREATE TABLE `{DBPREFIX}users` (
  `guid` bigint(20) unsigned NOT NULL DEFAULT 0,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('unspecified','male','female') NOT NULL DEFAULT 'unspecified',
  `timezone` varchar(50) DEFAULT 'UTC',
  `online` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `online_at` int(11) NOT NULL DEFAULT 0,
  `check_online_at` int(11) NOT NULL DEFAULT 0,
  `ip_address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `{DBPREFIX}users` VALUES ("1", '{EMAIL}', '{PASSWORD}', '{FIRSTNAME}', '{LASTNAME}', 'UTC', 'unspecified', '0', '0', '0', '{IP_ADDRESS}', '');

-- ----------------------------
-- Table structure for variables
-- ----------------------------
DROP TABLE IF EXISTS `{DBPREFIX}variables`;
CREATE TABLE `{DBPREFIX}variables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` bigint(20) unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `params` longtext DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Indexed Table Columns
-- ----------------------------

-- Convert table default
ALTER TABLE `{DBPREFIX}activities` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}entities` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}metadata` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}objects` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}options` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}relations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}sessions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}tokens` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}translations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `{DBPREFIX}variables` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;

-- delete entity trigger
DROP TRIGGER IF EXISTS after_entity_delete;

-- activities
CREATE INDEX idx_activities_user_id ON `{DBPREFIX}activities` (`user_id`);
CREATE INDEX idx_activities_module ON `{DBPREFIX}activities` (`module`);
CREATE INDEX idx_activities_controller ON `{DBPREFIX}activities` (`controller`);
CREATE INDEX idx_activities_method ON `{DBPREFIX}activities` (`method`);
CREATE INDEX idx_activities_user_module_controller_method ON `{DBPREFIX}activities` (`user_id`, `module`, `controller`, `method`);

-- entities
CREATE INDEX idx_entities_parent_id ON `{DBPREFIX}entities` (`parent_id`);
CREATE INDEX idx_entities_owner_id ON `{DBPREFIX}entities` (`owner_id`);
CREATE INDEX idx_entities_type ON `{DBPREFIX}entities` (`type`);
CREATE INDEX idx_entities_subtype ON `{DBPREFIX}entities` (`subtype`);
CREATE UNIQUE INDEX idx_entities_username ON `{DBPREFIX}entities` (`username`);
CREATE INDEX idx_entities_enabled ON `{DBPREFIX}entities` (`enabled`);
CREATE INDEX idx_entities_deleted ON `{DBPREFIX}entities` (`deleted`);
CREATE INDEX idx_entities_type_subtype ON `{DBPREFIX}entities` (`type`, `subtype`);

-- groups
CREATE INDEX idx_groups_name ON `{DBPREFIX}groups` (`name`);

-- metadata
CREATE INDEX idx_metadata_name ON `{DBPREFIX}metadata` (`name`);
CREATE UNIQUE INDEX idx_metadata_unique_guid_name ON `{DBPREFIX}metadata` (`guid`, `name`);

-- objects
CREATE INDEX idx_objects_name ON `{DBPREFIX}objects` (`name`);

-- options
CREATE UNIQUE INDEX idx_options_name ON `{DBPREFIX}options` (`name`);
CREATE INDEX idx_options_tab ON `{DBPREFIX}options` (`tab`);

-- relations
CREATE INDEX idx_relations_guid_from ON `{DBPREFIX}relations` (`guid_from`);
CREATE INDEX idx_relations_guid_to ON `{DBPREFIX}relations` (`guid_to`);
CREATE INDEX idx_relations_relation ON `{DBPREFIX}relations` (`relation`);
CREATE UNIQUE INDEX idx_relations_unique_from_to_relation ON `{DBPREFIX}relations` (`guid_from`, `guid_to`, `relation`);

-- sessions
CREATE INDEX idx_sessions_timestamp ON `{DBPREFIX}sessions` (`timestamp`);

-- tokens
CREATE INDEX idx_user_id ON `{DBPREFIX}tokens` (`user_id`);
CREATE INDEX idx_access_token ON `{DBPREFIX}tokens` (`access_token`);
CREATE INDEX idx_refresh_token ON `{DBPREFIX}tokens` (`refresh_token`);
CREATE INDEX idx_revoked ON `{DBPREFIX}tokens` (`revoked`);

-- translations
CREATE UNIQUE INDEX idx_translations_unique_guid_idiom_name ON `{DBPREFIX}translations` (`guid`, `idiom`, `name`);

-- users
CREATE UNIQUE INDEX idx_users_email ON `{DBPREFIX}users` (`email`);
CREATE INDEX idx_users_first_name ON `{DBPREFIX}users` (`first_name`);
CREATE INDEX idx_users_last_name ON `{DBPREFIX}users` (`last_name`);
CREATE INDEX idx_users_gender ON `{DBPREFIX}users` (`gender`);
CREATE INDEX idx_users_online ON `{DBPREFIX}users` (`online`);
CREATE INDEX idx_users_check_online_at ON `{DBPREFIX}users` (`check_online_at`);

-- variables
CREATE INDEX idx_variables_name ON `{DBPREFIX}variables` (`name`);
CREATE UNIQUE INDEX idx_variables_unique_guid_name ON `{DBPREFIX}variables` (`guid`, `name`);
