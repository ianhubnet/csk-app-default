<?php
defined('BASEPATH') OR die;

/**
 * Request email change email.
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

لقد تلقيت هذه الرسالة الإلكترونية لأننا تلقينا طلبًا لتغيير عنوان بريدك الالكتروني لحسابك على {site_anchor}.

انقر فوق الرابط التالي أو قم بنسخه ولصقه في المستعرض الخاص بك إذا كنت ترغب في المتابعة:
{link}

إذا لم تطلب تغيير عنوان بريدك الالكتروني، الرجاء تجاهل هذه الرسالة.

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

Você está recebendo este e-mail porque recebemos uma solicitação de alteração de e-mail para sua conta no {site_anchor}.

Clique no link a seguir ou copie e cole no seu navegador se desejar prosseguir:
{link}

Se você não solicitou a alteração de e-mail, nenhuma ação adicional é necessária.

Esta ação foi solicitada a partir do endereço IP: {ip_link}.

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

Получавате този имейл, защото бе получена заявка за промяна на имейл адреса за вашия акаунт в {site_anchor}.

Ако желаете да продължите, кликнете върху следния линк или го копирайте и поставете в браузъра си:
{link}

Ако не сте заявили промяна на имейл адреса, не се изисква никакво допълнително действие.

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

您收到此电子邮件是因为我们收到了您在 {site_anchor} 账户的电子邮件更改请求。

如果您希望继续，请单击以下链接或将其复制并粘贴到浏览器中：
{link}

如果您未请求更改电子邮件地址，则无需采取进一步操作。

此操作是从以下 IP 地址请求的：{ip_link}。

祝好，
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

Tento e-mail jste obdrželi, protože jsme obdrželi žádost o změnu e-mailové adresy pro váš účet na {site_anchor}.

Pokud si přejete pokračovat, klikněte na následující odkaz nebo jej zkopírujte a vložte do prohlížeče:
{link}

Pokud jste o změnu e-mailové adresy nežádali, nemusíte podnikat žádné další kroky.

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

Vous recevez cet e-mail, car nous avons reçu une demande de changement d'adresse e-mail pour votre compte sur {site_anchor}.

Cliquez sur le lien suivant ou copiez-collez-le dans votre navigateur si vous souhaitez continuer:
{link}

Si vous n'avez pas demandé à changer votre adresse e-mail, aucune autre action n'est requise.

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

du erhältst diese E-Mail, weil wir eine Anfrage zur Änderung deiner E-Mail-Adresse für dein Konto auf {site_anchor} erhalten haben.

Klicke auf den folgenden Link oder kopiere ihn in deinen Browser, wenn du fortfahren möchtest:
{link}

Wenn du diese Änderung nicht angefordert hast, ist keine weitere Aktion erforderlich.

Diese Aktion wurde von folgender IP-Adresse angefordert: {ip_link}.

Freundliche Grüße,
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

आपको यह ईमेल इसीलिए मिल रहा है क्योंकि हमने आपके {site_anchor} खाते के लिए ईमेल परिवर्तन का अनुरोध प्राप्त किया है।

यदि आप आगे बढ़ना चाहते हैं, तो कृपया निम्नलिखित लिंक पर क्लिक करें या इसे अपने ब्राउज़र में कॉपी-पेस्ट करें: {link}

यदि आपने ईमेल परिवर्तन का अनुरोध नहीं किया है, तो कोई और कार्रवाई की आवश्यकता नहीं है।

यह कार्रवाई इस IP पते से अनुरोध की गई थी: {ip_link}।

सादर नमस्कार,
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

Anda menerima email ini karena kami menerima permintaan perubahan email untuk akun Anda di {site_anchor}.

Klik tautan berikut atau salin dan tempel di browser Anda jika Anda ingin melanjutkan:
{link}

Jika Anda tidak meminta perubahan email, tidak ada tindakan lebih lanjut yang diperlukan.

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

Stai ricevendo questa email perché abbiamo ricevuto una richiesta di cambio email per il tuo account su {site_anchor}.

Clicca sul seguente link o copialo e incollalo nel tuo browser se desideri procedere:
{link}

Se non hai richiesto il cambio dell'email, non è necessaria alcuna ulteriore azione.

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
こんにちは {name} さん、

このメールは、{site_anchor} でアカウントのメールアドレス変更リクエストを受け取ったため、お送りしています。

続行を希望する場合は、次のリンクをクリックするか、ブラウザにコピーして貼り付けてください：
{link}

メールアドレスの変更をリクエストしなかった場合、追加のアクションは必要ありません。

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
안녕하세요, {name}님.

회원님의 {site_anchor} 계정에 대한 이메일 변경 요청을 받았기 때문에 이 메일을 보내드립니다.

계속 진행하려면 아래 링크를 클릭하거나 브라우저에 복사하여 붙여넣어 주세요:
{link}

이메일 변경을 요청하지 않으셨다면 이 메일은 무시하셔도 됩니다.

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

این ایمیل را دریافت می کنید زیرا ما درخواست تغییر آدرس ایمیل برای حساب شما در {site_anchor} دریافت کرده ایم.

برای ادامه، روی لینک زیر کلیک کنید یا آن را در مرورگر خود کپی و جای گذاری کنید:
{link}

اگر شما درخواست تغییر ایمیل نکرده اید، نیازی به انجام اقدام دیگری نیست.

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

Otrzymujesz tę wiadomość, ponieważ otrzymaliśmy prośbę o zmianę adresu e-mail dla Twojego konta na {site_anchor}.

Aby kontynuować, kliknij poniższy link lub skopiuj go i wklej do przeglądarki:
{link}

Jeśli nie zgłaszałeś prośby o zmianę adresu e-mail, nie musisz podejmować żadnych dalszych działań.

Żądanie zostało zgłoszone z tego adresu IP: {ip_link}.

Pozdrawiamy,
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

Está a receber este e-mail porque recebemos um pedido de alteração de e-mail para a sua conta no {site_anchor}.

Clique no seguinte link ou copie e cole no seu browser se desejar prosseguir:
{link}

Se não tiver solicitado a alteração de e-mail, não é necessária qualquer ação adicional.

Esta ação foi solicitada a partir do endereço IP: {ip_link}.

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

Вы получаете это письмо, потому что мы получили запрос на изменение адреса электронной почты для вашей учетной записи на {site_anchor}.

Нажмите на следующую ссылку или скопируйте ее в браузер, если вы хотите продолжить:
{link}

Если вы не запрашивали изменение адреса электронной почты, дополнительных действий не требуется.

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

Estás recibiendo este correo electrónico porque hemos recibido una solicitud para cambiar el correo electrónico de tu cuenta en {site_anchor}.

Haz clic en el siguiente enlace o cópialo y pégalo en tu navegador si deseas continuar:
{link}

Si no solicitaste un cambio de correo electrónico, no es necesario realizar ninguna otra acción.

Esta acción fue solicitada desde la siguiente dirección IP: {ip_link}.

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

您收到此電子郵件是因爲我們收到了您在 {site_anchor} 賬戶的電子郵件更改請求。

如果您希望繼續，請單擊以下鏈接或將其複製並粘貼到瀏覽器中：
{link}

如果您未請求更改電子郵件地址，則無需採取進一步操作。

此操作是從以下 IP 地址請求的：{ip_link}。

祝好，
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

Bu e-postayı, {site_anchor} hesabınız için bir e-posta değişikliği talebi aldığımız için alıyorsunuz.

Devam etmek istiyorsanız, aşağıdaki bağlantıya tıklayın veya tarayıcınıza yapıştırın:
{link}

Eğer e-posta değişikliği talep etmediyseniz, herhangi bir işlem yapmanıza gerek yoktur.

Bu işlem şu IP adresinden talep edildi: {ip_link}.

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

Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu thay đổi địa chỉ email cho tài khoản của bạn trên {site_anchor}.

Nhấp vào liên kết sau hoặc sao chép và dán nó vào trình duyệt của bạn nếu bạn muốn tiếp tục:
{link}

Nếu bạn không yêu cầu thay đổi email, bạn không cần thực hiện thêm hành động nào.

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

You are receiving this email because we received an email change request for your account on {site_anchor}.

Click on the following link or copy-paste it in your browser if you wish to proceed:
{link}

If you did not request an email change, no further action is required.

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
echo apply_filters('email_users_email_change_prep', $message);
