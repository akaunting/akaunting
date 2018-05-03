<?php

return [

    'company' => [
        'name'              => 'الاسم',
        'email'             => 'البريد الالكتروني',
        'phone'             => 'رقم الهاتف',
        'address'           => 'العنوان',
        'logo'              => 'الشعار',
    ],
    'localisation' => [
        'tab'               => 'المنطقة',
        'date' => [
            'format'        => 'صيغة التاريخ',
            'separator'     => 'صيغة الفاصل',
            'dash'          => 'فاصل (-)',
            'dot'           => 'نقطة (.)',
            'comma'         => 'فاصل (,)',
            'slash'         => 'فاصل (/)',
            'space'         => 'مسافة ( )',
        ],
        'timezone'          => 'التوقيت',
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
    ],
    'invoice' => [
        'tab'               => 'فاتورة الشراء',
        'prefix'            => 'رقم البداية',
        'digit'             => 'عدد الأرقام',
        'next'              => 'الرقم التالى',
        'logo'              => 'الشعار',
    ],
    'default' => [
        'tab'               => 'الافتراضى',
        'account'           => 'الحساب الافتراضى',
        'currency'          => 'العملة الافتراضية',
        'tax'               => 'معدل الضريبة الافتراضى',
        'payment'           => 'طريقة الدفع الافتراضية',
        'language'          => 'اللغة الافتراضية',
    ],
    'email' => [
        'protocol'          => 'بروتوكول',
        'php'               => 'بريد PHP',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'مُضيف SMTP',
            'port'          => 'منفذ SMTP',
            'username'      => 'اسم المستخدم SMTP',
            'password'      => 'كلمة مرور SMTP',
            'encryption'    => 'الأمن SMTP',
            'none'          => 'لا يوجد',
        ],
        'sendmail'          => 'ارسال بريد الكتروني',
        'sendmail_path'     => 'مسار البريد الالكتروني',
        'log'               => 'سجل الرسائل الالكترونية',
    ],
    'scheduling' => [
        'tab'               => 'الجدول الزمني',
        'send_invoice'      => 'ارسال اشعار تذكيرى لفاتورة البيع',
        'invoice_days'      => 'ارسال بعد أيام',
        'send_bill'         => 'ارسال اشعار تذكيرى لفاتورة الشراء',
        'bill_days'         => 'ارسال قبل ميعاد الاستحقاق بأيام',
        'cron_command'      => 'أمر تكرار',
        'schedule_time'     => 'ساعة البدء',
    ],
    'appearance' => [
        'tab'               => 'الظهور',
        'theme'             => 'القالب',
        'light'             => 'فاتح',
        'dark'              => 'داكن',
        'list_limit'        => 'عدد النتائج في كل صفحة',
        'use_gravatar'      => 'استخدام Gravatar',
    ],
    'system' => [
        'tab'               => 'النظام',
        'session' => [
            'lifetime'      => 'مدة الفتح الاوتوماتيكي (بالدقائق)',
            'handler'       => 'معالج الفتح الاوتوماتيكي',
            'file'          => 'ملف',
            'database'      => 'قاعدة البيانات',
        ],
        'file_size'         => 'الحجم الأقصى للملف (بالميجابايت)',
        'file_types'        => 'السماح لأنواع الملفات',
    ],

];
