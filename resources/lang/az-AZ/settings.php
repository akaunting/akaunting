<?php

return [

    'company' => [
        'description'                => 'Şirkətin adını, adresini, vergi nömrəsini vs. dəyişdirin',
        'name'                       => 'Şirkət Adı',
        'email'                      => 'Şirkət E-poçtu',
        'phone'                      => 'Telefon',
        'address'                    => 'Şirkət Ünvanı',
        'edit_your_business_address' => 'Hüquqi ünvanınızı daxil edin',
        'logo'                       => 'Şirkət Logosu',
    ],

    'localisation' => [
        'description'       => 'Maliyyə ilinin başlanğıcını, saat qurşağını, tarix formatını və s. dəyişdirin.',
        'financial_start'   => 'Maliyyə ilinin başlanğıcı',
        'timezone'          => 'Saat Qurşağı',
        'financial_denote' => [
            'title'         => 'Maliyyə ilini qeyd edin',
            'begins'        => 'Başlanğıc il',
            'ends'          => 'Bitiş il',
        ],
        'date' => [
            'format'        => 'Tarix Formatı',
            'separator'     => 'Tarix Ayracı',
            'dash'          => 'Tire (-)',
            'dot'           => 'Nöqtə (.)',
            'comma'         => 'Vergül (,)',
            'slash'         => 'Bölmə (/)',
            'space'         => 'Boşluq ( )',
        ],
        'percent' => [
            'title'         => 'Faiz (%) Yeri',
            'before'        => 'Nömrədən əvvəl',
            'after'         => 'Nömrədən Sonra',
        ],
        'discount_location' => [
            'name'          => 'Endirim Yeri',
            'item'          => 'Sətirdə',
            'total'         => 'Cəmdə',
            'both'          => 'Sətir və Cəmdə',
        ],
    ],

    'invoice' => [
        'description'       => 'Faktura nömrəsi, prefiks, müddət və s. özəlləşdirmək',
        'prefix'            => 'Nömrə prefiksi',
        'digit'             => 'Nömrə Rəqəm Sayı',
        'next'              => 'Sonraki Nömrə',
        'logo'              => 'Logo',
        'custom'            => 'Xüsusi',
        'item_name'         => 'Məhsul/Xidmət adı',
        'item'              => 'Məhsullar/Xidmətlər',
        'product'           => 'Məhsullar',
        'service'           => 'Xidmətlər',
        'price_name'        => 'Qiymət Adı',
        'price'             => 'Qiymət',
        'rate'              => 'Dərəcə',
        'quantity_name'     => 'Miqdar Adı',
        'quantity'          => 'Miqdar',
        'payment_terms'     => 'Ödəmə şərtləri',
        'title'             => 'Başlıq',
        'subheading'        => 'Altbaşlıq',
        'due_receipt'       => 'Qəbul edildikdən sonra',
        'due_days'          => ':days müddət',
        'choose_template'   => 'Faktura Şablonu seçin',
        'default'           => 'Varsayılan',
        'classic'           => 'Klasik',
        'modern'            => 'Modern',
        'hide'              => [
            'item_name'         => 'Məhsul/Xidmət Adını Gizlə',
            'item_description'  => 'Məhsul/Xidmət Açıqlamasını gizlə',
            'quantity'          => 'Miqdarı Gizlə',
            'price'             => 'Qiyməti Gizlə',
            'amount'            => 'Məbləği Gizlə',
        ],
    ],

    'default' => [
        'description'       => 'Şirkətinizin varsayılan hesabı, valyutası, dili və s.',
        'list_limit'        => 'Səhifə başına qeydlərin sayı',
        'use_gravatar'      => 'Gravatar istifadə edin',
        'income_category'   => 'Gəlir Kateqoriyası',
        'expense_category'  => 'Xərc Kateqoriyası',
    ],

    'email' => [
        'description'       => 'E-poçt şablonlarını və göndərmə protokolunu dəyişdirin',
        'protocol'          => 'Protokol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'SMTP İstifadəçi adı',
            'password'      => 'SMTP Şifrəsi',
            'encryption'    => 'SMTP Təhlükəsizlik',
            'none'          => 'Heçbiri',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Yolu',
        'log'               => 'E-mailleri logla',

        'templates' => [
            'subject'                   => 'Başlıq',
            'body'                      => 'Mətn',
            'tags'                      => '<strong>Mövcud Teqlər:</strong> :tag_list',
            'invoice_new_customer'      => 'Yeni Faktura Şablonu (müştəriyə göndərilir)',
            'invoice_remind_customer'   => 'Faktura Xatırlatma Şablonu (müştəriyə göndərilir)',
            'invoice_remind_admin'      => 'Faktura Xatırlatma Şablonu (inzibatçıya göndərilir)',
            'invoice_recur_customer'    => 'Təkrarlanan Faktura Şablonu (müştəriyə göndərilir)',
            'invoice_recur_admin'       => 'Təkrarlanan Faktura Şablonu (inzibatçıya göndərilir)',
            'invoice_payment_customer'  => 'Ödəniş qəbzi şablonu (müştəriyə göndərilir)',
            'invoice_payment_admin'     => 'Ödəniş qəbzi şablonu (inzibatçıya göndərilir)',
            'bill_remind_admin'         => 'Xərclər Xatırlatma Şablonu (inzibatçıya göndərilir)',
            'bill_recur_admin'          => 'Təkrarlanan Xərc Fakturası Şablonu (inzibatçıya göndərilir)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Vaxt',
        'description'       => 'Avtomatik xatırlatmalar və təkrarlanan hərəkətlər üçün komanda xətti',
        'send_invoice'      => 'Gəlir Fakturası Xatırlat',
        'invoice_days'      => 'Ödəniş gündən sonra göndər',
        'send_bill'         => 'Xərc Fakturası Xatırlat',
        'bill_days'         => 'Ödniş Günündən əvvəl göndər',
        'cron_command'      => 'Cron komandası',
        'schedule_time'     => 'Çalışma Saatı',
    ],

    'categories' => [
        'description'       => 'Limitsiz gəlir, xərc və Məhsul kateqoriyalarını yaradın',
    ],

    'currencies' => [
        'description'       => 'Valyuta yaradın və onların məzənnələrini tənzimləyin',
    ],

    'taxes' => [
        'description'       => 'Sabit, müntəzəm, əhatəli və qarışıq vergi sinifləri yaradın',
    ],

];
