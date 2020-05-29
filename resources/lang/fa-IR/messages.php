<?php

return [

    'success' => [
        'added'             => ':type اضافه شد!',
        'updated'           => ':type به‌روز شد!',
        'deleted'           => ':type حذف شد!',
        'duplicated'        => ':type دو عدد موجود است!',
        'imported'          => ':type درون ریزی شد!',
        'exported'          => ':type exported!',
        'enabled'           => ':نوع فعال است!',
        'disabled'          => ':نوع غیر فعال است!',
    ],

    'error' => [
        'over_payment'      => 'خطا: پرداخت اضافه نشد! مبلغی که وارد کردید از کل گذر کرد :مبلغ',
        'not_user_company'  => 'خطا: شما اجازه مدیریت این شرکت را ندارید!',
        'customer'          => 'خطا: کاربر ایجاد نشد :name از ایمیل وارد شده استفاده می کند.',
        'no_file'           => 'خطا: فایلی انتخاب نشده است!',
        'last_category'     => 'خطا: نمیتوان :نوع دسته بندی قبل را پاک کرد!',
        'change_type'       => 'Error: Can not change the type because it has :text related!',
        'invalid_apikey'    => 'Error: The API Key entered is invalid!',
        'import_column'     => 'خطا: :پیام :نام ورق :ورق. شماره خط :خط.',
        'import_sheet'      => 'خطا: نام ورق معتبر نیست. لطفاً، فایل نمونه را بررسی کنید.',
    ],

    'warning' => [
        'deleted'           => 'هشدار: شما نمی توانید <b>:name</b> را به دلیل :text حذف کنید.',
        'disabled'          => 'هشدار: شما نمی توانید <b>:name</b> را به دلیل :text غیر فعال کنید.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'هشدار: شما مجاز نیستید که واحد پولی <b>:name</b> را تغییر دهید یا غیر فعال کنید زیرا آن با :متن در ارتباط است.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
