<?php

return [

    'next'                  => 'İleri',
    'refresh'               => 'Yenile',

    'steps' => [
        'requirements'      => 'Sorunları gidermek için hosting firması ile iletişime geçin!',
        'language'          => 'Adım 1/3 : Dil Seçimi',
        'database'          => 'Adım 2/3 : Veritabanı Ayarları',
        'settings'          => 'Adım 3/3 : Şirket ve Yönetici Bilgileri',
    ],

    'language' => [
        'select'            => 'Dil Seçin',
    ],

    'requirements' => [
        'enabled'           => ':feature etkin olmalıdır!',
        'disabled'          => ':feature devre dışı bırakılmalıdır!',
        'extension'         => ':extension eklendisi kurulmalı ve yüklenmelidir!',
        'directory'         => ':directory dizini yazılabilir olmalıdır!',
        'executable'        => 'PHP CLI çalıştırıcısı bulunamadı veya çalışmıyor veya sürümü :php_version ve üstü değil. Lütfen, hosting firmanızdan PHP_BINARY veya PHP_PATH ortam değerlerini doğru ayarlamasını isteyin.',
    ],

    'database' => [
        'hostname'          => 'Sunucu',
        'username'          => 'Kullanıcı',
        'password'          => 'Şifre',
        'name'              => 'Veritabanı',
    ],

    'settings' => [
        'company_name'      => 'Şirket Adı',
        'company_email'     => 'Şirket e-Postası',
        'admin_email'       => 'Yönetici e-Postası',
        'admin_password'    => 'Yönetici Şifresi',
    ],

    'error' => [
        'connection'        => 'Hata: Veritabanına bağlanamıyoruz! Lütfen veritabanı bilgilerini kontrol ediniz.',
    ],

];
