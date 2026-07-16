<?php

return [

    'bill_number'           => 'Numero fattura di acquisto',
    'bill_date'             => 'Data fattura di acquisto',
    'bill_amount'           => 'Importo fattura di acquisto',
    'total_price'           => 'Prezzo totale',
    'due_date'              => 'Data di scadenza',
    'order_number'          => 'Numero d\'ordine',
    'bill_from'             => 'Fattura di acquisto da',

    'quantity'              => 'Quantità',
    'price'                 => 'Prezzo',
    'sub_total'             => 'Subtotale',
    'discount'              => 'Sconto',
    'item_discount'         => 'Sconto riga',
    'tax_total'             => 'Totale imposte',
    'total'                 => 'Totale',

    'item_name'             => 'Nome articolo|Nomi articoli',
    'recurring_bills'       => 'Fattura di acquisto ricorrente|Fatture di acquisto ricorrenti',

    'show_discount'         => ':discount% sconto',
    'add_discount'          => 'Aggiungi sconto',
    'discount_desc'         => 'del subtotale',

    'payment_made'          => 'Pagamento effettuato',
    'payment_due'           => 'Scadenza pagamento',
    'amount_due'            => 'Importo dovuto',
    'paid'                  => 'Pagato',
    'histories'             => 'Cronologia',
    'payments'              => 'Pagamenti',
    'add_payment'           => 'Aggiungi pagamento',
    'mark_paid'             => 'Contrassegna come pagato',
    'mark_received'         => 'Contrassegna come ricevuto',
    'mark_cancelled'        => 'Contrassegna come annullato',
    'download_pdf'          => 'Scarica PDF',
    'send_mail'             => 'Invia email',
    'create_bill'           => 'Crea fattura di acquisto',
    'receive_bill'          => 'Ricevi fattura di acquisto',
    'make_payment'          => 'Effettua pagamento',

    'form_description' => [
        'billing'           => 'I dettagli di fatturazione compaiono nella fattura di acquisto. La data della fattura di acquisto è utilizzata nella dashboard e nei report. Come data di scadenza, seleziona la data prevista per il pagamento.',
    ],

    'messages' => [
        'draft'             => 'Questa è una fattura di acquisto in <b>BOZZA</b> e sarà inclusa nei grafici dopo la ricezione.',

        'status' => [
            'created'       => 'Creata il :date',
            'receive' => [
                'draft'     => 'Non ricevuta',
                'received'  => 'Ricevuta il :date',
            ],
            'paid' => [
                'await'     => 'In attesa del pagamento',
            ],
        ],
    ],

];
