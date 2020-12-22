<?php

return [

    'company' => [
        'description'       => 'Şirketin ismini, adresini, vergi numrasını vs. değiştirin',
        'name'              => 'Şirket İsmi',
        'email'             => 'Şirket Emaili',
        'phone'             => 'Telefon',
        'address'           => 'Şirket Adresi',
        'logo'              => 'Şirket Logosu',
    ],

    'localisation' => [
        'description'       => 'Mali yıl başlangıcını, saat dilimini, tarih biçimini vs. ayarlayın',
        'financial_start'   => 'Mali Yıl Başlangıcı',
        'timezone'          => 'Saat Dilimi',
        'date' => [
            'format'        => 'Tarih Biçimi',
            'separator'     => 'Tarih Ayracı',
            'dash'          => 'Tire (-)',
            'dot'           => 'Nokta (.)',
            'comma'         => 'Virgül (,)',
            'slash'         => 'Taksim (/)',
            'space'         => 'Boşluk ( )',
        ],
        'percent' => [
            'title'         => 'Yüzde (%) Konumu',
            'before'        => 'Sayıdan Önce',
            'after'         => 'Sayıdan Sonra',
        ],
        'discount_location' => [
            'name'          => 'İndirim Konumu',
            'item'          => 'Satırda',
            'total'         => 'Toplamda',
            'both'          => 'Satırda ve toplamda',
        ],
    ],

    'invoice' => [
        'description'       => 'Fatura numarasını, önekini, vadesini vs. özelleştirin',
        'prefix'            => 'Numara Öneki',
        'digit'             => 'Numara Rakam Sayısı',
        'next'              => 'Sonraki Numara',
        'logo'              => 'Logo',
        'custom'            => 'Özel',
        'item_name'         => 'Ürün adı',
        'item'              => 'Ürünler',
        'product'           => 'Ürünler',
        'service'           => 'Hizmetler',
        'price_name'        => 'Fiyat Adı',
        'price'             => 'Fiyat',
        'rate'              => 'Oran',
        'quantity_name'     => 'Miktar Adı',
        'quantity'          => 'Miktar',
        'payment_terms'     => 'Ödeme Vadeleri',
        'title'             => 'Başlık',
        'subheading'        => 'Altbaşlık',
        'due_receipt'       => 'Teslim alındığında vadeli',
        'due_days'          => ':days vadeli',
        'choose_template'   => 'Fatura şablonu seçin',
        'default'           => 'Varsayılan',
        'classic'           => 'Klasik',
        'modern'            => 'Modern',
    ],

    'default' => [
        'description'       => 'Şirketinizin varsayılan hesap, para birimi, dil vs',
        'list_limit'        => 'Sayfa Başına Kayıt Sayısı',
        'use_gravatar'      => 'Gravatar kullanın',
    ],

    'email' => [
        'description'       => 'E-posta şablonları ve gönderim protokolünü değiştirin',
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

        'templates' => [
            'subject'                   => 'Başlık',
            'body'                      => 'İçerik',
            'tags'                      => '<strong>Mevcut Etiketler:</strong> :tag_list',
            'invoice_new_customer'      => 'Yeni Fatura Şablonu (müşteriye gönderilen)',
            'invoice_remind_customer'   => 'Fatura Hatırlatma Şablonu (müşteriye gönderilen)',
            'invoice_remind_admin'      => 'Fatura Hatırlatma Şablonu (yöneticiye gönderilen)',
            'invoice_recur_customer'    => 'Tekrarlı Fatura Şablonu (müşteriye gönderilen)',
            'invoice_recur_admin'       => 'Tekrarlı Fatura Şablonu (yöneticiye gönderilen)',
            'invoice_payment_customer'  => 'Ödeme Alındı Şablonu (müşteriye gönderilen)',
            'invoice_payment_admin'     => 'Ödeme Alındı Şablonu (yöneticiye gönderilen)',
            'bill_remind_admin'         => 'Gider Faturası Hatırlatma Şablonu (yöneticiye gönderilen)',
            'bill_recur_admin'          => 'Tekrarlı Gider Faturası Şablonu (yöneticiye gönderilen)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Zamanlama',
        'description'       => 'Otomatik hatırlatma ve tekrarlı işlemler için komut satırı',
        'send_invoice'      => 'Gelir Faturası Hatırlat',
        'invoice_days'      => 'Vade Gününden Sonra Gönder',
        'send_bill'         => 'Gider Faturası Hatırlat',
        'bill_days'         => 'Vade Gününden Önce Gönder',
        'cron_command'      => 'Cron Komutu',
        'schedule_time'     => 'Çalışma Saati',
    ],

    'categories' => [
        'description'       => 'Sınırsız gelir, gider ve ürün kategorisi oluşturun',
    ],

    'currencies' => [
        'description'       => 'Para birimi oluşturup onların kurlarını ayarlayın',
    ],

    'taxes' => [
        'description'       => 'Sabit, normal, dahil ve bileşik vergi sınıfları oluşturun',
    ],

];
