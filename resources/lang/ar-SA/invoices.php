<?php

return [

    'invoice_number'        => 'رقم فاتورة البيع',
    'invoice_date'          => 'تاريخ فاتورة البيع',
    'invoice_amount'        => 'مبلغ الفاتورة',
    'total_price'           => 'السعر الإجمالي',
    'due_date'              => 'تاريخ الاستحقاق',
    'order_number'          => 'رقم الطلب',
    'bill_to'               => 'فاتورة الشراء إلى',
    'cancel_date'           => 'تاريخ الإلغاء',

    'quantity'              => 'الكمية',
    'price'                 => 'السعر',
    'sub_total'             => 'المجموع الجزئي',
    'discount'              => 'الخصم',
    'item_discount'         => 'خصم على هذه المنتجات',
    'tax_total'             => 'إجمالي الضريبة',
    'total'                 => 'الإجمالي',

    'item_name'             => 'اسم الصنف|أسماء الأصناف',
    'recurring_invoices'    => 'فاتورة متكررة|الفواتير المتكررة',

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
    'mark_cancelled'        => 'تعيين كملغي',
    'download_pdf'          => 'تحميل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'all_invoices'          => 'سجّل الدخول لعرض جميع الفواتير',
    'create_invoice'        => 'انشئ فاتورة',
    'send_invoice'          => 'أرسل فاتورة',
    'get_paid'              => 'إحصل عالمبلغ',
    'accept_payments'       => 'قبول المدفوعات اﻹلكترونية',
    'payments_received'     => 'الدفعات المستلمة',

    'form_description' => [
        'billing'           => 'تظهر تفاصيل الفواتير في الفاتورة. يتم استخدام تاريخ الفاتورة في لوحة التحكم والتقارير. حدد التاريخ الذي تتوقع الحصول عليه كتاريخ الاستحقاق.',
    ],

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

    'slider' => [
        'create'            => ':user أنشأ هذه الفاتورة في :date',
        'create_recurring'  => ':user أنشأ هذا القالب المتكرر في :date',
        'schedule'          => 'كرر كل :interval :frequency منذ :date',
        'children'          => 'تم إنشاء :count فواتير تلقائياً',
    ],

    'share' => [
        'show_link'         => 'يمكن لعميلك عرض الفاتورة على هذا الرابط',
        'copy_link'         => 'انسخ الرابط وشاركه مع عميلك.',
        'success_message'   => 'تم نسخ رابط المشاركة إلى الحافظة!',
    ],

    'sticky' => [
        'description'       => 'أنت تقوم بمعاينة كيف سيرى العميل نسخة الويب من الفاتورة الخاصة بك.',
    ],

];
