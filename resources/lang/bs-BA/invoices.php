<?php

return [

    'invoice_number'        => 'Broj fakture',
    'invoice_date'          => 'Datum fakture',
    'invoice_amount'        => 'Iznos fakture',
    'total_price'           => 'Ukupna cijena',
    'due_date'              => 'Datum dospijeća',
    'order_number'          => 'Broj narudžbe',
    'bill_to'               => 'Naplatiti',

    'quantity'              => 'Količina',
    'price'                 => 'Cijena',
    'sub_total'             => 'Međuzbir',
    'discount'              => 'Popust',
    'item_discount'         => 'Popust',
    'tax_total'             => 'Porez ukupno',
    'total'                 => 'Ukupno',

    'item_name'             => 'Ime stavke|Imena stavki',

    'show_discount'         => ':discount% popusta',
    'add_discount'          => 'Dodaj popust',
    'discount_desc'         => 'od međuzbira',

    'payment_due'           => 'Dospijeća plaćanja',
    'paid'                  => 'Plaćeno',
    'histories'             => 'Historija',
    'payments'              => 'Plaćanja',
    'add_payment'           => 'Dodaj plaćanje',
    'mark_paid'             => 'Označi kao plaćeno',
    'mark_sent'             => 'Označi kao poslano',
    'mark_viewed'           => 'Označ kao pogledano',
    'mark_cancelled'        => 'Označi kao otkazano',
    'download_pdf'          => 'Preuzmite PDF',
    'send_mail'             => 'Pošalji e-mail',
    'all_invoices'          => 'Prijavite se za pregled svih faktura',
    'create_invoice'        => 'Kreiraj fakturu',
    'send_invoice'          => 'Pošalji fakturu',
    'get_paid'              => 'Kako biti plaćen',
    'accept_payments'       => 'Prihvatite online plaćanja',

    'messages' => [
        'email_required'    => 'Nema e-mail adrese za ovog kupca!',
        'draft'             => 'This is a <b>SKICA</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Kreirano :date',
            'viewed'        => 'Pogledano',
            'send' => [
                'draft'     => 'Nije poslano',
                'sent'      => 'Poslano: datum',
            ],
            'paid' => [
                'await'     => 'Čeka plaćanje',
            ],
        ],
    ],

];
