<?php

return [

    'bill_number'           => 'Bill Number',
    'bill_date'             => 'Bill Date',
    'total_price'           => 'Total Price',
    'due_date'              => 'Due Date',
    'order_number'          => 'Order Number',
    'bill_from'             => 'Bill From',

    'quantity'              => 'Quantity',
    'price'                 => 'Price',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Discount',
    'tax_total'             => 'Tax Total',
    'total'                 => 'Total',

    'item_name'             => 'Item Name|Item Names',

    'show_discount'         => ':discount% Discount',
    'add_discount'          => 'Add Discount',
    'discount_desc'         => 'of subtotal',

    'payment_due'           => 'Payment Due',
    'amount_due'            => 'Amount Due',
    'paid'                  => 'Paid',
    'histories'             => 'Histories',
    'payments'              => 'Payments',
    'add_payment'           => 'Add Payment',
    'mark_received'         => 'Mark Received',
    'download_pdf'          => 'Download PDF',
    'send_mail'             => 'Send Email',
    'create_bill'           => 'Create Bill',
    'receive_bill'          => 'Receive Bill',
    'make_payment'          => 'Make Payment',

    'status' => [
        'draft'             => 'Draft',
        'received'          => 'Received',
        'partial'           => 'Partial',
        'paid'              => 'Paid',
    ],

    'messages' => [
        'received'          => 'Bill marked as received successfully!',
        'draft'             => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'       => 'Created on :date',
            'receive' => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
