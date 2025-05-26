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
$lang['admin_components']      = 'التطبيقات';
$lang['admin_content']         = 'المحتوى';
$lang['admin_database_backup'] = 'نسخ قاعدة البيانات';
$lang['admin_extensions']      = 'الملحقات';
$lang['admin_footer_left']     = 'شكرًا لك لاستخدامك <a href="%s" target="_blank">كوديجنتر سكلتون</a>.';
$lang['admin_footer_right']    = 'الإصدار: <strong>%s</strong>';
$lang['admin_logs']            = 'سجلات النظام';
$lang['admin_media']           = 'مكتبة الوسائط';
$lang['admin_modules']         = 'الموديولات';
$lang['admin_reports']         = 'سجل الإجراءات';
$lang['admin_system']          = 'النظام';
$lang['admin_themes']          = 'القوالب';
$lang['admin_update_notice']   = '<a href="%2$s" target="_blank">النسخة <strong>%1$s</strong> متاحة الآن!<a>';
$lang['admin_users']           = 'المستخدمون';
$lang['admin_view_site']       = 'عرض الموقع';
$lang['settings_global']       = 'الإعدادات العامة';
$lang['settings_sysinfo']      = 'معلومات النظام';

/**
 * ---------------------------------------------------------------
 * Database & Backup Section
 * ---------------------------------------------------------------
 * Language lines for the database management section.
 */
$lang['admin_database_backup_clean_error']    = 'تعذر حذف ملفات النسخ الاحتياطي القديمة.';
$lang['admin_database_backup_clean_success']  = 'تم حذف %d من ملفات النسخ الاحتياطي. تم تحرير مساحة قرص %d.';
$lang['admin_database_backup_create']         = 'إنشاء نسخة احتياطية';
$lang['admin_database_backup_create_confirm'] = 'هل أنت متأكد أنك تريد إنشاء نسخة احتياطية الآن؟';
$lang['admin_database_backup_create_error']   = 'تعذر إنشاء ملف النسخ الاحتياطي. تأكد من أن المجلد <strong>%s</strong> قابل للكتابة.';
$lang['admin_database_backup_create_success'] = 'تم إنشاء ملف النسخ الاحتياطي لقاعدة البيانات <strong>%s</strong> بنجاح.';
$lang['admin_database_backup_delete_confirm'] = 'هل أنت متأكد أنك تريد حذف ملفات النسخ الاحتياطي المحددة؟';
$lang['admin_database_backup_delete_error']   = 'تعذر حذف ملفات النسخ الاحتياطي المحددة.';
$lang['admin_database_backup_delete_success'] = 'تم حذف ملفات النسخ الاحتياطي بنجاح.';
$lang['admin_database_backup_lock_confirm']   = 'هل أنت متأكد أنك تريد تأمين ملفات النسخ الاحتياطي المحددة؟';
$lang['admin_database_backup_lock_error']     = 'تعذر تأمين ملفات النسخ الاحتياطي المحددة.';
$lang['admin_database_backup_lock_success']   = 'تم تأمين ملفات النسخ الاحتياطي بنجاح.';
$lang['admin_database_backup_locked_error']   = 'لا يمكن حذف ملفات النسخ الاحتياطي المقفلة.';
$lang['admin_database_backup_missing_error']  = 'تعذر العثور على ملف النسخة الاحتياطية.';
$lang['admin_database_backup_unlock_confirm'] = 'هل أنت متأكد أنك تريد إلغاء تأمين ملفات النسخ الاحتياطي المحددة؟';
$lang['admin_database_backup_unlock_error']   = 'تعذر إلغاء تأمين ملفات النسخ الاحتياطي المحددة.';
$lang['admin_database_backup_unlock_success'] = 'تم إلغاء تأمين ملفات النسخ الاحتياطي بنجاح.';
$lang['admin_database_prune']                 = 'تنقية';
$lang['admin_database_prune_confirm']         = 'هل أنت متأكد أنك تريد تنقية قاعدة البيانات؟ سيتم إنشاء نسخة احتياطية قبل التنفيذ.';
$lang['admin_database_prune_error']           = 'تعذر تنقية قاعدة البيانات.';
$lang['admin_database_prune_next']            = 'التنقية التالية: <strong>%s</strong>';
$lang['admin_database_prune_success']         = 'تم تنقية قاعدة البيانات بنجاح.';

/**
 * ---------------------------------------------------------------
 * System Logs Section
 * ---------------------------------------------------------------
 * Language lines for the system logs section.
 */
$lang['admin_logs_delete']         = 'حذف السجلات';
$lang['admin_logs_delete_confirm'] = 'هل أنت متأكد أنك تريد حذف ملفات السجل المحددة؟';
$lang['admin_logs_delete_error']   = 'تعذر حذف ملفات السجل.';
$lang['admin_logs_delete_success'] = 'تم حذف ملفات السجل بنجاح.';
$lang['admin_logs_error_disabled'] = 'تسجيلات النظام غير مفعلة حالياً.';
$lang['admin_logs_error_empty']    = 'لم يتم العثور على السجلات.';
$lang['admin_logs_error_missing']  = 'تعذر تحديد موقع ملف السجل، أو كان فارغاً.';
$lang['admin_logs_tip']            = 'ملفات التسجيل قد تصبح كبيرة الحجم بسرعة. الرجاء حذف الملفات القديمة من وقت لآخر.';

/**
 * ---------------------------------------------------------------
 * System Settings Section
 * ---------------------------------------------------------------
 * Language lines for the admin dashboard settings section.
 */

// Page Titles
$lang['settings_captcha']  = 'إعدادات الكابتشا';
$lang['settings_datetime'] = 'إعدادات التاريخ والوقت';
$lang['settings_discord']  = 'إعدادات ديسكورد';
$lang['settings_email']    = 'إعدادات البريد الإلكتروني';
$lang['settings_facebook'] = 'إعدادات فيسبوك';
$lang['settings_github']   = 'إعدادات جت هاب';
$lang['settings_google']   = 'إعدادات جوجل';
$lang['settings_linkedin'] = 'إعدادات لينكد إن';
$lang['settings_manifest'] = 'إعدادات المانيفست';
$lang['settings_upload']   = 'إعدادات التحميلات';
$lang['settings_users']    = 'إعدادات المستخدمين';

// Settings Fields & Help Texts
$lang['admin_email']                     = 'البريد الإلكتروني للمشرف';
$lang['admin_email_tip']                 = 'عنوان البريد الإلكتروني الذي يتم إرسال إشعارات الموقع إليه.';
$lang['alert_login_failed_tip']          = 'تنبيه المستخدمين بأي محاولات تسجيل دخول فاشلة إلى حساباتهم.';
$lang['alert_login_success_tip']         = 'إعلام المستخدمين بأي تسجيل دخول ناجح إلى حساباتهم.';
$lang['allow_multi_session']             = 'السماح بالجلسات المتعددة';
$lang['allow_multi_session_tip']         = 'السماح لعدة مستخدمين بتسجيل الدخول إلى نفس الحساب في نفس الوقت.';
$lang['allow_oauth']                     = 'تسجيل الدخول بواسطة جهات خارجية';
$lang['allow_oauth_tip']                 = 'السماح للمستخدمين بتسجيل الدخول باستخدام مزودين خارجيين.';
$lang['allow_quick_login']               = 'تسجيل الدخول بنقرة واحدة';
$lang['allow_quick_login_tip']           = 'السماح للمستخدمين بتسجيل الدخول باستخدام رابط بريد إلكتروني آمن صالح لمدة 15 دقيقة.';
$lang['allow_registration']              = 'السماح بالتسجيل';
$lang['allow_registration_tip']          = 'ما إذا كان سيتم السماح للمستخدمين بإنشاء حساب على موقعك أم لا.';
$lang['allow_remember']                  = 'تمكين ملفات تعريف الارتباط';
$lang['allow_remember_tip']              = 'السماح للمتصفح بحفظ ملف تعريف ارتباط بحيث يظل المستخدمون مسجلين الدخول حتى إذا أغلقوا نافذة الموقع.';
$lang['allowed_types']                   = 'الملفات المسموح بها';
$lang['allowed_types_tip']               = 'قائمة الملفات التي يسمح بتحميلها. استخدم "|" للفصل بين الأنواع.';
$lang['base_controller']                 = 'النموذج الرئيسي';
$lang['base_controller_tip']             = 'النموذج المستخدم لعرض الصفحة الرئيسية.';
$lang['contact_email']                   = 'البريد الإلكتروني للتواصل';
$lang['contact_email_tip']               = 'عنوان البريد الإلكتروني الذي تُرسل إليه رسائل نموذج الاتصال.';
$lang['date_format']                     = 'تنسيق التاريخ';
$lang['date_format_tip']                 = 'اختر تنسيق التاريخ الذي تريد استخدامه.';
$lang['demo_mode']                       = 'وضع العرض التجريبي';
$lang['demo_mode_tip']                   = 'حدد ما إذا كنت تريد وضع الموقع في وضع العرض التجريبي.';
$lang['discord_auth']                    = 'تسجيل الدخول باستخدام ديسكورد';
$lang['discord_auth_tip']                = 'السماح للمستخدمين بتسجيل الدخول باستخدام <a href="https://discord.com/developers" target="_blank">ديسكورد</a>.';
$lang['discord_client_id']               = 'معرف العميل';
$lang['discord_client_id_tip']           = 'أدخل معرف العميل لتطبيق ديسكورد.';
$lang['discord_client_secret']           = 'الرمز السري';
$lang['discord_client_secret_tip']       = 'أدخل الرمز السري لتطبيق ديسكورد.';
$lang['email_activation']                = 'تنشيط البريد الإلكتروني';
$lang['email_activation_tip']            = 'ما إذا كان يلزم على المستخدمين التحقق من عناوين بريدهم الإلكتروني قبل السماح لهم بتسجيل الدخول.';
$lang['enable_profiler']                 = 'تشغيل البروفيلر';
$lang['enable_profiler_tip']             = 'عرض نتائج قياس الأداء، الإستعلامات والعديد من المعلومات الأخرى.';
$lang['facebook_app_id']                 = 'معرّف تطبيق فيسبوك';
$lang['facebook_app_id_tip']             = 'أدخل معرف التطبيق لتطبيق فيسبوك.';
$lang['facebook_app_secret']             = 'الرمز السري';
$lang['facebook_app_secret_tip']         = 'أدخل الرمز السري لتطبيق فيسبوك.';
$lang['facebook_auth']                   = 'تسجيل الدخول باستخدام فيسبوك';
$lang['facebook_auth_tip']               = 'السماح للمستخدمين بتسجيل الدخول باستخدام <a href="https://developers.facebook.com/apps/" target="_blank">فيسبوك</a>.';
$lang['facebook_pixel_id']               = 'معرف فيسبوك بيكسل';
$lang['facebook_pixel_id_tip']           = 'معرف فيسبوك بيكسل الخاص بك. يُستخدم لتتبع الزيارات والتحويلات. يمكنك العثور عليه في مدير الأحداث ضمن إعدادات البكسل الخاص بك.';
$lang['github_auth']                     = 'تسجيل الدخول باستخدام جيت هاب';
$lang['github_auth_tip']                 = 'السماح للمستخدمين بتسجيل الدخول باستخدام <a href="https://github.com/" target="_blank">جيت هاب</a>.';
$lang['github_client_id']                = 'معرف العميل';
$lang['github_client_id_tip']            = 'أدخل معرف العميل لتطبيق جيت هاب.';
$lang['github_client_secret']            = 'الرمز السري';
$lang['github_client_secret_tip']        = 'أدخل الرمز السري لتطبيق جيت هاب.';
$lang['google_analytics_id']             = 'معرف جوجل أناليتكس';
$lang['google_analytics_id_tip']         = 'أدخل معرّف تتبع جوجل أناليتكس.';
$lang['google_auth']                     = 'تسجيل الدخول باستخدام جوجل';
$lang['google_auth_tip']                 = 'السماح للمستخدمين بتسجيل الدخول باستخدام <a href="https://console.cloud.google.com/" target="_blank">جوجل</a>.';
$lang['google_client_id']                = 'معرف العميل';
$lang['google_client_id_tip']            = 'أدخل معرف العميل لتطبيق جوجل.';
$lang['google_client_secret']            = 'الرمز السري';
$lang['google_client_secret_tip']        = 'أدخل الرمز السري لتطبيق جوجل.';
$lang['google_site_verification']        = 'محقق جوجل للمواقع';
$lang['google_site_verification_tip']    = 'أدخل رمز محقق جوجل للمواقع الخاص بك.';
$lang['google_tagmanager_id']            = 'معرّف مدير علامات جوجل';
$lang['google_tagmanager_id_tip']        = 'أدخل معرّف الحاوية. اتركه فارغًا إذا كنت تريد استخدام جوجل أناليتكس بدلاً من ذلك.';
$lang['image_watermark']                 = 'إضافة علامة مائية';
$lang['image_watermark_tip']             = 'قم بتمكين لإضافة علامة مائية لحماية المحتوى.';
$lang['imgur_client_id']                 = 'معرف العميل';
$lang['imgur_client_id_tip']             = 'أدخل معرف العميل لتطبيق Imgur.';
$lang['imgur_client_secret']             = 'الرمز السري';
$lang['imgur_client_secret_tip']         = 'أدخل الرمز السري لتطبيق Imgur.';
$lang['linkedin_auth']                   = 'تسجيل الدخول باستخدام لينكدإن';
$lang['linkedin_auth_tip']               = 'السماح للمستخدمين بتسجيل الدخول باستخدام <a href="https://developer.linkedin.com/" target="_blank">لينكدإن</a>.';
$lang['linkedin_client_id']              = 'معرف العميل';
$lang['linkedin_client_id_tip']          = 'أدخل معرف العميل لتطبيق لينكدإن.';
$lang['linkedin_client_secret']          = 'الرمز السري';
$lang['linkedin_client_secret_tip']      = 'أدخل الرمز السري لتطبيق لينكدإن.';
$lang['login_fail_allowed_attempts']     = 'محاولات تسجيل الدخول المسموح بها';
$lang['login_fail_allowed_attempts_tip'] = 'عدد محاولات تسجيل الدخول الفاشلة قبل تطبيق القفل القصير.';
$lang['login_fail_allowed_lockouts']     = 'عدد أقفال تسجيل الدخول القصيرة';
$lang['login_fail_allowed_lockouts_tip'] = 'عدد أقفال تسجيل الدخول القصيرة قبل تطبيق القفل الطويل.';
$lang['login_fail_enabled']              = 'الحماية من تسجيلات الدخول الفاشلة';
$lang['login_fail_enabled_tip']          = 'تفعيل أو تعطيل حماية تسجيل الدخول الفاشل.';
$lang['login_fail_long_lockout']         = 'مدة قفل تسجيل الدخول الطويل';
$lang['login_fail_long_lockout_tip']     = 'مدة قفل تسجيل الدخول الطويل بالساعات بعد عدة أقفال قصيرة.';
$lang['login_fail_short_lockout']        = 'مدة قفل تسجيل الدخول القصير';
$lang['login_fail_short_lockout_tip']    = 'مدة قفل تسجيل الدخول القصير بالدقائق بعد عدة محاولات فاشلة.';
$lang['login_type']                      = 'نوع تسجيل الدخول';
$lang['login_type_tip']                  = 'يمكن للمستخدمين تسجيل الدخول باستخدام أسماء المستخدمين وعناوين البريد الإلكتروني أو كليهما.';
$lang['mail_protocol']                   = 'بروتوكول البريد';
$lang['mail_protocol_tip']               = 'اختر بروتوكول البريد الذي تريد إرسال رسائل البريد الإلكتروني به.';
$lang['manual_activation']               = 'تفعيل يدوي';
$lang['manual_activation_tip']           = 'ما إذا كان سيتم التحقق من حسابات المستخدمين يدويا أم لا.';
$lang['max_height']                      = 'أقصى ارتفاع';
$lang['max_height_tip']                  = 'أقصى ارتفاع بالبكسل. صفر لإزالة الحدود.';
$lang['max_size']                        = 'الحد الأقصى لحجم الملف';
$lang['max_size_tip']                    = 'الحجم الأقصى للملفات بالكيلوبايت. صفر لإزالة الحدود.';
$lang['max_width']                       = 'أقصى عرض';
$lang['max_width_tip']                   = 'أقصى عرض بالبكسل. صفر لإزالة الحدود.';
$lang['min_height']                      = 'أدنى ارتفاع';
$lang['min_height_tip']                  = 'أدنى ارتفاع بالبكسل. صفر لإزالة الحدود.';
$lang['min_width']                       = 'أدنى عرض';
$lang['min_width_tip']                   = 'أدنى عرض بالبكسل. صفر بلا حدود.';
$lang['offline_access_level']            = 'تسجيل الدخول أثناء وضع الصيانة';
$lang['offline_access_level_tip']        = 'أدنى مستوى يمكنه تسجيل الدخول أثناء وضع الصيانة.';
$lang['per_page']                        = 'لكل صفحة';
$lang['per_page_tip']                    = 'عدد العناصر التي يتم عرضها على الصفحات المجزأة.';
$lang['recaptcha_private_key']           = 'مفتاح خاص reCAPTCHA';
$lang['recaptcha_private_key_tip']       = 'أدخل المفتاح الخاص reCAPTCHA الذي وفره غوغل لك.';
$lang['recaptcha_site_key']              = 'مفتاح الموقع reCAPTCHA';
$lang['recaptcha_site_key_tip']          = 'أدخل مفتاح موقع reCAPTCHA الذي وفره غوغل لك.';
$lang['sendmail_path']                   = 'مسار Sendmail';
$lang['sendmail_path_tip']               = 'أدخل مسار Sendmail. الافتراضي: " /usr/sbin/sendmail". مطلوب فقط في حالة استخدام بروتوكول Sendmail.';
$lang['server_email']                    = 'البريد الإلكتروني للخادم';
$lang['server_email_tip']                = 'عنوان البريد الإلكتروني المستخدم لإرسال رسائل إلكترونية إلى المستخدمين. يمكنك استخدام "noreply@ ..." أو عنوان بريد إلكتروني موجود.';
$lang['site_author']                     = 'مؤلف الموقع';
$lang['site_author_tip']                 = 'أدخل مؤلف الموقع إذا كنت تريد إضافة العلامة الوصفية للمؤلف &lt;meta&gt;.';
$lang['site_background_color']           = 'لون الخلفية';
$lang['site_background_color_tip']       = 'يحدد لون الخلفية لشاشة التحميل التي تظهر عند تحميل التطبيق.';
$lang['site_description']                = 'وصف الموقع';
$lang['site_description_tip']            = 'أدخل وصفا موجزا لموقعك.';
$lang['site_favicon']                    = 'أيقونة الموقع';
$lang['site_favicon_tip']                = 'أدخل رابط الصورة أو الأيقونة التي تريد استخدامها كأيقونة لموقعك.';
$lang['site_keywords']                   = 'الكلمات الرئيسية للموقع';
$lang['site_keywords_tip']               = 'أدخل كلماتك الرئيسية المفصولة بفواصل.';
$lang['site_name']                       = 'اسم الموقع';
$lang['site_name_tip']                   = 'أدخل اسما لموقعك.';
$lang['site_offline_tip']                = 'حدد حالة الوصول للواجهة الامامية للموقع.';
$lang['site_short_name']                 = 'اسم الموقع المختصر';
$lang['site_short_name_tip']             = 'نسخة مختصرة من الاسم، تُستخدم في الأماكن التي تكون فيها المساحة على الشاشة محدودة، مثل أسفل الأيقونات.';
$lang['site_theme_color']                = 'لون القالب';
$lang['site_theme_color_tip']            = 'يحدد لون شريط العنوان في المتصفح عندما يقوم المستخدم بزيارة الموقع. بعض متصفحات الهواتف المحمولة، مثل Chrome على نظام أندرويد، تستخدم هذا لتوفير تجربة أكثر تكاملاً.';
$lang['smtp_crypto']                     = 'تشفير SMTP';
$lang['smtp_crypto_tip']                 = 'اختر تشفير SMTP.';
$lang['smtp_host']                       = 'مضيف SMTP';
$lang['smtp_host_tip']                   = 'أدخل اسم مضيف SMTP (على سبيل المثال: smtp.gmail.com). مطلوب فقط عند استخدام بروتوكول SMTP.';
$lang['smtp_pass']                       = 'كلمة مرور SMTP';
$lang['smtp_pass_tip']                   = 'أدخل كلمة المرور لحساب SMTP.';
$lang['smtp_port']                       = 'منفذ SMTP';
$lang['smtp_port_tip']                   = 'أدخل رقم منفذ SMTP الذي يقدمه المضيف. مطلوب فقط عند استخدام بروتوكول SMTP.';
$lang['smtp_user']                       = 'اسم مستخدم SMTP';
$lang['smtp_user_tip']                   = 'أدخل اسم المستخدم لحساب SMTP.';
$lang['time_format']                     = 'تنسيق الوقت';
$lang['time_format_tip']                 = 'اختر تنسيق الوقت الذي تريد استخدامه.';
$lang['time_reference']                  = 'المنطقة الزمنية للموقع';
$lang['time_reference_tip']              = 'اختر المنطقة الزمنية لموقعك والتي سيتم عرض جميع الأوقات حسبها.';
$lang['upload_path']                     = 'مسار التحميل';
$lang['upload_path_tip']                 = 'المسار الذي يتم تحميل الملفات المسموح بها إليه. الافتراضي: content/uploads/';
$lang['upload_year_month']               = 'مجلدات بناءً على السنة/الشهر';
$lang['upload_year_month_tip']           = 'نظم التحميلات في مجلدات تعتمد على السنة/الشهر.';
$lang['use_captcha']                     = 'استخدام كابتشا';
$lang['use_captcha_tip']                 = 'ما إذا كان سيتم تمكين كابتشا في بعض نماذج المواقع.';
$lang['use_gravatar']                    = 'استخدام غرافاتار';
$lang['use_gravatar_tip']                = 'استخدام غرافاتار أو السماح للمستخدمين بتحميل الصور الخاصة بهم.';
$lang['use_imgur']                       = 'تحميل إلى Imgur.com';
$lang['use_imgur_tip']                   = 'قم بتحميل صورك إلى Imgur (<a href="https://api.imgur.com/oauth2/addclient" target="_blank">يتطلب تطبيقًا</a>).';
$lang['use_manifest']                    = 'تمكين تطبيق الويب التقدمي';
$lang['use_manifest_tip']                = 'ملف "manifest.json" هو ملف JSON يوفر بيانات تعريفية عن الموقع أو التطبيق الإلكتروني، ويهدف بشكل أساسي إلى تحسين كيفية تصرف الموقع على الأجهزة المحمولة وكيفية إضافته إلى الشاشة الرئيسية للمستخدم كتطبيق ويب تقدمي (PWA).';
$lang['use_recaptcha']                   = 'استخدام reCAPTCHA';
$lang['use_recaptcha_tip']               = 'استخدم غوغل reCAPTCHA في حالة تمكينه، وإلا استخدم كابتشا المدمج في CodeIgniter إذا تم تعيين كابتشا على نعم.';
$lang['wpa']                             = 'WPA';

// System Information
$lang['sysinfo_database_type']      = 'نوع قاعدة البيانات';
$lang['sysinfo_database_version']   = 'نسخة قاعدة البيانات';
$lang['sysinfo_disable_functions']  = 'الوظائف المعطلة';
$lang['sysinfo_display_errors']     = 'عرض الأخطاء';
$lang['sysinfo_file_uploads']       = 'رفع الملفات';
$lang['sysinfo_iconv']              = 'Iconv متاح';
$lang['sysinfo_magic_quotes_gpc']   = 'الإقتباسات السحرية';
$lang['sysinfo_max_input_vars']     = 'متغيرات الإدخال القصوى';
$lang['sysinfo_mbstring']           = 'تمكين الجمل متعددة البايتات';
$lang['sysinfo_open_basedir']       = 'مجلد الدليل الأساسي المفتوح (الموقع)';
$lang['sysinfo_output_buffering']   = 'تخزين المخرجات';
$lang['sysinfo_php_built_on']       = 'معلومات نظام التشغيل';
$lang['sysinfo_php_info']           = 'معلومات PHP';
$lang['sysinfo_php_settings']       = 'إعدادات PHP';
$lang['sysinfo_php_version']        = 'نسخة PHP';
$lang['sysinfo_register_globals']   = 'التسجيل العام';
$lang['sysinfo_safe_mode']          = 'الوضع الآمن';
$lang['sysinfo_session.auto_start'] = 'بداية تلقائية للساشين';
$lang['sysinfo_session.save_path']  = 'مسار حفظ للساشين';
$lang['sysinfo_short_open_tag']     = 'علامات فتح قصيرة';
$lang['sysinfo_skeleton_version']   = 'نسخة سكلتون';
$lang['sysinfo_user_agent']         = 'متصفح المستخدم';
$lang['sysinfo_web_server']         = 'السيرفر';
$lang['sysinfo_xml']                = 'تفعيل XML';
$lang['sysinfo_zip']                = 'قابلية ضغط الملفات مفعلة';
$lang['sysinfo_zlib']               = 'تفعيل Zlib';

/**
 * ---------------------------------------------------------------
 * Users Section
 * ---------------------------------------------------------------
 * Language lines for the users management section.
 */
$lang['admin_users_add']                = 'إضافة حساب';
$lang['admin_users_all_users']          = 'كافة الأعضاء';
$lang['admin_users_ban_confirm']        = 'هل أنت متأكد أنك تريد حظر هذا المستخدم؟';
$lang['admin_users_ban_error']          = 'تعذر حظر المستخدم.';
$lang['admin_users_ban_success']        = 'تم حظر المستخدم بنجاح.';
$lang['admin_users_delete_confirm']     = 'هل أنت متأكد من أنك تريد حذف هذا الحساب؟';
$lang['admin_users_delete_error']       = 'تعذر حذف الحساب.';
$lang['admin_users_delete_success']     = 'تم حذف الحساب بنجاح.';
$lang['admin_users_disable_confirm']    = 'هل أنت متأكد من أنك تريد تعطيل هذا الحساب؟';
$lang['admin_users_disable_error']      = 'تعذر تعطيل الحساب.';
$lang['admin_users_disable_success']    = 'تم تعطيل الحساب بنجاح.';
$lang['admin_users_edit']               = 'تعديل الحساب';
$lang['admin_users_edit_error']         = 'تعذر تحديث الحساب.';
$lang['admin_users_edit_success']       = 'تم تحديث الحساب بنجاح.';
$lang['admin_users_enable_confirm']     = 'هل أنت متأكد من أنك تريد تفعيل هذا الحساب؟';
$lang['admin_users_enable_error']       = 'تعذر تفعيل الحساب.';
$lang['admin_users_enable_success']     = 'تم تفعيل الحساب بنجاح.';
$lang['admin_users_groups']             = 'المجموعات';
$lang['admin_users_logged']             = 'المستخدمون المسجلون';
$lang['admin_users_mailer']             = 'البريد الجماعي';
$lang['admin_users_mailer_to_banned']   = 'الإرسال للأعضاء المحظورين.';
$lang['admin_users_mailer_to_deleted']  = 'الإرسال للأعضاء المحذوفين.';
$lang['admin_users_mailer_to_disabled'] = 'الإرسال للأعضاء غير المفعلين.';
$lang['admin_users_manage']             = 'إدارة المستخدمين';
$lang['admin_users_remove_confirm']     = 'هل أنت متأكد من أنك تريد حذف هذا الحساب وكل بياناته نهائياً؟';
$lang['admin_users_remove_error']       = 'تعذر إزالة الحساب وجميع بياناته.';
$lang['admin_users_remove_success']     = 'تمت إزالة الحساب وجميع بياناته بصفة نهائية.';
$lang['admin_users_restore_confirm']    = 'هل أنت متأكد من أنك تريد استرجاع هذا الحساب؟';
$lang['admin_users_restore_error']      = 'تعذر استعادة الحساب.';
$lang['admin_users_restore_success']    = 'تم استعادة المستخدم بنجاح.';
$lang['admin_users_unban_confirm']      = 'هل أنت متأكد أنك تريد إلغاء حظر هذا المستخدم؟';
$lang['admin_users_unban_error']        = 'تعذر إلغاء حظر المستخدم.';
$lang['admin_users_unban_success']      = 'تم إلغاء حظر المستخدم بنجاح.';

/**
 * ---------------------------------------------------------------
 * Reports Section
 * ---------------------------------------------------------------
 * Language lines for the activity log section.
 */

// Title and Messages
$lang['admin_reports_clear']          = 'مسح الإجراءت';
$lang['admin_reports_clear_confirm']  = 'هل أنت متأكد أنك تريد مسح سجل الإجراءت؟';
$lang['admin_reports_clear_error']    = 'تعذر مسح سجل الإجراءت.';
$lang['admin_reports_clear_success']  = 'تم مسح سجل الإجراءت بنجاح.';
$lang['admin_reports_latest_actions'] = 'أحدث الإجراءات';

// Various Activity Logs
$lang['report_clear_reports']        = 'قام %s بمسح سجل الإجراءت.';
$lang['report_language_default']     = 'غير %s اللغة الافتراضية: <strong>%s</strong>.';
$lang['report_language_disable']     = 'عطل %s اللغة: <strong>%s</strong>.';
$lang['report_language_enable']      = 'فعل %s اللغة: <strong>%s</strong>.';
$lang['report_module_delete']        = 'مسح %s الموديول: <strong>%s</strong>.';
$lang['report_module_disable']       = 'عطل %s الموديول: <strong>%s</strong>.';
$lang['report_module_enable']        = 'فعل %s الموديول: <strong>%s</strong>.';
$lang['report_module_install']       = 'ثبت %s الموديول: <strong>%s</strong>.';
$lang['report_users_activate']       = 'تم تنشيط الحساب %s.';
$lang['report_users_activate_link']  = 'طلب %s ارتباط تنشيط جديد.';
$lang['report_users_login_admin']    = 'سجل %s الدخول إلى الإدارة.';
$lang['report_users_login_discord']  = 'قام %s بتسجيل الدخول من <u>ديسكورد</u>.';
$lang['report_users_login_facebook'] = 'قام %s بتسجيل الدخول من <u>فيسبوك</u>.';
$lang['report_users_login_github']   = 'قام %s بتسجيل الدخول من <u>جيت هاب</u>.';
$lang['report_users_login_google']   = 'قام %s بتسجيل الدخول من <u>جوجل</u>.';
$lang['report_users_login_linkedin'] = 'قام %s بتسجيل الدخول من <u>لينكدإن</u>.';
$lang['report_users_login_site']     = 'سجل %s الدخول إلى الموقع.';
$lang['report_users_recover']        = 'طلب %s إعادة تعيين كلمة المرور.';
$lang['report_users_register']       = 'تم إنشاء الحساب %s.';
$lang['report_users_reset']          = 'قام %s بإعادة تعيين كلمة المرور.';
$lang['report_users_restore']        = 'تمت إستعادة الحساب %s.';

/**
 * ---------------------------------------------------------------
 * Media Library Section
 * ---------------------------------------------------------------
 * Language lines for the media library section.
 */
$lang['admin_media_delete_confirm']      = 'هل أنت متأكد أنك تريد حذف الملفات المحددة؟';
$lang['admin_media_delete_error']        = 'تعذر حذف الملفات.';
$lang['admin_media_delete_success']      = 'تم حذف الملفات بنجاح.';
$lang['admin_media_file_delete_error']   = 'تعذر حذف الملف.';
$lang['admin_media_file_delete_success'] = 'تم حذف الملف بنجاح.';
$lang['admin_media_file_update_error']   = 'تعذر تحديث الملف.';
$lang['admin_media_file_update_success'] = 'تم تحديث الملف بنجاح.';
$lang['admin_media_url_copied']          = 'تم نسخ الرابط!';

/**
 * ---------------------------------------------------------------
 * Modules Section
 * ---------------------------------------------------------------
 * Language lines for the modules management section.
 */
$lang['admin_modules_add']                  = 'موديول جديد';
$lang['admin_modules_delete_confirm']       = 'هل أنت متأكد من أنك تريد حذف الموديول: <strong>%s</strong>؟';
$lang['admin_modules_delete_error']         = 'تعذر حذف الموديول.';
$lang['admin_modules_delete_success']       = 'تم حذف الموديول بنجاح.';
$lang['admin_modules_disable_confirm']      = 'هل أنت متأكد من أنك تريد تعطيل الموديول: <strong>%s</strong>؟';
$lang['admin_modules_disable_error']        = 'تعذر تعطيل الموديول.';
$lang['admin_modules_disable_success']      = 'تم تعطيل الموديول بنجاح.';
$lang['admin_modules_enable_confirm']       = 'هل أنت متأكد من أنك تريد تفعيل الموديول: <strong>%s</strong>؟';
$lang['admin_modules_enable_error']         = 'تعذر تفعيل الموديول.';
$lang['admin_modules_enable_success']       = 'تم تفعيل الموديول بنجاح.';
$lang['admin_modules_install']              = 'تنصيب';
$lang['admin_modules_install_error']        = 'تعذر تنصيب الموديول %s.';
$lang['admin_modules_install_success']      = 'تم تنصيب الموديول بنجاح.';
$lang['admin_modules_location_application'] = 'خاص بهذا التطبيق';
$lang['admin_modules_location_core']        = 'مشترك بين جميع التطبيقات';
$lang['admin_modules_location_public']      = 'عام لهذا التطبيق';
$lang['admin_modules_location_select']      = '&#151; اختر موقعا &#151;';
$lang['admin_modules_upload']               = 'رفع موديول';
$lang['admin_modules_upload_error']         = 'تعذر تحميل الموديول.';
$lang['admin_modules_upload_success']       = 'تم تحميل الموديول بنجاح.';
$lang['admin_modules_upload_tip']           = 'إذا كان الموديول في ملف .zip مضغوط, يمكنك تنصيبه بواسطة رفعه هنا.';

/**
 * ---------------------------------------------------------------
 * Themes Section
 * ---------------------------------------------------------------
 * Language lines for the themes management section.
 */
$lang['admin_themes_add']                 = 'إضافة قالب';
$lang['admin_themes_delete_confirm']      = 'هل أنت متأكد أنك تريد حذف القالب: <strong>%s</strong>؟';
$lang['admin_themes_delete_error']        = 'تعذر حذف القالب.';
$lang['admin_themes_delete_error_active'] = 'لا يمكنك حذف القالب المفعل.';
$lang['admin_themes_delete_success']      = 'تم حذف القالب بنجاح.';
$lang['admin_themes_enable_confirm']      = 'هل أنت متأكد أنك تريد تفعيل القالب: <strong>%s</strong>؟';
$lang['admin_themes_enable_error']        = 'تعذر تفعيل القالب.';
$lang['admin_themes_enable_success']      = 'تم تفعيل القالب بنجاح.';
$lang['admin_themes_install']             = 'تنصيب القالب';
$lang['admin_themes_install_error']       = 'تعذر تنصيب القالب.';
$lang['admin_themes_install_success']     = 'تم تنصيب القالب بنجاح.';
$lang['admin_themes_upload']              = 'رفع قالب';
$lang['admin_themes_upload_error']        = 'تعذر رفع القالب.';
$lang['admin_themes_upload_success']      = 'تم رفع القالب بنجاح.';

/**
 * ---------------------------------------------------------------
 * Languages Section
 * ---------------------------------------------------------------
 * Language lines for the languages management section.
 */
$lang['admin_languages_default_confirm']        = 'هل أنت متأكد من أنك تريد جعل هذه اللغة هي اللغة الافتراضية للموقع؟';
$lang['admin_languages_default_error']          = 'تعذر تغيير اللغة الافتراضية للموقع.';
$lang['admin_languages_default_error_nochange'] = 'هذه اللغة هي أصلاً اللغة الافتراضية للموقع.';
$lang['admin_languages_default_success']        = 'تم تغيير اللغة الافتراضية للموقع بنجاح.';
$lang['admin_languages_disable_all_confirm']    = 'هل أنت متأكد أنك تريد تعطيل جميع اللغات؟';
$lang['admin_languages_disable_all_error']      = 'تعذر تعطيل جميع اللغات.';
$lang['admin_languages_disable_all_success']    = 'تم تعطيل جميع اللغات بنجاح.';
$lang['admin_languages_disable_confirm']        = 'هل أنت متأكد من أنك تريد تعطيل اللغة: <strong>%s</strong>؟';
$lang['admin_languages_disable_error']          = 'تعذر تعطيل اللغة.';
$lang['admin_languages_disable_error_nochange'] = 'هذه اللغة معطلة أصلاً..';
$lang['admin_languages_disable_success']        = 'تم تعطيل اللغة بنجاح.';
$lang['admin_languages_enable_all_confirm']     = 'هل أنت متأكد أنك تريد تمكين جميع اللغات؟';
$lang['admin_languages_enable_all_error']       = 'تعذر تمكين جميع اللغات.';
$lang['admin_languages_enable_all_success']     = 'تم تمكين جميع اللغات بنجاح.';
$lang['admin_languages_enable_confirm']         = 'هل أنت متأكد من أنك تريد تفعيل اللغة: <strong>%s</strong>؟';
$lang['admin_languages_enable_error']           = 'تعذر تفعيل اللغة.';
$lang['admin_languages_enable_error_nochange']  = 'هذه اللغة مفعلة أصلاً.';
$lang['admin_languages_enable_success']         = 'تم تفعيل اللغة بنجاح.';
$lang['admin_languages_tip']                    = 'تفعيل، تعطيل وتعيين اللغة الافتراضية للموقع. اللغات المفعلة متاحة لزوار الموقع.';
