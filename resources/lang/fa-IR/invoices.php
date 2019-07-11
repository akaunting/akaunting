<?php

return [

    'invoice_number'    => 'شماره فاکتور',
    'invoice_date'      => 'تاریخ فاکتور',
    'total_price'       => 'قیمت کل',
    'due_date'          => 'سررسید',
    'order_number'      => 'شماره فاکتور',
    'bill_to'           => 'صورتحساب برای',

    'quantity'          => 'تعداد',
    'price'             => 'قيمت',
    'sub_total'         => 'جمع کل',
    'discount'          => 'تخفیف',
    'tax_total'         => 'مجموع مالیات',
    'total'             => 'مجموع',

    'item_name'         => 'نام آیتم | نام آیتم ها',

    'show_discount'     => ':discount% Discount',
    'add_discount'      => 'افزودن تخفیف',
    'discount_desc'     => 'از جمع کل',

    'payment_due'       => 'سررسید پرداخت',
    'paid'              => 'پرداخت شده',
    'histories'         => 'تاریخچه',
    'payments'          => 'پرداخت ها',
    'add_payment'       => 'پرداخت',
    'mark_paid'         => 'پرداخت شده',
    'mark_sent'         => 'ارسال شده',
    'download_pdf'      => 'دانلود PDF',
    'send_mail'         => 'ارسال ایمیل',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'پیش‌ نویس',
        'sent'          => 'ارسال شده',
        'viewed'        => 'مشاهده شده',
        'approved'      => 'تایید شده',
        'partial'       => 'جزئیات',
        'paid'          => 'پرداخت شده',
    ],

    'messages' => [
        'email_sent'     => 'فاکتور با موفقت ارسال شده است!',
        'marked_sent'    => 'فاکتور با موفقت ارسال شده است!',
        'email_required' => 'هیچ آدرس ایمیل برای این مشتری موجود نیست!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'ارسال نشده',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'در انتظار پرداخت',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'شما این ایمیل را دریافت کردید به دلیل اینکه مشتری شما :customer مقدار :amount فاکتور دارد.',
        'button'        => 'پرداخت',
    ],

];
