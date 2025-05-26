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

لقد طلبت تسجيل الدخول بنقرة واحدة إلى حسابك على {site_anchor}.

انقر على الرابط أدناه أو انسخه والصقه في متصفحك لتسجيل الدخول فورًا:
{link}

سينتهي صلاحية هذا الرابط خلال 15 دقيقة. إذا لم تطلب هذا، يُرجى تجاهل هذه الرسالة.

تم طلب هذا الإجراء من عنوان IP هذا: {ip_link}.

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

Você solicitou um login com um clique na sua conta em {site_anchor}.

Clique no link abaixo ou copie e cole no seu navegador para entrar instantaneamente:
{link}

Este link expirará em 15 minutos. Se você não solicitou isso, ignore este e-mail.

Esta ação foi solicitada a partir do seguinte endereço IP: {ip_link}.

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

Поискахте вход с едно кликване в акаунта си на {site_anchor}.

Кликнете върху линка по-долу или го копирайте и поставете в браузъра си, за да влезете веднага:
{link}

Този линк ще изтече след 15 минути. Ако не сте го поискали, моля игнорирайте този имейл.

Тази заявка е направена от следния IP адрес: {ip_link}

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

您请求通过一键登录访问您在 {site_anchor} 的账户。

点击下面的链接或将其复制粘贴到浏览器中，即可立即登录：
{link}

此链接将在15分钟后失效。如果您并未发起此请求，请忽略此邮件。

此操作是由 IP 地址 {ip_link} 请求的。

诚挚问候，
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

Požádali jste o přihlášení jedním kliknutím ke svému účtu na {site_anchor}.

Klikněte na odkaz níže, nebo ho zkopírujte a vložte do prohlížeče a budete okamžitě přihlášeni:
{link}

Tento odkaz vyprší za 15 minut. Pokud jste o něj nežádali, prosím ignorujte tento e-mail.

Tato žádost byla odeslána z této IP adresy: {ip_link}

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

Vous avez demandé une connexion en un clic à votre compte sur {site_anchor}.

Cliquez sur le lien ci-dessous, ou copiez-collez-le dans votre navigateur, et vous serez connecté instantanément:
{link}

Ce lien expirera dans 15 minutes. Si vous n'avez pas demandé cela, veuillez ignorer cet e-mail.

Cette action a été demandée depuis cette adresse IP: {ip_link}.

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

Sie haben einen Ein-Klick-Login für Ihr Konto auf {site_anchor} angefordert.

Klicken Sie auf den untenstehenden Link oder kopieren Sie ihn in Ihren Browser, um sich sofort anzumelden:
{link}

Dieser Link läuft in 15 Minuten ab. Wenn Sie dies nicht angefordert haben, ignorieren Sie bitte diese E-Mail.

Diese Aktion wurde von folgender IP-Adresse angefordert: {ip_link}.

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

आपने {site_anchor} पर अपने खाते के लिए वन-क्लिक लॉगिन का अनुरोध किया है।

नीचे दिए गए लिंक पर क्लिक करें या इसे अपने ब्राउज़र में पेस्ट करें, और आप तुरंत लॉग इन हो जाएंगे:
{link}

यह लिंक 15 मिनट में समाप्त हो जाएगा। यदि आपने यह अनुरोध नहीं किया है, तो कृपया इस ईमेल को अनदेखा करें।

यह कार्रवाई इस IP पते से की गई थी: {ip_link}।

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

Anda meminta login satu klik ke akun Anda di {site_anchor}.

Klik tautan di bawah ini, atau salin dan tempel ke browser Anda untuk langsung masuk:
{link}

Tautan ini akan kedaluwarsa dalam 15 menit. Jika Anda tidak meminta ini, abaikan email ini.

Tindakan ini diminta dari alamat IP ini: {ip_link}.

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
Ciao {name},

Hai richiesto un accesso con un clic al tuo account su {site_anchor}.

Fai clic sul link qui sotto, oppure copialo e incollalo nel browser per accedere immediatamente:
{link}

Questo link scadrà tra 15 minuti. Se non hai richiesto questo accesso, ignora questa email.

Questa azione è stata richiesta da questo indirizzo IP: {ip_link}.

Cordiali saluti,
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
こんにちは、{name} さん

{site_anchor} のアカウントへのワンクリックログインをリクエストされました。

以下のリンクをクリックするか、ブラウザにコピーして貼り付けると、すぐにログインできます：
{link}

このリンクは15分で無効になります。心当たりがない場合は、このメールを無視してください。

このアクションは、次のIPアドレスからリクエストされました：{ip_link}。

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
안녕하세요 {name}님,

{site_anchor} 계정에 대한 원클릭 로그인 요청을 하셨습니다.

아래 링크를 클릭하거나 브라우저에 복사하여 붙여넣으면 즉시 로그인됩니다:
{link}

이 링크는 15분 후에 만료됩니다. 요청하지 않으셨다면 이 이메일을 무시해주세요.

이 요청은 다음 IP 주소에서 발생했습니다: {ip_link}

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

شما درخواست ورود با یک کلیک به حساب خود در {site_anchor} را داده اید.

روی لینک زیر کلیک کنید یا آن را در مرورگر خود کپی و جای گذاری کنید تا فوراً وارد شوید:
{link}

این لینک پس از ۱۵ دقیقه منقضی می شود. اگر شما این درخواست را نداده اید، لطفاً این ایمیل را نادیده بگیرید.

این درخواست از این آدرس IP ارسال شده است: {ip_link}.

با احترام،
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

Poprosiłeś o logowanie jednym kliknięciem do swojego konta na {site_anchor}.

Kliknij poniższy link lub skopiuj go i wklej do przeglądarki, aby zalogować się natychmiast:
{link}

Ten link wygaśnie za 15 minut. Jeśli to nie Ty go zamówiłeś, zignoruj tę wiadomość.

Prośba została zgłoszona z tego adresu IP: {ip_link}.

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

Você solicitou um login com um clique na sua conta em {site_anchor}.

Clique no link abaixo ou cole-o no navegador para fazer login instantaneamente:
{link}

Este link expirará em 15 minutos. Se não foi você quem solicitou, ignore este e-mail.

Esta ação foi solicitada a partir do seguinte endereço IP: {ip_link}.

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

Вы запросили вход в аккаунт на {site_anchor} с помощью одной ссылки.

Нажмите на ссылку ниже или скопируйте её в браузер, чтобы мгновенно войти:
{link}

Эта ссылка будет действовать в течение 15 минут. Если вы не запрашивали это, просто проигнорируйте письмо.


Это действие было запрошено с этого IP-адреса: {ip_link}.

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

Has solicitado un inicio de sesión con un solo clic en tu cuenta de {site_anchor}.

Haz clic en el siguiente enlace o cópialo y pégalo en tu navegador para iniciar sesión al instante:
{link}

Este enlace caducará en 15 minutos. Si no solicitaste esto, por favor ignora este correo.

Esta solicitud se realizó desde la siguiente dirección IP: {ip_link}.

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

您已請求在 {site_anchor} 使用一鍵登入您的帳戶。

請點擊以下連結，或將其複製並貼上到您的瀏覽器中，即可立即登入：
{link}

此連結將在 15 分鐘後失效。若非您本人請求，請忽略此封電子郵件。

此操作是由 IP 地址 {ip_link} 請求的。

誠摯問候，
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

{site_anchor} üzerindeki hesabınıza tek tıklamayla giriş yapmak istediniz.

Aşağıdaki bağlantıya tıklayın veya tarayıcınıza yapıştırarak anında giriş yapın:
{link}

Bu bağlantı 15 dakika içinde geçerliliğini yitirecektir. Bu talebi siz yapmadıysanız, lütfen bu e-postayı dikkate almayın.

Bu talep şu IP adresinden yapıldı: {ip_link}.

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

Bạn đã yêu cầu đăng nhập bằng một cú nhấp vào tài khoản của mình trên {site_anchor}.

Nhấp vào liên kết bên dưới hoặc sao chép-dán vào trình duyệt của bạn để đăng nhập ngay lập tức:
{link}

Liên kết này sẽ hết hạn sau 15 phút. Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.

Yêu cầu này được gửi từ địa chỉ IP: {ip_link}.

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

You requested a one-click login to your account on {site_anchor}.

Click the link below, or copy-paste it in your browser, and you will be logged in instantly:
{link}

This link will expire in 15 minutes. If you did not request this, please ignore this email.

This action was requested from this IP address: {ip_link}.

Kind regards,
-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_lost_password', $message);
