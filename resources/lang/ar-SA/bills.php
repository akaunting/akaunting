<?php

return [

    'bill_number'           => 'رقم فاتورة الشراء',
    'bill_date'             => 'تاريخ فاتورة الشراء',
    'bill_amount'           => 'مبلغ فاتورة الشراء',
    'total_price'           => 'السعر الإجمالي',
    'due_date'              => 'تاريخ الاستحقاق',
    'order_number'          => 'رقم الطلب',
    'bill_from'             => 'فاتورة الشراء من',

    'quantity'              => 'الكمية',
    'price'                 => 'السعر',
    'sub_total'             => 'المجموع الجزئي',
    'discount'              => 'الخصم',
    'item_discount'         => 'خصم البند',
    'tax_total'             => 'إجمالي الضريبة',
    'total'                 => 'الإجمالي',

    'item_name'             => 'اسم الصنف|أسماء الأصناف',
    'recurring_bills'       => 'فاتورة شراء متكررة|فواتير شراء متكررة',

    'show_discount'         => 'خصم :discount%',
    'add_discount'          => 'إضافة خصم',
    'discount_desc'         => 'من المجموع الجزئي',

    'payment_made'          => 'تم الدفع',
    'payment_due'           => 'استحقاق الدفع',
    'amount_due'            => 'المبلغ المستحق',
    'paid'                  => 'مدفوع',
    'histories'             => 'السجلات',
    'payments'              => 'المدفوعات',
    'add_payment'           => 'إضافة دفعة',
    'mark_paid'             => 'تعيين كمدفوعة',
    'mark_received'         => 'تعيين كمستلمة',
    'mark_cancelled'        => 'تعيين كملغاة',
    'download_pdf'          => 'تنزيل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'create_bill'           => 'إنشاء فاتورة شراء',
    'receive_bill'          => 'استلام فاتورة شراء',
    'make_payment'          => 'إجراء دفعة',

    'form_description' => [
        'billing'           => 'تظهر تفاصيل الفوترة في فاتورة الشراء. ويُستخدم تاريخ فاتورة الشراء في لوحة المعلومات والتقارير. حدد التاريخ المتوقع للدفع بوصفه تاريخ الاستحقاق.',
    ],

    'messages' => [
        'draft'             => 'هذه فاتورة شراء <b>مسودة</b> وستظهر في الرسوم البيانية بعد استلامها.',

        'status' => [
            'created'       => 'تم الإنشاء في :date',
            'receive' => [
                'draft'     => 'لم تُستلم',
                'received'  => 'تم الاستلام في :date',
            ],
            'paid' => [
                'await'     => 'في انتظار الدفع',
            ],
        ],
    ],

];
