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
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'مجموع مالیات',
    'total'                 => 'مجموع',

    'item_name'             => 'نام آیتم | نام آیتم ها',

    'show_discount'         => ':تخفیف% تخفیف',
    'add_discount'          => 'افزودن تخفیف',
    'discount_desc'         => 'از جمع کل',

    'payment_due'           => 'سررسید پرداخت',
    'paid'                  => 'پرداخت شده',
    'histories'             => 'تاریخچه',
    'payments'              => 'پرداخت ها',
    'add_payment'           => 'پرداخت',
    'mark_paid'             => 'پرداخت شده',
    'mark_sent'             => 'ارسال شده',
    'mark_viewed'           => 'Mark Viewed',
    'mark_cancelled'        => 'Mark Cancelled',
    'download_pdf'          => 'دانلود PDF',
    'send_mail'             => 'ارسال ایمیل',
    'all_invoices'          => 'ورود برای دیدن تمام فاکتور ها',
    'create_invoice'        => 'ایجاد فاکتور',
    'send_invoice'          => 'ارسال فاکتور',
    'get_paid'              => 'پرداخت شده',
    'accept_payments'       => 'پذیرفتن پرداخت های آنلاین',

    'statuses' => [
        'draft'             => 'Draft',
        'sent'              => 'Sent',
        'viewed'            => 'Viewed',
        'approved'          => 'Approved',
        'partial'           => 'Partial',
        'paid'              => 'Paid',
        'overdue'           => 'Overdue',
        'unpaid'            => 'Unpaid',
        'cancelled'         => 'Cancelled',
    ],

    'messages' => [
        'email_sent'        => 'Invoice email has been sent!',
        'marked_sent'       => 'Invoice marked as sent!',
        'marked_paid'       => 'Invoice marked as paid!',
        'marked_viewed'     => 'Invoice marked as viewed!',
        'marked_cancelled'  => 'Invoice marked as cancelled!',
        'email_required'    => 'هیچ آدرس ایمیل برای این مشتری موجود نیست!',
        'draft'             => 'این یک پیشنویس است و پس از ارسال بر روی نمودار اعمال می شود.',

        'status' => [
            'created'       => 'ایجاد شده در تاریخ:',
            'viewed'        => 'Viewed',
            'send' => [
                'draft'     => 'ارسال نشده',
                'sent'      => 'ارسال شده در تاریخ:',
            ],
            'paid' => [
                'await'     => 'در انتظار پرداخت',
            ],
        ],
    ],

];
