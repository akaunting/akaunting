<?php

return [

    'invoice_number'        => 'Invoice Number',
    'invoice_date'          => 'Invoice Date',
    'total_price'           => 'Total Price',
    'due_date'              => 'Due Date',
    'order_number'          => 'Order Number',
    'bill_to'               => 'Bill To',

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
    'paid'                  => 'Paid',
    'histories'             => 'Histories',
    'payments'              => 'Payments',
    'add_payment'           => 'Add Payment',
    'mark_paid'             => 'Mark Paid',
    'mark_sent'             => 'Mark Sent',
    'download_pdf'          => 'Download PDF',
    'send_mail'             => 'Send Email',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',

    'status' => [
        'draft'             => 'Draft',
        'sent'              => 'Sent',
        'viewed'            => 'Viewed',
        'approved'          => 'Approved',
        'partial'           => 'Partial',
        'paid'              => 'Paid',
    ],

    'messages' => [
        'email_sent'        => 'Invoice email has been sent successfully!',
        'marked_sent'       => 'Invoice marked as sent successfully!',
        'email_required'    => 'No email address for this customer!',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Created on :date',
            'send' => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'You are receiving this email because you have an upcoming :amount invoice to :customer customer.',
        'button'            => 'Pay Now',
    ],

];
