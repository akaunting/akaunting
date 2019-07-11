<?php

return [

    'invoice_number'    => 'Broj fakture',
    'invoice_date'      => 'Datum fakture',
    'total_price'       => 'Ukupna cijena',
    'due_date'          => 'Datum dospijeća',
    'order_number'      => 'Broj narudžbe',
    'bill_to'           => 'Naplatiti',

    'quantity'          => 'Količina',
    'price'             => 'Cijena',
    'sub_total'         => 'Podzbroj',
    'discount'          => 'Popust',
    'tax_total'         => 'Porez Ukupno',
    'total'             => 'Ukupno',

    'item_name'         => 'Ime stavke|Imena stavaka',

    'show_discount'     => ':discount% popusta',
    'add_discount'      => 'Dodaj popust',
    'discount_desc'     => 'od podzbroja',

    'payment_due'       => 'Dospijeća plaćanja',
    'paid'              => 'Plaćeno',
    'histories'         => 'Povijesti',
    'payments'          => 'Plaćanja',
    'add_payment'       => 'Dodaj plaćanje',
    'mark_paid'         => 'Označi kao plaćeno',
    'mark_sent'         => 'Označi kao poslano',
    'download_pdf'      => 'Preuzmite PDF',
    'send_mail'         => 'Pošalji e-mail',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Skica',
        'sent'          => 'Poslano',
        'viewed'        => 'Pogledano',
        'approved'      => 'Odobreno',
        'partial'       => 'Djelomično',
        'paid'          => 'Plaćeno',
    ],

    'messages' => [
        'email_sent'     => 'E-mail računa je uspješno poslan!',
        'marked_sent'    => 'Račun je uspješno označen kao poslan!',
        'email_required' => 'Nema e-mail adrese za ovog kupca!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Kreirano :date',
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
        'message'       => 'Primili ste ovaj e-mail jer imate nadolazeću :amount fakturu za :customer.',
        'button'        => 'Platite sada',
    ],

];
