<?php

return [

    'bill_number'           => 'Numero fattura di acquisto',
    'bill_date'             => 'Data fattura di acquisto',
    'total_price'           => 'Prezzo Totale',
    'due_date'              => 'Data scadenza',
    'order_number'          => 'Ordine di acquisto numero',
    'bill_from'             => 'Fattura da',

    'quantity'              => 'Quantità',
    'price'                 => 'Prezzo',
    'sub_total'             => 'Subtotale',
    'discount'              => 'Sconto',
    'tax_total'             => 'Totale imposta',
    'total'                 => 'Totale',

    'item_name'             => 'Nome dell\'articolo|Nomi degli articoli',

    'show_discount'         => ': discount% Sconto',
    'add_discount'          => 'Aggiungi Sconto',
    'discount_desc'         => 'di subtotale',

    'payment_due'           => 'Scadenza pagamento',
    'amount_due'            => 'Importo dovuto',
    'paid'                  => 'Pagato',
    'histories'             => 'Storico',
    'payments'              => 'Pagamenti',
    'add_payment'           => 'Aggiungere pagamento',
    'mark_received'         => 'Segna come ricevuto',
    'download_pdf'          => 'Scarica PDF',
    'send_mail'             => 'Invia email',
    'create_bill'           => 'Creare Bolletta',
    'receive_bill'          => 'Ricevere Bolletta',
    'make_payment'          => 'Fare un pagamento',

    'status' => [
        'draft'             => 'Bozza',
        'received'          => 'Ricevuto',
        'partial'           => 'Parziale',
        'paid'              => 'Pagato',
    ],

    'messages' => [
        'received'          => 'Fattura segnata con successo come ricevuta!',
        'draft'             => 'Questa è una <b>BOZZA</b> della fattura e si rifletterà sui grafici dopo che sarà ricevuta.',

        'status' => [
            'created'       => 'Creato il :date',
            'receive' => [
                'draft'     => 'Non inviato',
                'received'  => 'Ricevuto il :date',
            ],
            'paid' => [
                'await'     => 'In attesa del pagamento',
            ],
        ],
    ],

];
