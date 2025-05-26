<?php
defined('BASEPATH') OR die;

/**
 * Two-factor authentication email
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Views
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.93
 */

/**
 * We make sure to use the correct translation if found.
 * Otherwise, we fall-back to English.
 */
isset($lang) OR $lang = $this->lang->idiom;

// Prepare the message depending on the language.
switch ($lang)
{
	/**
	 * Arabic version.
	 * @since 2.0
	 */
	case 'arabic':

		$message = <<<EOT
مرحبًا {name}،

رمز المصادقة الثنائية الخاص بك هو: <strong>{code}</strong>

يمكنك دائمًا تعطيل المصادقة الثنائية في إعدادات حسابك.

تم تنفيذ هذا الإجراء من عنوان IP هذا: {ip_link}.

مع أطيب التحيات،
-- فريق {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Brazillian-Portuguese version.
	 * @since 	2.92
	 */
	case 'brazilian':

		$message = <<<EOT
Hello {name},

Seu código de autenticação de dois fatores é: <strong>{code}</strong>

Você pode desativar a autenticação de dois fatores a qualquer momento nas configurações da sua conta.

Esta ação foi realizada a partir do seguinte endereço IP: {ip_link}.

Atenciosamente,
-- Equipe {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Bulgarian version.
	 * @since 	2.128
	 */
	case 'bulgarian':

		$message = <<<EOT
Здравейте {name},

Вашият код за двуфакторна автентикация е: <strong>{code}</strong>

Можете по всяко време да деактивирате двуфакторната автентикация от настройките на акаунта си.

Това действие е извършено от този IP адрес: {ip_link}.

С уважение,
-- Екипът на {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Chinese version.
	 * @since 2.64
	 */
	case 'chinese':

		$message = <<<EOT
你好 {name}，

您的双因素认证代码是：<strong>{code}</strong>

您可以随时在帐户设置中禁用双因素认证。

此操作是由 IP 地址 {ip_link} 执行的。

此致，
-- {site_name} 团队。
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Czech version.
	 * @since 	2.127
	 */
	case 'czech':

		$message = <<<EOT
Dobrý den, {name},

Váš ověřovací kód pro dvoufázové přihlášení je: <strong>{code}</strong>

Dvoufázové ověření můžete kdykoli vypnout v nastavení svého účtu.

Tato akce byla provedena z této IP adresy: {ip_link}.

S pozdravem,
-- Tým {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * French version.
	 * @since 2.0
	 */
	case 'french':

		$message = <<<EOT
Bonjour {name},

Votre code d'authentification à deux facteurs est : <strong>{code}</strong>

Vous pouvez toujours désactiver l'authentification à deux facteurs dans les paramètres de votre compte.

Cette action a été effectuée à partir de cette adresse IP: {ip_link}.

Cordialement,
-- Équipe {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * German version.
	 * @since 2.64
	 */
	case 'german':

		$message = <<<EOT
Hello {name},

Ihr Zwei-Faktor-Authentifizierungscode lautet: <strong>{code}</strong>

Sie können die Zwei-Faktor-Authentifizierung jederzeit in den Kontoeinstellungen deaktivieren.

Diese Aktion wurde von folgender IP-Adresse durchgeführt: {ip_link}.

Mit freundlichen Grüßen,
-- {site_name} Team.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Hindi version.
	 * @since 	2.92
	 */
	case 'hindi':

		$message = <<<EOT
नमस्ते {name},

आपका दो-चरणीय प्रमाणीकरण कोड है: <strong>{code}</strong>

आप हमेशा अपने खाता सेटिंग्स में दो-चरणीय प्रमाणीकरण को बंद कर सकते हैं।

यह कार्रवाई इस IP पते से की गई थी: {ip_link}।

सादर,
-- {site_name} टीम।
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Indonesian version.
	 * @since 	2.97
	 */
	case 'indonesian':

		$message = <<<EOT
Halo {name},

Kode autentikasi dua faktor Anda adalah: <strong>{code}</strong>

Anda selalu dapat menonaktifkan autentikasi dua faktor di pengaturan akun Anda.

Tindakan ini dilakukan dari alamat IP ini: {ip_link}.

Salam hormat,
-- Tim {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Italian version.
	 * @since 2.64
	 */
	case 'italian':

		$message = <<<EOT
Ciao {name},

Il tuo codice di autenticazione a due fattori è: <strong>{code}</strong>

Puoi sempre disattivare l'autenticazione a due fattori nelle impostazioni del tuo account.

Questa azione è stata eseguita da questo indirizzo IP: {ip_link}.

Cordiali saluti,
-- Il Team di {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Japanese version.
	 * @since 2.64
	 */
	case 'japanese':

		$message = <<<EOT
こんにちは {name}、

あなたの二要素認証コードは: <strong>{code}</strong> です。

アカウント設定でいつでも二要素認証を無効にすることができます。

このアクションは、次のIPアドレスから実行されました：{ip_link}。

敬具、
-- {site_name} チーム。
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Korean version.
	 * @since 	2.133
	 */
	case 'korean':

		$message = <<<EOT
안녕하세요, {name}님.

당신의 이중 인증 코드: <strong>{code}</strong>

언제든지 계정 설정에서 이중 인증을 비활성화할 수 있습니다.

이 작업은 다음 IP 주소에서 수행되었습니다: {ip_link}.

감사합니다.
-- {site_name} 팀.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Persian version.
	 * @since 2.111
	 */
	case 'persian':

		$message = <<<EOT
سلام {name}،

کد احراز هویت دومرحله ای شما: <strong>{code}</strong>

همیشه می توانید احراز هویت دومرحله ای را در تنظیمات حساب خود غیرفعال کنید.

این اقدام از این آدرس IP انجام شده است: {ip_link}.

با احترام،
-- تیم {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Polish version.
	 * @since 2.111
	 */
	case 'polish':

		$message = <<<EOT
Witaj {name},

Twój kod uwierzytelniania dwuskładnikowego to: <strong>{code}</strong>

Zawsze możesz wyłączyć uwierzytelnianie dwuskładnikowe w ustawieniach swojego konta.

Działanie zostało wykonane z tego adresu IP: {ip_link}.

Z wyrazami szacunku,
-- Zespół {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Portuguese version.
	 * @since 	2.133
	 */
	case 'portuguese':

		$message = <<<EOT
Olá {name},

O seu código de autenticação de dois fatores é: <strong>{code}</strong>

Pode desativar a autenticação de dois fatores a qualquer momento nas definições da sua conta.

Esta ação foi realizada a partir do seguinte endereço IP: {ip_link}.

Atenciosamente,
-- Equipa {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Russian version.
	 * @since 2.64
	 */
	case 'russian':

		$message = <<<EOT
Здравствуйте, {name},

Ваш код двухфакторной аутентификации: <strong>{code}</strong>

Вы всегда можете отключить двухфакторную аутентификацию в настройках своего аккаунта.

Это действие было выполнено с этого IP-адреса: {ip_link}.

С уважением,
-- Команда {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Spanish version.
	 * @since 2.64
	 */
	case 'spanish':

		$message = <<<EOT
Hola {name},

Tu código de autenticación de dos factores es: <strong>{code}</strong>

Siempre puedes desactivar la autenticación de dos factores en la configuración de tu cuenta.

Esta acción se realizó desde la siguiente dirección IP: {ip_link}.

Saludos cordiales,
-- Equipo de {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Taiwanese version.
	 * @since 2.112
	 */
	case 'taiwanese':

		$message = <<<EOT
你好 {name}，

您的雙因素認證代碼是：<strong>{code}</strong>

您可以隨時在帳戶設置中禁用雙因素認證。

此操作是由 IP 地址 {ip_link} 執行的。

此致，
-- {site_name} 團隊。
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Turkish version.
	 * @since 2.111
	 */
	case 'turkish':

		$message = <<<EOT
Merhaba {name},

İki faktörlü kimlik doğrulama kodunuz: <strong>{code}</strong>

Hesap ayarlarınızdan iki faktörlü kimlik doğrulamayı her zaman devre dışı bırakabilirsiniz.

Bu işlem şu IP adresinden gerçekleştirildi: {ip_link}.

Saygılarımızla,
-- {site_name} Ekibi.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Vietnamese version.
	 * @since 2.111
	 */
	case 'vietnamese':

		$message = <<<EOT
Xin chào {name},

Mã xác thực hai yếu tố của bạn là: <strong>{code}</strong>

Bạn luôn có thể tắt xác thực hai yếu tố trong cài đặt tài khoản của mình.

Hành động này được thực hiện từ địa chỉ IP: {ip_link}.

Trân trọng kính chào,
-- Đội ngũ {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * English version (Required).
	 * @since 2.0
	 */
	case 'english':
	default:

		$message = <<<EOT
Hello {name},

Your two-factor authentication code is: <strong>{code}</strong>

You can always disable the two-factor authentication on your account settings.

This action was performed from this IP address: {ip_link}.

Kind regards,
-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_two_factor', $message);
