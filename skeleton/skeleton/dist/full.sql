SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for activities
-- ----------------------------
DROP TABLE IF EXISTS `activities`;
CREATE TABLE `activities` (
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
DROP TABLE IF EXISTS `entities`;
CREATE TABLE `entities` (
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
INSERT INTO `entities` VALUES ('1', '0', '0', 'user', 'owner', 'owner', 'english', '2', '1', '0', '1526871009', '0', '0');

-- ----------------------------
-- Table structure for groups
-- ----------------------------
DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for metadata
-- ----------------------------
DROP TABLE IF EXISTS `metadata`;
CREATE TABLE `metadata` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` bigint(20) unsigned NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for objects
-- ----------------------------
DROP TABLE IF EXISTS `objects`;
CREATE TABLE `objects` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
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
INSERT INTO `options` VALUES ('active_modules', 'a:0:{}', 'modules', 'text', NULL, '1');
INSERT INTO `options` VALUES ('admin_email', 'admin@example.com', 'email', 'email', NULL, '1');
INSERT INTO `options` VALUES ('alert_login_failed', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('alert_login_success', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('allow_multi_session', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('allow_oauth', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('allow_quick_login', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('allow_registration', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('allow_remember', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('allowed_types', 'gif|png|jpeg|jpg|pdf|doc|docx|xls|pptx|zip|mp4|txt', 'upload', 'text', NULL, '1');
INSERT INTO `options` VALUES ('contact_email', 'contact@example.com', 'email', 'email', NULL, '0');
INSERT INTO `options` VALUES ('date_format', 'd/m/Y', 'datetime', 'dropdown', 'a:5:{s:5:"Y-m-d";s:5:"Y-m-d";s:5:"d-m-Y";s:5:"d-m-Y";s:5:"d/m/Y";s:5:"d/m/Y";s:5:"Y/m/d";s:5:"Y/m/d";s:5:"m/d/Y";s:5:"m/d/Y";}', '1');
INSERT INTO `options` VALUES ('demo_mode', 'false', 'general', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('discord_auth', 'false', 'discord', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('email_activation', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('enable_profiler', 'false', 'general', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('facebook_app_id', '', 'facebook', 'text', NULL, '0');
INSERT INTO `options` VALUES ('facebook_auth', 'false', 'facebook', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('facebook_pixel_id', '', 'facebook', 'text', NULL, '0');
INSERT INTO `options` VALUES ('github_auth', 'false', 'github', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('google_analytics_id', '', 'google', 'text', NULL, '0');
INSERT INTO `options` VALUES ('google_auth', 'false', 'google', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('google_site_verification', '', 'google', 'text', NULL, '0');
INSERT INTO `options` VALUES ('google_tagmanager_id', '', 'google', 'text', NULL, '0');
INSERT INTO `options` VALUES ('image_watermark', 'false', 'upload', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('linkedin_auth', 'false', 'linkedin', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('login_fail_allowed_attempts', '4', 'users', 'number', NULL, '1');
INSERT INTO `options` VALUES ('login_fail_allowed_lockouts', '4', 'users', 'number', NULL, '1');
INSERT INTO `options` VALUES ('login_fail_enabled', 'true', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('login_fail_long_lockout', '6', 'users', 'number', NULL, '1');
INSERT INTO `options` VALUES ('login_fail_short_lockout', '20', 'users', 'number', NULL, '1');
INSERT INTO `options` VALUES ('login_type', 'both', 'users', 'dropdown', 'a:3:{s:4:\"both\";s:9:\"lang:both\";s:8:\"username\";s:13:\"lang:username\";s:5:\"email\";s:18:\"lang:email_address\";}', '1');
INSERT INTO `options` VALUES ('mail_protocol', 'mail', 'email', 'dropdown', 'a:3:{s:4:\"mail\";s:4:\"Mail\";s:4:\"smtp\";s:4:\"SMTP\";s:8:\"sendmail\";s:8:\"Sendmail\";}', '1');
INSERT INTO `options` VALUES ('manual_activation', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('max_height', '0', 'upload', 'number', NULL, '1');
INSERT INTO `options` VALUES ('max_size', '0', 'upload', 'number', NULL, '1');
INSERT INTO `options` VALUES ('max_width', '0', 'upload', 'number', NULL, '1');
INSERT INTO `options` VALUES ('min_height', '0', 'upload', 'number', NULL, '1');
INSERT INTO `options` VALUES ('min_width', '0', 'upload', 'number', NULL, '1');
INSERT INTO `options` VALUES ('offline_access_level', '30', 'general', 'number', NULL, '1');
INSERT INTO `options` VALUES ('per_page', '10', 'general', 'dropdown', 'a:4:{i:10;i:10;i:20;i:20;i:30;i:30;i:50;i:50;}', '1');
INSERT INTO `options` VALUES ('recaptcha_private_key', '', 'captcha', 'text', NULL, '0');
INSERT INTO `options` VALUES ('recaptcha_site_key', '', 'captcha', 'text', NULL, '0');
INSERT INTO `options` VALUES ('sendmail_path', '/usr/sbin/sendmail', 'email', 'text', NULL, '0');
INSERT INTO `options` VALUES ('server_email', 'noreply@example.com', 'email', 'email', NULL, '1');
INSERT INTO `options` VALUES ('site_author', 'Kader Bouyakoub', 'general', 'text', NULL, '0');
INSERT INTO `options` VALUES ('site_background_color', 'ffffff', 'manifest', 'text', NULL, '1');
INSERT INTO `options` VALUES ('site_description', 'The Power to Build, The Freedom to Create', 'general', 'text', NULL, '0');
INSERT INTO `options` VALUES ('site_favicon', '', 'general', 'text', NULL, '0');
INSERT INTO `options` VALUES ('site_keywords', 'codeigniter, skeleton, algeria, ianhub, kader', 'general', 'text', NULL, '0');
INSERT INTO `options` VALUES ('site_name', 'Skeleton', 'general', 'text', NULL, '1');
INSERT INTO `options` VALUES ('site_offline', 'false', 'general', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('site_short_name', 'CSK', 'manifest', 'text', NULL, '1');
INSERT INTO `options` VALUES ('site_theme_color', '134d78', 'manifest', 'text', NULL, '1');
INSERT INTO `options` VALUES ('smtp_crypto', 'none', 'email', 'dropdown', 'a:3:{s:4:\"none\";s:9:\"lang:none\";s:3:\"ssl\";s:3:\"SSL\";s:3:\"tls\";s:3:\"TLS\";}', '1');
INSERT INTO `options` VALUES ('smtp_host', '', 'email', 'text', NULL, '0');
INSERT INTO `options` VALUES ('smtp_pass', '', 'email', 'password', NULL, '0');
INSERT INTO `options` VALUES ('smtp_port', '', 'email', 'text', NULL, '0');
INSERT INTO `options` VALUES ('smtp_user', '', 'email', 'text', NULL, '0');
INSERT INTO `options` VALUES ('theme', 'default', 'theme', 'text', NULL, '1');
INSERT INTO `options` VALUES ('time_format', 'H:i', 'datetime', 'dropdown', 'a:3:{s:5:"g:i a";s:5:"g:i a";s:5:"g:i A";s:5:"g:i A";s:3:"H:i";s:3:"H:i";}', '1');
INSERT INTO `options` VALUES ('time_reference', 'UTC', 'datetime', 'dropdown', '', '1');
INSERT INTO `options` VALUES ('upload_year_month', 'true', 'upload', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('uploads_path', 'content/uploads', 'upload', 'text', NULL, '0');
INSERT INTO `options` VALUES ('use_captcha', 'false', 'captcha', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('use_gravatar', 'false', 'users', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('use_manifest', 'false', 'manifest', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');
INSERT INTO `options` VALUES ('use_recaptcha', 'false', 'captcha', 'dropdown', 'a:2:{s:4:\"true\";s:8:\"lang:yes\";s:5:\"false\";s:7:\"lang:no\";}', '1');

-- ----------------------------
-- Table structure for relations
-- ----------------------------
DROP TABLE IF EXISTS `relations`;
CREATE TABLE `relations` (
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
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens`  (
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
DROP TABLE IF EXISTS `translations`;
CREATE TABLE `translations` (
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
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
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
INSERT INTO `users` VALUES ("1", 'admin@example.com', '$2a$08$MnCsgx3mmz3khvfFfDaEi.BaVS.gXVY/aQ1TmYEJVSAxm3cvfgtQO', 'Admin', 'Skeleton', 'unspecified', 'UP1', '0', '0', '0', '127.0.0.1', '');

-- ----------------------------
-- Table structure for variables
-- ----------------------------
DROP TABLE IF EXISTS `variables`;
CREATE TABLE `variables` (
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
ALTER TABLE `activities` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `entities` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `metadata` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `objects` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `options` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `relations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `sessions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `tokens` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `translations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
ALTER TABLE `variables` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;

-- delete entity trigger
DROP TRIGGER IF EXISTS after_entity_delete;

-- activities
CREATE INDEX idx_activities_user_id ON `activities` (`user_id`);
CREATE INDEX idx_activities_module ON `activities` (`module`);
CREATE INDEX idx_activities_controller ON `activities` (`controller`);
CREATE INDEX idx_activities_method ON `activities` (`method`);
CREATE INDEX idx_activities_user_module_controller_method ON `activities` (`user_id`, `module`, `controller`, `method`);

-- entities
CREATE INDEX idx_entities_parent_id ON `entities` (`parent_id`);
CREATE INDEX idx_entities_owner_id ON `entities` (`owner_id`);
CREATE INDEX idx_entities_type ON `entities` (`type`);
CREATE INDEX idx_entities_subtype ON `entities` (`subtype`);
CREATE UNIQUE INDEX idx_entities_username ON `entities` (`username`);
CREATE INDEX idx_entities_enabled ON `entities` (`enabled`);
CREATE INDEX idx_entities_deleted ON `entities` (`deleted`);
CREATE INDEX idx_entities_type_subtype ON `entities` (`type`, `subtype`);

-- groups
CREATE INDEX idx_groups_name ON `groups` (`name`);

-- metadata
CREATE INDEX idx_metadata_name ON `metadata` (`name`);
CREATE UNIQUE INDEX idx_metadata_unique_guid_name ON `metadata` (`guid`, `name`);

-- objects
CREATE INDEX idx_objects_name ON `objects` (`name`);

-- options
CREATE UNIQUE INDEX idx_options_name ON `options` (`name`);
CREATE INDEX idx_options_tab ON `options` (`tab`);

-- relations
CREATE INDEX idx_relations_guid_from ON `relations` (`guid_from`);
CREATE INDEX idx_relations_guid_to ON `relations` (`guid_to`);
CREATE INDEX idx_relations_relation ON `relations` (`relation`);
CREATE UNIQUE INDEX idx_relations_unique_from_to_relation ON `relations` (`guid_from`, `guid_to`, `relation`);

-- sessions
CREATE INDEX idx_sessions_timestamp ON `sessions` (`timestamp`);

-- tokens
CREATE INDEX idx_user_id ON `tokens` (`user_id`);
CREATE INDEX idx_access_token ON `tokens` (`access_token`);
CREATE INDEX idx_refresh_token ON `tokens` (`refresh_token`);
CREATE INDEX idx_revoked ON `tokens` (`revoked`);

-- translations
CREATE UNIQUE INDEX idx_translations_unique_guid_idiom_name ON `translations` (`guid`, `idiom`, `name`);

-- users
CREATE UNIQUE INDEX idx_users_email ON `users` (`email`);
CREATE INDEX idx_users_first_name ON `users` (`first_name`);
CREATE INDEX idx_users_last_name ON `users` (`last_name`);
CREATE INDEX idx_users_gender ON `users` (`gender`);
CREATE INDEX idx_users_online ON `users` (`online`);
CREATE INDEX idx_users_check_online_at ON `users` (`check_online_at`);

-- variables
CREATE INDEX idx_variables_name ON `variables` (`name`);
CREATE UNIQUE INDEX idx_variables_unique_guid_name ON `variables` (`guid`, `name`);
