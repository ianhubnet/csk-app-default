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
$lang['admin_components']      = 'Composants';
$lang['admin_content']         = 'Contenu';
$lang['admin_database_backup'] = 'Sauvegardes de données';
$lang['admin_extensions']      = 'Extensions';
$lang['admin_footer_left']     = 'Merci de votre création avec <a href="%s" target="_blank">%s</a>.';
$lang['admin_footer_right']    = 'Version: <strong>%s</strong>';
$lang['admin_logs']            = 'Logs du système';
$lang['admin_media']           = 'Médiathèque';
$lang['admin_modules']         = 'Modules';
$lang['admin_reports']         = 'Journal des actions';
$lang['admin_system']          = 'Système';
$lang['admin_themes']          = 'Thèmes';
$lang['admin_update_notice']   = '<a href="%2$s" target="_blank">La version <strong>%1$s<strong> est maintenant disponible !<a>';
$lang['admin_users']           = 'Utilisateurs';
$lang['admin_view_site']       = 'Voir le site';
$lang['settings_global']       = 'Paramètres globaux';
$lang['settings_sysinfo']      = 'Informations système';

/**
 * ---------------------------------------------------------------
 * Database & Backup Section
 * ---------------------------------------------------------------
 * Language lines for the database management section.
 */
$lang['admin_database_backup_clean_error']    = 'Impossible de nettoyer les anciens fichiers de sauvegarde.';
$lang['admin_database_backup_clean_success']  = '%d fichiers de sauvegarde supprimés. %d espace disque libéré.';
$lang['admin_database_backup_create']         = 'Créer une sauvegarde';
$lang['admin_database_backup_create_confirm'] = 'Êtes-vous sûr de vouloir créer une sauvegarde maintenant?';
$lang['admin_database_backup_create_error']   = 'Impossible de créer le fichier de sauvegarde. Assurez-vous que le dossier <strong>%s</strong> est accessible en écriture.';
$lang['admin_database_backup_create_success'] = 'Le fichier de sauvegarde de la base de données <strong>%s</strong> créé avec succès.';
$lang['admin_database_backup_delete_confirm'] = 'Êtes-vous sûr de vouloir supprimer ces fichiers de sauvegarde?';
$lang['admin_database_backup_delete_error']   = 'Impossible de supprimer les fichiers de sauvegarde sélectionnés.';
$lang['admin_database_backup_delete_success'] = 'Fichiers de sauvegarde supprimés avec succès.';
$lang['admin_database_backup_lock_confirm']   = 'Êtes-vous sûr de vouloir verrouiller ces fichiers de sauvegarde?';
$lang['admin_database_backup_lock_error']     = 'Impossible de verrouiller les fichiers de sauvegarde sélectionnés.';
$lang['admin_database_backup_lock_success']   = 'Fichiers de sauvegarde verrouillés avec succès.';
$lang['admin_database_backup_locked_error']   = 'Impossible de supprimer les fichiers de sauvegarde verrouillés.';
$lang['admin_database_backup_missing_error']  = 'Le fichier de sauvegarde est introuvable.';
$lang['admin_database_backup_unlock_confirm'] = 'Êtes-vous sûr de vouloir déverrouiller ces fichiers de sauvegarde?';
$lang['admin_database_backup_unlock_error']   = 'Impossible de déverrouiller les fichiers de sauvegarde sélectionnés.';
$lang['admin_database_backup_unlock_success'] = 'Fichiers de sauvegarde déverrouillés avec succès.';
$lang['admin_database_prune']                 = 'Nettoyer';
$lang['admin_database_prune_confirm']         = 'Êtes-vous sûr de vouloir nettoyer la base de données? Une sauvegarde sera créée avant l\'exécution.';
$lang['admin_database_prune_error']           = 'Impossible de nettoyer la base de données.';
$lang['admin_database_prune_next']            = 'Prochain nettoyage: <strong>%s</strong>';
$lang['admin_database_prune_success']         = 'Base de données nettoyée avec succès.';

/**
 * ---------------------------------------------------------------
 * System Logs Section
 * ---------------------------------------------------------------
 * Language lines for the system logs section.
 */
$lang['admin_logs_delete']         = 'Supprimer les logs';
$lang['admin_logs_delete_confirm'] = 'Êtes-vous sûr de vouloir supprimer les fichiers de log sélectionnés?';
$lang['admin_logs_delete_error']   = 'Impossible de supprimer les fichiers de log.';
$lang['admin_logs_delete_success'] = 'Fichiers de log supprimés avec succès.';
$lang['admin_logs_error_disabled'] = 'Le système de logs est actuellement désactivé.';
$lang['admin_logs_error_empty']    = 'Aucun journal trouvé.';
$lang['admin_logs_error_missing']  = 'Le fichier de log n\'a pas pu être localisé, soit il était vide.';
$lang['admin_logs_tip']            = 'Les fichiers de log peuvent rapidement devenir volumineux. Pensez à supprimer les anciens fichiers de temps à autre.';

/**
 * ---------------------------------------------------------------
 * System Settings Section
 * ---------------------------------------------------------------
 * Language lines for the admin dashboard settings section.
 */

// Page Titles
$lang['settings_captcha']  = 'Paramètres du Captcha';
$lang['settings_datetime'] = 'Paramètres de date et heure';
$lang['settings_discord']  = 'Paramètres Discord';
$lang['settings_email']    = 'Paramètres des emails';
$lang['settings_facebook'] = 'Paramètres Facebook';
$lang['settings_github']   = 'Paramètres GitHub';
$lang['settings_google']   = 'Paramètres Google';
$lang['settings_linkedin'] = 'Paramètres LinkedIn';
$lang['settings_manifest'] = 'Paramètres du manifeste';
$lang['settings_upload']   = 'Paramètres des téléchargements';
$lang['settings_users']    = 'Paramètres des utilisateurs';

// Settings Fields & Help Texts
$lang['admin_email']                     = 'E-mail de l\'administrateur';
$lang['admin_email_tip']                 = 'L\'adresse e-mail à laquelle les notifications de site sont envoyées.';
$lang['alert_login_failed_tip']          = 'Alerter les utilisateurs de toute tentative de connexion échouée à leur compte.';
$lang['alert_login_success_tip']         = 'Informer les utilisateurs de toute connexion réussie à leur compte.';
$lang['allow_multi_session']             = 'Sessions multiples';
$lang['allow_multi_session_tip']         = 'Autoriser plusieurs utilisateurs à se connecter au même compte en même temps.';
$lang['allow_oauth']                     = 'Connexion via des tiers';
$lang['allow_oauth_tip']                 = 'Autoriser les utilisateurs à se connecter avec des fournisseurs tiers.';
$lang['allow_quick_login']               = 'Autoriser la connexion en un clic';
$lang['allow_quick_login_tip']           = 'Permettre aux utilisateurs de se connecter via un lien e-mail sécurisé valable 15 minutes.';
$lang['allow_registration']              = 'Autoriser l\'enregistrement';
$lang['allow_registration_tip']          = 'Si vous autorisez les utilisateurs à créer un compte sur votre site.';
$lang['allow_remember']                  = 'Cookies de connexion';
$lang['allow_remember_tip']              = 'Enregistrer un cookie afin que les utilisateurs restent connectés même s\'ils ferment la fenêtre du site.';
$lang['allowed_types']                   = 'Extensions autorisées';
$lang['allowed_types_tip']               = 'Liste des extensions autorisées séparées par "&#124;".';
$lang['base_controller']                 = 'Contrôleur de base';
$lang['base_controller_tip']             = 'Le contrôleur utilisé pour votre page d\'accueil.';
$lang['contact_email']                   = 'Email de contact';
$lang['contact_email_tip']               = 'L\'adresse e-mail à laquelle les messages du formulaire de contact sont envoyés.';
$lang['date_format']                     = 'Format de la date';
$lang['date_format_tip']                 = 'Choisissez le format de date que vous souhaitez utiliser.';
$lang['demo_mode']                       = 'Mode Démo';
$lang['demo_mode_tip']                   = 'Choisissez si vous souhaitez mettre le site en mode démo.';
$lang['discord_auth']                    = 'Se connecter avec Discord';
$lang['discord_auth_tip']                = 'Autoriser les utilisateurs à se connecter avec <a href="https://discord.com/developers" target="_blank">Discord</a>.';
$lang['discord_client_id']               = 'ID Client';
$lang['discord_client_id_tip']           = 'Entrez l\'ID client de votre application Discord.';
$lang['discord_client_secret']           = 'Clé Secrète';
$lang['discord_client_secret_tip']       = 'Entrez la clé secrète de votre application Discord.';
$lang['email_activation']                = 'Activation par e-mail';
$lang['email_activation_tip']            = 'Forcer les utilisateurs à vérifier leurs adresses e-mail avant d\'être autorisés à se connecter.';
$lang['enable_profiler']                 = 'Activer le profileur';
$lang['enable_profiler_tip']             = 'Affiche les résultats d\'analyse comparative, les requêtes exécutées ainsi que d\'autres informations.';
$lang['facebook_app_id']                 = 'ID de l\'application Facebook';
$lang['facebook_app_id_tip']             = 'Entrez l\'ID de votre application Facebook.';
$lang['facebook_app_secret']             = 'Clé Secrète';
$lang['facebook_app_secret_tip']         = 'Entrez la clé secrète de votre application Facebook.';
$lang['facebook_auth']                   = 'Se connecter avec Facebook';
$lang['facebook_auth_tip']               = 'Autoriser les utilisateurs à se connecter avec <a href="https://developers.facebook.com/apps/" target="_blank">Facebook</a>.';
$lang['facebook_pixel_id']               = 'ID du Facebook Pixel';
$lang['facebook_pixel_id_tip']           = 'Votre ID du Facebook Pixel. Utilisé pour suivre les visites et les conversions. Vous le trouverez dans le Gestionnaire d’événements, sous les paramètres de votre pixel.';
$lang['github_auth']                     = 'Se connecter avec GitHub';
$lang['github_auth_tip']                 = 'Autoriser les utilisateurs à se connecter avec <a href="https://github.com/" target="_blank">GitHub</a>.';
$lang['github_client_id']                = 'ID Client';
$lang['github_client_id_tip']            = 'Entrez l\'ID client de votre application GitHub.';
$lang['github_client_secret']            = 'Clé Secrète';
$lang['github_client_secret_tip']        = 'Entrez la clé secrète de votre application GitHub.';
$lang['google_analytics_id']             = 'ID Google Analytics';
$lang['google_analytics_id_tip']         = 'Entrez votre ID de suivi Google Analytics.';
$lang['google_auth']                     = 'Se connecter avec Google';
$lang['google_auth_tip']                 = 'Autoriser les utilisateurs à se connecter avec <a href="https://console.cloud.google.com/" target="_blank">Google</a>.';
$lang['google_client_id']                = 'ID Client';
$lang['google_client_id_tip']            = 'Entrez l\'ID client de votre application Google.';
$lang['google_client_secret']            = 'Clé Secrète';
$lang['google_client_secret_tip']        = 'Entrez la clé secrète de votre application Google.';
$lang['google_site_verification']        = 'Google Site Verification';
$lang['google_site_verification_tip']    = 'Entrez le code de vérification de votre site Google.';
$lang['google_tagmanager_id']            = 'ID Google Tag Manager';
$lang['google_tagmanager_id_tip']        = 'Entrez l\'ID du conteneur. Laissez vide si vous souhaitez utiliser Google Analytics à la place.';
$lang['image_watermark']                 = 'Ajouter un Filigrane';
$lang['image_watermark_tip']             = 'Activez pour ajouter un filigrane aux images téléchargées.';
$lang['imgur_client_id']                 = 'ID Client';
$lang['imgur_client_id_tip']             = 'Entrez l\'ID client de votre application Imgur.';
$lang['imgur_client_secret']             = 'Clé Secrète';
$lang['imgur_client_secret_tip']         = 'Entrez la clé secrète de votre application Imgur.';
$lang['linkedin_auth']                   = 'Se connecter avec LinkedIn';
$lang['linkedin_auth_tip']               = 'Autoriser les utilisateurs à se connecter avec <a href="https://developer.linkedin.com/" target="_blank">LinkedIn</a>.';
$lang['linkedin_client_id']              = 'ID Client';
$lang['linkedin_client_id_tip']          = 'Entrez l\'ID client de votre application LinkedIn.';
$lang['linkedin_client_secret']          = 'Clé Secrète';
$lang['linkedin_client_secret_tip']      = 'Entrez la clé secrète de votre application LinkedIn.';
$lang['login_fail_allowed_attempts']     = 'Tentatives de connexion autorisées';
$lang['login_fail_allowed_attempts_tip'] = 'Nombre de tentatives échouées avant un verrouillage court.';
$lang['login_fail_allowed_lockouts']     = 'Verrouillages courts autorisés';
$lang['login_fail_allowed_lockouts_tip'] = 'Nombre de verrouillages courts avant qu\'un verrouillage long s\'applique.';
$lang['login_fail_enabled']              = 'Verrouillage après échec de la connexion';
$lang['login_fail_enabled_tip']          = 'Activer ou désactiver la protection contre les échecs de connexion.';
$lang['login_fail_long_lockout']         = 'Verrouillage long de la connexion';
$lang['login_fail_long_lockout_tip']     = 'Durée du verrouillage en heures après plusieurs verrouillages courts.';
$lang['login_fail_short_lockout']        = 'Verrouillage court de la connexion';
$lang['login_fail_short_lockout_tip']    = 'Durée du verrouillage en minutes après trop d\'échecs.';
$lang['login_type']                      = 'Type de connexion';
$lang['login_type_tip']                  = 'Les utilisateurs peuvent se connecter en utilisant des noms d\'utilisateur, des adresses e-mail ou les deux.';
$lang['mail_protocol']                   = 'Protocole de messagerie';
$lang['mail_protocol_tip']               = 'Choisissez le protocole de messagerie avec lequel vous souhaitez envoyer des e-mails.';
$lang['manual_activation']               = 'Activation manuelle';
$lang['manual_activation_tip']           = 'Vérification manuelle des comptes des utilisateurs.';
$lang['max_height']                      = 'Hauteur maximale';
$lang['max_height_tip']                  = 'La hauteur minimale en pixels. Zéro pour aucune limite.';
$lang['max_size']                        = 'Taille maximale';
$lang['max_size_tip']                    = 'La taille maximale (en kilo-octets) des fichiers envoyés. Zéro pour aucune limite.';
$lang['max_width']                       = 'Largeur maximale';
$lang['max_width_tip']                   = 'La largeur maximale en pixels. Zéro pour aucune limite.';
$lang['min_height']                      = 'Hauteur minimale';
$lang['min_height_tip']                  = 'La hauteur minimale en pixels. Zéro pour aucune limite.';
$lang['min_width']                       = 'Largeur minimale';
$lang['min_width_tip']                   = 'La largeur minimale en pixels. Zéro pour aucune limite.';
$lang['offline_access_level']            = 'Accès en mode maintenance';
$lang['offline_access_level_tip']        = 'Le niveau minimum requis pour se connecter en mode maintenance.';
$lang['per_page']                        = 'Éléments par page';
$lang['per_page_tip']                    = 'Combien d\'éléments sont affichés sur les pages utilisant la pagination.';
$lang['recaptcha_private_key']           = 'Clé privée reCAPTCHA';
$lang['recaptcha_private_key_tip']       = 'Entrez la clé privée reCAPTCHA fournie par Google.';
$lang['recaptcha_site_key']              = 'Clé de site reCAPTCHA';
$lang['recaptcha_site_key_tip']          = 'Entrez la clé de site reCAPTCHA fournie par Google.';
$lang['sendmail_path']                   = 'Chemin de Sendmail';
$lang['sendmail_path_tip']               = 'Entrez le chemin Sendmail. Par défaut: /usr/sbin/. Requis uniquement si vous utilisez le protocole Sendmail.';
$lang['server_email']                    = 'E-mail du serveur';
$lang['server_email_tip']                = 'L\'adresse e-mail utilisée pour envoyer des e-mails aux utilisateurs. Vous pouvez utiliser "noreply@..." ou une adresse e-mail existante.';
$lang['site_author']                     = 'Auteur du site';
$lang['site_author_tip']                 = 'Entrez l\'auteur du site si vous voulez ajouter la balise META auteur.';
$lang['site_background_color']           = 'Couleur de fond';
$lang['site_background_color_tip']       = 'Définit la couleur de fond pour l\'écran de démarrage qui apparaît lorsque l\'application se charge.';
$lang['site_description']                = 'Description du site';
$lang['site_description_tip']            = 'Entrez une courte description pour votre site Web.';
$lang['site_favicon']                    = 'Favicon du site';
$lang['site_favicon_tip']                = 'Entez l\'adresse de l\'image à utiliser comme favicon du site.';
$lang['site_keywords']                   = 'Mots clés du site';
$lang['site_keywords_tip']               = 'Entrez vos mots clés de site, séparés par des virgules.';
$lang['site_name']                       = 'Nom du site';
$lang['site_name_tip']                   = 'Entrez le nom de votre site Web.';
$lang['site_offline_tip']                = 'Choisissez si l\'accès du site doit être verrouillé au public.';
$lang['site_short_name']                 = 'Nom court du site';
$lang['site_short_name_tip']             = 'Version plus courte du nom, utilisée dans les espaces où l\'espace à l\'écran est limité, comme sous les icônes.';
$lang['site_theme_color']                = 'Couleur du thème';
$lang['site_theme_color_tip']            = 'Définit la couleur de la barre d\'adresse du navigateur lorsque l\'utilisateur consulte le site. Certains navigateurs mobiles, comme Chrome sur Android, utilisent cela pour offrir une expérience plus intégrée.';
$lang['smtp_crypto']                     = 'Chiffrement SMTP';
$lang['smtp_crypto_tip']                 = 'Choisissez le cryptage SMTP.';
$lang['smtp_host']                       = 'Hôte SMTP';
$lang['smtp_host_tip']                   = 'Entrez le nom d\'hôte SMTP (i.e.: smtp.gmail.com). Requis uniquement si vous utilisez le protocole SMTP.';
$lang['smtp_pass']                       = 'Mot de passe SMTP';
$lang['smtp_pass_tip']                   = 'Entrez le mot de passe de votre compte SMTP.';
$lang['smtp_port']                       = 'Port SMTP';
$lang['smtp_port_tip']                   = 'Entrez le numéro de port SMTP fourni par votre hôte. Requis uniquement si vous utilisez le protocole SMTP.';
$lang['smtp_user']                       = 'Nom d\'utilisateur SMTP';
$lang['smtp_user_tip']                   = 'Entrez le nom d\'utilisateur de votre compte SMTP.';
$lang['time_format']                     = 'Format de l\'heure';
$lang['time_format_tip']                 = 'Choisissez le format d\'heure que vous souhaitez utiliser.';
$lang['time_reference']                  = 'Fuseau horaire du site';
$lang['time_reference_tip']              = 'Sélectionnez le fuseau horaire de votre site dans lequel toutes les heures seront affichées.';
$lang['upload_path']                     = 'Chemin de téléchargement';
$lang['upload_path_tip']                 = 'Chemin d\'accès aux différents fichiers. Par défaut: content/uploads/';
$lang['upload_year_month']               = 'Dossiers basés sur l\'année et le mois';
$lang['upload_year_month_tip']           = 'Organiser les téléchargements dans des dossiers basés sur l\'année et le mois.';
$lang['use_captcha']                     = 'Utiliser Captcha';
$lang['use_captcha_tip']                 = 'Activer ou désactiver l\'utilisation de la sécurité par captcha.';
$lang['use_gravatar']                    = 'Utiliser Gravatar';
$lang['use_gravatar_tip']                = 'Utiliser Gravatar ou autorisez les utilisateurs à télécharger leurs avatars.';
$lang['use_imgur']                       = 'Téléverser sur Imgur.com';
$lang['use_imgur_tip']                   = 'Téléverser vos images sur Imgur (<a href="https://api.imgur.com/oauth2/addclient" target="_blank">requiert une app</a>).';
$lang['use_manifest']                    = 'Activer l\'application Web progressive';
$lang['use_manifest_tip']                = 'Un fichier "manifest.json" est un fichier JSON qui fournit des métadonnées sur un site web ou une application web, principalement pour améliorer le comportement du site sur les appareils mobiles et pour permettre son ajout à l\'écran d\'accueil de l\'utilisateur en tant qu\'application Web progressive (PWA).';
$lang['use_recaptcha']                   = 'Utiliser reCAPTCHA';
$lang['use_recaptcha_tip']               = 'Utiliser Google reCAPTCHA si activé, sinon utiliser le captcha par défaut si Utiliser Captcha est activé.';
$lang['wpa']                             = 'WPA';

// System Information
$lang['sysinfo_database_type']      = 'Type de la base de données';
$lang['sysinfo_database_version']   = 'Version de la base de données';
$lang['sysinfo_disable_functions']  = 'Disabled Functions (fonctions désactivées)';
$lang['sysinfo_display_errors']     = 'Display Errors (afficher les erreurs)';
$lang['sysinfo_file_uploads']       = 'File Uploads (transfert HTTP de fichiers)';
$lang['sysinfo_iconv']              = 'Iconv activé (conversion des chaînes)';
$lang['sysinfo_magic_quotes_gpc']   = 'Magic quotes (ajout antislash aux guillemets)';
$lang['sysinfo_max_input_vars']     = 'Nombre maximum de champs de saisie (Maximum Input Variables)';
$lang['sysinfo_mbstring']           = 'MBstring actif (interprétation des chaînes)';
$lang['sysinfo_open_basedir']       = 'Open Basedir (dossier limite d\'arborescence)';
$lang['sysinfo_output_buffering']   = 'Output Buffering (limitation du buffer de sortie)';
$lang['sysinfo_php_built_on']       = 'PHP exécuté sur';
$lang['sysinfo_php_info']           = 'Informations PHP';
$lang['sysinfo_php_settings']       = 'Paramètres PHP';
$lang['sysinfo_php_version']        = 'Version de PHP';
$lang['sysinfo_register_globals']   = 'Register Globals (EGPCS variables globales)';
$lang['sysinfo_safe_mode']          = 'Safe Mode (mode de sécurité PHP)';
$lang['sysinfo_session.auto_start'] = 'Session auto start (démarrer à chaque script)';
$lang['sysinfo_session.save_path']  = 'Session Save Path (répertoire de sessions)';
$lang['sysinfo_short_open_tag']     = 'Short open tags (balises courtes d\'ouverture)';
$lang['sysinfo_skeleton_version']   = 'Version Skeleton';
$lang['sysinfo_user_agent']         = 'Navigateur';
$lang['sysinfo_web_server']         = 'Serveur Web';
$lang['sysinfo_xml']                = 'XML activé (lire et écrire les fichiers XML)';
$lang['sysinfo_zip']                = 'Zip natif activé';
$lang['sysinfo_zlib']               = 'Zlib activé (lire et écrire les fichiers GZIP)';

/**
 * ---------------------------------------------------------------
 * Users Section
 * ---------------------------------------------------------------
 * Language lines for the users management section.
 */
$lang['admin_users_add']                = 'Nouvel utilisateur';
$lang['admin_users_all_users']          = 'Tous les utilisateurs';
$lang['admin_users_ban_confirm']        = 'Êtes-vous sûr de vouloir bannir cet utilisateur?';
$lang['admin_users_ban_error']          = 'Impossible de bannir cet utilisateur.';
$lang['admin_users_ban_success']        = 'Utilisateur banni avec succès.';
$lang['admin_users_delete_confirm']     = 'Êtes-vous sûr de vouloir supprimer cet utilisateur?';
$lang['admin_users_delete_error']       = 'Impossible de supprimer l\'utilisateur.';
$lang['admin_users_delete_success']     = 'Utilisateur supprimé avec succès.';
$lang['admin_users_disable_confirm']    = 'Êtes-vous sûr de vouloir désactiver ce compte?';
$lang['admin_users_disable_error']      = 'Impossible de désactiver le compte.';
$lang['admin_users_disable_success']    = 'Compte désactivé avec succès.';
$lang['admin_users_edit']               = 'Modifier l\'utilisateur';
$lang['admin_users_edit_error']         = 'Impossible de mettre à jour le compte.';
$lang['admin_users_edit_success']       = 'Compte mis à jour avec succès.';
$lang['admin_users_enable_confirm']     = 'Êtes-vous sûr de vouloir activer ce compte?';
$lang['admin_users_enable_error']       = 'Impossible d\'activer le compte.';
$lang['admin_users_enable_success']     = 'Compte activé avec succès.';
$lang['admin_users_groups']             = 'Groupes';
$lang['admin_users_logged']             = 'Utilisateurs connectés';
$lang['admin_users_mailer']             = 'Courrier de masse';
$lang['admin_users_mailer_to_banned']   = 'Envoyer aux utilisateurs bannis.';
$lang['admin_users_mailer_to_deleted']  = 'Envoyer aux utilisateurs supprimés.';
$lang['admin_users_mailer_to_disabled'] = 'Envoyer aux utilisateurs désactivés.';
$lang['admin_users_manage']             = 'Gérer les utilisateurs';
$lang['admin_users_remove_confirm']     = 'Êtes-vous sûr de vouloir supprimer définitivement ce compte et toutes ses données?';
$lang['admin_users_remove_error']       = 'Impossible de supprimer définitivement le compte et toutes ses données.';
$lang['admin_users_remove_success']     = 'Le compte ainsi que toutes ses données ont été supprimés avec succès.';
$lang['admin_users_restore_confirm']    = 'Êtes-vous sûr de vouloir restaurer ce compte?';
$lang['admin_users_restore_error']      = 'Impossible de restaurer le compte.';
$lang['admin_users_restore_success']    = 'Utilisateur restauré avec succès.';
$lang['admin_users_unban_confirm']      = 'Êtes-vous sûr de vouloir débanner cet utilisateur?';
$lang['admin_users_unban_error']        = 'Impossible de débanner cet utilisateur.';
$lang['admin_users_unban_success']      = 'Utilisateur débanné avec succès.';

/**
 * ---------------------------------------------------------------
 * Reports Section
 * ---------------------------------------------------------------
 * Language lines for the activity log section.
 */

// Title and Messages
$lang['admin_reports_clear']          = 'Vider le journal';
$lang['admin_reports_clear_confirm']  = 'Êtes-vous sûr de vouloir vider le journal des actions?';
$lang['admin_reports_clear_error']    = 'Impossible de vider le journal des actions.';
$lang['admin_reports_clear_success']  = 'Journal des actions vidé avec succès.';
$lang['admin_reports_latest_actions'] = 'Dernières actions';

// Various Activity Logs
$lang['report_clear_reports']        = '%s a effacé le journal des actions.';
$lang['report_language_default']     = '%s a changé la langue par défaut: <strong>%s</strong>';
$lang['report_language_disable']     = '%s a désactiver la langue: <strong>%s</strong>.';
$lang['report_language_enable']      = '%s a activé la langue: <strong>%s</strong>.';
$lang['report_module_delete']        = '%s a supprimé le module: <strong>%s</strong>.';
$lang['report_module_disable']       = '%s a désactivé le module: <strong>%s</strong>.';
$lang['report_module_enable']        = '%s a activé le module: <strong>%s</strong>.';
$lang['report_module_install']       = '%s a installé le module: <strong>%s</strong>.';
$lang['report_users_activate']       = 'Compte %s activé.';
$lang['report_users_activate_link']  = '%s a demandé un lien d\'activation.';
$lang['report_users_login_admin']    = '%s s\'est connecté(e) à l\'administration.';
$lang['report_users_login_discord']  = '%s s\'est connecté(e) depuis <u>Discord</u>.';
$lang['report_users_login_facebook'] = '%s s\'est connecté(e) depuis <u>Facebook</u>.';
$lang['report_users_login_github']   = '%s s\'est connecté(e) depuis <u>GitHub</u>.';
$lang['report_users_login_google']   = '%s s\'est connecté(e) depuis <u>Google</u>.';
$lang['report_users_login_linkedin'] = '%s s\'est connecté(e) depuis <u>LinkedIn</u>.';
$lang['report_users_login_site']     = '%s s\'est connecté(e) au site.';
$lang['report_users_recover']        = '%s a demandé une réinitialisation.';
$lang['report_users_register']       = 'Compte %s créé.';
$lang['report_users_reset']          = '%s a réinitialisé leur mdp.';
$lang['report_users_restore']        = 'Compte %s a été restauré.';

/**
 * ---------------------------------------------------------------
 * Media Library Section
 * ---------------------------------------------------------------
 * Language lines for the media library section.
 */
$lang['admin_media_delete_confirm']      = 'Êtes-vous sûr de vouloir supprimer les fichiers sélectionnés?';
$lang['admin_media_delete_error']        = 'Impossible de supprimer les fichiers.';
$lang['admin_media_delete_success']      = 'Fichiers supprimés avec succès.';
$lang['admin_media_file_delete_error']   = 'Impossible de supprimer le fichier.';
$lang['admin_media_file_delete_success'] = 'Fichier supprimé avec succès.';
$lang['admin_media_file_update_error']   = 'Impossible de mettre à jour le fichier.';
$lang['admin_media_file_update_success'] = 'Fichier mis à jour avec succès.';
$lang['admin_media_url_copied']          = 'URL copiée!';

/**
 * ---------------------------------------------------------------
 * Modules Section
 * ---------------------------------------------------------------
 * Language lines for the modules management section.
 */
$lang['admin_modules_add']                  = 'Ajouter un module';
$lang['admin_modules_delete_confirm']       = 'Êtes-vous sûr de vouloir supprimer le module: <strong>%s</strong>?';
$lang['admin_modules_delete_error']         = 'Impossible de supprimer le module.';
$lang['admin_modules_delete_success']       = 'Module supprimé avec succès.';
$lang['admin_modules_disable_confirm']      = 'Êtes-vous sûr de vouloir désactiver le module: <strong>%s</strong>?';
$lang['admin_modules_disable_error']        = 'Impossible de désactiver le module.';
$lang['admin_modules_disable_success']      = 'Module désactivé avec succès.';
$lang['admin_modules_enable_confirm']       = 'Êtes-vous sûr de vouloir activer le module: <strong>%s</strong>?';
$lang['admin_modules_enable_error']         = 'Impossible d\'activer le module.';
$lang['admin_modules_enable_success']       = 'Module activé avec succès.';
$lang['admin_modules_install']              = 'Installer le module';
$lang['admin_modules_install_error']        = 'Impossible d\'installer le module.';
$lang['admin_modules_install_success']      = 'Module installé avec succès.';
$lang['admin_modules_location_application'] = 'Privé à cette application';
$lang['admin_modules_location_core']        = 'Partagé entre toutes les applications';
$lang['admin_modules_location_public']      = 'Public à cette application';
$lang['admin_modules_location_select']      = '&#151; Emplacement &#151;';
$lang['admin_modules_upload']               = 'Téléverser un module';
$lang['admin_modules_upload_error']         = 'Impossible de téléverser le module.';
$lang['admin_modules_upload_success']       = 'Module téléversé avec succès.';
$lang['admin_modules_upload_tip']           = 'Si vous avez un module au format "zip", vous pouvez l\'installer en le téléversant ici.';

/**
 * ---------------------------------------------------------------
 * Themes Section
 * ---------------------------------------------------------------
 * Language lines for the themes management section.
 */
$lang['admin_themes_add']                 = 'Ajouter un thème';
$lang['admin_themes_delete_confirm']      = 'Êtes-vous sûr de vouloir supprimer le thème: <strong>%s</strong>?';
$lang['admin_themes_delete_error']        = 'Impossible de supprimer le thème.';
$lang['admin_themes_delete_error_active'] = 'Vous ne pouvez pas supprimer un thème activé.';
$lang['admin_themes_delete_success']      = 'Thème supprimé avec succès.';
$lang['admin_themes_enable_confirm']      = 'Êtes-vous sûr de vouloir activer le thème: <strong>%s</strong>?';
$lang['admin_themes_enable_error']        = 'Impossible d\'activer le thème.';
$lang['admin_themes_enable_success']      = 'Thème activé avec succès.';
$lang['admin_themes_install']             = 'Installer le thème';
$lang['admin_themes_install_error']       = 'Impossible d\'installer le thème.';
$lang['admin_themes_install_success']     = 'Thème installé avec succès.';
$lang['admin_themes_upload']              = 'Téléverser un thème';
$lang['admin_themes_upload_error']        = 'Impossible de téléverser le thème.';
$lang['admin_themes_upload_success']      = 'Thème téléversé avec succès.';

/**
 * ---------------------------------------------------------------
 * Languages Section
 * ---------------------------------------------------------------
 * Language lines for the languages management section.
 */
$lang['admin_languages_default_confirm']        = 'Êtes-vous sûr de vouloir faire de cette langue la langue par défaut du site?';
$lang['admin_languages_default_error']          = 'Impossible de changer la langue par défaut.';
$lang['admin_languages_default_error_nochange'] = 'Cette langue est déjà celle par défaut.';
$lang['admin_languages_default_success']        = 'Langue par défaut changée avec succès.';
$lang['admin_languages_disable_all_confirm']    = 'Êtes-vous sûr de vouloir désactiver toutes les langues?';
$lang['admin_languages_disable_all_error']      = 'Impossible de désactiver toutes les langues.';
$lang['admin_languages_disable_all_success']    = 'Toutes les langues ont été désactivées avec succès.';
$lang['admin_languages_disable_confirm']        = 'Êtes-vous sûr de vouloir désactiver la langue: <strong>%s</strong>?';
$lang['admin_languages_disable_error']          = 'Impossible de désactiver la langue.';
$lang['admin_languages_disable_error_nochange'] = 'Cette langue est déjà désactivée.';
$lang['admin_languages_disable_success']        = 'Langue désactivée avec succès.';
$lang['admin_languages_enable_all_confirm']     = 'Êtes-vous sûr de vouloir activer toutes les langues?';
$lang['admin_languages_enable_all_error']       = 'Impossible d\'activer toutes les langues.';
$lang['admin_languages_enable_all_success']     = 'Toutes les langues ont été activées avec succès.';
$lang['admin_languages_enable_confirm']         = 'Êtes-vous sûr de vouloir activer la langue: <strong>%s</strong>?';
$lang['admin_languages_enable_error']           = 'Impossible d\'activer la langue.';
$lang['admin_languages_enable_error_nochange']  = 'Cette langue est déjà activée.';
$lang['admin_languages_enable_success']         = 'Langue activée avec succès.';
$lang['admin_languages_tip']                    = 'Activer, désactiver et définir la langue par défaut du site. Les langues activées sont disponibles pour les visiteurs du site.';
