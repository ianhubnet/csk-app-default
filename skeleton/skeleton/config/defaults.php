<?php
defined('BASEPATH') OR die;

/**
 * This configuration file holds defaults site options that will
 * be later overridden but options stores in database.
 *
 * @since 1.0
 */

/*
|--------------------------------------------------------------------------
| Global Settings
|--------------------------------------------------------------------------
|
| 'site_title'       = Your website name, used as title or page footer.
| 'site_description' = Your website description, used to generate meta.
| 'site_keywords'    = Your website keywords, used to generate meta.
| 'site_favicon'     = Your icon that serves as branding for your website.
| 'site_author'      = The main author of the website, used to generate meta.
| 'site_offline'     = Enables the site under maintenance mode..
| 'time_reference'   = Master time reference
|
*/
$config['site_name']        = KB_LABEL;
$config['site_description'] = KB_SLOGAN;
$config['site_keywords']    = KB_KEYWORDS;
$config['site_favicon']     = '';
$config['site_author']      = KB_AUTHOR;
$config['site_offline']     = false;
$config['time_reference']   = 'UTC';

/*
|--------------------------------------------------------------------------
| Manifest.json Settings
|--------------------------------------------------------------------------
|
| 'use_manifest'          = Whether to enable the user of 'manifest.json'
| 'site_short_name'       = A short version of your website name.
| 'site_theme_color'      = Your site theme color used for 'manifest.json'
| 'site_background_color' = Your site theme background color.
|
*/
$config['use_manifest']          = false;
$config['site_short_name']       = KB_SHORT;
$config['site_theme_color']      = '134d78';
$config['site_background_color'] = 'ffffff';

/*
|-------------------------------------------------------------------------
| Language Files
|-------------------------------------------------------------------------
|
| 'language_files'   = Array of language files to be autoloaded.
|
*/
$config['language_files'] = array('core', 'app');

/*
|-------------------------------------------------------------------------
| Entries Per Page
|-------------------------------------------------------------------------
|
| This can be used by modules to limit how many items are shown by page.
| Also used to limit how many entries are grabbed from the database.
|
*/
$config['per_page'] = 10;

/*
|-------------------------------------------------------------------------
| Google Configuration
|-------------------------------------------------------------------------
|
| 'google_analytics_id'
|
|   If you have a Google Analytics account and ID you can use it here
|   to better monitor your site's traffic.
|
| 'google_site_verification'
|
|   Google site verification is used by google to verify your site's
|   ownership. You can use a file instead but we highly recommend you
|   check Google documentation about this.
|
| 'facebook_app_id'
|
|	A Facebook App ID is a unique number that identifies your app when
|	you request ads from Audience Network.
|	Each app on Audience Network must have a unique Facebook App ID.
|	You may already have an App ID if your app uses Facebook Login or
|	Facebook Analytics for Apps.
|
*/
$config['google_analytics_id']      = '';
$config['google_site_verification'] = '';
$config['facebook_app_id']          = '';

/*
|-------------------------------------------------------------------------
| Trace URL
|-------------------------------------------------------------------------
|
| 'trace_url_key'
|
|   A key that will be appended your site URL using "trace_url" function
|   and used to track stuff on your site.
|
|   It is a custom key that be can used by Google Analytics to monitor
|   site users behavior, clicks .. etc, which allows you to better
|   organize your stuff.
|
*/
$config['trace_url_key'] = 'trk';

/*
|-------------------------------------------------------------------------
| Users Settings
|-------------------------------------------------------------------------
|
| 'allow_registration'  = Whether to allow users to create accounts.
| 'allow_remember'      = Enables the "Remember me" field on login.
| 'email_activation'    = If enabled, created account require email activation.
| 'manual_activation'   = If enabled, created accounts require manual activation.
| 'login_type'          = Choose the login type. both, username or email.
| 'allow_multi_session' = Whether to allow multiple usage of a single account.
| 'use_gravatar'        = Whether to use Gravatar or allow users to upload theirs.
|
*/
$config['allow_registration']  = true;
$config['allow_remember']      = true;
$config['email_activation']    = false;
$config['manual_activation']   = false;
$config['login_type']          = 'both';
$config['allow_multi_session'] = true;
$config['use_gravatar']        = false;

/*
|-------------------------------------------------------------------------
| Email Settings
|-------------------------------------------------------------------------
|
| 'admin_email'   = Email address of the super user
| 'mail_protocol' = The mail sending protocol
| 'sendmail_path' = The server path to Sendmail
| 'server_email'  = Email address used to send emails to users
| 'smtp_host'     = SMTP server address
| 'smtp_port'     = SMTP port
| 'smtp_crypto'   = SMTP encryption
| 'smtp_user'     = SMTP username
| 'smtp_pass'     = SMTP password
| 'contact_email' = Email address to which contact form messages are sent
|
*/
$config['admin_email']   = '';
$config['mail_protocol'] = 'mail';
$config['sendmail_path'] = '/usr/sbin/sendmail';
$config['server_email']  = '';
$config['smtp_host']     = '';
$config['smtp_port']     = '';
$config['smtp_crypto']   = 'none';
$config['smtp_user']     = '';
$config['smtp_pass']     = '';
$config['contact_email'] = '';

/*
|-------------------------------------------------------------------------
| Theme Settings
|-------------------------------------------------------------------------
|
| 'theme'           = Site's enabled theme
| 'title_separator' = A separator used to separate site name from page name
| 'theme_compress'  = Whether to compress the final HTML output
| 'theme_beautify'  = Whether to beautify the fina HTML output
| 'cache_lifetime'  = Cache HTML output. Set to 0 to disable
|
*/
$config['theme']           = 'default';
$config['title_separator'] = ' &#8212; ';
$config['theme_compress']  = (ENVIRONMENT === 'production');
$config['theme_beautify']  = true;
$config['cache_lifetime']  = 0;

/*
|-------------------------------------------------------------------------
| Upload Settings
|-------------------------------------------------------------------------
|
| 'upload_path'
|
|   The path to the directory where the upload should be placed.
|   The directory must be writable and the path can be absolute or relative.
|
| 'allowed_types'
|
|   The mime types corresponding to the types of files you allow to be uploaded.
|
| 'max_height'
|
|   The maximum height (in pixels) that the image can be. Set to zero for no limit.
|
| 'max_width'
|
|   The maximum width (in pixels) that the image can be. Set to zero for no limit.
|
| 'min_height'
|
|   The minimum height (in pixels) that the image can be. Set to zero for no limit.
|
| 'min_width'
|
|   The minimum width (in pixels) that the image can be. Set to zero for no limit.
|
| 'max_size'
|
|   The maximum size (in kilobytes) that the file can be.
|
*/
$config['upload_path']   = 'content/uploads';
$config['allowed_types'] = 'gif|png|jpeg|jpg|pdf|doc|txt|docx|xls|zip|rar|xls|mp4';
$config['max_height']    = 0;
$config['max_size']      = 0;
$config['max_width']     = 0;
$config['min_height']    = 0;
$config['min_width']     = 0;

/*
|-------------------------------------------------------------------------
| Imgur Settings
|-------------------------------------------------------------------------
| 'use_imgur' 				= Enables uploading images to Imgur.
| 'imgur_client_id' 		= Imgur client id (required).
| 'imgur_client_secret' 	= Imgur client secrent hash.
|
*/
$config['use_imgur']           = false;
$config['imgur_client_id']     = '';
$config['imgur_client_secret'] = '';

/*
|-------------------------------------------------------------------------
| Captcha Settings
|-------------------------------------------------------------------------
|
| 'use_captcha'           = Enables sites CAPTCHA
| 'use_recaptcha'         = Use Google reCAPTCHA instead (requires captcha on)
| 'recaptcha_site_key'    = Google reCAPTCHA public key
| 'recaptcha_private_key' = Google reCAPTCHA private key
|
*/
$config['use_captcha']           = false;
$config['use_recaptcha']         = false;
$config['recaptcha_site_key']    = '';
$config['recaptcha_private_key'] = '';

/*
|-------------------------------------------------------------------------
| Date & Time Formats
|-------------------------------------------------------------------------
|
| 'date_format'          = The default date format
| 'time_format'          = The default time format
| 'time_format_full'     = The default time format including seconds
| 'datetime_format'      = The default date & time format
| 'datetime_format_full' = The default date & time format including seconds
|
*/
$config['date_format']          = 'd/m/Y';      // 21/03/1988
$config['time_format']          = 'H:i';        // 23:15

/*
|-------------------------------------------------------------------------
| Social Share
|-------------------------------------------------------------------------
|
| A list of social networks urls used to share your website stuff.
|
| Elements used with "sprintf" MUST be in the following order:
|   1. URL         = The URL to your page or post to share
|   2. Title       = The page's or post's title
|   3. Description = The page's description or the post's excerpt
|   4. Site Name   = The website name, only used by some links
|
*/
$config['share_links']['buffer']      = 'https://bufferapp.com/add?text=%2$s&url=%1$s';
$config['share_links']['delicious']   = 'https://delicious.com/save?v=5&provider=%4$s&noui&jump=close&url=%1$s&title=%2$s';
$config['share_links']['email']       = 'mailto:?subject=%2$s&amp;body=%3$s:%0A%1$s';
$config['share_links']['evernote']    = 'https://www.evernote.com/clip.action?url=%1$s&title=%2$s';
$config['share_links']['facebook']    = 'https://www.facebook.com/sharer.php?u=%1$s&amp;t=%2$s&amp;d=%3$s';
$config['share_links']['googleplus']  = 'https://plus.google.com/share?url=%1$s';
$config['share_links']['linkedin']    = 'https://www.linkedin.com/shareArticle?url=%1$s&title=%2$s';
$config['share_links']['reddit']      = 'https://reddit.com/submit?url=%1$s&title=%2$s';
$config['share_links']['stumbleupon'] = 'https://www.stumbleupon.com/submit?url=%1$s&title=%2$s';
$config['share_links']['tumblr']      = 'https://www.tumblr.com/share/link?url=%1$s&name=%2$s&description=%3$s';
$config['share_links']['twitter']     = 'http://twitter.com/share?url=%1$s&amp;text=%2$s&amp;via=%4$s';
$config['share_links']['whatsapp']    = 'https://api.whatsapp.com/send?text=%2$s %1$s';

/*
|-------------------------------------------------------------------------
| User Config
|-------------------------------------------------------------------------
|
| 'username_min'   = Minimum username length.
| 'username_max'   = Maximum username length.
| 'password_min'   = Minimum password length.
| 'password_max'   = Maximum password length.
| 'first_name_min' = Minimum first name length.
| 'first_name_max' = Maximum first name length.
| 'last_name_min'  = Minimum last name length.
| 'last_name_max'  = Maximum last name length.
| 'name_min'       = Minimum group/objects name length.
| 'name_max'       = Maximum group/objects name length.
|
*/
$config['username_min']   = 5;
$config['username_max']   = 32;
$config['password_min']   = 8;
$config['password_max']   = 24;
$config['first_name_min'] = 1;
$config['first_name_max'] = 32;
$config['last_name_min']  = 1;
$config['last_name_max']  = 32;
$config['name_min']       = 3;
$config['name_max']       = 100;
