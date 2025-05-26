<?php
defined('BASEPATH') OR die;

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
	 * @since 	2.112
	 */
	case 'arabic':

		$message = <<<EOT
مرحبًا {name}،

حاول شخص مؤخرًا استخدام كلمات مرور خاطئة لتسجيل الدخول إلى حسابك على {site_name}.

تاريخ الخادم: {date}
الجهاز: {browser}، {platform}
عنوان IP: {ip_link}

إذا لم تكن هذه المحاولة منك، فلا تقلق، لم تنجح محاولة تسجيل الدخول.

إذا كانت هذه المحاولة منك ولا تتذكر كلمة المرور، اضغط على الرابط أدناه لإعادة تعيينها:
{reset_link}

إذا واجهت مشكلة في تسجيل الدخول إلى حسابك، اتصل بفريقنا واطلب رابط تسجيل دخول بنقرة واحدة.

-- فريق {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Brazillian-Portuguese version.
	 * @since 	2.112
	 */
	case 'brazilian':

		$message = <<<EOT
Olá {name},

Alguém recentemente tentou usar senhas erradas para acessar sua conta {site_name}.

Data do servidor: {date}
Dispositivo: {browser}, {platform}
Endereço IP: {ip_link}

Se não foi você, não se preocupe, a tentativa de login não foi bem-sucedida.

Se foi você e não se lembra da senha, clique no link abaixo para redefini-la:
{reset_link}

Se estiver com dificuldades para acessar sua conta, entre em contato com nossa equipe e solicite um link de login direto.

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

Наскоро някой се опита да влезе във вашия акаунт в {site_name} с грешни пароли.

Сървърен час: {date}
Устройство: {browser}, {platform}
IP адрес: {ip_link}

Ако това не сте били вие, не се тревожете — опитът за влизане не е бил успешен.

Ако все пак сте били вие и не помните паролата си, кликнете на следния линк, за да я нулирате:
{reset_link}

Ако имате затруднения с влизането, свържете се с нашия екип и поискайте линк за еднократно влизане.

-- Екипът на {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Chinese version.
	 * @since 	2.112
	 */
	case 'chinese':

		$message = <<<EOT
您好，{name}，

有人最近使用错误的密码尝试登录您的{site_name}账户。

服务器日期：{date}
设备：{browser}，{platform}
IP地址：{ip_link}

如果这不是您本人操作，请不用担心，登录尝试未成功。

如果是您本人操作，但忘记了密码，请点击下面的链接重置密码：
{reset_link}

如果您在登录账户时遇到问题，请联系我们的团队，申请一键登录链接。

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

Někdo se nedávno pokusil přihlásit k vašemu účtu na {site_name} pomocí nesprávného hesla.

Datum serveru: {date}
Zařízení: {browser}, {platform}
IP adresa: {ip_link}

Pokud jste to nebyli vy, buďte v klidu – přihlášení nebylo úspěšné.

Pokud jste to byli vy a nepamatujete si své heslo, klikněte na následující odkaz pro jeho obnovení:
{reset_link}

Máte-li potíže s přihlášením, kontaktujte náš tým a požádejte o jednorázový přihlašovací odkaz.

-- Tým {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * French version.
	 * @since 	2.112
	 */
	case 'french':

		$message = <<<EOT
Bonjour {name},

Quelqu'un a récemment utilisé des mots de passe incorrects pour tenter de se connecter à votre compte {site_name}.

Date du serveur : {date}
Appareil : {browser}, {platform}
Adresse IP : {ip_link}

Si ce n'était pas vous, ne vous inquiétez pas, la tentative de connexion n'a pas réussi.

Si c'était vous et que vous ne vous souvenez pas de votre mot de passe, cliquez sur le lien ci-dessous pour le réinitialiser :
{reset_link}

Si vous rencontrez des difficultés pour vous connecter à votre compte, contactez notre équipe et demandez un lien de connexion direct.

-- Équipe {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * German version.
	 * @since 	2.112
	 */
	case 'german':

		$message = <<<EOT
Hallo {name},

Jemand hat kürzlich falsche Passwörter verwendet, um sich in Ihr {site_name}-Konto einzuloggen.

Serverdatum: {date}
Gerät: {browser}, {platform}
IP-Adresse: {ip_link}

Falls Sie das nicht waren, keine Sorge, der Login-Versuch war nicht erfolgreich.

Falls Sie das waren und Ihr Passwort vergessen haben, klicken Sie auf den untenstehenden Link, um es zurückzusetzen:
{reset_link}

Wenn Sie Probleme beim Einloggen haben, kontaktieren Sie unser Team und fordern Sie einen Ein-Klick-Anmeldelink an.

-- {site_name} Team.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Hindi version.
	 * @since 	2.112
	 */
	case 'hindi':

		$message = <<<EOT
नमस्ते {name},

किसी ने हाल ही में गलत पासवर्ड का उपयोग करके आपके {site_name} खाते में लॉगिन करने की कोशिश की।

सर्वर तिथि: {date}
डिवाइस: {browser}, {platform}
आईपी पता: {ip_link}

यदि यह आपने नहीं किया है, तो चिंता न करें, लॉगिन प्रयास सफल नहीं हुआ।

यदि यह आपने किया था और आप अपना पासवर्ड याद नहीं कर पा रहे हैं, तो इसे रीसेट करने के लिए नीचे दिए गए लिंक पर क्लिक करें:
{reset_link}

यदि आपको अपने खाते में लॉगिन करने में परेशानी हो रही है, तो हमारी टीम से संपर्क करें और एक-क्लिक लॉगिन लिंक का अनुरोध करें।

-- {site_name} टीम।
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Indonesian version.
	 * @since 	2.112
	 */
	case 'indonesian':

		$message = <<<EOT
Halo {name},

Seseorang baru-baru ini mencoba menggunakan kata sandi yang salah untuk masuk ke akun {site_name} Anda.

Tanggal Server: {date}
Perangkat: {browser}, {platform}
Alamat IP: {ip_link}

Jika ini bukan Anda, jangan khawatir, percobaan login tidak berhasil.

Jika ini memang Anda dan Anda lupa kata sandi, klik tautan di bawah ini untuk meresetnya:
{reset_link}

Jika Anda mengalami kesulitan masuk ke akun Anda, hubungi tim kami dan minta tautan login sekali klik.

-- Tim {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Italian version.
	 * @since 	2.112
	 */
	case 'italian':

		$message = <<<EOT
Ciao {name},

Qualcuno ha recentemente utilizzato password errate per tentare di accedere al tuo account {site_name}.

Data del server: {date}
Dispositivo: {browser}, {platform}
Indirizzo IP: {ip_link}

Se non sei stato tu, non preoccuparti, il tentativo di accesso non ha avuto successo.

Se sei stato tu e non ricordi la password, clicca sul link qui sotto per reimpostarla:
{reset_link}

Se hai problemi ad accedere al tuo account, contatta il nostro team e richiedi un link di accesso rapido.

-- Il team di {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Japanese version.
	 * @since 	2.112
	 */
	case 'japanese':

		$message = <<<EOT
こんにちは、{name} さん

最近、誰かが間違ったパスワードを使用してあなたの {site_name} アカウントにログインしようとしました。

サーバー日時: {date}
デバイス: {browser}, {platform}
IPアドレス: {ip_link}

もしこれがあなたでない場合はご安心ください。ログイン試行は成功しませんでした。

もしこれがあなたで、パスワードを思い出せない場合は、以下のリンクをクリックしてリセットしてください：
{reset_link}

アカウントへのログインに問題がある場合は、当チームに連絡してワンクリックログインリンクをリクエストしてください。

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

최근 누군가가 {site_name} 계정에 잘못된 비밀번호로 로그인하려고 시도했습니다.

서버 날짜: {date}
기기: {browser}, {platform}
IP 주소: {ip_link}

본인이 아닌 경우 걱정하지 않으셔도 됩니다. 로그인 시도는 실패했습니다.

만약 본인이시고 비밀번호가 기억나지 않는다면, 아래 링크를 클릭하여 비밀번호를 재설정해 주세요:
{reset_link}

로그인에 문제가 있다면, 저희 팀에 문의하여 원클릭 로그인 링크를 요청하실 수 있습니다.

-- {site_name} 팀.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Persian version.
	 * @since 	2.112
	 */
	case 'persian':

		$message = <<<EOT
سلام {name}،

اخیراً کسی با استفاده از رمز عبور اشتباه سعی کرده وارد حساب {site_name} شما شود.

تاریخ سرور: {date}
دستگاه: {browser}، {platform}
آدرس IP: {ip_link}

اگر این شما نبودید، نگران نباشید، تلاش برای ورود موفقیت‌آمیز نبود.

اگر این شما بودید و رمز عبور خود را به خاطر ندارید، روی لینک زیر کلیک کنید تا آن را بازنشانی کنید:
{reset_link}

اگر در ورود به حساب خود مشکل دارید، با تیم ما تماس بگیرید و درخواست یک لینک ورود فوری کنید.

-- تیم {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Polish version.
	 * @since 	2.112
	 */
	case 'polish':

		$message = <<<EOT
Cześć {name},

Ktoś ostatnio próbował zalogować się do Twojego konta {site_name}, używając nieprawidłowych haseł.

Data serwera: {date}
Urządzenie: {browser}, {platform}
Adres IP: {ip_link}

Jeśli to nie byłeś Ty, nie martw się, próba logowania nie powiodła się.

Jeśli to byłeś Ty i nie pamiętasz swojego hasła, kliknij poniższy link, aby je zresetować:
{reset_link}

Jeśli masz trudności z zalogowaniem się do swojego konta, skontaktuj się z naszym zespołem i poproś o link do logowania jednym kliknięciem.

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

Alguém tentou recentemente usar passwords erradas para aceder à sua conta {site_name}.

Data: {date}
Dispositivo: {browser}, {platform}
Endereço IP: {ip_link}

Se não foi você, não se preocupe, a tentativa de login não foi bem-sucedida.

Se foi você e não se recorda da palavra-passe, clique no link abaixo para a repor:
{reset_link}

Se estiver com dificuldades em aceder à sua conta, entre em contacto com a nossa equipa e solicite um link de login direto.

-- Equipa {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Russian version.
	 * @since 	2.112
	 */
	case 'russian':

		$message = <<<EOT
Здравствуйте, {name},

Кто-то недавно пытался войти в ваш аккаунт {site_name}, используя неправильные пароли.

Дата сервера: {date}
Устройство: {browser}, {platform}
IP-адрес: {ip_link}

Если это были не вы, не волнуйтесь, попытка входа не удалась.

Если это были вы и вы не помните свой пароль, нажмите на ссылку ниже, чтобы сбросить его:
{reset_link}

Если у вас возникли проблемы со входом в аккаунт, свяжитесь с нашей командой и запросите ссылку для входа в один клик.

-- Команда {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Spanish version.
	 * @since 	2.112
	 */
	case 'spanish':

		$message = <<<EOT
Hola {name},

Alguien intentó recientemente usar contraseñas incorrectas para iniciar sesión en tu cuenta de {site_name}.

Fecha del servidor: {date}
Dispositivo: {browser}, {platform}
Dirección IP: {ip_link}

Si no fuiste tú, no te preocupes, el intento de inicio de sesión no tuvo éxito.

Si fuiste tú y no recuerdas tu contraseña, haz clic en el enlace de abajo para restablecerla:
{reset_link}

Si tienes problemas para iniciar sesión en tu cuenta, contacta a nuestro equipo y solicita un enlace de inicio de sesión con un solo clic.

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
您好，{name}，

有人最近使用錯誤的密碼嘗試登錄您的{site_name}帳戶。

伺服器日期：{date}
裝置：{browser}，{platform}
IP地址：{ip_link}

如果這不是您本人操作，請不用擔心，登錄嘗試未成功。

如果是您本人操作，但忘記了密碼，請點擊下面的鏈接重置密碼：
{reset_link}

如果您在登錄帳戶時遇到問題，請聯繫我們的團隊，申請一鍵登錄鏈接。

-- {site_name} 團隊。
EOT;

	// --------------------------------------------------------------------

	/**
	 * Turkish version.
	 * @since 	2.112
	 */
	case 'turkish':

		$message = <<<EOT
Merhaba {name},

Birisi yakın zamanda yanlış şifreler kullanarak {site_name} hesabınıza giriş yapmaya çalıştı.

Sunucu Tarihi: {date}
Cihaz: {browser}, {platform}
IP Adresi: {ip_link}

Bu siz değilseniz endişelenmeyin, giriş denemesi başarısız oldu.

Bu sizseniz ve şifrenizi hatırlamıyorsanız, şifreyi sıfırlamak için aşağıdaki bağlantıya tıklayın:
{reset_link}

Hesabınıza giriş yapmakta sorun yaşıyorsanız, ekibimizle iletişime geçin ve tek tıklamayla giriş bağlantısı talep edin.

-- {site_name} Ekibi.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Vietnamese version.
	 * @since 	2.112
	 */
	case 'vietnamese':

		$message = <<<EOT
Xin chào {name},

Gần đây có ai đó đã sử dụng mật khẩu sai để cố gắng đăng nhập vào tài khoản {site_name} của bạn.

Ngày máy chủ: {date}
Thiết bị: {browser}, {platform}
Địa chỉ IP: {ip_link}

Nếu không phải bạn, đừng lo lắng, nỗ lực đăng nhập không thành công.

Nếu đó là bạn và bạn không nhớ mật khẩu, hãy nhấp vào liên kết bên dưới để đặt lại mật khẩu:
{reset_link}

Nếu bạn gặp khó khăn khi đăng nhập vào tài khoản, hãy liên hệ với đội ngũ của chúng tôi và yêu cầu liên kết đăng nhập một lần nhấn.

-- Đội ngũ {site_name}.
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * English version (Required).
	 * @since 	2.112
	 */
	case 'english':
	default:

		$message = <<<EOT
Hello {name},

Someone recently used wrong passwords to try to login to your {site_name} account.

Server Date: {date}
Device: {browser}, {platform}
IP Address: {ip_link}

If this was not you, do not worry, their login attempt was not successful.

If this was you and you do not remember your password, click the link below to reset it:
{reset_link}

If you have trouble logging in to your account, contact our team and request a one-click login link.

-- {site_name} Team.
EOT;
}

// --------------------------------------------------------------------

/**
 * Filters the email message.
 * @since 	2.112
 */
echo apply_filters('email_users_login_failed', $message);
