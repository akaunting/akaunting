<?php

return [

    'bill_number'           => 'رقم فاتورة الشراء',
    'bill_date'             => 'تاريخ الفاتورة',
    'total_price'           => 'السعر الإجمالي',
    'due_date'              => 'تاريخ الاستحقاق',
    'order_number'          => 'رقم الطلب',
    'bill_from'             => 'فاتورة الشراء من',

    'quantity'              => 'الكمية',
    'price'                 => 'السعر',
    'sub_total'             => 'المبلغ الإجمالي',
    'discount'              => 'خصم',
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'إجمالي الضريبة',
    'total'                 => 'المجموع',

    'item_name'             => 'اسم الصنف|أسماء الأصناف',

    'show_discount'         => 'خصم :discount%',
    'add_discount'          => 'إضافة خصم',
    'discount_desc'         => 'من المجموع الجزئي',

    'payment_due'           => 'الدفع المستحق',
    'amount_due'            => 'المبلغ المستحق',
    'paid'                  => 'مدفوع',
    'histories'             => 'سجلات',
    'payments'              => 'المدفوعات',
    'add_payment'           => 'إضافة مدفوعات',
    'mark_paid'             => 'تم التحديد كمدفوع',
    'mark_received'         => 'تحديد كمستلم',
    'mark_cancelled'        => 'تم إلغاء العلامة',
    'download_pdf'          => 'تحميل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'create_bill'           => 'إنشاء فاتورة شراء',
    'receive_bill'          => 'إستلام فاتورة شراء',
    'make_payment'          => 'القيام بالدفع',

    'statuses' => [
        'draft'             => 'مسودة',
        'received'          => 'مستلمة',
        'partial'           => 'جزئي',
        'paid'              => 'مدفوع',
        'overdue'           => 'متأخر',
        'unpaid'            => 'غير مدفوع',
        'cancelled'         => 'Cancelled',
    ],

    'messages' => [
        'marked_received'   => 'Bill marked as received!',
        'marked_paid'       => 'الفاتورة عُلّمت كمدفوعة!',
        'marked_cancelled'  => 'Bill marked as cancelled!',
        'draft'             => 'هذة فاتورة شراء عبارة عن <b> مسودة </b> و سوف يتم اظهارها بالنظام بعد ان يتم استحقاقها.',

        'status' => [
            'created'       => 'إضافة في: تاريخ',
            'receive' => [
                'draft'     => 'لم يتم ارسالها',
                'received'  => 'وردت في: تاريخ',
            ],
            'paid' => [
                'await'     => 'في انتظار الدفع',
            ],
        ],
    ],

];
