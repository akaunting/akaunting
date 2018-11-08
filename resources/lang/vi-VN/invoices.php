<?php

return [

    'invoice_number'    => 'Số hoá đơn',
    'invoice_date'      => 'Ngày hóa đơn',
    'total_price'       => 'Tổng giá',
    'due_date'          => 'Ngày hết hạn',
    'order_number'      => 'Số đơn hàng',
    'bill_to'           => 'Hoá đơn tới',

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
    'paid'              => 'Đã thanh toán',
    'histories'         => 'Lịch sử thanh toán',
    'payments'          => 'Thanh toán',
    'add_payment'       => 'Thêm thanh toán',
    'mark_paid'         => 'Đánh dấu đã trả tiền',
    'mark_sent'         => 'Đánh dấu đã gửi',
    'download_pdf'      => 'Tải PDF',
    'send_mail'         => 'Gửi Email',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Bản nháp',
        'sent'          => 'Đã gửi',
        'viewed'        => 'Đã xem',
        'approved'      => 'Đã duyệt',
        'partial'       => 'Một phần',
        'paid'          => 'Đã thanh toán',
    ],

    'messages' => [
        'email_sent'     => 'Hoá đơn email đã được gửi thành công!',
        'marked_sent'    => 'Hóa đơn được đánh dấu là đã gửi thành công!',
        'email_required' => 'No email address for this customer!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Bạn nhận được email này bởi vì bạn sắp có :amount hóa đơn cần thanh toán cho khách hàng :customer.',
        'button'        => 'Trả ngay',
    ],

];
