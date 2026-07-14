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
    'sub_total'             => 'المجموع الفرعي',
    'discount'              => 'خصم',
    'item_discount'         => 'خصم البند',
    'tax_total'             => 'إجمالي الضريبة',
    'total'                 => 'المجموع',

    'item_name'             => 'اسم الصنف|أسماء الأصناف',
    'recurring_bills'       => 'الفاتورة المتكررة|الفواتير المتكررة',

    'show_discount'         => 'خصم :discount%',
    'add_discount'          => 'إضافة خصم',
    'discount_desc'         => 'من المجموع الجزئي',

    'payment_made'          => 'الدفع',
    'payment_due'           => 'الدفع المستحق',
    'amount_due'            => 'المبلغ المستحق',
    'paid'                  => 'مدفوع',
    'histories'             => 'السجل',
    'payments'              => 'المدفوعات',
    'add_payment'           => 'إضافة دفعة',
    'mark_paid'             => 'تعيين كمدفوعة',
    'mark_received'         => 'تعيين كمستلمة',
    'mark_cancelled'        => 'تعيين كملغاة',
    'download_pdf'          => 'تحميل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'create_bill'           => 'إنشاء فاتورة شراء',
    'receive_bill'          => 'استلام فاتورة شراء',
    'make_payment'          => 'إجراء دفعة',

    'form_description' => [
        'billing'           => 'تظهر تفاصيل الفوترة في فاتورة الشراء. ويُستخدم تاريخ الفاتورة في لوحة المعلومات والتقارير. حدد التاريخ المتوقع للدفع بوصفه تاريخ الاستحقاق.',
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
