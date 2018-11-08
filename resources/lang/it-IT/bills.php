<?php

return [

    'bill_number'       => 'Numero fattura di acquisto',
    'bill_date'         => 'Data fattura di acquisto',
    'total_price'       => 'Prezzo Totale',
    'due_date'          => 'Data scadenza',
    'order_number'      => 'Ordine di acquisto numero',
    'bill_from'         => 'Fattura da',

    'quantity'          => 'Quantità',
    'price'             => 'Prezzo',
    'sub_total'         => 'Subtotale',
    'discount'          => 'Sconto',
    'tax_total'         => 'Totale imposta',
    'total'             => 'Totale',

    'item_name'         => 'Nome dell\'articolo|Nomi degli articoli',

    'show_discount'     => ': discount% Sconto',
    'add_discount'      => 'Aggiungi Sconto',
    'discount_desc'     => 'di subtotale',

    'payment_due'       => 'Scadenza pagamento',
    'amount_due'        => 'Importo dovuto',
    'paid'              => 'Pagato',
    'histories'         => 'Storico',
    'payments'          => 'Pagamenti',
    'add_payment'       => 'Aggiungere pagamento',
    'mark_received'     => 'Segna come ricevuto',
    'download_pdf'      => 'Scarica PDF',
    'send_mail'         => 'Invia email',

    'status' => [
        'draft'         => 'Bozza',
        'received'      => 'Ricevuto',
        'partial'       => 'Parziale',
        'paid'          => 'Pagato',
    ],

    'messages' => [
        'received'      => 'Fattura segnata con successo come ricevuta!',
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
