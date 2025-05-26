<?php
defined('BASEPATH') OR die;

/**
 * Resend activation link.
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

لقد طلبت مؤخرًا رابطاً جديداً لتنشيط حسابك على {site_anchor} لأن حسابك لم يكن مفعلاً.
لتنشيط حسابك، انقر على الرابط التالي أو انسخه في متصفحك:
{link}

إذا لم تطلب ذلك، الرجاء تجاهل هذه الرسالة..

تم طلب هذا الإجراء من عنوان IP هذا: {ip_link}.

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

Recentemente, você solicitou um novo link de ativação no {site_anchor} porque sua conta não estava ativa.
Para ativar sua conta, clique no seguinte link ou copie e cole no seu navegador:
{link}

Se você não solicitou isso, nenhuma ação adicional é necessária.

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

Скоро заявихте нов линк за активация в {site_anchor}, тъй като акаунтът ви все още не беше активен.
За да активирате акаунта си, кликнете върху следния линк или го копирайте и поставете в браузъра си:
{link}

Ако не сте направили тази заявка, не се изисква допълнително действие.

Тази заявка е направена от следния IP адрес: {ip_link}

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

您最近在 {site_anchor} 请求了一个新的激活链接，因为您的帐户尚未激活。
要激活您的帐户，请单击以下链接或将其复制粘贴到浏览器中：
{link}

如果您未请求此操作，则无需采取进一步的操作。

此操作是从以下 IP 地址请求的：{ip_link}。

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

Nedávno jste si na {site_anchor} vyžádali nový aktivační odkaz, protože váš účet nebyl aktivní.
Pro aktivaci účtu klikněte na následující odkaz nebo jej zkopírujte a vložte do prohlížeče:
{link}

Pokud jste o tento odkaz nežádali, nemusíte podnikat žádné další kroky.

Tato žádost byla odeslána z této IP adresy: {ip_link}

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

Vous avez récemment demandé un nouveau lien d'activation sur {site_anchor}, car votre compte n'était pas actif.
Pour activer votre compte, cliquez sur le lien suivant ou copiez-collez-le dans votre navigateur:
{link}

Si vous ne l'avez pas demandé, aucune autre action n'est requise.

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

du hast kürzlich einen neuen Aktivierungslink auf {site_anchor} angefordert, weil dein Konto nicht aktiv war.
Um dein Konto zu aktivieren, klicke auf den folgenden Link oder kopiere ihn in deinen Browser:
{link}

Wenn du dies nicht angefordert hast, ist keine weitere Aktion erforderlich.

Diese Aktion wurde von dieser IP-Adresse angefordert: {ip_link}.

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

आपने हाल ही में {site_anchor} पर एक नया सक्रियण लिंक मांगा है क्योंकि आपका खाता सक्रिय नहीं था।
अपने खाते को सक्रिय करने के लिए निम्नलिखित लिंक पर क्लिक करें या इसे अपने ब्राउज़र में कॉपी-पेस्ट करें:
{link}

यदि आपने यह अनुरोध नहीं किया है, तो कोई और कार्रवाई करने की आवश्यकता नहीं है।

यह कार्रवाई इस आईपी पते से मांगी गई थी: {ip_link}।

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

Anda baru-baru ini meminta tautan aktivasi baru di {site_anchor} karena akun Anda belum aktif.
Untuk mengaktifkan akun Anda, klik tautan berikut atau salin-tempel ke peramban Anda:
{link}

Jika Anda tidak meminta ini, tidak perlu tindakan lebih lanjut.

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

Hai recentemente richiesto un nuovo link di attivazione su {site_anchor} perché il tuo account non era attivo.
Per attivare il tuo account, clicca sul seguente link o copialo e incollalo nel tuo browser:
{link}

Se non hai fatto questa richiesta, non è necessaria alcuna ulteriore azione.

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

あなたは最近、{site_anchor} で新しいアクティベーションリンクをリクエストしました。なぜならあなたのアカウントがアクティブではなかったからです。
アカウントをアクティベートするには、以下のリンクをクリックするか、ブラウザにコピーして貼り付けてください：
{link}

もしリクエストしていない場合は、何もする必要はありません。

このアクションは次のIPアドレスからリクエストされました： {ip_link}。

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

회원님의 계정이 아직 활성화되지 않아 {site_anchor}에서 새 활성화 링크를 요청하셨습니다.
계정을 활성화하려면 아래 링크를 클릭하거나 브라우저에 복사하여 붙여넣어 주세요:
{link}

이 요청을 본인이 하지 않으셨다면 별도로 조치하실 필요는 없습니다.

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

شما به تازگی یک لینک فعال سازی جدید در {site_anchor} درخواست داده اید زیرا حساب شما فعال نبود.
برای فعال سازی حساب خود، روی لینک زیر کلیک کنید یا آن را در مرورگر خود کپی و جای گذاری کنید:
{link}

اگر این درخواست از طرف شما نبوده است، نیازی به انجام اقدام دیگری نیست.

این درخواست از این آدرس آی پی انجام شده است: {ip_link}.

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

Niedawno poprosiłeś o nowy link aktywacyjny na {site_anchor}, ponieważ Twoje konto nie było aktywne.
Aby aktywować swoje konto, kliknij w poniższy link lub skopiuj go i wklej w przeglądarce:
{link}

Jeśli to nie Ty złożyłeś ten wniosek, nie musisz podejmować żadnych dalszych działań.

To działanie zostało zgłoszone z tego adresu IP: {ip_link}.

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

Recentemente, solicitou um novo link de ativação no {site_anchor} porque a sua conta não estava ativa.
Para ativar a sua conta, clique no seguinte link ou copie e cole no seu browser:
{link}

Se não o tiver solicitado, nenhuma ação adicional é necessária.

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

Вы недавно запросили новую ссылку активации на {site_anchor}, потому что ваш аккаунт не был активен.
Для активации вашего аккаунта нажмите на следующую ссылку или скопируйте ее в браузер:
{link}

Если вы не делали такого запроса, дополнительных действий не требуется.

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

Recientemente has solicitado un nuevo enlace de activación en {site_anchor} porque tu cuenta no estaba activa.
Para activar tu cuenta, haz clic en el siguiente enlace o cópialo y pégalo en tu navegador:
{link}

Si no solicitaste esto, no es necesario tomar ninguna medida adicional.

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

您最近在 {site_anchor} 請求了一個新的激活鏈接，因爲您的帳戶尚未激活。
要激活您的帳戶，請單擊以下鏈接或將其複製粘貼到瀏覽器中：
{link}

如果您未請求此操作，則無需採取進一步的操作。

此操作是從以下 IP 地址請求的：{ip_link}。

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

Hesabınız aktif olmadığı için {site_anchor} üzerinde yeni bir etkinleştirme bağlantısı talep ettiniz.
Hesabınızı etkinleştirmek için aşağıdaki bağlantıya tıklayın veya tarayıcınıza yapıştırın:
{link}

Eğer bu talebi siz yapmadıysanız, başka bir işlem yapmanıza gerek yoktur.

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

Bạn vừa yêu cầu một liên kết kích hoạt mới trên {site_anchor} vì tài khoản của bạn chưa được kích hoạt.
Để kích hoạt tài khoản, hãy nhấp vào liên kết sau hoặc sao chép và dán vào trình duyệt của bạn:
{link}

Nếu bạn không yêu cầu điều này, bạn không cần thực hiện thêm hành động nào.

Yêu cầu này được thực hiện từ địa chỉ IP: {ip_link}.

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

You have recently requested a new activation link on {site_anchor} because your account was not active.
To activate your account click on the following link or copy-paste it in your browser:
{link}

If you did not request this, no further action is required.

This action was requested from this IP address: {ip_link}.

Very kind regards,
-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_resend_activation', $message);
