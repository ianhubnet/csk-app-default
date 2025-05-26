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

نرسل إليك هذا البريد الإلكتروني بعد تسجيل الدخول بنجاح إلى حساب {site_name} الخاص بك.

التاريخ: {date}
الجهاز: {browser}، {platform}
عنوان IP: {ip_link}

إذا كنت أنت من قام بتسجيل الدخول، يمكنك تجاهل هذا البريد الإلكتروني بأمان.

إذا لم تقم بتسجيل الدخول، يرجى تغيير كلمة المرور الخاصة بك على الفور لتأمين حسابك.

للحصول على أي مساعدة، تواصل مع فريق الدعم لدينا.

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

Enviamos este e-mail após você efetuar login com sucesso na sua conta {site_name}.

Data: {date}
Dispositivo: {browser}, {platform}
Endereço IP: {ip_link}

Se foi você quem fez o login, pode ignorar este e-mail com segurança.

Se você não fez o login, altere sua senha imediatamente para proteger sua conta.

Para qualquer assistência, entre em contato com nossa equipe de suporte.

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

Изпращаме ви този имейл във връзка с успешно влизане във вашия акаунт в {site_name}.

Дата: {date}
Устройство: {browser}, {platform}
IP адрес: {ip_link}

Ако това сте били вие, можете спокойно да игнорирате този имейл.

Ако не сте влизали в системата, моля незабавно сменете паролата си, за да защитите акаунта си.

При нужда от съдействие се свържете с нашия екип по поддръжка.

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

当您成功登录{site_name}帐户后，我们会向您发送此电子邮件。

日期：{date}
设备：{browser}，{platform}
IP地址：{ip_link}

如果这是您本人操作，请忽略此邮件。

如果您没有登录，请立即更改密码以确保您的账户安全。

如需帮助，请联系我们的支持团队。

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

Tento e-mail vám posíláme v návaznosti na úspěšné přihlášení k vašemu účtu na {site_name}.

Datum: {date}
Zařízení: {browser}, {platform}
IP adresa: {ip_link}

Pokud jste to byli vy, tento e-mail můžete klidně ignorovat.

Pokud jste se nepřihlašovali vy, změňte prosím ihned své heslo a zabezpečte účet.

V případě potřeby nás neváhejte kontaktovat.

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

Nous vous envoyons cet e-mail suite à une connexion réussie à votre compte {site_name}.

Date : {date}
Appareil : {browser}, {platform}
Adresse IP : {ip_link}

Si vous êtes à l'origine de cette connexion, vous pouvez ignorer cet e-mail.

Si vous n'avez pas effectué cette connexion, veuillez changer immédiatement votre mot de passe pour sécuriser votre compte.

Pour toute assistance, contactez notre équipe de support.

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

Wir senden Ihnen diese E-Mail nach einer erfolgreichen Anmeldung bei Ihrem {site_name}-Konto.

Datum: {date}
Gerät: {browser}, {platform}
IP-Adresse: {ip_link}

Wenn Sie sich selbst angemeldet haben, können Sie diese E-Mail ignorieren.

Falls Sie sich nicht angemeldet haben, ändern Sie bitte umgehend Ihr Passwort, um Ihr Konto zu schützen.

Für Unterstützung kontaktieren Sie bitte unser Support-Team.

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

हम आपको यह ईमेल आपके {site_name} खाते में सफलतापूर्वक लॉगिन करने के बाद भेज रहे हैं।

तारीख: {date}
डिवाइस: {browser}, {platform}
आईपी पता: {ip_link}

यदि यह आपने लॉगिन किया है, तो आप इस ईमेल को अनदेखा कर सकते हैं।

यदि आपने लॉगिन नहीं किया है, तो अपने खाते को सुरक्षित करने के लिए तुरंत अपना पासवर्ड बदलें।

किसी भी सहायता के लिए, हमारी समर्थन टीम से संपर्क करें।

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

Kami mengirimkan email ini setelah berhasil masuk ke akun {site_name} Anda.

Tanggal: {date}
Perangkat: {browser}, {platform}
Alamat IP: {ip_link}

Jika ini adalah Anda, abaikan email ini dengan aman.

Jika ini bukan Anda, segera ganti kata sandi Anda untuk mengamankan akun Anda.

Untuk bantuan, hubungi tim dukungan kami.

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

Ti inviamo questa email dopo aver effettuato correttamente l'accesso al tuo account {site_name}.

Data: {date}
Dispositivo: {browser}, {platform}
Indirizzo IP: {ip_link}

Se sei stato tu ad accedere, puoi tranquillamente ignorare questa email.

Se non sei stato tu, cambia immediatamente la tua password per proteggere il tuo account.

Per qualsiasi assistenza, contatta il nostro team di supporto.

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

{site_name} アカウントへのログインが成功すると、このメールが送信されます。

日付: {date}
デバイス: {browser}, {platform}
IPアドレス: {ip_link}

ご自身によるログインの場合は、このメールを無視してください。

もし心当たりがない場合は、直ちにパスワードを変更してアカウントを保護してください。

サポートが必要な場合は、サポートチームまでご連絡ください。

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

회원님의 {site_name} 계정에 성공적으로 로그인한 기록이 있어 이 메일을 보내드립니다.

날짜: {date}
기기: {browser}, {platform}
IP 주소: {ip_link}

로그인한 사람이 본인이라면 이 메일은 무시하셔도 됩니다.

본인이 아닌 경우, 계정 보안을 위해 즉시 비밀번호를 변경해 주세요.

도움이 필요하시면 고객 지원팀에 문의해 주세요.

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

پس از ورود موفقیت آمیز به حساب {site_name} خود، این ایمیل را برای شما ارسال می کنیم.

تاریخ: {date}
دستگاه: {browser}، {platform}
آدرس IP: {ip_link}

اگر این ورود توسط شما انجام شده است، می توانید این ایمیل را نادیده بگیرید.

اگر این ورود توسط شما نبوده است، لطفاً فوراً رمز عبور خود را تغییر دهید تا حساب کاربری تان را ایمن کنید.

برای هرگونه کمک، با تیم پشتیبانی ما تماس بگیرید.

-- تیم {site_name}
EOT;

		break;

	// --------------------------------------------------------------------

	/**
	 * Polish version.
	 * @since 	2.112
	 */
	case 'polish':

		$message = <<<EOT
Witaj {name},

Wyślemy Ci tę wiadomość e-mail po pomyślnym zalogowaniu się na Twoje konto {site_name}.

Data: {date}
Urządzenie: {browser}, {platform}
Adres IP: {ip_link}

Jeśli to Ty się zalogowałeś, możesz zignorować tę wiadomość.

Jeśli to nie Ty, natychmiast zmień hasło, aby zabezpieczyć swoje konto.

W razie potrzeby skontaktuj się z naszym zespołem wsparcia.

-- Zespół {site_name}
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

Enviámos este e-mail após ter efetuado o login com sucesso na sua conta {site_name}.

Data: {date}
Dispositivo: {browser}, {platform}
Endereço IP: {ip_link}

Se foi você quem fez o login, pode ignorar este e-mail em segurança.

Se não tiver feito o login, altere a sua palavra-passe imediatamente para proteger a sua conta.

Para qualquer assistência, contacte a nossa equipa de suporte.

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

Мы отправляем вам это письмо после успешного входа в вашу учетную запись {site_name}.

Дата: {date}
Устройство: {browser}, {platform}
IP-адрес: {ip_link}

Если это были вы, просто проигнорируйте это письмо.

Если это были не вы, немедленно измените пароль для защиты вашего аккаунта.

Для получения помощи свяжитесь с нашей службой поддержки.

-- Команда {site_name}
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

Le enviaremos este correo electrónico luego de iniciar sesión exitosamente en su cuenta {site_name}.

Fecha: {date}
Dispositivo: {browser}, {platform}
Dirección IP: {ip_link}

Si fuiste tú, puedes ignorar este correo electrónico.

Si no fuiste tú, cambia tu contraseña de inmediato para proteger tu cuenta.

Para cualquier ayuda, contacta con nuestro equipo de soporte.

-- El equipo de {site_name}
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

成功登入您的 {site_name} 帳戶後，我們會向您發送此電子郵件。

日期：{date}
設備：{browser}，{platform}
IP地址：{ip_link}

如果是您本人操作，請忽略此郵件。

如果您沒有登入，請立即更改密碼以確保您的帳戶安全。

如需協助，請聯繫我們的支援團隊。

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

{site_name} hesabınıza başarılı bir şekilde giriş yaptıktan sonra size bu e-postayı gönderiyoruz.

Tarih: {date}
Cihaz: {browser}, {platform}
IP Adresi: {ip_link}

Eğer bu sizseniz, bu e-postayı güvenle göz ardı edebilirsiniz.

Eğer bu siz değilseniz, hesabınızı korumak için hemen şifrenizi değiştirin.

Her türlü destek için, destek ekibimizle iletişime geçin.

-- {site_name} Ekibi
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

Chúng tôi gửi cho bạn email này sau khi bạn đăng nhập thành công vào tài khoản {site_name}.

Ngày: {date}
Thiết bị: {browser}, {platform}
Địa chỉ IP: {ip_link}

Nếu bạn đã thực hiện đăng nhập này, bạn có thể bỏ qua email này.

Nếu không phải bạn, hãy thay đổi mật khẩu ngay lập tức để bảo vệ tài khoản của mình.

Nếu cần hỗ trợ, vui lòng liên hệ với đội ngũ hỗ trợ của chúng tôi.

-- Đội ngũ {site_name}
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

We send you this email following a successful login to your {site_name} account.

Date: {date}
Device: {browser}, {platform}
IP Address: {ip_link}

If this was you, you can safely ignore this email.

If you did not log in, please change your password immediately to secure your account.

For any assistance, contact our support team.

-- {site_name} Team.
EOT;
}

// --------------------------------------------------------------------

/**
 * Filters the email message.
 * @since 	2.112
 */
echo apply_filters('email_users_login_failed', $message);
