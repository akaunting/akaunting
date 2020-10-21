<?php

return [

    'bill_number'           => 'شماره صورتحساب',
    'bill_date'             => 'تاریخ صورتحساب',
    'total_price'           => 'قیمت کل',
    'due_date'              => 'سررسید',
    'order_number'          => 'شماره سفارش',
    'bill_from'             => 'صورتحساب از',

    'quantity'              => 'تعداد',
    'price'                 => 'قيمت',
    'sub_total'             => 'جمع کل',
    'discount'              => 'تخفیف',
    'item_discount'         => 'تخفیف جزء',
    'tax_total'             => 'مجموع مالیات',
    'total'                 => 'مجموع',

    'item_name'             => 'نام آیتم | نام آیتم ها',

    'show_discount'         => 'تخفیف: discount%',
    'add_discount'          => 'اضافه کردن تخفیف',
    'discount_desc'         => 'از جمع کل',

    'payment_due'           => 'سررسید پرداخت',
    'amount_due'            => 'مقدار سررسید',
    'paid'                  => 'پرداخت شده',
    'histories'             => 'تاریخچه',
    'payments'              => 'پرداخت ها',
    'add_payment'           => 'پرداخت',
    'mark_paid'             => 'تغییر وضعیت به پرداخت شده',
    'mark_received'         => 'دریافت شده',
    'mark_cancelled'        => 'تغییر وضعیت به لغو شده',
    'download_pdf'          => 'دانلود PDF',
    'send_mail'             => 'ارسال ایمیل',
    'create_bill'           => 'ایجاد صورتحساب',
    'receive_bill'          => 'دریافت صورتحساب',
    'make_payment'          => 'تغییر وضعیت به پرداخت شده',

    'statuses' => [
        'draft'             => 'پیش‌نویس',
        'received'          => 'دریافت شده',
        'partial'           => 'جزئی',
        'paid'              => 'پرداخت شده',
        'overdue'           => 'سر رسید شده',
        'unpaid'            => 'پرداخت نشده',
        'cancelled'         => 'لغو شده',
    ],

    'messages' => [
        'marked_received'   => 'وضعیت صورتحساب به دریافت شده تغییر کرد!',
        'marked_paid'       => 'وضعیت صورتحساب به پرداخت شده تغییر کرد!',
        'marked_cancelled'  => 'وضعیت صورتحساب به لغو شده تغییر کرد!',
        'draft'             => 'این پیشنویس <b> صورت حساب  <b/> است و پس از دریافت وجه بر روی نمودار را اعمال می شود.',

        'status' => [
            'created'       => 'تاریخ ایجاد :date',
            'receive' => [
                'draft'     => 'ارسال نشده',
                'received'  => 'تاریخ دریافت :date',
            ],
            'paid' => [
                'await'     => 'در انتظار پرداخت',
            ],
        ],
    ],

];
