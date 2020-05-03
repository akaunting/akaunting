<?php

return [

    'company' => [
        'description'       => 'تغيير اسم الشركه، البريد اﻹلكتروني، العنوان، الرقم الضريبي الخ',
        'name'              => 'الاسم',
        'email'             => 'البريد الإلكتروني',
        'phone'             => 'رقم الهاتف',
        'address'           => 'العنوان',
        'logo'              => 'الشعار',
    ],

    'localisation' => [
        'description'       => 'قم بتحديد السنة المالية والمنطقة الزمنية وصيغة التاريخ والمزيد من الاعدادات',
        'financial_start'   => 'بدء السنة المالية',
        'timezone'          => 'التوقيت',
        'date' => [
            'format'        => 'صيغة التاريخ',
            'separator'     => 'فاصل التاريخ',
            'dash'          => 'شَرطة (-)',
            'dot'           => 'نقطة (.)',
            'comma'         => 'فاصلة (,)',
            'slash'         => 'خط مائل (/)',
            'space'         => 'مسافة ( )',
        ],
        'percent' => [
            'title'         => 'مكان النسبة (%)',
            'before'        => 'قبل الرقم',
            'after'         => 'بعد الرقم',
        ],
        'discount_location' => [
            'name'          => 'Discount Location',
            'item'          => 'At line',
            'total'         => 'At total',
            'both'          => 'Both line and total',
        ],
    ],

    'invoice' => [
        'description'       => 'تخصيص بادئة الفاتورة ، الرقم ، الشروط ، تذييل الصفحة ، إلخ',
        'prefix'            => 'بادئة الرقم',
        'digit'             => 'عدد الأرقام',
        'next'              => 'الرقم التالي',
        'logo'              => 'الشعار',
        'custom'            => 'مُخصّص',
        'item_name'         => 'اسم العنصر',
        'item'              => 'عناصر',
        'product'           => 'المنتجات',
        'service'           => 'الخدمات',
        'price_name'        => 'اسم السعر',
        'price'             => 'السعر',
        'rate'              => 'معدل',
        'quantity_name'     => 'اسم الكمية',
        'quantity'          => 'الكمية',
        'payment_terms'     => 'شروط الدفع',
        'title'             => 'العنوان',
        'subheading'        => 'عنوان فرعي',
        'due_receipt'       => 'مستحق عند الاستلام',
        'due_days'          => 'مُستحقة خلال :days أيام',
        'choose_template'   => 'اختيار قالب الفاتورة',
        'default'           => 'الافتراضي',
        'classic'           => 'كلاسيكي',
        'modern'            => 'عصري',
    ],

    'default' => [
        'description'       => 'الحساب اﻹفتراضي، العملة، لغة الشركة',
        'list_limit'        => 'عدد السجلات في كل صفحة',
        'use_gravatar'      => 'إستخدم Gravatar',
    ],

    'email' => [
        'description'       => 'تغيير بروتوكول اﻹرسال و قالب البريد اﻹلكتروني',
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

        'templates' => [
            'subject'                   => 'الموضوع',
            'body'                      => 'الجسم',
            'tags'                      => '<strong>الوسوم المتوفرة:<stonge/> tag_list:',
            'invoice_new_customer'      => 'انشاء قالب فاتورة جديد (يُرسل الى العميل)',
            'invoice_remind_customer'   => 'قالب تذكير لفاتورة (تُرسل الى العميل)',
            'invoice_remind_admin'      => 'قالب تذكير لفاتورة (تُرسل الى المدير)',
            'invoice_recur_customer'    => 'قالب تكرار الفاتورة (يتم إرساله إلى العميل)',
            'invoice_recur_admin'       => 'قالب تكرار الفاتورة (يتم إرساله إلى المدير)',
            'invoice_payment_customer'  => 'قالب استلام المدفوعات (يُرسل الى العميل)',
            'invoice_payment_admin'     => 'قالب استلام المدفوعات (يُرسل الى المدير)',
            'bill_remind_admin'         => 'قالب تذكير لفاتورة (أُرسل الى المدير)',
            'bill_recur_admin'          => 'قالب تكرار الفاتورة (أُرسل إلى المدير)',
        ],
    ],

    'scheduling' => [
        'name'              => 'جدولة',
        'description'       => 'التذاكير التلقائية والأمر للتكرار',
        'send_invoice'      => 'إرسال تذكير لفاتورة البيع',
        'invoice_days'      => 'إرسال بعد ميعاد الاستحقاق بأيام',
        'send_bill'         => 'إرسال تذكير لفاتورة الشراء',
        'bill_days'         => 'إرسال قبل ميعاد الاستحقاق بأيام',
        'cron_command'      => 'أمر التكرار',
        'schedule_time'     => 'ساعة التنفيذ',
    ],

    'categories' => [
        'description'       => 'تصنيفات غير محدودة للإيرادت و المصروفات و العناصر',
    ],

    'currencies' => [
        'description'       => 'أنشئ و أدر العملات و أضف معدلاتها',
    ],

    'taxes' => [
        'description'       => 'معدلات الضريبة الثابتة والعادية والشاملة والمركبة',
    ],

];
