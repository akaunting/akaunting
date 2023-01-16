<?php

return [

    'company' => [
        'description'       => 'تغییر اسم شرکت، ایمیل، آدرس، کد اقتصادی و ...',
        'name'              => 'نام',
        'email'             => 'ایمیل',
        'phone'             => 'تلفن',
        'address'           => 'آدرس',
        'logo'              => 'لوگو',
    ],

    'localisation' => [
        'description'       => 'تنظیم سال مالی، منطقه زمانی، فرمت تاریخ و سایر بومی سازی ها',
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
            'name'          => 'نوع تخفیف',
            'item'          => 'جزئی',
            'total'         => 'کلی',
            'both'          => 'جزئی و کلی',
        ],
    ],

    'invoice' => [
        'description'       => 'شخصی سازی پیشوند فاکتور، شماره، شرایط، پانویس و ...',
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
        'payment_terms'     => 'شرایط پرداخت',
        'title'             => 'عنوان',
        'subheading'        => 'زیر عنوان',
        'due_receipt'       => 'به محض دریافت',
        'due_days'          => 'طی :days روز',
        'choose_template'   => 'قالب فاکتور را انتخاب کنید',
        'default'           => 'پیشفرض',
        'classic'           => 'کلاسیک',
        'modern'            => 'مدرن',
        'hide'              => [
            'item_name'         => 'پنهان کردن نام مورد',
            'item_description'  => 'پنهان کردن توضیحات مورد',
            'quantity'          => 'پنهان کردن تعداد',
            'price'             => 'پنهان کردن قیمت',
            'amount'            => 'پنهان کردن مقدار',
        ],
    ],

    'default' => [
        'description'       => 'حساب پیش فرض، واحد پول و زبان شرکت شما',
        'list_limit'        => 'تعداد رکورد ها در هر صفحه',
        'use_gravatar'      => 'استفاده از آواتار شناخته شده جهانی',
        'income_category'   => 'دسته درآمد',
        'expense_category'  => 'دسته هزینه',
    ],

    'email' => [
        'description'       => 'تغییر پروتکل ارسال و قالب های ایمیل',
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
            'subject'                   => 'موضوع',
            'body'                      => 'بدنه',
            'tags'                      => '<strong>تگ های در دسترس:</strong> :tag_list',
            'invoice_new_customer'      => 'قالب فاکتور جدید (برای ارسال به مشتری)',
            'invoice_remind_customer'   => 'قالب یادآوری فاکتور (برای ارسال به مشتری)',
            'invoice_remind_admin'      => 'قالب یادآوری فاکتور (برای ارسال به مدیر)',
            'invoice_recur_customer'    => 'قالب تکرار فاکتور (برای ارسال به مشتری)',
            'invoice_recur_admin'       => 'قالب تکرار فاکتور (برای ارسال به مدیر)',
            'invoice_payment_customer'  => 'قالب پرداخت فاکتور (برای ارسال به مشتری)',
            'invoice_payment_admin'     => 'قالب پرداخت فاکتور (برای ارسال به مدیر)',
            'bill_remind_admin'         => 'قالب یادآوری صورتحساب (برای ارسال به مدیر)',
            'bill_recur_admin'          => 'قالب تکرار صورتحساب (برای ارسال به مدیر)',
        ],
    ],

    'scheduling' => [
        'name'              => 'برنامه‌ریزی',
        'description'       => 'یادآوری ها و دستورالعمل های خودکار برای تکرار فاکتور',
        'send_invoice'      => 'ارسال فاکتور یادآور',
        'invoice_days'      => 'ارسال بعد از چند روز',
        'send_bill'         => 'ارسال یاد آور صورتحساب',
        'bill_days'         => 'تعداد روز ارسال قبل از سررسید',
        'cron_command'      => 'فرمان Cron',
        'schedule_time'     => 'ساعت به اجرا',
    ],

    'categories' => [
        'description'       => 'دسته بندی های نامحدود برای درآمد ، هزینه و اقلام',
    ],

    'currencies' => [
        'description'       => 'ساخت و مدیریت واحد های پولی و تنظیم نسبت آن ها با یکدیگر',
    ],

    'taxes' => [
        'description'       => 'نرخ مالیات ثابت، عادی، شامل و ترکیبی',
    ],

];
