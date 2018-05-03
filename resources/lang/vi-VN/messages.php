<?php

return [

    'success' => [
        'added'             => ':type đã được thêm!',
        'updated'           => ':type đã được cập nhật!',
        'deleted'           => ':type đã được xoá!',
        'duplicated'        => ':type bị trùng!',
        'imported'          => ':type imported!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Lỗi: Bạn không được phép để quản lý công ty này!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Error: No file selected!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Chú ý: Bạn không được phép xoá <b>:name</b> này bởi vì nó có :text liên quan.',
        'disabled'          => 'Chú ý: Bạn không được phép vô hiệu hoá <b>:name</b> này bởi vì nó có :text liên quan.',
    ],

];
