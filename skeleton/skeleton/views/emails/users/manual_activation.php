<?php
defined('BASEPATH') OR die;

/**
 * Users manual activation email template.
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

نشكرك على الانضمام إلينا في {site_anchor}. تم إنشاء حسابك ولكن يحتاج إلى موافقة من قبل مسؤول الموقع قبل أن يكون نشطًا.

نعتذر بشدة عن هذه الخطوة ولكنها لأغراض أمنية فقط.

ستتلقى رسالة تأكيد بمجرد تنشيطه حسابك.

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

Obrigado por se juntar a nós no {site_anchor}. Sua conta foi criada, mas precisa de aprovação de um administrador do site antes de ser ativada.
Pedimos sinceras desculpas por este passo crucial, mas ele é apenas para fins de segurança.

Você receberá um e-mail de confirmação assim que sua conta for aprovada.

Esperamos que aproveite sua estadia, por favor, aceite nossos melhores cumprimentos.

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

Благодарим ви, че се присъединихте към {site_anchor}. Акаунтът ви е създаден, но се нуждае от одобрение от администратор на сайта, преди да стане активен.
Искрено се извиняваме за тази важна стъпка, но тя е необходима с цел сигурност.

Ще получите потвърждение по имейл веднага след като акаунтът ви бъде одобрен.

Надяваме се престоят ви да бъде приятен. Приемете нашите сърдечни поздрави.

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

感谢您加入我们在 {site_anchor}。您的帐户已经创建，但在激活之前需要网站管理员的批准。
对于这一关键步骤，我们诚挚地道歉，但这仅是为了安全目的。

一旦您的帐户获得批准，您将收到一封确认电子邮件。

希望您在这里愉快，接受我们的诚挚问候。

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

Děkujeme, že jste se připojili k {site_anchor}. Váš účet byl vytvořen, ale než bude aktivní, musí být schválen administrátorem.
Omlouváme se za tuto nezbytnou fázi – je zavedena výhradně z bezpečnostních důvodů.

Jakmile bude váš účet schválen, obdržíte potvrzovací e-mail.

Doufáme, že se vám u nás bude líbit. Přijměte náš upřímný pozdrav.

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

Merci de vous être joint à {site_anchor}. Votre compte est créé mais doit être approuvé par un administrateur du site avant d'être actif.
Nous nous excusons sincèrement pour cette étape cruciale, mais ce n'est que pour des raisons de sécurité.

Vous recevrez un email de confirmation dès que votre compte aura été approuvé.

En espérant que vous apprécierez votre séjour, veuillez accepter nos salutations distinguées.

- Équipe {site_name}.
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

vielen Dank, dass du dich bei uns auf {site_anchor} angemeldet hast. Dein Konto wurde erstellt, muss jedoch von einem Administrator der Website genehmigt werden, bevor es aktiv wird.
Wir entschuldigen uns aufrichtig für diesen wichtigen Schritt, der jedoch nur aus Sicherheitsgründen erfolgt.

Du erhältst eine Bestätigungs-E-Mail, sobald dein Konto genehmigt wurde.

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

{site_anchor} में हमारे साथ जुड़ने के लिए धन्यवाद। आपका खाता बनाया गया है, लेकिन इसे सक्रिय होने से पहले साइट प्रशासन द्वारा अनुमोदन की आवश्यकता है। इस महत्वपूर्ण कदम के लिए हम sincerely क्षमा चाहते हैं, लेकिन यह केवल सुरक्षा के उद्देश्य से है।

आपको आपके खाते के अनुमोदित होने के तुरंत बाद एक पुष्टिकरण ईमेल प्राप्त होगा।

आपके ठहरने का आनंद लेने की उम्मीद है, कृपया हमारी ओर से सादर नमस्कार स्वीकार करें।

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

Terima kasih telah bergabung dengan kami di {site_anchor}. Akun Anda telah dibuat tetapi perlu disetujui oleh admin situs sebelum aktif.
Kami mohon maaf atas langkah penting ini, tetapi ini hanya untuk tujuan keamanan.

Anda akan menerima email konfirmasi segera setelah akun Anda disetujui.

Kami berharap Anda menikmati waktu Anda, mohon terima salam hormat kami.

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

Grazie per esserti unito a noi su {site_anchor}. Il tuo account è stato creato ma necessita dell'approvazione da parte di un amministratore del sito prima di diventare attivo.
Ci scusiamo sinceramente per questo passaggio cruciale, ma è solo a scopo di sicurezza.

Riceverai una email di conferma non appena il tuo account verrà approvato.

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

{site_anchor} にご参加いただき、アカウントが作成されましたが、アクティブになる前にサイト管理者の承認が必要です。
この重要なステップについて心からお詫び申し上げますが、これはセキュリティのためのものです。

アカウントが承認されるとすぐに確認メールをお受け取りいただけます。

ご滞在をお楽しみいただければ幸いです。どうぞよろしくお願いいたします。

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

{site_anchor}에 가입해 주셔서 감사합니다. 회원님의 계정은 생성되었지만, 활성화되기 위해서는 사이트 관리자 승인이 필요합니다.
이 중요한 절차에 대해 불편을 드려 진심으로 사과드리며, 보안을 위한 조치임을 양해 부탁드립니다.

계정이 승인되면 확인 이메일을 보내드릴 예정입니다.

즐거운 시간 보내시길 바라며, 진심을 담아 인사드립니다.

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

از اینکه به ما در {site_anchor} پیوستید سپاسگزاریم. حساب شما ایجاد شده است، اما قبل از فعال شدن نیاز به تایید مدیر سایت دارد.
ما بابت این مرحله ضروری صمیمانه عذرخواهی می کنیم، اما این کار فقط به دلایل امنیتی انجام می شود.

به محض تایید حساب شما، یک ایمیل تایید دریافت خواهید کرد.

امیدواریم اقامت شما لذت بخش باشد. لطفاً احترامات ما را بپذیرید.

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

Dziękujemy za dołączenie do nas na {site_anchor}. Twoje konto zostało utworzone, ale wymaga zatwierdzenia przez administratora strony, zanim stanie się aktywne.
Przepraszamy za ten istotny krok, ale jest on niezbędny ze względów bezpieczeństwa.

Otrzymasz wiadomość e-mail z potwierdzeniem, gdy Twoje konto zostanie zatwierdzone.

Mamy nadzieję, że będziesz zadowolony, prosimy o przyjęcie naszych pozdrowień.

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

Obrigado por se juntar a nós no {site_anchor}. A sua conta foi criada, mas necessita de aprovação de um administrador do site antes de ser ativada.
Pedimos sinceras desculpas por este passo crucial, mas é apenas para fins de segurança.

Receberá um e-mail de confirmação assim que a sua conta for aprovada.

Esperamos que desfrute da sua estadia, por favor aceite os nossos melhores cumprimentos.

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

Благодарим вас за присоединение к нам на {site_anchor}. Ваша учетная запись создана, но перед тем как она станет активной, ей необходимо одобрение администратора сайта.
Мы искренне приносим извинения за этот важный шаг, но это сделано исключительно в целях безопасности.

Вы получите подтверждение по электронной почте, как только ваша учетная запись будет одобрена.

Надеемся, что вам понравится ваше пребывание, и просим принять наши добрые пожелания.

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

Gracias por unirte a nosotros en {site_anchor}. Tu cuenta ha sido creada pero necesita la aprobación de un administrador del sitio antes de estar activa.
Nos disculpamos sinceramente por este paso crucial, pero es solo por razones de seguridad.

Recibirás un correo electrónico de confirmación tan pronto como tu cuenta sea aprobada.

Esperamos que disfrutes tu estadía, por favor, acepta nuestros cordiales saludos.

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

感謝您加入我們在 {site_anchor}。您的帳戶已經創建，但在激活之前需要網站管理員的批准。
對於這一關鍵步驟，我們誠摯地道歉，但這僅是爲了安全目的。

一旦您的帳戶獲得批准，您將收到一封確認電子郵件。

希望您在這裏愉快，接受我們的誠摯問候。

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

{site_anchor}'a katıldığınız için teşekkür ederiz. Hesabınız oluşturuldu, ancak aktif hale gelmesi için site yöneticisi tarafından onaylanması gerekiyor.
Bu önemli adım için içtenlikle özür dileriz, ancak bu yalnızca güvenlik amaçlıdır.

Hesabınız onaylandığında size bir onay e-postası gönderilecektir.

Umarız burada keyifli vakit geçirirsiniz, lütfen saygılarımızı kabul edin.

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

Cảm ơn bạn đã tham gia cùng chúng tôi tại {site_anchor}. Tài khoản của bạn đã được tạo nhưng cần sự phê duyệt của quản trị viên trước khi được kích hoạt.
Chúng tôi thành thật xin lỗi vì bước quan trọng này, nhưng điều này chỉ nhằm đảm bảo an toàn.

Bạn sẽ nhận được email xác nhận ngay khi tài khoản của bạn được phê duyệt.

Hy vọng bạn sẽ hài lòng khi ở đây, xin vui lòng chấp nhận lời chào trân trọng từ chúng tôi.

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

Thank you for joining us at {site_anchor}. Your account is created but needs approval by a site admin before being active.
We sincerely apologies for this crucial step, but it is only for security purposes.

You will receive a confirmation email as soon as your account is approved.

Hoping you enjoy your stay, please accept our kind regards.

-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_manual_activation', $message);
