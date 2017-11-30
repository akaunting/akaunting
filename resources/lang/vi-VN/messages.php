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
        'not_user_company'  => 'Lỗi: Bạn không được phép để quản lý công ty này!',
        'customer'          => 'Lỗi: Bạn có thể không tạo người dùng! :name đã sử dụng địa chỉ email này.',
        'no_file'           => 'Error: No file selected!',
    ],
    'warning' => [
        'deleted'           => 'Chú ý: Bạn không được phép xoá <b>:name</b> này bởi vì nó có :text liên quan.',
        'disabled'          => 'Chú ý: Bạn không được phép vô hiệu hoá <b>:name</b> này bởi vì nó có :text liên quan.',
    ],

];
