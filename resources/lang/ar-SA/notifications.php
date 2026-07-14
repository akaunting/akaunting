<?php

return [

    'whoops'              => 'عذراً!',
    'hello'               => 'مرحباً!',
    'salutation'          => 'مع التحية،<br> :company_name',
    'subcopy'             => 'إذا كنت تواجه مشكلة في النقر على زر ":text"، فانسخ والصق عنوان URL أدناه في متصفح الويب الخاص بك: [:url](:url)',
    'mark_read'           => 'وضع علامة كمقروء',
    'mark_read_all'       => 'وضع علامة على الكل كمقروء',
    'empty'               => 'رائع، لا توجد إشعارات!',
    'new_apps'            => ':app متاح. <a href=":url">تحقق منه الآن</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ فشل التحديث على :domain',
            'description'   => 'فشل تحديث :alias من :current_version إلى :new_version في خطوة <strong>:step</strong> بالرسالة التالية: :error_message',

        ],

        'slack' => [

            'description'   => 'فشل التحديث على :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'التنزيل جاهز',
            'description'   => 'الملف جاهز للتنزيل من الرابط التالي:',

        ],

        'failed' => [

            'title'         => 'فشل التنزيل',
            'description'   => 'تعذر إنشاء الملف بسبب المشكلة التالية:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'اكتمل الاستيراد',
            'description'   => 'اكتمل الاستيراد والسجلات متاحة في لوحتك.',

        ],

        'failed' => [

            'title'         => 'فشل الاستيراد',
            'description'   => 'تعذر استيراد الملف بسبب المشكلات التالية:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'التصدير جاهز',
            'description'   => 'ملف التصدير جاهز للتنزيل من الرابط التالي:',

        ],

        'failed' => [

            'title'         => 'فشل التصدير',
            'description'   => 'تعذر إنشاء ملف التصدير بسبب المشكلة التالية:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'بريد إلكتروني :type غير صالح',
            'description'   => 'تم الإبلاغ عن عنوان البريد الإلكتروني :email كغير صالح، وتم تعطيل الشخص. يرجى التحقق من رسالة الخطأ التالية وإصلاح عنوان البريد الإلكتروني:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'التنزيل جاهز',
            'description'   => 'ملف <strong>:type</strong> الخاص بك جاهز <a href=":url" target="_blank"><strong>للتنزيل</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'فشل التنزيل',
            'description'   => 'تعذر إنشاء الملف بسبب عدة مشكلات. تحقق من بريدك الإلكتروني للاطلاع على التفاصيل.',

        ],

        'export_completed' => [

            'title'         => 'التصدير جاهز',
            'description'   => 'ملف تصدير <strong>:type</strong> الخاص بك جاهز <a href=":url" target="_blank"><strong>للتنزيل</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'فشل التصدير',
            'description'   => 'تعذر إنشاء ملف التصدير بسبب عدة مشكلات. تحقق من بريدك الإلكتروني للاطلاع على التفاصيل.',

        ],

        'import_completed' => [

            'title'         => 'اكتمل الاستيراد',
            'description'   => 'تم استيراد بيانات <strong>:type</strong> التي تحتوي على <strong>:count</strong> صفاً بنجاح.',

        ],

        'import_failed' => [

            'title'         => 'فشل الاستيراد',
            'description'   => 'تعذر استيراد الملف بسبب عدة مشكلات. تحقق من بريدك الإلكتروني للاطلاع على التفاصيل.',

        ],

        'new_apps' => [

            'title'         => 'تطبيق جديد',
            'description'   => 'تطبيق <strong>:name</strong> متاح الآن. يمكنك <a href=":url">النقر هنا</a> لعرض التفاصيل.',

        ],

        'invoice_new_customer' => [

            'title'         => 'فاتورة جديدة',
            'description'   => 'تم إنشاء فاتورة <strong>:invoice_number</strong>. يمكنك <a href=":invoice_portal_link">النقر هنا</a> لعرض التفاصيل والمتابعة في الدفع.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'فاتورة متأخرة',
            'description'   => 'كانت فاتورة <strong>:invoice_number</strong> مستحقة في <strong>:invoice_due_date</strong>. يمكنك <a href=":invoice_portal_link">النقر هنا</a> لعرض التفاصيل والمتابعة في الدفع.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'فاتورة متأخرة',
            'description'   => 'كانت فاتورة <strong>:invoice_number</strong> مستحقة في <strong>:invoice_due_date</strong>. يمكنك <a href=":invoice_admin_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'فاتورة متكررة جديدة',
            'description'   => 'تم إنشاء فاتورة <strong>:invoice_number</strong> بناءً على دورتك المتكررة. يمكنك <a href=":invoice_portal_link">النقر هنا</a> لعرض التفاصيل والمتابعة في الدفع.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'فاتورة متكررة جديدة',
            'description'   => 'تم إنشاء فاتورة <strong>:invoice_number</strong> بناءً على دورة <strong>:customer_name</strong> المتكررة. يمكنك <a href=":invoice_admin_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'invoice_view_admin' => [

            'title'         => 'تمت مشاهدة الفاتورة',
            'description'   => 'قام <strong>:customer_name</strong> بمشاهدة فاتورة <strong>:invoice_number</strong>. يمكنك <a href=":invoice_admin_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'revenue_new_customer' => [

            'title'         => 'تم استلام الدفع',
            'description'   => 'شكراً لك على دفع فاتورة <strong>:invoice_number</strong>. يمكنك <a href=":invoice_portal_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'تم استلام الدفع',
            'description'   => 'شكراً لك على دفع فاتورة <strong>:invoice_number</strong>. يمكنك <a href=":invoice_portal_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'تم استلام الدفع',
            'description'   => 'سجل :customer_name دفعة لفاتورة <strong>:invoice_number</strong>. يمكنك <a href=":invoice_admin_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'bill_remind_admin' => [

            'title'         => 'فاتورة شراء متأخرة',
            'description'   => 'كانت فاتورة شراء <strong>:bill_number</strong> مستحقة في <strong>:bill_due_date</strong>. يمكنك <a href=":bill_admin_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'bill_recur_admin' => [

            'title'         => 'فاتورة شراء متكررة جديدة',
            'description'   => 'تم إنشاء فاتورة شراء <strong>:bill_number</strong> بناءً على دورة <strong>:vendor_name</strong> المتكررة. يمكنك <a href=":bill_admin_link">النقر هنا</a> لعرض التفاصيل.',

        ],

        'invalid_email' => [

            'title'         => 'بريد إلكتروني :type غير صالح',
            'description'   => 'تم الإبلاغ عن عنوان البريد الإلكتروني <strong>:email</strong> كغير صالح، وتم تعطيل الشخص. يرجى التحقق وإصلاح عنوان البريد الإلكتروني.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type قرأ هذا الإشعار!',
        'mark_read_all'         => ':type قرأ كل الإشعارات!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'تكوين أيقونات Firefox',
            'description'  => '<span class="font-medium">إذا لم تظهر أيقوناتك يرجى؛</span> <br /> <span class="font-medium">السماح للصفحات باختيار خطوطها الخاصة، بدلاً من اختياراتك أعلاه</span> <br /><br /> <span class="font-bold"> الإعدادات (تفضيلات) > الخطوط > متقدم </span>',

        ],

    ],

];
