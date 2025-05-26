<?php
defined('BASEPATH') OR die;

/**
 * Users email changed.
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

تؤكد هذه الرسالة الإلكترونية أنه تم تغيير عنوان بريدك الالكتروني على الموقع {site_anchor} بنجاح.

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

Este e-mail confirma que o endereço de e-mail utilizado no {site_anchor} foi alterado com sucesso.

Se você não realizou esta ação, entre em contato conosco o mais rápido possível para resolver este problema.

Esta ação foi realizada a partir do endereço IP: {ip_link}.

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

Този имейл потвърджава, че вашият имейл адрес, използван в {site_anchor}, е успешно променен.

Ако не сте извършвали това действие, моля свържете се с нас възможно най-скоро, за да разрешим този проблем.

Това действие беше извършено от следния IP адрес: {ip_link}.

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

此邮件确认您在 {site_anchor} 使用的电子邮件地址已成功更改。

如果您没有执行此操作，请尽快与我们联系以解决此问题。

此操作是从以下 IP 地址执行的：{ip_link}。

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

Tento e-mail potvrzuje, že vaše e-mailová adresa používaná na {site_anchor} byla úspěšně změněna.

Pokud jste tuto akci neprovedli, co nejdříve nás kontaktujte, abychom tento problém vyřešili.

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

Cet e-mail confirme que votre adresse e-mail utilisée sur {site_anchor} a bien été modifiée.

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

diese E-Mail bestätigt, dass deine E-Mail-Adresse auf {site_anchor} erfolgreich geändert wurde.

Wenn du diese Änderung nicht vorgenommen hast, kontaktiere uns bitte so schnell wie möglich, um dieses Problem zu klären.

Diese Aktion wurde von folgender IP-Adresse durchgeführt: {ip_link}.

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

यह ईमेल पुष्टि करता है कि आपके {site_anchor} पर उपयोग किया गया ईमेल पता सफलतापूर्वक बदल दिया गया है।

यदि आपने यह कार्रवाई नहीं की है, तो कृपया इस समस्या को हल करने के लिए हमसे जल्द से जल्द संपर्क करें।

यह कार्रवाई इस IP पते से की गई थी: {ip_link}।

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

Email ini mengonfirmasi bahwa alamat email Anda yang digunakan di {site_anchor} telah berhasil diubah.

Jika Anda tidak melakukan tindakan ini, silakan hubungi kami secepat mungkin untuk menyelesaikan masalah ini.

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

Questa email conferma che il tuo indirizzo email utilizzato su {site_anchor} è stato cambiato con successo.

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

このメールは、{site_anchor} でのアカウントのメールアドレス変更が正常に完了したことを確認するものです。

このアクションを実行していない場合は、できるだけ早くお問い合わせいただき、この問題を解決してください。

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

이 이메일은 {site_anchor}에서 사용된 이메일 주소가 성공적으로 변경되었음을 확인해드립니다.

만약 본인이 수행하지 않은 작업이라면 가능한 빨리 저희에게 연락하여 문제를 해결해 주세요.

이 작업은 다음 IP 주소에서 수행되었습니다: {ip_link}

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

این ایمیل تأیید می کند که آدرس ایمیل شما در {site_anchor} با موفقیت تغییر کرده است.

اگر شما این اقدام را انجام نداده اید، لطفاً در اسرع وقت با ما تماس بگیرید تا این مسئله برطرف شود.

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

Ten e-mail potwierdza, że Twój adres e-mail używany na {site_anchor} został pomyślnie zmieniony.

Jeśli to nie Ty wykonałeś tę czynność, skontaktuj się z nami jak najszybciej, aby rozwiązać problem.

Ta czynność została wykonana z tego adresu IP: {ip_link}.

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

Este e-mail confirma que o endereço de e-mail utilizado no {site_anchor} foi alterado com sucesso.

Se não realizou esta ação, por favor contacte-nos o mais rapidamente possível para resolver este problema.

Esta ação foi realizada a partir do endereço IP: {ip_link}.

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

Это подтверждение того, что ваш адрес электронной почты, использованный на {site_anchor}, был успешно изменен.

Если вы не выполняли это действие, пожалуйста, свяжитесь с нами как можно скорее, чтобы решить этот вопрос.

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

Este correo electrónico confirma que tu dirección de correo electrónico utilizada en {site_anchor} se ha cambiado correctamente.

Si no realizaste esta acción, contáctanos lo antes posible para resolver este problema.

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

此郵件確認您在 {site_anchor} 使用的電子郵件地址已成功更改。

如果您沒有執行此操作，請儘快與我們聯繫以解決此問題。

此操作是從以下 IP 地址執行的：{ip_link}。

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

Bu e-posta, {site_anchor} üzerinde kullandığınız e-posta adresinizin başarıyla değiştirildiğini onaylar.

Eğer bu işlemi siz yapmadıysanız, lütfen sorunu çözmek için en kısa sürede bizimle iletişime geçin.

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

Email này xác nhận rằng địa chỉ email của bạn được sử dụng trên {site_anchor} đã được thay đổi thành công.

Nếu bạn không thực hiện hành động này, vui lòng liên hệ với chúng tôi càng sớm càng tốt để giải quyết vấn đề.

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

This email confirms that your email address used on {site_anchor} has been successfully changed.

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
echo apply_filters('email_users_email_changed', $message);
