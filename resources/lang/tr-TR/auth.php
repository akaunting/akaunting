<?php

return [

    'auth'                  => 'Doğrulama',
    'profile'               => 'Profil',
    'logout'                => 'Çıkış',
    'login'                 => 'Giriş',
    'forgot'                => 'Unutmak',
    'login_to'              => 'Oturum açmak için giriş yapınız',
    'remember_me'           => 'Beni Hatırla',
    'forgot_password'       => 'Şifremi unuttum',
    'reset_password'        => 'Şifremi Sıfırla',
    'change_password'       => 'Şifre Değiştir',
    'enter_email'           => 'Email Adresinizi Giriniz',
    'current_email'         => 'Geçerli Email',
    'reset'                 => 'Sıfırla',
    'never'                 => 'hiçbir zaman',
    'landing_page'          => 'Açılış Sayfası',
    'personal_information'  => 'Kişisel Bilgiler',
    'register_user'         => 'Kullanıcı Kaydı',
    'register'              => 'Kayıt Ol',

    'form_description' => [
        'personal'          => 'Davet bağlantısı yeni kullanıcıya gönderilecektir, bu nedenle e-posta adresinin doğru olduğundan emin olun. Kullanıcı kendi şifresini belirleyebilecektir.',
        'assign'            => 'Kullanıcılar seçili olan şirketlere erişebileceklerdir. İzinleri <a href=":url" class="border-b border-black">roller</a> sayfasından kısıtlayabilirsiniz.',
        'preferences'       => 'Kullanıcının varsayılan dilini seçin. Ayrıca kullanıcı giriş yaptıktan sonra açılış sayfasını da ayarlayabilirsiniz.',
    ],

    'password' => [
        'pass'              => 'Şifre',
        'pass_confirm'      => 'Şifre Doğrula',
        'current'           => 'Mevcut Şifre',
        'current_confirm'   => 'Mevcut Şifre Onayı',
        'new'               => 'Yeni Şifre',
        'new_confirm'       => 'Yeni Şifre Onayı',
    ],

    'error' => [
        'self_delete'       => 'Hata: Kendinizi silemezsiniz!',
        'self_disable'      => 'Hata: Kendinizi devre dışı bırakamazsınız!',
        'unassigned'        => 'Hata: Şirket ataması kaldırılamaz! :company şirketine en az bir kullanıcı atanmış olmalıdır.',
        'no_company'        => 'Hata: Hesabınıza atanmış herhangi bir şirket yok. Lütfen, sistem yöneticisi ile iletişime geçin.',
    ],

    'login_redirect'        => 'Doğrulama başarılı! Şimdi yönlendiriliyorsunuz...',
    'failed'                => 'Bu kullanıcı bilgileri, bizim verilerimizle eşleşmiyor.',
    'throttle'              => 'Çok fazla oturum açma girişimi. Lütfen :seconds saniye içinde tekrar deneyin.',
    'disabled'              => 'Bu hesap devre dışı bırakılmıştır. Lütfen, sistem yöneticisi ile iletişime geçin.',

    'notification' => [
        'message_1'         => 'Şifre sıfırlama talebiniz doğrultusunda bu e-postayı alıyorsunuz.',
        'message_2'         => 'Şifre sıfırlama talebinde bulunmadısyanız herhangi bir şey yapmayın.',
        'button'            => 'Şifre Sıfırlama',
    ],

    'invitation' => [
        'message_1'         => 'Akaunting\'e katılmaya davet edildiğiniz için bu e-postayı alıyorsunuz.',
        'message_2'         => 'Eğer katılmak istemiyorsanız, herhangi bir işlem yapmanıza gerek yoktur.',
        'button'            => 'Şimdi başlayın',
    ],

    'information' => [
        'invoice'           => 'Kolayca fatura oluşturun',
        'reports'           => 'Detaylı raporlar alın',
        'expense'           => 'Her türlü harcamayı takip edin',
        'customize'         => 'Akaunting\'inizi kişiselleştirin',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Yönetici',
            'description'   => 'Müşteriler, faturalar, raporlar, ayarlar ve uygulamalar dahil olmak üzere Akaunting\'inize tam erişim elde ederler.',
        ],
        'manager' => [
            'name'          => 'Yetkili',
            'description'   => 'Akaunting\'inize tam erişime sahip olurlar, ancak kullanıcıları ve uygulamaları yönetemezler.',
        ],
        'customer' => [
            'name'          => 'Müşteri',
            'description'   => 'Müşteri Portalına erişebilir ve belirlediğiniz ödeme yöntemleri aracılığıyla faturalarını çevrimiçi olarak ödeyebilirler.',
        ],
        'accountant' => [
            'name'          => 'Muhasebeci',
            'description'   => 'Faturalara, işlemlere, raporlara erişebilir ve yevmiye kayıtları oluşturabilirler.',
        ],
        'employee' => [
            'name'          => 'Personel',
            'description'   => 'Gider talepleri oluşturabilir ve atanan projeler için zaman takibi yapabilirler, ancak yalnızca kendi bilgilerini görebilirler.',
        ],
    ],

];
