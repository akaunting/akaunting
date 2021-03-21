<?php

return [

    'invoice_number'        => 'Broj fakture',
    'invoice_date'          => 'Datum fakture',
    'total_price'           => 'Ukupna cijena',
    'due_date'              => 'Datum dospijeća',
    'order_number'          => 'Broj narudžbe',
    'bill_to'               => 'Naplatiti',

    'quantity'              => 'Količina',
    'price'                 => 'Cijena',
    'sub_total'             => 'Podzbroj',
    'discount'              => 'Popust',
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'Porez Ukupno',
    'total'                 => 'Ukupno',

    'item_name'             => 'Ime stavke|Imena stavaka',

    'show_discount'         => ':discount% popusta',
    'add_discount'          => 'Dodaj popust',
    'discount_desc'         => 'od podzbroja',

    'payment_due'           => 'Dospijeća plaćanja',
    'paid'                  => 'Plaćeno',
    'histories'             => 'Povijesti',
    'payments'              => 'Plaćanja',
    'add_payment'           => 'Dodaj plaćanje',
    'mark_paid'             => 'Označi kao plaćeno',
    'mark_sent'             => 'Označi kao poslano',
    'mark_viewed'           => 'Označi pogledano',
    'mark_cancelled'        => 'Mark Cancelled',
    'download_pdf'          => 'Preuzmite PDF',
    'send_mail'             => 'Pošalji e-mail',
    'all_invoices'          => 'Prijavite se za pregled svih faktura',
    'create_invoice'        => 'Kreiraj fakturu',
    'send_invoice'          => 'Pošalji fakturu',
    'get_paid'              => 'Biti plaćen',
    'accept_payments'       => 'Prihvatite mrežna plaćanja',

    'statuses' => [
        'draft'             => 'Skica',
        'sent'              => 'Poslano',
        'viewed'            => 'Pogledano',
        'approved'          => 'Odobreno',
        'partial'           => 'Djelomično',
        'paid'              => 'Plaćeno',
        'overdue'           => 'Kasne',
        'unpaid'            => 'Neplaćeno',
        'cancelled'         => 'Cancelled',
    ],

    'messages' => [
        'email_sent'        => 'E-adresa s računom je poslana!',
        'marked_sent'       => 'Račun označen kao poslan!',
        'marked_paid'       => 'Račun označen kao plaćen!',
        'marked_viewed'     => 'Invoice marked as viewed!',
        'marked_cancelled'  => 'Invoice marked as cancelled!',
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
