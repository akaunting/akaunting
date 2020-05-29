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
    'item_discount'         => 'Line Discount',
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
    'mark_paid'             => 'Mark Paid',
    'mark_received'         => 'دریافت شده',
    'mark_cancelled'        => 'Mark Cancelled',
    'download_pdf'          => 'دانلود PDF',
    'send_mail'             => 'ارسال ایمیل',
    'create_bill'           => 'ایجاد صورتحساب',
    'receive_bill'          => 'دریافت صورتحساب',
    'make_payment'          => 'پرداخت کردن',

    'statuses' => [
        'draft'             => 'پیش‌نویس',
        'received'          => 'دریافت شده',
        'partial'           => 'جزئی',
        'paid'              => 'پرداخت شده',
        'overdue'           => 'سر رسید شده',
        'unpaid'            => 'پرداخت نشده',
        'cancelled'         => 'Cancelled',
    ],

    'messages' => [
        'marked_received'   => 'Bill marked as received!',
        'marked_paid'       => 'Bill marked as paid!',
        'marked_cancelled'  => 'Bill marked as cancelled!',
        'draft'             => 'این صورت حساب به صورت پیشنویس است و پس از دریافت وجه بر روی نمودار را اعمال می شود.',

        'status' => [
            'created'       => 'تاریخ ایجاد :date',
            'receive' => [
                'draft'     => 'ارسال نشده',
                'received'  => 'تاریخ دریافت :date',
            ],
            'paid' => [
                'await'     => 'انتظار پرداخت',
            ],
        ],
    ],

];
