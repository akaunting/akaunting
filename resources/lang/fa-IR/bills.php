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
        'draft'          => 'این صورت حساب به صورت پیشنویس است و پس از دریافت وجه بر روی نمودار را اعمال می شود.',

        'status' => [
            'created'   => 'تاریخ ایجاد :date',
            'receive'      => [
                'draft'     => 'ارسال نشده',
                'received'  => 'تاریخ دریافت :date',
            ],
            'paid'      => [
                'await'     => 'انتظار پرداخت',
            ],
        ],
    ],

];
