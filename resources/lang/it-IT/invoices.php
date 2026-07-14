<?php

return [

    'invoice_number'        => 'Numero fattura',
    'invoice_date'          => 'Data fattura',
    'invoice_amount'        => 'Importo fattura',
    'total_price'           => 'Prezzo Totale',
    'due_date'              => 'Data scadenza',
    'order_number'          => 'Numero d\'ordine',
    'bill_to'               => 'Fatturare a',
    'cancel_date'           => 'Data di annullamento',

    'quantity'              => 'Quantità',
    'price'                 => 'Prezzo',
    'sub_total'             => 'Subtotale',
    'discount'              => 'Sconto',
    'item_discount'         => 'Linea Sconto',
    'tax_total'             => 'Totale imposte',
    'total'                 => 'Totale',

    'item_name'             => 'Nome dell\'articolo|Nomi degli articoli',
    'recurring_invoices'    => 'Fattura ricorrente|Fatture ricorrenti',

    'show_discount'         => ':discount% Sconto',
    'add_discount'          => 'Aggiungi Sconto',
    'discount_desc'         => 'di subtotale',

    'payment_due'           => 'Scadenza pagamento',
    'paid'                  => 'Pagato',
    'histories'             => 'Cronologia',
    'payments'              => 'Pagamenti',
    'add_payment'           => 'Aggiungere pagamento',
    'mark_paid'             => 'Segna come pagato',
    'mark_sent'             => 'Segna come inviato',
    'mark_viewed'           => 'Segna come visualizzato',
    'mark_cancelled'        => 'Segna come annullato',
    'download_pdf'          => 'Scarica PDF',
    'send_mail'             => 'Invia email',
    'all_invoices'          => 'Accedi per visualizzare tutte le fatture',
    'create_invoice'        => 'Crea Fattura',
    'send_invoice'          => 'Inviare Fattura',
    'get_paid'              => 'Essere pagato',
    'accept_payments'       => 'Accetta pagamenti online',
    'payments_received'     => 'Pagamenti ricevuti',
    'over_payment'          => 'L\'importo inserito supera il totale: :amount',

    'form_description' => [
        'billing'           => 'I dettagli di fatturazione vengono visualizzati nella fattura. La Data fattura viene utilizzata nel dashboard e nei report. Seleziona la data in cui prevedi di ricevere il pagamento come Data di scadenza.',
    ],

    'messages' => [
        'email_required'    => 'Nessun indirizzo email per questo cliente!',
        'totals_required'   => 'Sono richiesti i totali della fattura Modificare :type e salvarlo di nuovo.',

        'draft'             => 'Questa è una fattura in <b>BOZZA</b> e sarà inclusa nei grafici dopo l\'invio.',

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

        'name_or_description_required' => 'La fattura deve riportare almeno uno dei codici <b>:name</b> o <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Il tuo cliente può visualizzare la fattura a questo link',
        'copy_link'         => 'Copia il link e condividilo con il tuo cliente.',
        'success_message'   => 'Link di condivisione copiato negli appunti!',
    ],

    'sticky' => [
        'description'       => 'Stai visualizzando l\'anteprima di come il tuo cliente vedrà la versione web della tua fattura.',
    ],

];
