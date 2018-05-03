<?php

return [

    'success' => [
        'added'             => ':type اضافه شد!',
        'updated'           => ':type به‌روز شد!',
        'deleted'           => ':type حذف شد!',
        'duplicated'        => ':type دو عدد موجود است!',
        'imported'          => ':type درون ریزی شد!',
    ],
    'error' => [
        'over_payment'      => 'خطا: پرداخت اضافه نشده! مبلغ وارد شده از جمع کل بیشتر است.',
        'not_user_company'  => 'خطا: شما اجازه مدیریت این شرکت را ندارید!',
        'customer'          => 'خطا: کاربر ایجاد نشد :name از ایمیل وارد شده استفاده می کند.',
        'no_file'           => 'خطا: فایلی انتخاب نشده است!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'هشدار: شما نمی توانید <b>:name</b> را به دلیل :text حذف کنید.',
        'disabled'          => 'هشدار: شما نمی توانید <b>:name</b> را به دلیل :text غیر فعال کنید.',
    ],

];
