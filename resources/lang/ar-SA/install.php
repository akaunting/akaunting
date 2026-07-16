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
        'executable'        => 'ملف PHP CLI التنفيذي غير معرّف أو لا يعمل، أو أن إصداره ليس :php_version أو أحدث! اطلب من شركة الاستضافة ضبط متغير البيئة PHP_BINARY أو PHP_PATH بصورة صحيحة.',
        'npm'               => '<b>ملفات JavaScript مفقودة!</b> <br><br><span>يجب عليك تشغيل أوامر <em class="underline">npm install</em> و <em class="underline">npm run dev</em>.</span>',
    ],

    'database' => [
        'hostname'          => 'اسم المضيف',
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
        'php_version'       => 'خطأ: اطلب من مزود الاستضافة استخدام PHP :php_version أو إصدار أحدث لكل من HTTP وCLI.',
        'connection'        => 'خطأ: لا يمكن الاتصال بقاعدة البيانات! من فضلك، تأكد من صحة المعلومات.',
    ],

    'update' => [
        'core'              => 'إصدار جديد من Akaunting متاح! يرجى تحديث <a href=":url">تثبيتك.</a>',
        'module'            => 'إصدار جديد من :module متاح! يرجى تحديث <a href=":url">تثبيتك.</a>',
    ],
];
