<?php

return [

    'company' => [
        'name'              => 'نام',
        'email'             => 'ایمیل',
        'phone'             => 'تلفن',
        'address'           => 'آدرس',
        'logo'              => 'لوگو',
    ],
    'localisation' => [
        'tab'               => 'موقعیت',
        'date' => [
            'format'        => 'فرمت تاریخ',
            'separator'     => 'جداکننده تاریخ',
            'dash'          => 'خط تیره (-)',
            'dot'           => 'نقطه (.)',
            'comma'         => 'کاما (,)',
            'slash'         => 'علامت ممیز (/)',
            'space'         => 'فضا ( )',
        ],
        'timezone'          => 'منطقه زمانی',
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
    ],
    'invoice' => [
        'tab'               => 'فاکتور',
        'prefix'            => 'پیشوند شماره',
        'digit'             => 'تعداد ارقام',
        'next'              => 'شماره بعدی',
        'logo'              => 'لوگو',
    ],
    'default' => [
        'tab'               => 'پیش‌فرض‌ها',
        'account'           => 'حساب پیش فرض',
        'currency'          => 'واحد پول پیش فرض',
        'tax'               => 'نرخ مالیات پیش فرض',
        'payment'           => 'پیش فرض روش پرداخت',
        'language'          => 'زبان پیش فرض',
    ],
    'email' => [
        'protocol'          => 'پروتکل',
        'php'               => 'ایمیل PHP',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'هاست SMTP',
            'port'          => 'پورت SMTP',
            'username'      => 'نام کاربری SMTP',
            'password'      => 'رمز عبور SMTP',
            'encryption'    => 'امنیت SMTP',
            'none'          => 'هیچ',
        ],
        'sendmail'          => 'Sendmail ',
        'sendmail_path'     => 'مسیر Sendmail',
        'log'               => 'رکورد های ایمیل',
    ],
    'scheduling' => [
        'tab'               => 'برنامه‌ریزی',
        'send_invoice'      => 'ارسال فاکتور یادآور',
        'invoice_days'      => 'ارسال بعد از چند روز',
        'send_bill'         => 'ارسال یاد آور صورتحساب',
        'bill_days'         => 'تعداد روز ارسال قبل از سررسید',
        'cron_command'      => 'فرمان Cron',
        'schedule_time'     => 'ساعت به اجرا',
    ],
    'appearance' => [
        'tab'               => 'ظاهر',
        'theme'             => 'قالب',
        'light'             => 'روشن',
        'dark'              => 'تاریک',
        'list_limit'        => 'نتایج در هر صفحه',
        'use_gravatar'      => 'استفاده از Gravatar',
    ],
    'system' => [
        'tab'               => 'سیستم',
        'session' => [
            'lifetime'      => 'جلسه طول عمر (دقیقه)',
            'handler'       => 'مکانیزم نشست',
            'file'          => 'فایل',
            'database'      => 'پایگاه داده',
        ],
        'file_size'         => 'حداکثر اندازه فایل (MB)',
        'file_types'        => 'نوع فایل مجاز',
    ],

];
