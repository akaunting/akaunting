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
        'extension'         => ':افزونه نیاز است افزونه نصب و بارگذاری شود!',
        'directory'         => ':directory باید فابل نوشتن باشد!',
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
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
        'connection'        => 'خطا: نمی تواند به پایگاه داده وصل شد! لطفا اطلاعات صحیح را وارد کنید.',
    ],

];
