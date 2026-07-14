<?php

return [

    'edit_columns'              => 'تحرير الأعمدة',
    'empty_items'               => 'لم تقم بإضافة أي عناصر.',
    'grand_total'               => 'الإجمالي الكلي',
    'accept_payment_online'     => 'قبول المدفوعات عبر الإنترنت',
    'transaction'               => 'تم دفع مبلغ قدره :amount باستخدام :account.',
    'portal_transaction'        => 'تم دفع مبلغ قدره :amount باستخدام :payment_method.',
    'billing'                   => 'الفوترة',
    'advanced'                  => 'متقدم',

    'item_price_hidden'         => 'هذا العمود مخفي في :type الخاص بك.',

    'actions' => [
        'cancel'                => 'إلغاء',
    ],

    'invoice_detail' => [
        'marked'                => 'عيّنت <b>أنت</b> حالة هذه الفاتورة إلى',
        'services'              => 'الخدمات',
        'another_item'          => 'عنصر آخر',
        'another_description'   => 'ووصف آخر',
        'more_item'             => '+:count عنصر آخر',
    ],

    'statuses' => [
        'draft'                 => 'مسودة',
        'sent'                  => 'مُرسلة',
        'expired'               => 'منتهية',
        'viewed'                => 'مُشاهدة',
        'approved'              => 'مُعتمدة',
        'received'              => 'مُستلمة',
        'refused'               => 'مرفوضة',
        'restored'              => 'مُستعادة',
        'reversed'              => 'معكوسة',
        'partial'               => 'جزئية',
        'paid'                  => 'مدفوعة',
        'pending'               => 'قيد الانتظار',
        'invoiced'              => 'مُفوترة',
        'overdue'               => 'متأخرة',
        'unpaid'                => 'غير مدفوعة',
        'cancelled'             => 'ملغاة',
        'voided'                => 'باطلة',
        'completed'             => 'مكتملة',
        'shipped'               => 'مُشحونة',
        'refunded'              => 'مُستردة',
        'failed'                => 'فاشلة',
        'denied'                => 'مرفوضة',
        'processed'             => 'مُعالجة',
        'open'                  => 'مفتوحة',
        'closed'                => 'مغلقة',
        'billed'                => 'مُفوترة',
        'delivered'             => 'مُسلمة',
        'returned'              => 'مُرتجعة',
        'drawn'                 => 'مُسحوبة',
        'not_billed'            => 'غير مُفوترة',
        'issued'                => 'مُصدرة',
        'not_invoiced'          => 'غير مُفوترة',
        'confirmed'             => 'مؤكدة',
        'not_confirmed'         => 'غير مؤكدة',
        'active'                => 'نشطة',
        'ended'                 => 'منتهية',
    ],

    'form_description' => [
        'companies'             => 'قم بتغيير العنوان والشعار والمعلومات الأخرى لشركتك.',
        'billing'               => 'تظهر تفاصيل الفوترة في المستند.',
        'advanced'              => 'حدد الفئة، وأضف أو حرر التذييل، وأضف مرفقات إلى :type الخاص بك.',
        'attachment'            => 'نزّل الملفات المرفقة بهذا :type',
    ],

    'slider' => [
        'create'            => 'قام :user بإنشاء هذا :type في :date',
        'create_recurring'  => 'قام :user بإنشاء هذا القالب المتكرر في :date',
        'send'              => 'قام :user بإرسال هذا :type في :date',
        'schedule'          => 'التكرار كل :interval :frequency منذ :date',
        'children'          => 'تم إنشاء :count من :type تلقائياً',
        'cancel'            => 'قام :user بإلغاء هذا :type في :date',
    ],

    'messages' => [
        'email_sent'            => 'تم إرسال بريد :type الإلكتروني!',
        'restored'              => 'تمت استعادة :type!',
        'marked_as'             => 'تم تغيير حالة :type إلى :status!',
        'marked_sent'           => 'تم تعيين :type كمُرسلة!',
        'marked_paid'           => 'تم تعيين :type كمدفوعة!',
        'marked_viewed'         => 'تم تعيين :type كمُشاهدة!',
        'marked_cancelled'      => 'تم تعيين :type كملغاة!',
        'marked_received'       => 'تم تعيين :type كمُستلمة!',
    ],

    'recurring' => [
        'auto_generated'        => 'مُنشأ تلقائياً',

        'tooltip' => [
            'document_date'     => 'سيتم تعيين تاريخ :type تلقائياً بناءً على جدول :type والتكرار.',
            'document_number'   => 'سيتم تعيين رقم :type تلقائياً عند إنشاء كل :type متكرر.',
        ],
    ],

    'empty_attachments'         => 'لا توجد ملفات مرفقة بهذا :type.',
];
