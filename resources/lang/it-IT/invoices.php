<?php

return [

    'invoice_number'    => 'Numero fattura',
    'invoice_date'      => 'Data fattura',
    'total_price'       => 'Prezzo Totale',
    'due_date'          => 'Data scadenza',
    'order_number'      => 'Numero d\'ordine',
    'bill_to'           => 'Fatturare a',

    'quantity'          => 'Quantità',
    'price'             => 'Prezzo',
    'sub_total'         => 'Subtotale',
    'discount'          => 'Sconto',
    'tax_total'         => 'Totale imposte',
    'total'             => 'Totale',

    'item_name'         => 'Nome dell\'articolo|Nomi degli articoli',

    'show_discount'     => ': discount% Sconto',
    'add_discount'      => 'Aggiungi Sconto',
    'discount_desc'     => 'di subtotale',

    'payment_due'       => 'Scadenza pagamento',
    'paid'              => 'Pagato',
    'histories'         => 'Storico',
    'payments'          => 'Pagamenti',
    'add_payment'       => 'Aggiungere pagamento',
    'mark_paid'         => 'Segna come pagata',
    'mark_sent'         => 'Segna come inviata',
    'download_pdf'      => 'Scarica PDF',
    'send_mail'         => 'Invia email',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Bozza',
        'sent'          => 'Inviato',
        'viewed'        => 'Visto',
        'approved'      => 'Approvato',
        'partial'       => 'Parziale',
        'paid'          => 'Pagato',
    ],

    'messages' => [
        'email_sent'     => 'La mail è stata inviata con successo.',
        'marked_sent'    => 'La mail è stata contrassegnata con successo come inviata.',
        'email_required' => 'Nessun indirizzo email per questo cliente!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
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
        'message'       => 'Hai ricevuto questa e-mail perché avete un imminente importo di :amount a :customer cliente.',
        'button'        => 'Paga adesso',
    ],

];
