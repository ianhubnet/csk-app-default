<?php
defined('BASEPATH') OR die;

/**
 * Default users welcome email.
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

معظم الناس لديهم رسائل ترحيب طويلة حقا بعد التسجيل في موقعهم.

خبر سار: لسنا معظم الناس.
ولكننا نريد أن نرحب بك على أي حال، ونشكرك على انضمامك إلينا على {site_anchor}.

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

A maioria das pessoas tem sequências de e-mails de boas-vindas muito longas após você se registrar em seus sites.

Boas notícias: nós não somos como a maioria das pessoas. Mas, ainda assim, queremos dar-lhe as boas-vindas e agradecer por se juntar a nós no {site_anchor}.

Esperamos que aproveite sua estadia, aceite nossos sinceros cumprimentos.

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

Повечето хора изпращат дълги поредици от приветствени имейли след регистрация на сайта им.

Добрата новина: ние не сме като повечето хора.
Но все пак искаме да ви приветстваме и да ви благодарим, че се присъединихте към {site_anchor}.

Надяваме се да ви бъде приятно тук. Приемете нашите най-сърдечни поздрави.

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

大多数人在你在他们的网站上注册后都有非常长的欢迎邮件序列。

好消息：我们不是大多数人。
但是，我们仍然想欢迎你，并感谢你加入我们在 {site_anchor}。

希望你在这里过得愉快，请接受我们的诚挚问候。

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

Většina webů vás po registraci zasype dlouhou sérií uvítacích e-mailů.

Dobrá zpráva: my mezi ně nepatříme.
Přesto vás chceme srdečně přivítat a poděkovat, že jste se připojili k {site_anchor}.

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

La plupart des gens ont de très longues phrases de bienvenue après votre inscription sur leur site.

Bonne nouvelle: nous ne sommes pas la plupart des gens.
Mais nous souhaitons tout de même vous accueillir et vous remercier de nous avoir rejoints sur {site_anchor}.

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

die meisten Leute haben wirklich lange Willkommens-E-Mail-Sequenzen, nachdem du dich auf ihrer Website registriert hast.

Gute Nachrichten: Wir sind nicht wie die meisten Leute.
Aber wir möchten dich trotzdem herzlich willkommen heißen und uns bei dir dafür bedanken, dass du dich uns bei {site_anchor} angeschlossen hast.

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

ज्यादातर लोगों के पास साइट पर पंजीकरण करने के बाद बहुत लंबी स्वागत ईमेल श्रृंखलाएँ होती हैं।

अच्छी खबर: हम ज्यादातर लोग नहीं हैं।
लेकिन, हम आपको फिर भी स्वागत करना चाहते हैं, और {site_anchor} में शामिल होने के लिए धन्यवाद कहना चाहते हैं।

आशा है कि आप अपने समय का आनंद लेंगे, कृपया हमारी शुभकामनाएँ स्वीकार करें।

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

Kebanyakan orang memiliki urutan email sambutan yang sangat panjang setelah Anda mendaftar di situs mereka.

Kabar baik: kami bukan kebanyakan orang.
Namun, kami tetap ingin menyambut Anda dan mengucapkan terima kasih telah bergabung dengan kami di {site_anchor}.

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

Molte persone hanno sequenze di benvenuto molto lunghe dopo che ti registri sul loro sito.

Buone notizie: noi non siamo come la maggior parte delle persone.
Ma, vogliamo darti comunque il benvenuto e ringraziarti per esserti unito a noi su {site_anchor}.

Speriamo che tu apprezzi il tuo soggiorno, per favore accetta i nostri cordiali saluti.

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

ほとんどの人はサイトに登録すると、非常に長い歓迎メールのシーケンスを送ります。

良いニュースは、私たちはほとんどの人とは違います。
でも、とにかくあなたを歓迎し、{site_anchor} で私たちに参加してくれてありがとう。

滞在を楽しんでいただければ嬉しいです。どうぞごゆっくりお楽しみください。

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

대부분의 사이트는 회원 가입 후 긴 환영 이메일 시리즈를 보냅니다.

하지만 저희는 다릅니다.
{site_anchor}에 가입해 주셔서 진심으로 감사드리며, 따뜻하게 환영합니다.

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

بیشتر وب سایت ها پس از ثبت نام، مجموعه ای طولانی از ایمیل های خوشامدگویی ارسال می کنند.

خبر خوب: ما مانند بیشتر افراد نیستیم.
اما با این حال، می خواهیم به شما خوش آمد بگوییم و از پیوستن شما به {site_anchor} تشکر کنیم.

امیدواریم اقامتتان خوشایند باشد. با احترام، لطف ما را بپذیرید.

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

Większość stron internetowych wysyła długie sekwencje powitalnych e-maili po rejestracji.

Dobra wiadomość: my nie jesteśmy jak większość.
Chcemy jednak Cię powitać i podziękować za dołączenie do {site_anchor}.

Mamy nadzieję, że pobyt będzie przyjemny. Przyjmij nasze serdeczne pozdrowienia.

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

A maioria das pessoas tem sequências de e-mails de boas-vindas muito longas após o registo nos seus sites.

Boas notícias: não somos como a maioria das pessoas. Mas, ainda assim, queremos dar-lhe as boas-vindas e agradecer-lhe por se juntar a nós no {site_anchor}.

Esperamos que desfrute da sua estadia, aceite os nossos sinceros cumprimentos.

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

Большинство людей создают длинные приветственные электронные последовательности после регистрации на своем сайте.

Хорошая новость: мы не такие.
Тем не менее, мы все равно хотим приветствовать вас и благодарим за присоединение к нам на {site_anchor}.

Надеемся, что вам понравится у нас, примите наши дружелюбные пожелания.

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

La mayoría de las personas tienen secuencias de bienvenida muy largas después de que te registras en su sitio.

Buenas noticias: no somos la mayoría de las personas.
Sin embargo, queremos darte la bienvenida de todos modos y agradecerte por unirte a nosotros en {site_anchor}.

Esperamos que disfrutes tu estadía. Por favor, acepta nuestros amables saludos.

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

大多數人在你在他們的網站上註冊後都有非常長的歡迎郵件序列。

好消息：我們不是大多數人。
但是，我們仍然想歡迎你，並感謝你加入我們在 {site_anchor}。

希望你在這裏過得愉快，請接受我們的誠摯問候。

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

Çoğu web sitesi, kaydolduktan sonra uzun bir hoş geldiniz e-posta dizisi gönderir.

İyi haber: biz çoğu insan gibi değiliz.
Yine de sizi ağırlamak ve {site_anchor}'a katıldığınız için teşekkür etmek istiyoruz.

Umarız keyifli bir deneyim yaşarsınız. Saygılarımızı kabul edin lütfen.

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

Hầu hết các trang web đều có chuỗi email chào mừng rất dài sau khi bạn đăng ký.

Tin tốt: chúng tôi không giống như hầu hết mọi người.
Tuy nhiên, chúng tôi muốn chào đón bạn và cảm ơn bạn đã tham gia cùng chúng tôi tại {site_anchor}.

Hy vọng bạn sẽ có một trải nghiệm tuyệt vời. Vui lòng chấp nhận lời chào trân trọng từ chúng tôi.

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

Most people have really long welcome email sequences after you register on their site.

Good news: we aren't most people.
But, we want to welcome you anyways, and thank you for joining us at {site_anchor}.

Hoping you enjoy your stay, please accept our kind regards.

-- {site_name} Team.
EOT;

}

// --------------------------------------------------------------------

/**
 * Filters the welcome email message.
 * @since 	2.0
 */
echo apply_filters('email_users_welcome', $message);
