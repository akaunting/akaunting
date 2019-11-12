<?php

return [

    'invoice_number'        => 'Numărul facturii',
    'invoice_date'          => 'Data facturii',
    'total_price'           => 'Preț total',
    'due_date'              => 'Scadenta',
    'order_number'          => 'Număr de comandă',
    'bill_to'               => 'Facturaţi Către',

    'quantity'              => 'Cantitate',
    'price'                 => 'Preț',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Reducere',
    'tax_total'             => 'Total taxe',
    'total'                 => 'Total',

    'item_name'             => 'Articol|Articole
Nume articol|Nume articole',

    'show_discount'         => ':discount% Reducere',
    'add_discount'          => 'Adauga Reducere',
    'discount_desc'         => 'din subtotal',

    'payment_due'           => 'Plata scadenta',
    'paid'                  => 'Plătit',
    'histories'             => 'Istoric',
    'payments'              => 'Plăți',
    'add_payment'           => 'Adauga plata',
    'mark_paid'             => 'Marcheaza ca si Platit',
    'mark_sent'             => 'Marcheaza ca si Trimis',
    'download_pdf'          => 'Descarca PDF',
    'send_mail'             => 'Trimite Email',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',

    'status' => [
        'draft'             => 'Ciornă',
        'sent'              => 'Trimis',
        'viewed'            => 'Vizualizat',
        'approved'          => 'Aprobat',
        'partial'           => 'Parţial
Parţială',
        'paid'              => 'Plătit',
    ],

    'messages' => [
        'email_sent'        => 'Emailul cu factura a fost trimis cu succes!',
        'marked_sent'       => 'Factura a fost marcata ca si trimisa cu succes!',
        'email_required'    => 'Nu exista adresa de email pentru acest client!',
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
        'message'           => 'Primesti acest e-mail, pentru că urmeaza la plata o factura in suma de :amount emisa catre :customer.',
        'button'            => 'Plateste acum',
    ],

];
