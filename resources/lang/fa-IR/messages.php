<?php

return [

    'success' => [
        'added'             => ':type اضافه شد!',
        'updated'           => ':type به‌روز شد!',
        'deleted'           => ':type حذف شد!',
        'duplicated'        => ':type دو عدد موجود است!',
        'imported'          => ':type درون ریزی شد!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'خطا: شما اجازه مدیریت این شرکت را ندارید!',
        'customer'          => 'خطا: کاربر ایجاد نشد :name از ایمیل وارد شده استفاده می کند.',
        'no_file'           => 'خطا: فایلی انتخاب نشده است!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],

    'warning' => [
        'deleted'           => 'هشدار: شما نمی توانید <b>:name</b> را به دلیل :text حذف کنید.',
        'disabled'          => 'هشدار: شما نمی توانید <b>:name</b> را به دلیل :text غیر فعال کنید.',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
    ],

];
