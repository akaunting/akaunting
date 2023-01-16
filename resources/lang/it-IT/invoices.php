<?php

return [

    'invoice_number'        => 'Numero fattura',
    'invoice_date'          => 'Data fattura',
    'total_price'           => 'Prezzo Totale',
    'due_date'              => 'Data scadenza',
    'order_number'          => 'Numero d\'ordine',
    'bill_to'               => 'Fatturare a',

    'quantity'              => 'Quantità',
    'price'                 => 'Prezzo',
    'sub_total'             => 'Subtotale',
    'discount'              => 'Sconto',
    'item_discount'         => 'Linea Sconto',
    'tax_total'             => 'Totale imposte',
    'total'                 => 'Totale',

    'item_name'             => 'Nome dell\'articolo|Nomi degli articoli',

    'show_discount'         => ': discount% Sconto',
    'add_discount'          => 'Aggiungi Sconto',
    'discount_desc'         => 'di subtotale',

    'payment_due'           => 'Scadenza pagamento',
    'paid'                  => 'Pagato',
    'histories'             => 'Storico',
    'payments'              => 'Pagamenti',
    'add_payment'           => 'Aggiungere pagamento',
    'mark_paid'             => 'Segna come pagata',
    'mark_sent'             => 'Segna come inviata',
    'mark_viewed'           => 'Segna come visto',
    'mark_cancelled'        => 'Segna come annullata',
    'download_pdf'          => 'Scarica PDF',
    'send_mail'             => 'Invia email',
    'all_invoices'          => 'Accedi per visualizzare tutte le fatture',
    'create_invoice'        => 'Crea Fattura',
    'send_invoice'          => 'Inviare Fattura',
    'get_paid'              => 'Essere pagato',
    'accept_payments'       => 'Accetta pagamenti online',

    'messages' => [
        'email_required'    => 'Nessun indirizzo email per questo cliente!',
        'draft'             => 'Questa è una <b>BOZZA</b> della fattura e si rifletterà sui grafici dopo che sarà inviata.',

        'status' => [
            'created'       => 'Creato il :date',
            'viewed'        => 'Visto',
            'send' => [
                'draft'     => 'Non inviato',
                'sent'      => 'Inviato il :date',
            ],
            'paid' => [
                'await'     => 'In attesa del pagamento',
            ],
        ],
    ],

];
