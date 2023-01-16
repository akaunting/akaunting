<?php

return [

    'company' => [
        'description'       => 'Kompaniya nomini, elektron pochta manzilini, soliq raqamini va boshqalarni o\'zgartirish',
        'name'              => 'Ismi',
        'email'             => 'Elektron pochta',
        'phone'             => 'Telefon',
        'address'           => 'Manzil',
        'logo'              => 'Logotip',
    ],

    'localisation' => [
        'description'       => 'Moliya yili, vaqt zonasi, sana formati va boshqa joylarni belgilang',
        'financial_start'   => 'Moliyaviy yil boshlanishi',
        'timezone'          => 'Vaqt zonasi',
        'date' => [
            'format'        => 'Sana formati',
            'separator'     => 'Sana ajratuvchi',
            'dash'          => 'Tire (-)',
            'dot'           => 'Nuqta (.)',
            'comma'         => 'Vergul (,)',
            'slash'         => 'Qiya chiziq (/)',
            'space'         => 'Bo‘sh joy ()',
        ],
        'percent' => [
            'title'         => 'Foiz (%) pozitsiyasi',
            'before'        => 'Raqamdan oldin',
            'after'         => 'Raqamdan keyin',
        ],
    ],

    'invoice' => [
        'description'       => 'Prefiksni, raqamni, shartlarni, kolontitul va boshqalarni sozlang',
        'prefix'            => 'Raqam prefiksi',
        'digit'             => 'Raqam',
        'next'              => 'Keyingi raqam',
        'logo'              => 'Logotip',
        'custom'            => 'Buyurtmaga tayyorlangan',
        'item_name'         => 'Mahsulot nomi',
        'item'              => 'Bandlar',
        'product'           => 'Mahsulotlar',
        'service'           => 'Xizmatlar',
        'price_name'        => 'Narx nomi',
        'price'             => 'Narx',
        'rate'              => 'Baholash',
        'quantity_name'     => 'Miqdori nomi',
        'quantity'          => 'Miqdori',
        'payment_terms'     => 'To\'lov shartlari',
        'title'             => 'Sarlavha',
        'subheading'        => 'Kichik sarlavha',
        'due_receipt'       => 'Qabul qilinganidan keyin to\'lanishi kerak',
        'due_days'          => 'To\'lash muddati: kunlar davomida',
    ],

    'default' => [
        'description'       => 'Sizning hisob qaydnomangiz, valyutangiz, kompaniyangizning tili',
        'list_limit'        => 'Bir sahifadagi yozuvlar',
        'use_gravatar'      => 'Gravataradan foydalaning',
    ],

    'email' => [
        'description'       => 'Yuborilgan protokol va elektron pochta shablonlarini o\'zgartiring',
        'protocol'          => 'Protokol',
        'php'               => 'PHP pochta',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP xo\'jayin',
            'port'          => 'SMTP Porti',
            'username'      => 'SMTP login',
            'password'      => 'SMTP Paroli',
            'encryption'    => 'SMTP xavfsizligi',
            'none'          => 'Yo\'q',
        ],
        'sendmail'          => 'Xat yuboring',
        'sendmail_path'     => 'Pochta manzilini yuboring',
        'log'               => 'Elektron pochta jurnali',

        'templates' => [
            'subject'                   => 'Mavzu',
            'body'                      => 'Asosiy matn',
            'tags'                      => '<strong>Mavjud teglar</strong> :tag_list',
            'invoice_new_customer'      => 'Hisob-fakturaning yangi shablonlari (mijozga yuborilgan)',
            'invoice_remind_customer'   => 'Hisob-fakturani eslatuvchi shablon (mijozga yuborilgan)',
            'invoice_remind_admin'      => 'Hisob-fakturani eslatuvchi shablon (administratorga yuborilgan)',
            'invoice_recur_customer'    => 'Hisob-fakturanining shabloni (mijozga yuboriladi)',
            'invoice_recur_admin'       => 'Hisob-faktura shablonlari (administratorga yuborilgan)',
            'invoice_payment_customer'  => 'To\'lov qabul qilingan shablon (mijozga yuboriladi)',
            'invoice_payment_admin'     => 'To\'lov qabul qilingan shablon (administratorga yuborilgan)',
            'bill_remind_admin'         => 'Eslatib turuvchi shablon (administratorga yuborilgan)',
            'bill_recur_admin'          => 'Hisobni takrorlovchi shablon (administratorga yuborilgan)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Rejalashtirish',
        'description'       => 'Avtomatik eslatmalar va takrorlash buyrug\'i',
        'send_invoice'      => 'Hisob-faktura eslatmasini yuborish',
        'invoice_days'      => 'Tugash kunidan keyin yuboring',
        'send_bill'         => 'Hisob eslatmasini yuboring',
        'bill_days'         => 'Belgilangan kun vuddatidan oldin yuboring',
        'cron_command'      => 'Cron buyrug\'i',
        'schedule_time'     => 'Yugurish vaqti',
    ],

    'categories' => [
        'description'       => 'Daromad, xarajat va element uchun cheklanmagan toifalar',
    ],

    'currencies' => [
        'description'       => 'Valyutalarni yarating va boshqaring va narxlarni belgilang',
    ],

    'taxes' => [
        'description'       => 'Ruxsat etilgan, normal, qayd etilgan va murakkab soliq stavkalari',
    ],

];
