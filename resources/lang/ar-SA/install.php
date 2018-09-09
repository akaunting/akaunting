<?php

return [

    'next'                  => 'التالي',
    'refresh'               => 'تحديث',

    'steps' => [
        'requirements'      => 'من فضلك، تواصل مع مزود خدمة الاستضافة لديك لإصلاح الأخطاء!',
        'language'          => 'الخطوة 1/3: اختيار اللغة',
        'database'          => 'الخطوة 2/3: إعداد قاعدة البيانات',
        'settings'          => 'الخطوة 3/3: معلومات الشركة ومسؤول النظام',
    ],

    'language' => [
        'select'            => 'اختر اللغة',
    ],

    'requirements' => [
        'enabled'           => 'يجب تفعيل :feature!',
        'disabled'          => 'يجب تعطيل :feature!',
        'extension'         => 'يجب تثبيت وتشغيل ملحق :extension!',
        'directory'         => 'يجب منح صلاحية الكتابة على مجلد :directory!',
    ],

    'database' => [
        'hostname'          => 'اسم المستضيف',
        'username'          => 'اسم المستخدم',
        'password'          => 'كلمة المرور',
        'name'              => 'قاعدة البيانات',
    ],

    'settings' => [
        'company_name'      => 'اسم الشركة',
        'company_email'     => 'البريد الإلكتروني للشركة',
        'admin_email'       => 'البريد الإلكتروني لمسؤول النظام',
        'admin_password'    => 'كلمة مرور مسؤول النظام',
    ],

    'error' => [
        'connection'        => 'خطأ: لا يمكن الاتصال بقاعدة البيانات! من فضلك، تأكد من صحة المعلومات.',
    ],

];
