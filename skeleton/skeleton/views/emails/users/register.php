<?php
defined('BASEPATH') OR die;

/**
 * User registration email.
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

شكرًا لك على التسجيل في {site_anchor}. تم إنشاء حسابك ويجب تنشيطه قبل أن تتمكن من استخدامه.

لتنشيط حسابك، انقر على الرابط التالي أو انسخه في متصفحك:
{link}

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
Olá {name},

Obrigado por se registrar no {site_anchor}. Sua conta foi criada e deve ser ativada antes de poder utilizá-la.

Para ativar sua conta, clique no seguinte link ou copie e cole no seu navegador:
{link}

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

Благодарим ви, че се регистрирахте в {site_anchor}. Акаунтът ви е създаден, но трябва да бъде активиран, преди да можете да го използвате.

За да активирате акаунта си, кликнете на следния линк или го копирайте и поставете в браузъра си:
{link}

С най-добри пожелания,
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

感谢您在 {site_anchor} 注册。您的帐户已创建，但在使用之前必须激活。

要激活您的帐户，请单击以下链接或将其复制粘贴到浏览器中：
{link}

非常感谢，
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

Děkujeme za registraci na {site_anchor}. Váš účet byl vytvořen, ale je třeba jej aktivovat, než jej budete moci používat.

Pro aktivaci svého účtu klikněte na následující odkaz nebo jej zkopírujte a vložte do prohlížeče:
{link}

S přáním všeho dobrého,
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

Merci de vous être enregistré sur {site_anchor}. Votre compte est créé et doit être activé avant de pouvoir l'utiliser.

Pour activer votre compte, cliquez sur le lien suivant ou copiez-collez-le dans votre navigateur:
{link}

Amicalement,
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

vielen Dank für deine Registrierung auf {site_anchor}. Dein Konto wurde erstellt und muss aktiviert werden, bevor du es nutzen kannst.

Um dein Konto zu aktivieren, klicke bitte auf den folgenden Link oder kopiere ihn in deinen Browser:
{link}

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

{site_anchor} पर पंजीकरण करने के लिए धन्यवाद। आपका खाता बन गया है और इसे उपयोग करने से पहले सक्रिय करना आवश्यक है।

अपने खाते को सक्रिय करने के लिए निम्नलिखित लिंक पर क्लिक करें या इसे अपने ब्राउज़र में कॉपी-पेस्ट करें:
{link}

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

Terima kasih telah mendaftar di {site_anchor}. Akun Anda telah dibuat dan harus diaktifkan sebelum Anda dapat menggunakannya.

Untuk mengaktifkan akun Anda, klik tautan berikut atau salin-tempel ke peramban Anda:
{link}

Salam hormat,
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
Here’s the translation in Italian:

Ciao {name},

Grazie per esserti registrato su {site_anchor}. Il tuo account è stato creato e deve essere attivato prima di poterlo utilizzare.

Per attivare il tuo account, fai clic sul seguente link o copialo e incollalo nel tuo browser: {link}

Cordiali saluti,
-- Team di {site_name}.
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

{site_anchor} へのご登録、誠にありがとうございます。アカウントが作成されましたが、使用する前にアクティベートする必要があります。

アカウントをアクティベートするには、以下のリンクをクリックするか、ブラウザにコピーして貼り付けてください：
{link}

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

{site_anchor}에 가입해 주셔서 감사합니다. 회원님의 계정은 생성되었으며, 사용하시기 전에 활성화가 필요합니다.

계정을 활성화하려면 아래 링크를 클릭하거나 브라우저에 복사하여 붙여넣어 주세요:
{link}

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

از ثبت نام شما در {site_anchor} متشکریم. حساب شما ایجاد شده است و قبل از استفاده باید فعال شود.

برای فعال سازی حساب خود، روی لینک زیر کلیک کنید یا آن را در مرورگر خود کپی و جای گذاری کنید:
{link}

با احترام فراوان،
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

Dziękujemy za zarejestrowanie się na {site_anchor}. Twoje konto zostało utworzone i musi zostać aktywowane, zanim będzie można z niego korzystać.

Aby aktywować swoje konto, kliknij w poniższy link lub skopiuj go i wklej w przeglądarce:
{link}

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

Obrigado por se registar no {site_anchor}. A sua conta foi criada e deve ser ativada antes de a poder utilizar.

Para ativar a sua conta, clique no seguinte link ou copie e cole no seu browser:
{link}

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

Спасибо за регистрацию на {site_anchor}. Ваш аккаунт создан и должен быть активирован перед использованием.

Чтобы активировать ваш аккаунт, нажмите на следующую ссылку или скопируйте ее в свой браузер:
{link}

С наилучшими пожеланиями,
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

Gracias por registrarte en {site_anchor}. Tu cuenta ha sido creada y debe ser activada antes de que puedas utilizarla.

Para activar tu cuenta, haz clic en el siguiente enlace o cópialo y pégalo en tu navegador:
{link}

Saludos cordiales,
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

感謝您在 {site_anchor} 註冊。您的帳戶已創建，但在使用之前必須激活。

要激活您的帳戶，請單擊以下鏈接或將其複製粘貼到瀏覽器中：
{link}

非常感謝，
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

{site_anchor} sitesine kaydolduğunuz için teşekkür ederiz. Hesabınız oluşturuldu ve kullanmadan önce etkinleştirilmesi gerekiyor.

Hesabınızı etkinleştirmek için aşağıdaki bağlantıya tıklayın veya tarayıcınıza yapıştırın:
{link}

Saygılarımızla,
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

Cảm ơn bạn đã đăng ký tại {site_anchor}. Tài khoản của bạn đã được tạo và cần được kích hoạt trước khi sử dụng.

Để kích hoạt tài khoản, hãy nhấp vào liên kết sau hoặc sao chép và dán vào trình duyệt của bạn:
{link}

Trân trọng,
-- Đội ngũ {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * English version (Required).
	 * @since 	2.0
	 */
	case 'english':
	default:

		$message = <<<EOT
Hello {name},

Thank you for registering at {site_anchor}. Your account is created and must be activated before you can use it.

To activate your account click on the following link or copy-paste it in your browser:
{link}

Very kind regards,
-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_register', $message);
