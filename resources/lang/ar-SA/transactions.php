<?php

return [

    'payment_received'      => 'تم استلام الدفع',
    'payment_made'          => 'تم الدفع',
    'paid_by'               => 'مدفوع بواسطة',
    'paid_to'               => 'مدفوع إلى',
    'related_invoice'       => 'الفاتورة ذات الصلة',
    'related_bill'          => 'فاتورة الشراء ذات الصلة',
    'recurring_income'      => 'دخل متكرر',
    'recurring_expense'     => 'مصروف متكرر',
    'included_tax'          => 'مبلغ الضريبة المضمن',
    'connected'             => 'مرتبط',
    'connect_message'       => 'لم تُحتسب ضرائب :type أثناء عملية الربط، لذلك لا يمكن ربط الضرائب.',

    'form_description' => [
        'general'           => 'يمكنك هنا إدخال المعلومات العامة للمعاملة، مثل التاريخ والمبلغ والحساب والوصف وغيرها.',
        'assign_income'     => 'حدد فئة وعميل لجعل تقاريرك أكثر تفصيلاً.',
        'assign_expense'    => 'حدد فئة ومورداً لجعل تقاريرك أكثر تفصيلاً.',
        'other'             => 'أدخل رقماً ومرجعاً لإبقاء المعاملة مرتبطة بسجلاتك.',
    ],

    'slider' => [
        'create'            => 'أنشأ :user هذه المعاملة في :date',
        'attachments'       => 'نزّل الملفات المرفقة بهذه المعاملة',
        'create_recurring'  => 'قام :user بإنشاء هذا القالب المتكرر في :date',
        'schedule'          => 'التكرار كل :interval :frequency منذ :date',
        'children'          => 'تم إنشاء :count معاملة تلقائياً',
        'connect'           => 'هذه المعاملة مرتبطة بـ :count معاملة',
        'transfer_headline' => '<div> <span class="font-bold"> من: </span> :from_account </div> <div> <span class="font-bold"> إلى: </span> :to_account </div>',
        'transfer_desc'     => 'تم إنشاء التحويل في :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'يمكن لعميلك عرض المعاملة على هذا الرابط',
            'copy_link'     => 'انسخ الرابط وشاركه مع عميلك.',
        ],

        'expense' => [
            'show_link'     => 'يمكن لمورّدك عرض المعاملة على هذا الرابط',
            'copy_link'     => 'انسخ الرابط وشاركه مع مورّدك.',
        ],
    ],

    'sticky' => [
        'description'       => 'أنت تشاهد معاينة لكيفية رؤية عميلك للنسخة الإلكترونية من دفعتك.',
    ],

    'messages' => [
        'update_document_transaction' => 'يمكنك تحديث هذه المعاملة من خلال الانتقال إلى المستند وتحريرها هناك.',
        'create_document_transaction_error' => 'لا يمكن إضافة نقطة النهاية هذه إلى مستند. استخدم {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'لا يمكن تحديث نقطة النهاية هذه لمستند. استخدم {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'لا يمكن حذف نقطة النهاية هذه من مستند. استخدم {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
