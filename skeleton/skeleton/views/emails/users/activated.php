<?php
defined('BASEPATH') OR die;

/**
 * Account activated email.
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
	 * @since 	2.0
	 */
	case 'arabic':

		$message = <<<EOT
مرحبًا {name}،

تم تفعيل حسابك في {site_anchor} بنجاح. يمكنك الآن <a href="{login_url}" target="_blank">تسجيل الدخول</a>.

على أمل التمتع بإقامتك، يرجى قبول تحياتنا.

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

Sua conta no {site_anchor} foi ativada com sucesso. Agora você pode <a href="{login_url}" target="_blank">fazer login</a> a qualquer momento.

Esperamos que aproveite sua estadia. Aceite nossos cumprimentos.

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

Вашият акаунт в {site_anchor} беше успешно активиран. Можете да се <a href="{login_url}" target="_blank">влезете</a> по всяко време.

Надяваме се да се насладите на престоя си, приемете нашите любезни поздрави.

С уважение,
-- Екипът на {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Chinese version.
	 * @since 	2.64
	 */
	case 'chinese':

		$message = <<<EOT
你好，{name}，

您在 {site_anchor} 的帐户已成功激活。您现在可以随时 <a href="{login_url}" target="_blank">登录</a>。

希望您在这里过得愉快，请接受我们的诚挚问候。

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

Váš účet na {site_anchor} byl úspěšně aktivován. Nyní se můžete <a href="{login_url}" target="_blank">přihlásit</a> kdykoli budete chtít.

Doufáme, že si pobyt u nás užijete, přijměte naše srdečné pozdravy.

S pozdravem,
-- Tým {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * French version.
	 * @since 	2.0
	 */
	case 'french':

		$message = <<<EOT
Salut {name},

Votre compte sur {site_anchor} a bien été activé. Vous pouvez maintenant <a href="{login_url}" target="_blank">vous connecter</a> à tout moment.

En espérant que vous apprécierez votre séjour, veuillez accepter nos salutations distinguées.

-- Équipe {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * German version.
	 * @since 	2.64
	 */
	case 'german':

		$message = <<<EOT
Hallo {name},

dein Konto auf {site_anchor} wurde erfolgreich aktiviert. Du kannst dich jetzt jederzeit <a href="{login_url}" target="_blank">einloggen</a>.

Wir hoffen, dass du deinen Aufenthalt genießt. Bitte akzeptiere unsere freundlichen Grüße.

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

आपका {site_anchor} पर खाता सफलतापूर्वक सक्रिय कर दिया गया है। आप अब किसी भी समय <a href="{login_url}" target="_blank">लॉगिन</a> कर सकते हैं।

हमें उम्मीद है कि आप यहाँ रहना पसंद करेंगे, कृपया हमारे सादर नमस्कार स्वीकार करें।

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

Akun Anda di {site_anchor} telah berhasil diaktifkan. Anda sekarang dapat <a href="{login_url}" target="_blank">masuk</a> kapan saja Anda mau.

Semoga Anda menikmati kunjungan Anda, terimalah salam hormat kami.

-- Tim {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Italian version.
	 * @since 	2.64
	 */
	case 'italian':

		$message = <<<EOT
Ciao {name},

Il tuo account su {site_anchor} è stato attivato con successo. Ora puoi effettuare il <a href="{login_url}" target="_blank">login</a> ogni volta che desideri.

Speriamo che tu possa goderti la tua permanenza, per favore accetta i nostri cordiali saluti.

-- Il Team di {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Japanese version.
	 * @since 	2.64
	 */
	case 'japanese':

		$message = <<<EOT
こんにちは {name} さん、

{site_anchor} でのアカウントが正常にアクティブ化されました。いつでも <a href="{login_url}" target="_blank">ログイン</a> できるようになりました。

どうぞご滞在をお楽しみいただければ幸いです。どうぞよろしくお願いいたします。

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

{site_anchor}에서 귀하의 계정이 성공적으로 활성화되었습니다. 이제 언제든지 <a href="{login_url}" target="_blank">로그인</a> 하실 수 있습니다.

저희와 함께하는 동안 즐거운 시간 되시기를 바라며, 진심으로 감사드립니다.

감사합니다.
-- {site_name} 팀.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Persian version.
	 * @since 	2.111
	 */
	case 'persian':

		$message = <<<EOT
سلام {name}،

حساب شما در {site_anchor} با موفقیت فعال شد. اکنون می‌توانید هر زمان که بخواهید <a href="{login_url}" target="_blank">وارد شوید</a>.

امیدواریم از حضور خود لذت ببرید. لطفاً احترام ما را پذیرا باشید.

-- تیم {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Polish version.
	 * @since 	2.111
	 */
	case 'polish':

		$message = <<<EOT
Witaj {name},

Twoje konto na {site_anchor} zostało pomyślnie aktywowane. Teraz możesz <a href="{login_url}" target="_blank">zalogować się</a> w dowolnym momencie.

Mamy nadzieję, że będziesz zadowolony z pobytu. Prosimy o przyjęcie naszych serdecznych pozdrowień.

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

A sua conta no {site_anchor} foi ativada com sucesso. Agora pode <a href="{login_url}" target="_blank">iniciar sessão</a> a qualquer momento.

Esperamos que aproveite a sua estadia. Aceite os nossos cumprimentos.

Atenciosamente,
-- Equipa {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Russian version.
	 * @since 	2.64
	 */
	case 'russian':

		$message = <<<EOT
Привет, {name},

Ваш аккаунт на {site_anchor} успешно активирован. Теперь вы можете <a href="{login_url}" target="_blank">войти</a> в любое удобное для вас время.

Надеемся, что вам понравится ваше пребывание, пожалуйста, примите наши дружелюбные пожелания.

-- Команда {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Spanish version.
	 * @since 	2.64
	 */
	case 'spanish':

		$message = <<<EOT
Hola {name},

Tu cuenta en {site_anchor} se ha activado correctamente. Ahora puedes <a href="{login_url}" target="_blank">iniciar sesión</a> en cualquier momento.

Esperamos que disfrutes tu estancia, por favor, acepta nuestros cordiales saludos.

-- El equipo de {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Taiwanese version.
	 * @since 	2.112
	 */
	case 'taiwanese':

		$message = <<<EOT
你好，{name}，

您在 {site_anchor} 的帳戶已成功激活。您現在可以隨時 <a href="{login_url}" target="_blank">登錄</a>。

希望您在這裏過得愉快，請接受我們的誠摯問候。

-- {site_name} 團隊。
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Turkish version.
	 * @since 	2.111
	 */
	case 'turkish':

		$message = <<<EOT
Merhaba {name},

{site_anchor} üzerindeki hesabınız başarıyla etkinleştirildi. Artık istediğiniz zaman <a href="{login_url}" target="_blank">giriş yapabilirsiniz</a>.

Keyifli vakit geçirmenizi umar, saygılarımızı sunarız.

-- {site_name} Ekibi.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Vietnamese version.
	 * @since 	2.111
	 */
	case 'vietnamese':

		$message = <<<EOT
Xin chào {name},

Tài khoản của bạn tại {site_anchor} đã được kích hoạt thành công. Bây giờ bạn có thể <a href="{login_url}" target="_blank">đăng nhập</a> bất cứ khi nào bạn muốn.

Chúng tôi hy vọng bạn sẽ hài lòng khi sử dụng dịch vụ. Trân trọng kính chào.

-- Đội ngũ {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * English version (Required/Default).
	 * @since 	2.0
	 */
	case 'english':
	default:

		$message = <<<EOT
Hello {name},

Your account at {site_anchor} was successfully activated. You may now <a href="{login_url}" target="_blank">login</a> anytime you want.

Hoping you enjoy your stay, please accept our kind regards.

-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_activated', $message);
