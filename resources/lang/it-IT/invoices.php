<?php

return [

    'invoice_number'        => 'Numero fattura',
    'invoice_date'          => 'Data fattura',
    'invoice_amount'        => 'Importo fattura',
    'total_price'           => 'Prezzo totale',
    'due_date'              => 'Data di scadenza',
    'order_number'          => 'Numero d\'ordine',
    'bill_to'               => 'Fatturare a',
    'cancel_date'           => 'Data di annullamento',

    'quantity'              => 'Quantità',
    'price'                 => 'Prezzo',
    'sub_total'             => 'Subtotale',
    'discount'              => 'Sconto',
    'item_discount'         => 'Sconto riga',
    'tax_total'             => 'Totale imposte',
    'total'                 => 'Totale',

    'item_name'             => 'Nome articolo|Nomi articoli',
    'recurring_invoices'    => 'Fattura ricorrente|Fatture ricorrenti',

    'show_discount'         => ':discount% sconto',
    'add_discount'          => 'Aggiungi sconto',
    'discount_desc'         => 'del subtotale',

    'payment_due'           => 'Scadenza pagamento',
    'paid'                  => 'Pagato',
    'histories'             => 'Cronologia',
    'payments'              => 'Pagamenti',
    'add_payment'           => 'Aggiungi pagamento',
    'mark_paid'             => 'Contrassegna come pagato',
    'mark_sent'             => 'Contrassegna come inviato',
    'mark_viewed'           => 'Contrassegna come visualizzato',
    'mark_cancelled'        => 'Contrassegna come annullato',
    'download_pdf'          => 'Scarica PDF',
    'send_mail'             => 'Invia email',
    'all_invoices'          => 'Accedi per visualizzare tutte le fatture',
    'create_invoice'        => 'Crea fattura',
    'send_invoice'          => 'Invia fattura',
    'get_paid'              => 'Incassa',
    'accept_payments'       => 'Accetta pagamenti online',
    'payments_received'     => 'Pagamenti ricevuti',
    'over_payment'          => 'L\'importo inserito supera il totale: :amount',

    'form_description' => [
        'billing'           => 'I dettagli di fatturazione compaiono nella fattura. La data fattura è utilizzata nella dashboard e nei report. Seleziona come data di scadenza la data in cui prevedi di ricevere il pagamento.',
    ],

    'messages' => [
        'email_required'    => 'Nessun indirizzo email per questo cliente!',
        'totals_required'   => 'I totali della fattura sono obbligatori. Modifica :type e salvalo di nuovo.',

        'draft'             => 'Questa è una fattura in <b>BOZZA</b> e sarà inclusa nei grafici dopo l\'invio.',

        'status' => [
            'created'       => 'Creata il :date',
            'viewed'        => 'Visualizzata',
            'send' => [
                'draft'     => 'Non inviata',
                'sent'      => 'Inviata il :date',
            ],
            'paid' => [
                'await'     => 'In attesa del pagamento',
            ],
        ],

        'name_or_description_required' => 'La fattura deve riportare almeno uno dei campi <b>:name</b> o <b>:description</b>.',
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
