<?php

return [

    'success' => [
        'added'             => 'تم إضافة :type!',
        'updated'           => 'تم تحديث :type!',
        'deleted'           => 'تم حذف :type!',
        'duplicated'        => 'تم نسخ :type!',
        'imported'          => 'تم استيراد :type!',
        'enabled'           => 'تم تفعيل :type!',
        'disabled'          => 'تم تعطيل :type!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'خطأ: غير مسموح لك بإدارة هذه الشركة!',
        'customer'          => 'خطأ: لم تتم إضافة المستخدم! :name يستخدم هذا البريد الإلكتروني مسبقاً.',
        'no_file'           => 'خطأ: لم يتم تحديد أي ملف!',
        'last_category'     => 'خطأ: لا يمكن حذف آخر فئة من :type!',
        'invalid_token'     => 'خطأ: رمز الوصول المدخل غير صحيح!',
        'import_column'     => 'خطأ: :message اسم الورقة: :sheet. رقم السطر: :line.',
        'import_sheet'      => 'خطأ: اسم الورقة غير صحيح. من فضلك، راجع ملف العينة.',
    ],

    'warning' => [
        'deleted'           => 'تنبيه: لا يمكنك حذف <b>:name</b> لأنه لديه :text مرتبط به.',
        'disabled'          => 'تنبيه: لا يمكنك تعطيل <b>:name</b> لأنه لديه :text مرتبط به.',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
    ],

];
