<?php
defined('BASEPATH') OR die;

/**
 * User password changed email.
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

تؤكد هذه الرسالة الإلكترونية أنه تم تغيير كلمة مرورك على الموقع {site_anchor} بنجاح. يمكنك الآن <a href="{login_url}" target="_blank">تسجيل الدخول</a> باستخدام كلمة المرور الجديدة.

إذا لم تقم بتنفيذ هذا الإجراء، فالرجاء الاتصال بنا في أسرع وقت ممكن لحل هذه المشكلة.

تم تنفيذ هذا الإجراء من عنوان IP هذا: {ip_link}.

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

Este e-mail confirma que sua senha no {site_anchor} foi alterada com sucesso. Agora você pode <a href="{login_url}" target="_blank">fazer login</a> utilizando a nova senha.

Se você não realizou esta ação, por favor, entre em contato conosco o mais rápido possível para resolver esta questão.

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

Този имейл потвърждава, че паролата ви в {site_anchor} беше успешно променена. Вече можете да <a href="{login_url}" target="_blank">влезете</a> с новата парола.

Ако не сте извършили това действие, моля свържете се с нас възможно най-скоро, за да разрешим проблема.

Това действие е извършено от този IP адрес: {ip_link}.

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

这封邮件确认您在 {site_anchor} 的密码已成功更改。您现在可以使用新密码 <a href="{login_url}" target="_blank">登录</a>。

如果您未执行此操作，请尽快与我们联系以解决此问题。

此操作是由 IP 地址 {ip_link} 执行的。

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

Tento e-mail potvrzuje, že vaše heslo na {site_anchor} bylo úspěšně změněno. Nyní se můžete <a href="{login_url}" target="_blank">přihlásit</a> pomocí nového hesla.

Pokud jste tuto akci neprovedli vy, kontaktujte nás prosím co nejdříve, abychom problém vyřešili.

Tato akce byla provedena z této IP adresy: {ip_link}.

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

Cet e-mail confirme que votre mot de passe sur {site_anchor} a bien été modifié. Vous pouvez maintenant <a href="{login_url}" target="_blank">vous connecter</a> en utilisant le nouveau.

Si vous n'avez pas effectué cette action, veuillez nous contacter le plus vite possible pour résoudre ce problème.

Cette action a été effectuée à partir de cette adresse IP: {ip_link}.

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

diese E-Mail bestätigt, dass dein Passwort auf {site_anchor} erfolgreich geändert wurde. Du kannst dich jetzt mit dem neuen Passwort <a href="{login_url}" target="_blank">anmelden</a>.

Wenn du diese Aktion nicht durchgeführt hast, kontaktiere uns bitte so schnell wie möglich, um das Problem zu klären.

Diese Aktion wurde von folgender IP-Adresse durchgeführt: {ip_link}.

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

यह ईमेल पुष्टि करता है कि आपके पासवर्ड को {site_anchor} पर सफलतापूर्वक बदल दिया गया है। आप अब नए पासवर्ड का उपयोग करके <a href="{login_url}" target="_blank">लॉगिन</a> कर सकते हैं।

यदि आपने यह कार्रवाई नहीं की है, तो कृपया इस समस्या को हल करने के लिए जितनी जल्दी हो सके हमसे संपर्क करें।

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

Email ini mengonfirmasi bahwa kata sandi Anda di {site_anchor} telah berhasil diubah. Anda sekarang dapat <a href="{login_url}" target="_blank">masuk</a> menggunakan yang baru.

Jika Anda tidak melakukan tindakan ini, harap hubungi kami secepatnya untuk menyelesaikan masalah ini.

Tindakan ini dilakukan dari alamat IP ini: {ip_link}.

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

Questa email conferma che la tua password su {site_anchor} è stata cambiata con successo. Ora puoi effettuare il <a href="{login_url}" target="_blank">login</a> utilizzando quella nuova.

Se non hai eseguito questa azione, ti preghiamo di contattarci il prima possibile per risolvere questo problema.

Questa azione è stata eseguita da questo indirizzo IP: {ip_link}.

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

このメールは、{site_anchor} でのパスワードが正常に変更されたことを確認するものです。これで新しいパスワードを使用して<a href="{login_url}" target="_blank">ログイン</a>できます。

このアクションがあなたによるものでない場合は、できるだけ早くお問い合わせいただき、この問題を解決してください。

このアクションは、次のIPアドレスから実行されました：{ip_link}。

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

이 이메일은 {site_anchor}에서 비밀번호가 성공적으로 변경되었음을 확인합니다. 이제 새로운 비밀번호로 <a href="{login_url}" target="_blank">로그인</a>하실 수 있습니다.

이 작업을 본인이 수행하지 않았다면, 문제를 해결할 수 있도록 가능한 빨리 저희에게 연락해 주세요.

이 작업은 다음 IP 주소에서 수행되었습니다: {ip_link}.

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

این ایمیل تایید می کند که رمز عبور شما در {site_anchor} با موفقیت تغییر کرده است. اکنون می توانید <a href="{login_url}" target="_blank">با رمز عبور جدید وارد شوید</a>.

اگر شما این اقدام را انجام نداده اید، لطفاً برای حل مشکل در اسرع وقت با ما تماس بگیرید.

این اقدام از این آدرس IP انجام شده است: {ip_link}.

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

Ta wiadomość e-mail potwierdza, że Twoje hasło na {site_anchor} zostało pomyślnie zmienione. Możesz teraz <a href="{login_url}" target="_blank">zalogować się</a> używając nowego hasła.

Jeśli to nie Ty dokonałeś tej zmiany, skontaktuj się z nami jak najszybciej, aby rozwiązać problem.

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

Este e-mail confirma que a sua palavra-passe no {site_anchor} foi alterada com sucesso. Agora pode <a href="{login_url}" target="_blank">iniciar sessão</a> utilizando a nova palavra-passe.

Se não realizou esta ação, por favor contacte-nos o mais rapidamente possível para resolver esta questão.

Esta ação foi realizada a partir do seguinte endereço IP: {ip_link}.

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

Это письмо подтверждает, что ваш пароль на {site_anchor} успешно изменен. Теперь вы можете <a href="{login_url}" target="_blank">войти</a>, используя новый пароль.

Если вы не совершали это действие, пожалуйста, свяжитесь с нами как можно скорее, чтобы разрешить этот вопрос.

Это действие было выполнено с этого IP-адреса: {ip_link}.

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

Este correo electrónico confirma que tu contraseña en {site_anchor} se ha cambiado correctamente. Ahora puedes <a href="{login_url}" target="_blank">iniciar sesión</a> utilizando la nueva contraseña.

Si no realizaste esta acción, por favor contáctanos lo más pronto posible para resolver este problema.

Esta acción se realizó desde la siguiente dirección IP: {ip_link}.

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

這封郵件確認您在 {site_anchor} 的密碼已成功更改。您現在可以使用新密碼 <a href="{login_url}" target="_blank">登錄</a>。

如果您未執行此操作，請儘快與我們聯繫以解決此問題。

此操作是由 IP 地址 {ip_link} 執行的。

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

Bu e-posta, {site_anchor}'daki şifrenizin başarıyla değiştirildiğini onaylar. Artık <a href="{login_url}" target="_blank">yeni şifrenizle giriş yapabilirsiniz</a>.

Eğer bu işlemi siz gerçekleştirmediyseniz, lütfen sorunu çözmek için en kısa sürede bizimle iletişime geçin.

Bu işlem şu IP adresinden gerçekleştirildi: {ip_link}.

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

Email này xác nhận rằng mật khẩu của bạn tại {site_anchor} đã được thay đổi thành công. Bạn có thể <a href="{login_url}" target="_blank">đăng nhập</a> bằng mật khẩu mới.

Nếu bạn không thực hiện hành động này, vui lòng liên hệ với chúng tôi sớm nhất có thể để giải quyết vấn đề.

Hành động này được thực hiện từ địa chỉ IP: {ip_link}.

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

This email confirms that your password at {site_anchor} has been successfully changed. You may now <a href="{login_url}" target="_blank">login</a> using the new one.

If you did not perform this action, please contact us as quick as possible to resolve this issue.

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
echo apply_filters('email_users_password_changed', $message);
