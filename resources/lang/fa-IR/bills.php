<?php

return [

    'bill_number'       => 'شماره صورتحساب',
    'bill_date'         => 'تاریخ صورتحساب',
    'total_price'       => 'قیمت کل',
    'due_date'          => 'سررسید',
    'order_number'      => 'شماره سفارش',
    'bill_from'         => 'صورتحساب از',

    'quantity'          => 'تعداد',
    'price'             => 'قيمت',
    'sub_total'         => 'جمع کل',
    'discount'          => 'تخفیف',
    'tax_total'         => 'مجموع مالیات',
    'total'             => 'مجموع',

    'item_name'         => 'نام آیتم | نام آیتم ها',

    'show_discount'     => 'تخفیف: discount%',
    'add_discount'      => 'اضافه کردن تخفیف',
    'discount_desc'     => 'از جمع کل',

    'payment_due'       => 'سررسید پرداخت',
    'amount_due'        => 'مقدار سررسید',
    'paid'              => 'پرداخت شده',
    'histories'         => 'تاریخچه',
    'payments'          => 'پرداخت ها',
    'add_payment'       => 'پرداخت',
    'mark_received'     => 'دریافت شده',
    'download_pdf'      => 'دانلود PDF',
    'send_mail'         => 'ارسال ایمیل',

    'status' => [
        'draft'         => 'پیش‌ نویس',
        'received'      => 'دریافت شده',
        'partial'       => 'جزئیات',
        'paid'          => 'پرداخت شده',
    ],

    'messages' => [
        'received'      => 'صورتحساب مشخص شده با موفقیت علامت گذاری شد.',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Created on :date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
