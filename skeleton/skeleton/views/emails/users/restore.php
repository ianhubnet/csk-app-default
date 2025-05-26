<?php
defined('BASEPATH') OR die;

/**
 * Users restore account email.
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Views
 * @author 		Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link 		http://bit.ly/KaderGhb
 * @copyright 	Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since 		2.0
 * @version 	2.0
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

تؤكد هذه الرسالة الإلكترونية أنه تمت استعادة حسابك على الموقع {site_anchor} بنجاح.

مرحبًا بك مرة أخرى ونأمل أن تستمتع بإقامتك في هذا المرة.

أطيب التحيات،
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
Olá {name},

Este e-mail confirma que sua conta no {site_anchor} foi restaurada com sucesso.

Seja bem-vindo de volta, esperamos que desta vez aproveite sua estadia.

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

Този имейл потвърждава, че акаунтът ви в {site_anchor} беше успешно възстановен.

Добре дошли отново! Надяваме се този път престоят ви да бъде още по-приятен.

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
你好，{name}，

此邮件确认您在 {site_anchor} 的帐户已成功恢复。

欢迎回来，我们希望这一次您能在这里愉快度过。

亲切的问候，
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

Tento e-mail potvrzuje, že váš účet na {site_anchor} byl úspěšně obnoven.

Vítejte zpět! Doufáme, že si tentokrát pobyt u nás opravdu užijete.

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
Salut {name},

Cet e-mail confirme que votre compte sur {site_anchor} a bien été récupéré.

Bienvenue à nouveau avec nous et nous espérons que cette fois vous apprécierez votre séjour.

Amicalement,
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
Hallo {name},

diese E-Mail bestätigt, dass dein Konto auf {site_anchor} erfolgreich wiederhergestellt wurde.

Willkommen zurück bei uns, und wir hoffen, dass du dieses Mal deinen Aufenthalt genießen wirst.

Herzliche Grüße,
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

यह ईमेल पुष्टि करता है कि आपका खाता {site_anchor} पर सफलतापूर्वक पुनर्स्थापित हो गया है।

हमारे साथ फिर से स्वागत है और हम आशा करते हैं कि इस बार आप अपने समय का आनंद लेंगे।

शुभकामनाएँ,
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

Email ini mengonfirmasi bahwa akun Anda di {site_anchor} telah berhasil dipulihkan.

Selamat datang kembali, dan kami harap kali ini Anda akan menikmati masa tinggal Anda.

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

Questa email conferma che il tuo account su {site_anchor} è stato ripristinato con successo.

Benvenuto di nuovo con noi e speriamo che questa volta tu possa goderti il tuo soggiorno.

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
こんにちは {name} さん、

このメールは、{site_anchor} のアカウントが正常に復元されたことを確認するものです。

再びお帰りいただき、今回はより快適にお過ごしいただけることを願っています。

どうぞよろしくお願いいたします。
-- {site_name} チーム
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

이 이메일은 {site_anchor}에서 회원님의 계정이 성공적으로 복구되었음을 알려드립니다.

다시 돌아오신 것을 환영합니다. 이번에는 더욱 즐거운 시간을 보내시길 바랍니다.

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

این ایمیل تأیید می کند که حساب شما در {site_anchor} با موفقیت بازیابی شده است.

خوش آمدید! امیدواریم این بار از حضورتان لذت ببرید.

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

Ta wiadomość potwierdza, że Twoje konto na {site_anchor} zostało pomyślnie przywrócone.

Witamy ponownie i mamy nadzieję, że tym razem będziesz zadowolony z pobytu.

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

Este e-mail confirma que a sua conta no {site_anchor} foi restaurada com sucesso.

Seja bem-vindo de volta, esperamos que desta vez aproveite a sua estadia.

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
Привет, {name},

Это письмо подтверждает, что ваш аккаунт на {site_anchor} успешно восстановлен.

Добро пожаловать назад, и мы надеемся, что на этот раз вам понравится ваше пребывание.

С наилучшими пожеланиями,
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

Este correo electrónico confirma que tu cuenta en {site_anchor} ha sido restaurada con éxito.

Te damos la bienvenida de nuevo y esperamos que esta vez disfrutes de tu estancia.

Saludos cordiales,
-- El equipo de {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Taiwanese version.
	 * @since 2.112
	 */
	case 'taiwanese':

		$message = <<<EOT
你好，{name}，

此郵件確認您在 {site_anchor} 的帳戶已成功恢復。

歡迎回來，我們希望這一次您能在這裏愉快度過。

親切的問候，
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

Bu e-posta, {site_anchor} üzerindeki hesabınızın başarıyla geri yüklendiğini onaylar.

Tekrar hoş geldiniz! Umarız bu sefer keyifli vakit geçirirsiniz.

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

Email này xác nhận rằng tài khoản của bạn tại {site_anchor} đã được khôi phục thành công.

Chào mừng bạn quay lại, và chúng tôi hy vọng lần này bạn sẽ hài lòng khi sử dụng dịch vụ.

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

This email confirms that your account at {site_anchor} has been successfully restored.

Welcome back with us and we hope this time you will enjoy your stay.

Kind regards,
-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_restore_account', $message);
