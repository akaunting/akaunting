<?php

return [

    'bill_number'       => 'رقم فاتورة الشراء',
    'bill_date'         => 'تاريخ فاتورة الشراء',
    'total_price'       => 'السعر الإجمالي',
    'due_date'          => 'تاريخ التسليم',
    'order_number'      => 'رقم الطلب',
    'bill_from'         => 'فاتورة الشراء من',

    'quantity'          => 'الكمية',
    'price'             => 'السعر',
    'sub_total'         => 'المبلغ الإجمالي',
    'discount'          => 'خصم',
    'tax_total'         => 'إجمالي الضريبة',
    'total'             => 'المجموع',

    'item_name'         => 'اسم الصنف|أسماء الأصناف',

    'show_discount'     => 'خصم :discount%',
    'add_discount'      => 'إضافة خصم',
    'discount_desc'     => 'من المجموع الجزئي',

    'payment_due'       => 'استحقاق الدفع',
    'amount_due'        => 'استحقاق المبلغ',
    'paid'              => 'مدفوع',
    'histories'         => 'سجلات',
    'payments'          => 'المدفوعات',
    'add_payment'       => 'إضافة مدفوعات',
    'mark_received'     => 'تحديد كمستلم',
    'download_pdf'      => 'تحميل PDF',
    'send_mail'         => 'إرسال بريد إلكتروني',

    'status' => [
        'draft'         => 'مسودة',
        'received'      => 'مستلم',
        'partial'       => 'جزئي',
        'paid'          => 'مدفوع',
    ],

    'messages' => [
        'received'      => 'تم تحويل فاتورة الشراء إلى فاتورة مستلمة بنجاح!',
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
