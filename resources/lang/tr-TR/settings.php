<?php

return [

    'company' => [
        'name'              => 'Şirket İsmi',
        'email'             => 'Şirket Emaili',
        'phone'             => 'Telefon',
        'address'           => 'Şirket Adresi',
        'logo'              => 'Şirket Logosu',
    ],
    'localisation' => [
        'tab'               => 'Yerelleştirme',
        'date' => [
            'format'        => 'Tarih Biçimi',
            'separator'     => 'Tarih Ayracı',
            'dash'          => 'Tire (-)',
            'dot'           => 'Nokta (.)',
            'comma'         => 'Virgül (,)',
            'slash'         => 'Taksim (/)',
            'space'         => 'Boşluk ( )',
        ],
        'timezone'          => 'Saat dilimi',
        'percent' => [
            'title'         => 'Yüzde (%) Konumu',
            'before'        => 'Sayıdan Önce',
            'after'         => 'Sayıdan Sonra',
        ],
    ],
    'invoice' => [
        'tab'               => 'Fatura',
        'prefix'            => 'Numara Öneki',
        'digit'             => 'Numara Rakam Sayısı',
        'next'              => 'Sonraki Numara',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Varsayılanlar',
        'account'           => 'Varsayılan Hesap',
        'currency'          => 'Varsayılan Para Birimi',
        'tax'               => 'Varsayılan Vergi Oranı',
        'payment'           => 'Varsayılan Ödeme Yöntemi',
        'language'          => 'Varsayılan Dil',
    ],
    'email' => [
        'protocol'          => 'Protokol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'SMTP Kullanıcı Adı',
            'password'      => 'SMTP Şifresi',
            'encryption'    => 'SMTP Güvenlik',
            'none'          => 'Hiçbiri',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Dizini',
        'log'               => 'E-mailleri logla',
    ],
    'scheduling' => [
        'tab'               => 'Zamanlama',
        'send_invoice'      => 'Gelir Faturası Hatırlat',
        'invoice_days'      => 'Vade Gününden Sonra Gönder',
        'send_bill'         => 'Gider Faturası Hatırlat',
        'bill_days'         => 'Vade Gününden Önce Gönder',
        'cron_command'      => 'Cron Komutu',
        'schedule_time'     => 'Çalışma Saati',
    ],
    'appearance' => [
        'tab'               => 'Görünüm',
        'theme'             => 'Tema',
        'light'             => 'Açık',
        'dark'              => 'Koyu',
        'list_limit'        => 'Sayfa Başına Kayıt Sayısı',
        'use_gravatar'      => 'Gravatar kullanın',
    ],
    'system' => [
        'tab'               => 'Sistem',
        'session' => [
            'lifetime'      => 'Oturum süresi (Dakika)',
            'handler'       => 'Oturum Yöneticisi',
            'file'          => 'Dosya',
            'database'      => 'Veritabanı',
        ],
        'file_size'         => 'Maksimum dosya boyutu (MB)',
        'file_types'        => 'İzin verilen dosya türleri',
    ],

];
