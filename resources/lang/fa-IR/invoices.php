<?php

return [

    'invoice_number'        => 'شماره فاکتور',
    'invoice_date'          => 'تاریخ فاکتور',
    'total_price'           => 'قیمت کل',
    'due_date'              => 'سررسید',
    'order_number'          => 'شماره فاکتور',
    'bill_to'               => 'صورتحساب برای',

    'quantity'              => 'تعداد',
    'price'                 => 'قيمت',
    'sub_total'             => 'جمع کل',
    'discount'              => 'تخفیف',
    'item_discount'         => 'تخفیف جزء',
    'tax_total'             => 'مجموع مالیات',
    'total'                 => 'مجموع',

    'item_name'             => 'نام آیتم | نام آیتم ها',

    'show_discount'         => ':discount% تخفیف',
    'add_discount'          => 'افزودن تخفیف',
    'discount_desc'         => 'از جمع کل',

    'payment_due'           => 'سررسید پرداخت',
    'paid'                  => 'پرداخت شده',
    'histories'             => 'تاریخچه',
    'payments'              => 'پرداخت ها',
    'add_payment'           => 'پرداخت',
    'mark_paid'             => 'پرداخت شده',
    'mark_sent'             => 'ارسال شده',
    'mark_viewed'           => 'تغییر وضعیت به مشاهده شده',
    'mark_cancelled'        => 'تغییر وضعیت به لغو شده',
    'download_pdf'          => 'دانلود PDF',
    'send_mail'             => 'ارسال ایمیل',
    'all_invoices'          => 'ورود برای دیدن تمام فاکتور ها',
    'create_invoice'        => 'ایجاد فاکتور',
    'send_invoice'          => 'ارسال فاکتور',
    'get_paid'              => 'دریافت حقوق',
    'accept_payments'       => 'پذیرش پرداخت های آنلاین',

    'messages' => [
        'email_required'    => 'هیچ آدرس ایمیل برای این مشتری موجود نیست!',
        'draft'             => 'این یک <b>پیشنویس</b> از فاکتور است و پس از ارسال بر روی نمودار اعمال می شود.',

        'status' => [
            'created'       => 'ایجاد شده در :date',
            'viewed'        => 'مشاهده شده',
            'send' => [
                'draft'     => 'ارسال نشده',
                'sent'      => 'ارسال شده در :date',
            ],
            'paid' => [
                'await'     => 'در انتظار پرداخت',
            ],
        ],
    ],

];
