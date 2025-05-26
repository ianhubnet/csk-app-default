<?php
defined('BASEPATH') OR die;

/**
 * ------------------------------------------------------------------------
 * CI Skeleton Admin Language File
 * ------------------------------------------------------------------------
 * This file contains all language lines used in the CSK admin dashboard.
 * Each section is separated by comments for easier navigation and maintenance.
 */

/**
 * ------------------------------------------------------------------------
 * Core Dashboard Section
 * ------------------------------------------------------------------------
 * General terms and messages used across the admin dashboard.
 */
$lang['admin_components']      = 'Components';
$lang['admin_content']         = 'Content';
$lang['admin_database_backup'] = 'Database Backups';
$lang['admin_extensions']      = 'Extensions';
$lang['admin_footer_left']     = 'Thank you for creating with <a href="%s" target="_blank">%s</a>.';
$lang['admin_footer_right']    = 'Version: <strong>%s</strong>';
$lang['admin_logs']            = 'System Logs';
$lang['admin_media']           = 'Media Library';
$lang['admin_modules']         = 'Modules';
$lang['admin_reports']         = 'Actions Log';
$lang['admin_system']          = 'System';
$lang['admin_themes']          = 'Themes';
$lang['admin_update_notice']   = '<a href="%2$s" target="_blank">Version <strong>%1$s<strong> is now available!<a>';
$lang['admin_users']           = 'Users';
$lang['admin_view_site']       = 'View Site';
$lang['settings_global']       = 'Global Settings';
$lang['settings_sysinfo']      = 'System Information';

/**
 * ---------------------------------------------------------------
 * Database & Backup Section
 * ---------------------------------------------------------------
 * Language lines for the database management section.
 */
$lang['admin_database_backup_clean_error']    = 'Unable to clean old backup files.';
$lang['admin_database_backup_clean_success']  = '%d backup files deleted. %d disk space freed.';
$lang['admin_database_backup_create']         = 'Create Backup';
$lang['admin_database_backup_create_confirm'] = 'Are you sure you want to create a backup now?';
$lang['admin_database_backup_create_error']   = 'Unable to create backup file. Make sure the folder <strong>%s</strong> is writable.';
$lang['admin_database_backup_create_success'] = 'Database backup file <strong>%s</strong> successfully created.';
$lang['admin_database_backup_delete_confirm'] = 'Are you sure you want to delete these backup files?';
$lang['admin_database_backup_delete_error']   = 'Unable to delete selected backup files.';
$lang['admin_database_backup_delete_success'] = 'Backup files were successfully deleted.';
$lang['admin_database_backup_lock_confirm']   = 'Are you sure you want to lock these backup files?';
$lang['admin_database_backup_lock_error']     = 'Unable to lock selected backup files.';
$lang['admin_database_backup_lock_success']   = 'Backup files successfully locked.';
$lang['admin_database_backup_locked_error']   = 'Unable to delete locked backup files.';
$lang['admin_database_backup_missing_error']  = 'The backup file could not be found.';
$lang['admin_database_backup_unlock_confirm'] = 'Are you sure you want to unlock these backup files?';
$lang['admin_database_backup_unlock_error']   = 'Unable to unlock selected backup files.';
$lang['admin_database_backup_unlock_success'] = 'Backup files successfully unlocked.';
$lang['admin_database_prune']                 = 'Prune';
$lang['admin_database_prune_confirm']         = 'Are you sure you want to prune the database? A backup will be created execution.';
$lang['admin_database_prune_error']           = 'Unable to prune database.';
$lang['admin_database_prune_next']            = 'Next prune: <strong>%s</strong>';
$lang['admin_database_prune_success']         = 'Database pruned successfully.';

/**
 * ---------------------------------------------------------------
 * System Logs Section
 * ---------------------------------------------------------------
 * Language lines for the system logs section.
 */
$lang['admin_logs_delete']         = 'Delete Logs';
$lang['admin_logs_delete_confirm'] = 'Are you sure you want to delete selected log files?';
$lang['admin_logs_delete_error']   = 'Unable to delete log files.';
$lang['admin_logs_delete_success'] = 'Log files successfully deleted.';
$lang['admin_logs_error_disabled'] = 'Logging is not currently enabled.';
$lang['admin_logs_error_empty']    = 'No logs found.';
$lang['admin_logs_error_missing']  = 'Either the log file could not be located, or it was empty.';
$lang['admin_logs_tip']            = 'Logging can rapidly create very large files. For live sites, think of deleting old ones.';

/**
 * ---------------------------------------------------------------
 * System Settings Section
 * ---------------------------------------------------------------
 * Language lines for the admin dashboard settings section.
 */

// Page Titles
$lang['settings_captcha']  = 'Captcha Settings';
$lang['settings_datetime'] = 'Date & Time Settings';
$lang['settings_discord']  = 'Discord Settings';
$lang['settings_email']    = 'Email Settings';
$lang['settings_facebook'] = 'Facebook Settings';
$lang['settings_github']   = 'GitHub Settings';
$lang['settings_google']   = 'Google Settings';
$lang['settings_linkedin'] = 'LinkedIn Settings';
$lang['settings_manifest'] = 'Manifest Settings';
$lang['settings_upload']   = 'Uploads Settings';
$lang['settings_users']    = 'Users Settings';

// Settings Fields & Help Texts
$lang['admin_email']                     = 'Admin Email';
$lang['admin_email_tip']                 = 'The email address to which site notices are sent.';
$lang['alert_login_failed_tip']          = 'Alert users about any failed login attempts to their account.';
$lang['alert_login_success_tip']         = 'Notify users about any successful login to their account.';
$lang['allow_multi_session']             = 'Allow multiple sessions.';
$lang['allow_multi_session_tip']         = 'Whether to allow multiple users to login to the same account at the same time.';
$lang['allow_oauth']                     = 'Third-party Login';
$lang['allow_oauth_tip']                 = 'Allow users to login with third-party providers.';
$lang['allow_quick_login']               = 'Allow One-Click Login';
$lang['allow_quick_login_tip']           = 'Allow users to log in using a secure one-time email link that expires in 15 minutes.';
$lang['allow_registration']              = 'Allow Registration';
$lang['allow_registration_tip']          = 'Whether to allow users to create accounts on your site.';
$lang['allow_remember']                  = 'Enable Login Cookies';
$lang['allow_remember_tip']              = 'Allow the browser to save a cookie so that users remain logged in even if they close the site window.';
$lang['allowed_types']                   = 'Allowed Files';
$lang['allowed_types_tip']               = 'List of files that are allowed to be uploaded. Use "&#124;" to separate between types.';
$lang['base_controller']                 = 'Base Controller';
$lang['base_controller_tip']             = 'The controller used as your default homepage.';
$lang['contact_email']                   = 'Contact Email';
$lang['contact_email_tip']               = 'The email address to which contact form messages are sent.';
$lang['date_format']                     = 'Date Format';
$lang['date_format_tip']                 = 'Choose the date format you want to use.';
$lang['demo_mode']                       = 'Demo Mode';
$lang['demo_mode_tip']                   = 'Choose whether to put the site on demo-mode.';
$lang['discord_auth']                    = 'Login with Discord';
$lang['discord_auth_tip']                = 'Allow users to login with <a href="https://discord.com/developers" target="_blank">Discord</a>.';
$lang['discord_client_id']               = 'Client ID';
$lang['discord_client_id_tip']           = 'Enter your Discord application client ID.';
$lang['discord_client_secret']           = 'Client Secret';
$lang['discord_client_secret_tip']       = 'Enter your Discord application secret key.';
$lang['email_activation']                = 'Email Activation';
$lang['email_activation_tip']            = 'Whether to force users to verify their email addresses before being allowed to log in.';
$lang['enable_profiler']                 = 'Enable Profiler';
$lang['enable_profiler_tip']             = 'Display benchmark results, queries you have run, and various other info.';
$lang['facebook_app_id']                 = 'Facebook App ID';
$lang['facebook_app_id_tip']             = 'Enter your Facebook application ID.';
$lang['facebook_app_secret']             = 'Client Secret';
$lang['facebook_app_secret_tip']         = 'Enter your Facebook application secret key.';
$lang['facebook_auth']                   = 'Login with Facebook';
$lang['facebook_auth_tip']               = 'Allow users to login with <a href="https://developers.facebook.com/apps/" target="_blank">Facebook</a>.';
$lang['facebook_pixel_id']               = 'Facebook Pixel ID';
$lang['facebook_pixel_id_tip']           = 'Your Facebook Pixel ID. Used to track visits and conversions. You can find it in Events Manager under your pixel\'s settings.';
$lang['github_auth']                     = 'Login with GitHub';
$lang['github_auth_tip']                 = 'Allow users to login with <a href="https://github.com/" target="_blank">GitHub</a>.';
$lang['github_client_id']                = 'Client ID';
$lang['github_client_id_tip']            = 'Enter your GitHub application client ID.';
$lang['github_client_secret']            = 'Client Secret';
$lang['github_client_secret_tip']        = 'Enter your GitHub application secret key.';
$lang['google_analytics_id']             = 'Google Analytics ID';
$lang['google_analytics_id_tip']         = 'Enter your Google Analytics Tracking ID.';
$lang['google_auth']                     = 'Login with Google';
$lang['google_auth_tip']                 = 'Allow users to login with <a href="https://console.cloud.google.com/" target="_blank">Google</a>.';
$lang['google_client_id']                = 'Client ID';
$lang['google_client_id_tip']            = 'Enter your Google application client ID.';
$lang['google_client_secret']            = 'Client Secret';
$lang['google_client_secret_tip']        = 'Enter your Google application secret key.';
$lang['google_site_verification']        = 'Google Site Verification';
$lang['google_site_verification_tip']    = 'Enter your Google site verification code.';
$lang['google_tagmanager_id']            = 'Google Tag Manager ID';
$lang['google_tagmanager_id_tip']        = 'Enter the container ID. Leave empty if you want to use Google Analytics instead.';
$lang['image_watermark']                 = 'Watermark Images';
$lang['image_watermark_tip']             = 'Enable to automatically add a watermark to all uploaded images.';
$lang['imgur_client_id']                 = 'Client ID';
$lang['imgur_client_id_tip']             = 'Enter your Imgur application client ID.';
$lang['imgur_client_secret']             = 'Client Secret';
$lang['imgur_client_secret_tip']         = 'Enter your Imgur application secret key.';
$lang['linkedin_auth']                   = 'Login with LinkedIn';
$lang['linkedin_auth_tip']               = 'Allow users to login with <a href="https://developer.linkedin.com/" target="_blank">LinkedIn</a>.';
$lang['linkedin_client_id']              = 'Client ID';
$lang['linkedin_client_id_tip']          = 'Enter your LinkedIn application client ID.';
$lang['linkedin_client_secret']          = 'Client Secret';
$lang['linkedin_client_secret_tip']      = 'Enter your LinkedIn application secret key.';
$lang['login_fail_allowed_attempts']     = 'Allowed login attempts';
$lang['login_fail_allowed_attempts_tip'] = 'Number of failed attempts before short lockout.';
$lang['login_fail_allowed_lockouts']     = 'Allowed login lockouts';
$lang['login_fail_allowed_lockouts_tip'] = 'Number of short lockouts before long lockout applies.';
$lang['login_fail_enabled']              = 'Enable failed login lock';
$lang['login_fail_enabled_tip']          = 'Enable or disable failed login protection.';
$lang['login_fail_long_lockout']         = 'Long login lockout';
$lang['login_fail_long_lockout_tip']     = 'Lockout time in hours after multiple short lockouts.';
$lang['login_fail_short_lockout']        = 'Short login lockout';
$lang['login_fail_short_lockout_tip']    = 'Lockout time in minutes after too many failed attempts.';
$lang['login_type']                      = 'Login Type';
$lang['login_type_tip']                  = 'Users may log in using usernames, email addresses, or both.';
$lang['mail_protocol']                   = 'Mail Protocol';
$lang['mail_protocol_tip']               = 'Choose the mail protocol you want to send emails with.';
$lang['manual_activation']               = 'Manual Activation';
$lang['manual_activation_tip']           = 'Whether to manually verify user accounts.';
$lang['max_height']                      = 'Max Height';
$lang['max_height_tip']                  = 'The maximum height in pixels. Set to zero for no limit.';
$lang['max_size']                        = 'Max File Size';
$lang['max_size_tip']                    = 'The maximum size (in kilobytes) that the file can be. Set to zero for no limit.';
$lang['max_width']                       = 'Max width';
$lang['max_width_tip']                   = 'The maximum width in pixels. Set to zero for no limit.';
$lang['min_height']                      = 'Min Height';
$lang['min_height_tip']                  = 'The minimum height in pixels. Set to zero for no limit.';
$lang['min_width']                       = 'Min width';
$lang['min_width_tip']                   = 'The minimum width in pixels. Set to zero for no limit.';
$lang['offline_access_level']            = 'Maintenance Mode Access';
$lang['offline_access_level_tip']        = 'The minimum level capable to login while in maintenance mode.';
$lang['per_page']                        = 'Per Page';
$lang['per_page_tip']                    = 'How many items are shown on pages using pagination?';
$lang['recaptcha_private_key']           = 'Private reCAPTCHA Key';
$lang['recaptcha_private_key_tip']       = 'Enter the reCAPTCHA private key provided by Google.';
$lang['recaptcha_site_key']              = 'Site reCAPTCHA Key';
$lang['recaptcha_site_key_tip']          = 'Enter the reCAPTCHA site key provided by Google.';
$lang['sendmail_path']                   = 'Sendmail Path';
$lang['sendmail_path_tip']               = 'Enter the sendmail path. Default: /usr/sbin/sendmail. Required only if using the Sendmail protocol.';
$lang['server_email']                    = 'Server Email';
$lang['server_email_tip']                = 'The email address used to send emails to users. Set as "From". You can use "noreply@..." or an existing email address.';
$lang['site_author']                     = 'Site Author';
$lang['site_author_tip']                 = 'Enter the site author if you want to add the author meta tag.';
$lang['site_background_color']           = 'Background Color';
$lang['site_background_color_tip']       = 'Sets the background color for the splash screen that appears when the app is loading.';
$lang['site_description']                = 'Site Description';
$lang['site_description_tip']            = 'Enter a short description for your site.';
$lang['site_favicon']                    = 'Site Favicon';
$lang['site_favicon_tip']                = 'Enter the URL to the image or icon you want to use as your site\'s favicon.';
$lang['site_keywords']                   = 'Site Keywords';
$lang['site_keywords_tip']               = 'Enter your comma-separated site keywords.';
$lang['site_name']                       = 'Site Name';
$lang['site_name_tip']                   = 'Enter the name of your site.';
$lang['site_offline_tip']                = 'Select if access to the site frontend is available.';
$lang['site_short_name']                 = 'Shorter Site Name';
$lang['site_short_name_tip']             = 'Shorter version of the name, used in spaces where screen real estate is limited, like under icons.';
$lang['site_theme_color']                = 'Theme Color';
$lang['site_theme_color_tip']            = 'Sets the color of the browser\'s address bar when the user is viewing the site. Some mobile browsers, like Chrome on Android, use this to provide a more integrated experience.';
$lang['smtp_crypto']                     = 'SMTP Encryption';
$lang['smtp_crypto_tip']                 = 'Choose the SMTP encryption.';
$lang['smtp_host']                       = 'SMTP Host';
$lang['smtp_host_tip']                   = 'Enter the SMTP host name (i.e: smtp.gmail.com). Required only if using the SMTP protocol.';
$lang['smtp_pass']                       = 'SMTP Password';
$lang['smtp_pass_tip']                   = 'Enter the password for your SMTP account.';
$lang['smtp_port']                       = 'SMTP Port';
$lang['smtp_port_tip']                   = 'Enter the SMTP port number provided by your host. Required only if using the SMTP protocol.';
$lang['smtp_user']                       = 'SMTP Username';
$lang['smtp_user_tip']                   = 'Enter the username of your SMTP account.';
$lang['time_format']                     = 'Time Format';
$lang['time_format_tip']                 = 'Choose the time format you want to use.';
$lang['time_reference']                  = 'Site Timezone';
$lang['time_reference_tip']              = 'Select the timezone for your site. All times will be displayed in this timezone.';
$lang['upload_path']                     = 'Upload Path';
$lang['upload_path_tip']                 = 'The path where different allowed files are uploaded to. Default: content/uploads/';
$lang['upload_year_month']               = 'Year/Month based folders';
$lang['upload_year_month_tip']           = 'Organize uploads into year/month based folders.';
$lang['use_captcha']                     = 'Use captcha';
$lang['use_captcha_tip']                 = 'Whether to enable Captcha on some site forms.';
$lang['use_gravatar']                    = 'Use Gravatar';
$lang['use_gravatar_tip']                = 'Use Gravatar or allow users to upload their avatars.';
$lang['use_imgur']                       = 'Upload to Imgur.com';
$lang['use_imgur_tip']                   = 'Upload your images to Imgur instead (<a href="https://api.imgur.com/oauth2/addclient" target="_blank">Requires App</a>).';
$lang['use_manifest']                    = 'Enable Progressive Web App';
$lang['use_manifest_tip']                = 'A "manifest.json" file is a JSON file that provides metadata about a site or web application, primarily to enhance how the site behaves on mobile devices and how it can be added to the user\'s home screen as a Progressive Web App (PWA).';
$lang['use_recaptcha']                   = 'Use reCAPTCHA';
$lang['use_recaptcha_tip']               = 'Use Google reCAPTCHA if enabled, or use CodeIgniter\'s built-in captcha if Use captcha is set to Yes.';
$lang['wpa']                             = 'WPA';

// System Information
$lang['sysinfo_database_type']      = 'Database Type';
$lang['sysinfo_database_version']   = 'Database Version';
$lang['sysinfo_disable_functions']  = 'Disabled Functions';
$lang['sysinfo_display_errors']     = 'Display Errors';
$lang['sysinfo_file_uploads']       = 'File Uploads';
$lang['sysinfo_iconv']              = 'Iconv Available';
$lang['sysinfo_magic_quotes_gpc']   = 'Magic Quotes';
$lang['sysinfo_max_input_vars']     = 'Maximum Input Variables';
$lang['sysinfo_mbstring']           = 'Multibyte String (mbstring) enabled';
$lang['sysinfo_open_basedir']       = 'Open basedir';
$lang['sysinfo_output_buffering']   = 'Output Buffering';
$lang['sysinfo_php_built_on']       = 'PHP Built On';
$lang['sysinfo_php_info']           = 'PHP Information';
$lang['sysinfo_php_settings']       = 'PHP Settings';
$lang['sysinfo_php_version']        = 'PHP Version';
$lang['sysinfo_register_globals']   = 'Register Globals';
$lang['sysinfo_safe_mode']          = 'Safe Mode';
$lang['sysinfo_session.auto_start'] = 'Session Auto Start';
$lang['sysinfo_session.save_path']  = 'Session Save Path';
$lang['sysinfo_short_open_tag']     = 'Short Open Tag';
$lang['sysinfo_skeleton_version']   = 'Skeleton Version';
$lang['sysinfo_user_agent']         = 'User Agent';
$lang['sysinfo_web_server']         = 'Web Server';
$lang['sysinfo_xml']                = 'XML Enabled';
$lang['sysinfo_zip']                = 'Native ZIP Enabled';
$lang['sysinfo_zlib']               = 'Zlib Enabled';

/**
 * ---------------------------------------------------------------
 * Users Section
 * ---------------------------------------------------------------
 * Language lines for the users management section.
 */
$lang['admin_users_add']                = 'Add User';
$lang['admin_users_all_users']          = 'All Users';
$lang['admin_users_ban_confirm']        = 'Are you sure you want to ban this user?';
$lang['admin_users_ban_error']          = 'Unable to ban users.';
$lang['admin_users_ban_success']        = 'User successfully banned.';
$lang['admin_users_delete_confirm']     = 'Are you sure you want to delete this user?';
$lang['admin_users_delete_error']       = 'Unable to delete user.';
$lang['admin_users_delete_success']     = 'User successfully deleted.';
$lang['admin_users_disable_confirm']    = 'Are you sure you want to deactivate this user?';
$lang['admin_users_disable_error']      = 'Unable to deactivate user.';
$lang['admin_users_disable_success']    = 'User successfully deactivated.';
$lang['admin_users_edit']               = 'Edit User';
$lang['admin_users_edit_error']         = 'Unable to update user.';
$lang['admin_users_edit_success']       = 'User successfully updated.';
$lang['admin_users_enable_confirm']     = 'Are you sure you want to activate this user?';
$lang['admin_users_enable_error']       = 'Unable to activate user account.';
$lang['admin_users_enable_success']     = 'Account successfully activated.';
$lang['admin_users_groups']             = 'Groups';
$lang['admin_users_logged']             = 'Logged-in Users';
$lang['admin_users_mailer']             = 'Mass Mail';
$lang['admin_users_mailer_to_banned']   = 'Send to banned users.';
$lang['admin_users_mailer_to_deleted']  = 'Send to deleted users.';
$lang['admin_users_mailer_to_disabled'] = 'Send to inactive users.';
$lang['admin_users_manage']             = 'Manage Users';
$lang['admin_users_remove_confirm']     = 'Are you sure you want to permanently delete this user and all his/her data?';
$lang['admin_users_remove_error']       = 'Unable to permanently delete the users and all their data.';
$lang['admin_users_remove_success']     = 'Account and all its data successfully deleted.';
$lang['admin_users_restore_confirm']    = 'Are you sure you want to restore this user?';
$lang['admin_users_restore_error']      = 'Unable to restore account.';
$lang['admin_users_restore_success']    = 'User successfully restored.';
$lang['admin_users_unban_confirm']      = 'Are you sure you want to unban this user?';
$lang['admin_users_unban_error']        = 'Unable to unban user.';
$lang['admin_users_unban_success']      = 'User successfully unbanned.';

/**
 * ---------------------------------------------------------------
 * Reports Section
 * ---------------------------------------------------------------
 * Language lines for the activity log section.
 */

// Title and Messages
$lang['admin_reports_clear']          = 'Clear Logs';
$lang['admin_reports_clear_confirm']  = 'Are you sure you want to clear the actions log?';
$lang['admin_reports_clear_error']    = 'Unable to clear actions log.';
$lang['admin_reports_clear_success']  = 'Actions log successfully cleared.';
$lang['admin_reports_latest_actions'] = 'Latest Actions';

// Various Activity Logs
$lang['report_clear_reports']        = '%s cleared the action logs.';
$lang['report_language_default']     = '%s changed default language: <strong>%s</strong>.';
$lang['report_language_disable']     = '%s disabled language: <strong>%s</strong>.';
$lang['report_language_enable']      = '%s enabled language: <strong>%s</strong>.';
$lang['report_module_delete']        = '%s deleted module: <strong>%s</strong>.';
$lang['report_module_disable']       = '%s disabled module: <strong>%s</strong>.';
$lang['report_module_enable']        = '%s enabled module: <strong>%s</strong>.';
$lang['report_module_install']       = '%s installed module: <strong>%s</strong>.';
$lang['report_users_activate']       = 'Account %s activated.';
$lang['report_users_activate_link']  = '%s requested a new activation link.';
$lang['report_users_link']           = '%s requested a quick-login link.';
$lang['report_users_login_admin']    = '%s logged in to admin.';
$lang['report_users_login_discord']  = '%s logged in from <u>Discord</u>.';
$lang['report_users_login_facebook'] = '%s logged in from <u>Facebook</u>.';
$lang['report_users_login_github']   = '%s logged in from <u>GitHub</u>.';
$lang['report_users_login_google']   = '%s logged in from <u>Google</u>.';
$lang['report_users_login_linkedin'] = '%s logged in from <u>LinkedIn</u>.';
$lang['report_users_login_site']     = '%s logged in to site.';
$lang['report_users_recover']        = '%s requested a password reset.';
$lang['report_users_register']       = 'Account %s created.';
$lang['report_users_reset']          = '%s reset their password.';
$lang['report_users_restore']        = 'User %s was restored.';

/**
 * ---------------------------------------------------------------
 * Media Library Section
 * ---------------------------------------------------------------
 * Language lines for the media library section.
 */
$lang['admin_media_delete_confirm']      = 'Are you sure you want to delete selected files?';
$lang['admin_media_delete_error']        = 'Unable to delete files.';
$lang['admin_media_delete_success']      = 'Files successfully deleted.';
$lang['admin_media_file_delete_error']   = 'Unable to delete file.';
$lang['admin_media_file_delete_success'] = 'File successfully deleted.';
$lang['admin_media_file_update_error']   = 'Unable to update file.';
$lang['admin_media_file_update_success'] = 'File successfully updated.';
$lang['admin_media_url_copied']          = 'URL Copied!';

/**
 * ---------------------------------------------------------------
 * Modules Section
 * ---------------------------------------------------------------
 * Language lines for the modules management section.
 */
$lang['admin_modules_add']                  = 'Add Module';
$lang['admin_modules_delete_confirm']       = 'Are you sure you want to delete the module: <strong>%s</strong>?';
$lang['admin_modules_delete_error']         = 'Unable to delete the module.';
$lang['admin_modules_delete_success']       = 'Mdule successfully deleted.';
$lang['admin_modules_disable_confirm']      = 'Are you sure you want to disable the module: <strong>%s</strong>?';
$lang['admin_modules_disable_error']        = 'Unable to deactivate the module.';
$lang['admin_modules_disable_success']      = 'Module successfully deactivated.';
$lang['admin_modules_enable_confirm']       = 'Are you sure you want to enable the module: <strong>%s</strong>?';
$lang['admin_modules_enable_error']         = 'Unable to activate the module.';
$lang['admin_modules_enable_success']       = 'Module successfully activated.';
$lang['admin_modules_install']              = 'Install Module';
$lang['admin_modules_install_error']        = 'Unable to install module.';
$lang['admin_modules_install_success']      = 'Module successfully installed.';
$lang['admin_modules_location_application'] = 'Private to this app';
$lang['admin_modules_location_core']        = 'Shared across all apps';
$lang['admin_modules_location_public']      = 'Public to this app';
$lang['admin_modules_location_select']      = '&#151; Select location &#151;';
$lang['admin_modules_upload']               = 'Upload Module';
$lang['admin_modules_upload_error']         = 'Unable to upload module.';
$lang['admin_modules_upload_success']       = 'Module successfully uploaded.';
$lang['admin_modules_upload_tip']           = 'If you have a module in a .zip format, you may install it by uploading it here.';

/**
 * ---------------------------------------------------------------
 * Themes Section
 * ---------------------------------------------------------------
 * Language lines for the themes management section.
 */
$lang['admin_themes_add']                 = 'Add Theme';
$lang['admin_themes_delete_confirm']      = 'Are you sure you want to delete the theme: <strong>%s</strong>?';
$lang['admin_themes_delete_error']        = 'Unable to delete theme.';
$lang['admin_themes_delete_error_active'] = 'You cannot delete the currently active theme.';
$lang['admin_themes_delete_success']      = 'Theme successfully deleted.';
$lang['admin_themes_enable_confirm']      = 'Are you sure you want to enable the theme: <strong>%s</strong>?';
$lang['admin_themes_enable_error']        = 'Unable to activate theme.';
$lang['admin_themes_enable_success']      = 'Theme successfully activated.';
$lang['admin_themes_install']             = 'Install Theme';
$lang['admin_themes_install_error']       = 'Unable to install theme.';
$lang['admin_themes_install_success']     = 'Theme successfully installed.';
$lang['admin_themes_upload']              = 'Upload Theme';
$lang['admin_themes_upload_error']        = 'Unable to upload theme.';
$lang['admin_themes_upload_success']      = 'Theme successfully uploaded.';

/**
 * ---------------------------------------------------------------
 * Languages Section
 * ---------------------------------------------------------------
 * Language lines for the languages management section.
 */
$lang['admin_languages_default_confirm']        = 'Are you sure you want to make this language the site\'s default language?';
$lang['admin_languages_default_error']          = 'Unable to change default language.';
$lang['admin_languages_default_error_nochange'] = 'This language is already the default one.';
$lang['admin_languages_default_success']        = 'Default language successfully changed.';
$lang['admin_languages_disable_all_confirm']    = 'Are you sure you want to disable all languages?';
$lang['admin_languages_disable_all_error']      = 'Unable to disable all languages.';
$lang['admin_languages_disable_all_success']    = 'All languages disabled successfully.';
$lang['admin_languages_disable_confirm']        = 'Are you sure you want to disable the language: <strong>%s</strong>?';
$lang['admin_languages_disable_error']          = 'Unable to disable language.';
$lang['admin_languages_disable_error_nochange'] = 'This language is already disabled.';
$lang['admin_languages_disable_success']        = 'Language successfully disabled.';
$lang['admin_languages_enable_all_confirm']     = 'Are you sure you want to enable all languages?';
$lang['admin_languages_enable_all_error']       = 'Unable to enable all languages.';
$lang['admin_languages_enable_all_success']     = 'All languages enabled successfully.';
$lang['admin_languages_enable_confirm']         = 'Are you sure you want to enable the language: <strong>%s</strong>?';
$lang['admin_languages_enable_error']           = 'Unable to enable language.';
$lang['admin_languages_enable_error_nochange']  = 'This language is already enabled.';
$lang['admin_languages_enable_success']         = 'Language successfully enabled.';
$lang['admin_languages_tip']                    = 'Enable, disable, and set the site\'s default language. Enabled languages are available to site visitors.';
