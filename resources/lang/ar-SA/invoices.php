<?php

return [

    'invoice_number'        => 'رقم فاتورة البيع',
    'invoice_date'          => 'تاريخ الفاتورة',
    'invoice_amount'        => 'مبلغ الفاتورة',
    'total_price'           => 'السعر الإجمالي',
    'due_date'              => 'تاريخ الاستحقاق',
    'order_number'          => 'رقم الطلب',
    'bill_to'               => 'إصدار الفاتورة إلى',
    'cancel_date'           => 'تاريخ الإلغاء',

    'quantity'              => 'الكمية',
    'price'                 => 'السعر',
    'sub_total'             => 'المجموع الجزئي',
    'discount'              => 'الخصم',
    'item_discount'         => 'خصم البند',
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
    'add_payment'           => 'إضافة دفعة',
    'mark_paid'             => 'تعيين كمدفوعة',
    'mark_sent'             => 'تعيين كمُرسلة',
    'mark_viewed'           => 'تعيين كمُشاهدة',
    'mark_cancelled'        => 'تعيين كملغاة',
    'download_pdf'          => 'تحميل PDF',
    'send_mail'             => 'إرسال بريد إلكتروني',
    'all_invoices'          => 'سجّل الدخول لعرض جميع الفواتير',
    'create_invoice'        => 'إنشاء فاتورة',
    'send_invoice'          => 'أرسل فاتورة',
    'get_paid'              => 'تحصيل المستحقات',
    'accept_payments'       => 'قبول المدفوعات اﻹلكترونية',
    'payments_received'     => 'الدفعات المستلمة',
    'over_payment'          => 'المبلغ الذي أدخلته يتجاوز الإجمالي: :amount',

    'form_description' => [
        'billing'           => 'تظهر تفاصيل الفوترة في الفاتورة. ويُستخدم تاريخ الفاتورة في لوحة المعلومات والتقارير. حدد التاريخ المتوقع لتحصيل المبلغ بوصفه تاريخ الاستحقاق.',
    ],

    'messages' => [
        'email_required'    => 'لا يوجد عنوان البريد إلكتروني لهذا العميل!',
        'totals_required'   => 'إجماليات الفاتورة مطلوبة. يرجى تحرير :type وحفظها مرة أخرى.',
        'draft'             => 'هذه فاتورة <b>مسودة</b> وستظهر في الرسوم البيانية بعد إرسالها.',

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

        'name_or_description_required' => 'يجب أن تعرض فاتورتك واحداً على الأقل من <b>:name</b> أو <b>:description</b>.',
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
