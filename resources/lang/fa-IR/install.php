<?php

return [

    'next'                  => 'بعدی',
    'refresh'               => 'تازه سازی',

    'steps' => [
        'requirements'      => 'لطفاً از ارائه دهنده سرویس میزبانی خود بخواهید که ایراد را بر طرف کند!',
        'language'          => 'گام 1/3: انتخاب زبان',
        'database'          => 'مرحله 2/3: راه اندازی پایگاه داده',
        'settings'          => 'مرحله 3/3: شرکت و مدیریت اطلاعات',
    ],

    'language' => [
        'select'            => 'انتخاب زبان',
    ],

    'requirements' => [
        'enabled'           => ':feature باید فعال باشد!',
        'disabled'          => ':feature باید غیر فعال باشد!',
        'extension'         => ':extension نیاز است افزونه نصب و بارگذاری شود!',
        'directory'         => ':directory باید فابل نوشتن باشد!',
        'executable'        => 'فایل اجرایی PHP CLI تعریف نشده یا کار نمی کند یا نسخه آن :php_version یا بالاتر نیست! لطفاً از هاست خود بخواهید متغیر محیطی PHP_BINARY یا PHP_PATH را به درستی تنظیم کند.',
    ],

    'database' => [
        'hostname'          => 'نام هاست',
        'username'          => 'نام کاربری',
        'password'          => 'رمز عبور',
        'name'              => 'پایگاه داده',
    ],

    'settings' => [
        'company_name'      => 'نام شرکت',
        'company_email'     => 'ایمیل شرکت',
        'admin_email'       => 'ایمیل مدیر',
        'admin_password'    => 'کلمه عبور مدیر',
    ],

    'error' => [
        'php_version'       => 'خطا: از هاست خود بخواهید که از PHP ورژن :php_version یا بالاتر برای HTTP و CLI استفاده کند.',
        'connection'        => 'خطا: نمی تواند به پایگاه داده وصل شد! لطفا اطلاعات صحیح را وارد کنید.',
    ],

];
