<?php

return [

    'invoice_number'        => 'Invoice Number',
    'invoice_date'          => 'Invoice Date',
    'invoice_amount'        => 'Invoice Amount',
    'total_price'           => 'Total Price',
    'due_date'              => 'Due Date',
    'order_number'          => 'Order Number',
    'bill_to'               => 'Bill To',
    'cancel_date'           => 'Cancel Date',

    'quantity'              => 'Quantity',
    'price'                 => 'Price',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Discount',
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'Tax Total',
    'total'                 => 'Total',

    'item_name'             => 'Item Name|Item Names',
    'recurring_invoices'    => 'Recurring Invoice|Recurring Invoices',

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
    'mark_viewed'           => 'Mark Viewed',
    'mark_cancelled'        => 'Mark Cancelled',
    'download_pdf'          => 'Download PDF',
    'send_mail'             => 'Send Email',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',
    'payment_received'      => 'Payment received',

    'form_description' => [
        'billing'           => 'Billing details appear in your invoice. Invoice Date is used in the dashboard and reports. Select the date you expect to get paid as the Due Date.',
    ],

    'messages' => [
        'email_required'    => 'No email address for this customer!',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected in charts after it is sent.',

        'status' => [
            'created'       => 'Created on :date',
            'viewed'        => 'Viewed',
            'send' => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user created this invoice on :date',
        'create_recurring'  => ':user created this recurring template on :date',
        'schedule'          => 'Repeat every :interval :frequency since :date',
        'children'          => ':count invoices were created automatically',
    ],

    'share' => [
        'show_link'         => 'Your customer can view the invoice at this link',
        'copy_link'         => 'Copy the link and share it with your customer.',
        'success_message'   => 'Copied share link to clipboard!',
    ],

    'sticky' => [
        'description'       => 'You are previewing how your customer will see the web version of your invoice.',
    ],

];
