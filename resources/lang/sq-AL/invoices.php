<?php

return [

    'invoice_number'    => 'Numri i faturës',
    'invoice_date'      => 'Data e Faturës',
    'total_price'       => 'Cmimi Total',
    'due_date'          => 'Data e Duhur',
    'order_number'      => 'Numri i Porosisë',
    'bill_to'           => 'Faturë Për',

    'quantity'          => 'Sasia',
    'price'             => 'Çmimi',
    'sub_total'         => 'Nëntotali',
    'discount'          => 'Skonto',
    'tax_total'         => 'Tatimi Gjithsej',
    'total'             => 'Totali',

    'item_name'         => 'Emri i Artikullit | Emrat e Artikullit',

    'show_discount'     => ':discount% Skonto',
    'add_discount'      => 'Shto Skonto',
    'discount_desc'     => 'e nëntotalit',

    'payment_due'       => 'Pagesa e Duhur',
    'paid'              => 'I paguar',
    'histories'         => 'Historitë',
    'payments'          => 'Pagesat',
    'add_payment'       => 'Shto Pagesë',
    'mark_paid'         => 'Shënoje të Paguar',
    'mark_sent'         => 'Shënoje të Dërguar',
    'download_pdf'      => 'Shkarko PDF',
    'send_mail'         => 'Dërgo Email',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Draft',
        'sent'          => 'E Dërguar',
        'viewed'        => 'E Shikuar',
        'approved'      => 'I Miratuar',
        'partial'       => 'I pjesshëm',
        'paid'          => 'I paguar',
    ],

    'messages' => [
        'email_sent'     => 'Emaili i faturës është dërguar me sukses!',
        'marked_sent'    => 'Fatura shënohet si e dërguar me sukses!',
        'email_required' => 'Ska adresë e-mail për këtë klient!',
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
        'message'       => 'Ju po merrni këtë email sepse keni një të ardhshme :amount fature e :customer klientit.',
        'button'        => 'Paguaj Tani',
    ],

];
