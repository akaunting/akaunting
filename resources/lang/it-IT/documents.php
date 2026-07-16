<?php

return [

    'edit_columns'              => 'Modifica colonne',
    'empty_items'               => 'Non hai aggiunto alcun articolo.',
    'grand_total'               => 'Totale complessivo',
    'accept_payment_online'     => 'Accetta pagamenti online',
    'transaction'               => 'È stato effettuato un pagamento di :amount utilizzando :account.',
    'portal_transaction'        => 'È stato effettuato un pagamento di :amount utilizzando :payment_method.',
    'billing'                   => 'Fatturazione',
    'advanced'                  => 'Avanzate',

    'item_price_hidden'         => 'Questa colonna è nascosta sul tuo :type.',

    'actions' => [
        'cancel'                => 'Annulla',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Tu</b> hai contrassegnato questa fattura come',
        'services'              => 'Servizi',
        'another_item'          => 'Un altro articolo',
        'another_description'   => 'e un\'altra descrizione',
        'more_item'             => '+:count altro articolo',
    ],

    'statuses' => [
        'draft'                 => 'Bozza',
        'sent'                  => 'Inviata',
        'expired'               => 'Scaduta',
        'viewed'                => 'Visualizzata',
        'approved'              => 'Approvata',
        'received'              => 'Ricevuta',
        'refused'               => 'Rifiutata',
        'restored'              => 'Ripristinata',
        'reversed'              => 'Stornata',
        'partial'               => 'Parziale',
        'paid'                  => 'Pagata',
        'pending'               => 'In sospeso',
        'invoiced'              => 'Fatturata',
        'overdue'               => 'Scaduta',
        'unpaid'                => 'Non pagata',
        'cancelled'             => 'Annullata',
        'voided'                => 'Nulla',
        'completed'             => 'Completata',
        'shipped'               => 'Spedita',
        'refunded'              => 'Rimborsata',
        'failed'                => 'Non riuscita',
        'denied'                => 'Negata',
        'processed'             => 'Elaborata',
        'open'                  => 'Aperta',
        'closed'                => 'Chiusa',
        'billed'                => 'Fatturata',
        'delivered'             => 'Consegnata',
        'returned'              => 'Resa',
        'drawn'                 => 'Emessa',
        'not_billed'            => 'Non fatturata',
        'issued'                => 'Emessa',
        'not_invoiced'          => 'Non fatturata',
        'confirmed'             => 'Confermata',
        'not_confirmed'         => 'Non confermata',
        'active'                => 'Attiva',
        'ended'                 => 'Terminata',
    ],

    'form_description' => [
        'companies'             => 'Modifica l\'indirizzo, il logo e altre informazioni della tua azienda.',
        'billing'               => 'I dettagli di fatturazione vengono visualizzati nel documento.',
        'advanced'              => 'Seleziona la categoria, aggiungi o modifica il piè di pagina e aggiungi allegati al tuo :type.',
        'attachment'            => 'Scarica i file allegati a questo :type',
    ],

    'slider' => [
        'create'            => ':user ha creato questo :type il :date',
        'create_recurring'  => ':user ha creato questo modello ricorrente il :date',
        'send'              => ':user ha inviato questo :type il :date',
        'schedule'          => 'Ripeti ogni :interval :frequency a partire dal :date',
        'children'          => ':count :type sono stati creati automaticamente',
        'cancel'            => ':user ha annullato questo :type il :date',
    ],

    'messages' => [
        'email_sent'            => 'L\'email di :type è stata inviata!',
        'restored'              => ':type è stato ripristinato!',
        'marked_as'             => ':type contrassegnato come :status!',
        'marked_sent'           => ':type contrassegnato come inviato!',
        'marked_paid'           => ':type contrassegnato come pagato!',
        'marked_viewed'         => ':type contrassegnato come visualizzato!',
        'marked_cancelled'      => ':type contrassegnato come annullato!',
        'marked_received'       => ':type contrassegnato come ricevuto!',
    ],

    'recurring' => [
        'auto_generated'        => 'Generato automaticamente',

        'tooltip' => [
            'document_date'     => 'La data del :type verrà assegnata automaticamente in base alla pianificazione e alla frequenza del :type.',
            'document_number'   => 'Il numero del :type verrà assegnato automaticamente quando viene generato ogni :type ricorrente.',
        ],
    ],

    'empty_attachments'         => 'Non ci sono file allegati a questo :type.',
];
