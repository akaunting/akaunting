<?php

return [

    'invoice_number'        => 'رقم فاتورة البيع',
    'invoice_date'          => 'تاريخ فاتورة البيع',
    'total_price'           => 'السعر الإجمالي',
    'due_date'              => 'تاريخ الاستحقاق',
    'order_number'          => 'رقم الطلب',
    'bill_to'               => 'فاتورة الشراء إلى',

    'quantity'              => 'الكمية',
    'price'                 => 'السعر',
    'sub_total'             => 'المجموع الجزئي',
    'discount'              => 'الخصم',
    'item_discount'         => 'خصم على هذه المنتجات',
    'tax_total'             => 'إجمالي الضريبة',
    'total'                 => 'الإجمالي',

    'item_name'             => 'اسم الصنف|أسماء الأصناف',

    'show_discount'         => 'خصم :discount%',
    'add_discount'          => 'إضافة خصم',
    'discount_desc'         => 'من المجموع الجزئي',

    'payment_due'           => 'استحقاق الدفع',
    'paid'                  => 'مدفوع',
    'histories'             => 'سجلات',
    'payments'              => 'المدفوعات',
    'add_payment'           => 'إضافة الدفع',
    'mark_paid'             => 'التحديد كمدفوع',
    'mark_sent'             => 'التحديد كمرسل',
    'mark_viewed'           => 'وضع علامة مشاهدة',
    'mark_cancelled'        => 'العلامه ألغيت',
    'download_pdf'          => 'تحميل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'all_invoices'          => 'سجّل الدخول لعرض جميع الفواتير',
    'create_invoice'        => 'انشئ فاتورة',
    'send_invoice'          => 'أرسل فاتورة',
    'get_paid'              => 'إحصل عالمبلغ',
    'accept_payments'       => 'قبول المدفوعات اﻹلكترونية',

    'messages' => [
        'email_required'    => 'لا يوجد عنوان البريد إلكتروني لهذا العميل!',
        'draft'             => 'هذه <b>مسودة</b> الفاتورة و سوف تظهر في النظام بعد ارسالها.',

        'status' => [
            'created'       => 'أنشئت في :date',
            'viewed'        => 'شُوهدت',
            'send' => [
                'draft'     => 'لم يتم اﻹرسال',
                'sent'      => 'أُرسلت في :date',
            ],
            'paid' => [
                'await'     => 'في انتظار الدفع',
            ],
        ],
    ],

];
