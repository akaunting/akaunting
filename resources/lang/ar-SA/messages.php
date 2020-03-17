<?php

return [

    'success' => [
        'added'             => 'تم إضافة :type!',
        'updated'           => 'تم تحديث :type!',
        'deleted'           => 'تم حذف :type!',
        'duplicated'        => 'تم نسخ :type!',
        'imported'          => 'تم استيراد :type!',
        'exported'          => 'تم تصدير :type!',
        'enabled'           => 'تم تفعيل :type!',
        'disabled'          => 'تم تعطيل :type!',
    ],

    'error' => [
        'over_payment'      => 'خطأ: الدفعه لم تُضاف! المبلغ المدخل يتخطى المجموع: :amount',
        'not_user_company'  => 'خطأ: غير مسموح لك بإدارة هذه الشركة!',
        'customer'          => 'خطأ: لم تتم إضافة المستخدم! :name يستخدم بالفعل هذا البريد الإلكتروني.',
        'no_file'           => 'خطأ: لم يتم تحديد أي ملف!',
        'last_category'     => 'خطأ: لا يمكن حذف آخر فئة من :type!',
        'change_type'       => 'خطأ: لا يمكنك تغيير النوع لارتباطه مع :text!',
        'invalid_apikey'    => 'خطأ: مفتاح API الذي تم إدخاله غير صالح!',
        'import_column'     => 'خطأ: :message اسم الورقة: :sheet. رقم السطر: :line.',
        'import_sheet'      => 'خطأ: اسم الورقة غير صحيح. من فضلك، راجع ملف العينة.',
    ],

    'warning' => [
        'deleted'           => 'تنبيه: لا يمكنك حذف <b>:name</b> لأنه لديه :text مرتبط به.',
        'disabled'          => 'تنبيه: لا يمكنك تعطيل <b>:name</b> لأنه لديه :text مرتبط به.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'تنبيه: لا يمكنك تعطيل او تغير عملة <b>:name</b> بسبب ارتباطه ب :text.',
        'payment_cancel'    => 'تنبيه: قمت بالغاء اخر طريقة :method دفع!',
    ],

];
