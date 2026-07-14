<?php

return [

    'bill_number'           => 'Numero fattura di acquisto',
    'bill_date'             => 'Data fattura di acquisto',
    'bill_amount'           => 'Importo fattura di acquisto',
    'total_price'           => 'Prezzo Totale',
    'due_date'              => 'Data scadenza',
    'order_number'          => 'Ordine di acquisto numero',
    'bill_from'             => 'Fattura da',

    'quantity'              => 'Quantità',
    'price'                 => 'Prezzo',
    'sub_total'             => 'Subtotale',
    'discount'              => 'Sconto',
    'item_discount'         => 'Linea Sconto',
    'tax_total'             => 'Totale imposta',
    'total'                 => 'Totale',

    'item_name'             => 'Nome dell\'articolo|Nomi degli articoli',
    'recurring_bills'       => 'Fattura di acquisto ricorrente|Fatture di acquisto ricorrenti',

    'show_discount'         => ':discount% Sconto',
    'add_discount'          => 'Aggiungi Sconto',
    'discount_desc'         => 'di subtotale',

    'payment_made'          => 'Pagamento effettuato',
    'payment_due'           => 'Scadenza pagamento',
    'amount_due'            => 'Importo dovuto',
    'paid'                  => 'Pagato',
    'histories'             => 'Cronologia',
    'payments'              => 'Pagamenti',
    'add_payment'           => 'Aggiungere pagamento',
    'mark_paid'             => 'Segna come pagato',
    'mark_received'         => 'Segna come ricevuto',
    'mark_cancelled'        => 'Segna come annullato',
    'download_pdf'          => 'Scarica PDF',
    'send_mail'             => 'Invia email',
    'create_bill'           => 'Crea fattura di acquisto',
    'receive_bill'          => 'Ricevi fattura di acquisto',
    'make_payment'          => 'Fare un pagamento',

    'form_description' => [
        'billing'           => 'I dati di fatturazione compaiono nella fattura di acquisto. La data della fattura di acquisto è utilizzata nella dashboard e nei report. Come data di scadenza, seleziona la data prevista per il pagamento.',
    ],

    'messages' => [
        'draft'             => 'Questa è una fattura di acquisto in <b>BOZZA</b> e sarà inclusa nei grafici dopo la ricezione.',

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
