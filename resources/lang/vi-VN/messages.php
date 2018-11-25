<?php

return [

    'success' => [
        'added'             => ':type đã được thêm!',
        'updated'           => ':type đã được cập nhật!',
        'deleted'           => ':type đã được xoá!',
        'duplicated'        => ':type bị trùng!',
        'imported'          => ':type đã được nhập!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Lỗi: Bạn không được phép để quản lý công ty này!',
        'customer'          => 'Lỗi: Người dùng chưa được tạo! Đã có người dùng :name sử dụng địa chỉ email này.',
        'no_file'           => 'Lỗi: Không có tập tin nào được chọn!',
        'last_category'     => 'Lỗi: Không thể xóa mục :type cuối!',
        'invalid_token'     => 'Lỗi: Chữ ký số nhập vào không hợp lệ!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],
    'warning' => [
        'deleted'           => 'Chú ý: Bạn không được phép xoá <b>:name</b> này bởi vì nó có :text liên quan.',
        'disabled'          => 'Chú ý: Bạn không được phép vô hiệu hoá <b>:name</b> này bởi vì nó có :text liên quan.',
    ],

];
