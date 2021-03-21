<?php

return [

    'bill_number'       => 'Số hoá đơn',
    'bill_date'         => 'Ngày trên hoá đơn',
    'total_price'       => 'Tổng giá',
    'due_date'          => 'Ngày hết hạn',
    'order_number'      => 'Số đơn hàng',
    'bill_from'         => 'Hoá đơn từ',

    'quantity'          => 'Số lượng',
    'price'             => 'Đơn giá',
    'sub_total'         => 'Tổng phụ',
    'discount'          => 'Discount',
    'tax_total'         => 'Tổng thuế',
    'total'             => 'Tổng số',

    'item_name'         => 'Tên mục | Tên mục',

    'show_discount'     => ':discount% Discount',
    'add_discount'      => 'Add Discount',
    'discount_desc'     => 'of subtotal',

    'payment_due'       => 'Hạn thanh toán',
    'amount_due'        => 'Số tiền phải trả',
    'paid'              => 'Đã thanh toán',
    'histories'         => 'Lịch sử thanh toán',
    'payments'          => 'Thanh toán',
    'add_payment'       => 'Thêm thanh toán',
    'mark_received'     => 'Đã nhận được',
    'download_pdf'      => 'Tải PDF',
    'send_mail'         => 'Gửi email',

    'statuses' => [
        'draft'         => 'Bản nháp',
        'received'      => 'Đã nhận',
        'partial'       => 'Một phần',
        'paid'          => 'Đã thanh toán',
    ],

    'messages' => [
        'received'      => 'Hoá đợn được đánh dấu là đã nhận thanh toán!',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Created on :date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
