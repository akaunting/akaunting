<?php

return [

    'company' => [
        'name'              => 'الاسم',
        'email'             => 'البريد الإلكتروني',
        'phone'             => 'رقم الهاتف',
        'address'           => 'العنوان',
        'logo'              => 'الشعار',
    ],
    'localisation' => [
        'tab'               => 'المنطقة',
        'date' => [
            'format'        => 'صيغة التاريخ',
            'separator'     => 'فاصل التاريخ',
            'dash'          => 'شَرطة (-)',
            'dot'           => 'نقطة (.)',
            'comma'         => 'فاصلة (,)',
            'slash'         => 'خط مائل (/)',
            'space'         => 'مسافة ( )',
        ],
        'timezone'          => 'التوقيت',
        'percent' => [
            'title'         => 'مكان النسبة (%)',
            'before'        => 'قبل الرقم',
            'after'         => 'بعد الرقم',
        ],
    ],
    'invoice' => [
        'tab'               => 'فاتورة الشراء',
        'prefix'            => 'بادئة الرقم',
        'digit'             => 'عدد الأرقام',
        'next'              => 'الرقم التالي',
        'logo'              => 'الشعار',
        'custom'            => 'مخصص',
        'item_name'         => 'اسم العنصر',
        'item'              => 'العناصر',
        'product'           => 'المنتجات',
        'service'           => 'الخدمات',
        'price_name'        => 'اسم السعر',
        'price'             => 'السعر',
        'rate'              => 'التقيم',
        'quantity_name'     => 'اسم الكمية',
        'quantity'          => 'الكمية',
    ],
    'default' => [
        'tab'               => 'الافتراضي',
        'account'           => 'الحساب الافتراضي',
        'currency'          => 'العملة الافتراضية',
        'tax'               => 'معدل الضريبة الافتراضي',
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
            'username'      => 'اسم مستخدم SMTP',
            'password'      => 'كلمة مرور SMTP',
            'encryption'    => 'نوع تشفير SMTP',
            'none'          => 'لا يوجد',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'مسار Sendmail',
        'log'               => 'سجل الرسائل الإلكترونية',
    ],
    'scheduling' => [
        'tab'               => 'الجدول الزمني',
        'send_invoice'      => 'إرسال تذكير لفاتورة البيع',
        'invoice_days'      => 'إرسال بعد ميعاد الاستحقاق بأيام',
        'send_bill'         => 'إرسال تذكير لفاتورة الشراء',
        'bill_days'         => 'إرسال قبل ميعاد الاستحقاق بأيام',
        'cron_command'      => 'أمر التكرار',
        'schedule_time'     => 'ساعة البدء',
        'send_item_reminder'=> 'إرسال رسالة تذكيرية للعنصر',
        'item_stocks'       => 'إرسال رسالة للعنصر المخزون',
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
            'lifetime'      => 'مدة الجلسة (بالدقائق)',
            'handler'       => 'معالج الجلسة',
            'file'          => 'ملف',
            'database'      => 'قاعدة البيانات',
        ],
        'file_size'         => 'الحجم الأقصى للملف (بالميجابايت)',
        'file_types'        => 'أنواع الملفات المسموحة',
    ],

];
