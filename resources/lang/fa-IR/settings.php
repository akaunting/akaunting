<?php

return [

    'company' => [
        'description'       => 'Change company name, email, address, tax number etc',
        'name'              => 'نام',
        'email'             => 'ایمیل',
        'phone'             => 'تلفن',
        'address'           => 'آدرس',
        'logo'              => 'لوگو',
    ],

    'localisation' => [
        'description'       => 'Set fiscal year, time zone, date format and more locals',
        'financial_start'   => 'شروع سال مالی',
        'timezone'          => 'منطقه زمانی',
        'date' => [
            'format'        => 'فرمت تاریخ',
            'separator'     => 'جداکننده تاریخ',
            'dash'          => 'خط تیره (-)',
            'dot'           => 'نقطه (.)',
            'comma'         => 'کاما (,)',
            'slash'         => 'علامت ممیز (/)',
            'space'         => 'فضا ( )',
        ],
        'percent' => [
            'title'         => 'درصد (%) موقعیت',
            'before'        => 'قبل از شماره',
            'after'         => 'پس از شماره',
        ],
        'discount_location' => [
            'name'          => 'Discount Location',
            'item'          => 'At line',
            'total'         => 'At total',
            'both'          => 'Both line and total',
        ],
    ],

    'invoice' => [
        'description'       => 'Customize invoice prefix, number, terms, footer etc',
        'prefix'            => 'پیشوند شماره',
        'digit'             => 'تعداد ارقام',
        'next'              => 'شماره بعدی',
        'logo'              => 'لوگو',
        'custom'            => 'سفارشی',
        'item_name'         => 'نام کالا',
        'item'              => 'کالاها',
        'product'           => 'محصول‌ها',
        'service'           => 'خدمات',
        'price_name'        => 'قیمت نام',
        'price'             => 'قيمت',
        'rate'              => 'نرخ',
        'quantity_name'     => 'نام مقدار',
        'quantity'          => 'مقدار',
        'payment_terms'     => 'Payment Terms',
        'title'             => 'Title',
        'subheading'        => 'Subheading',
        'due_receipt'       => 'Due upon receipt',
        'due_days'          => 'Due within :days days',
        'choose_template'   => 'Choose invoice template',
        'default'           => 'Default',
        'classic'           => 'Classic',
        'modern'            => 'Modern',
    ],

    'default' => [
        'description'       => 'Default account, currency, language of your company',
        'list_limit'        => 'Records Per Page',
        'use_gravatar'      => 'Use Gravatar',
    ],

    'email' => [
        'description'       => 'Change the sending protocol and email templates',
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

        'templates' => [
            'subject'                   => 'Subject',
            'body'                      => 'Body',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'New Invoice Template (sent to customer)',
            'invoice_remind_customer'   => 'Invoice Reminder Template (sent to customer)',
            'invoice_remind_admin'      => 'Invoice Reminder Template (sent to admin)',
            'invoice_recur_customer'    => 'Invoice Recurring Template (sent to customer)',
            'invoice_recur_admin'       => 'Invoice Recurring Template (sent to admin)',
            'invoice_payment_customer'  => 'Payment Received Template (sent to customer)',
            'invoice_payment_admin'     => 'Payment Received Template (sent to admin)',
            'bill_remind_admin'         => 'Bill Reminder Template (sent to admin)',
            'bill_recur_admin'          => 'Bill Recurring Template (sent to admin)',
        ],
    ],

    'scheduling' => [
        'name'              => 'برنامه‌ریزی',
        'description'       => 'Automatic reminders and command for recurring',
        'send_invoice'      => 'ارسال فاکتور یادآور',
        'invoice_days'      => 'ارسال بعد از چند روز',
        'send_bill'         => 'ارسال یاد آور صورتحساب',
        'bill_days'         => 'تعداد روز ارسال قبل از سررسید',
        'cron_command'      => 'فرمان Cron',
        'schedule_time'     => 'ساعت به اجرا',
    ],

    'categories' => [
        'description'       => 'Unlimited categories for income, expense, and item',
    ],

    'currencies' => [
        'description'       => 'Create and manage currencies and set their rates',
    ],

    'taxes' => [
        'description'       => 'Fixed, normal, inclusive, and compound tax rates',
    ],

];
