<?php

return [

    'edit_columns'              => 'Modifica Colonne',
    'empty_items'               => 'Non hai aggiunto alcun elemento.',
    'grand_total'               => 'Totale complessivo',
    'accept_payment_online'     => 'Accetta pagamenti online',
    'transaction'               => 'È stato effettuato un pagamento per :amount utilizzando :account.',
    'portal_transaction'        => 'È stato effettuato un pagamento per :amount utilizzando :payment_method.',
    'billing'                   => 'Fatturazione',
    'advanced'                  => 'Avanzato',

    'item_price_hidden'         => 'Questa colonna è nascosta sul tuo :type.',

    'actions' => [
        'cancel'                => 'Annulla',
    ],

    'invoice_detail' => [
        'marked'                => '<b>You</b> ha contrassegnato questa fattura come',
        'services'              => 'Servizi',
        'another_item'          => 'Un altro articolo',
        'another_description'   => 'e un\'altra descrizione',
        'more_item'             => '+:count più articolo',
    ],

    'statuses' => [
        'draft'                 => 'Bozza',
        'sent'                  => 'Inviato',
        'expired'               => 'Scaduto',
        'viewed'                => 'Visto',
        'approved'              => 'Approvato',
        'received'              => 'Ricevuto',
        'refused'               => 'Rifiutato',
        'restored'              => 'Ripristinato',
        'reversed'              => 'Annullato',
        'partial'               => 'Parziale',
        'paid'                  => 'Pagato',
        'pending'               => 'In sospeso',
        'invoiced'              => 'Fatturato',
        'overdue'               => 'In Ritardo',
        'unpaid'                => 'Non pagato',
        'cancelled'             => 'Cancellato',
        'voided'                => 'Annullata',
        'completed'             => 'Completato',
        'shipped'               => 'Spedito',
        'refunded'              => 'Rimborsato',
        'failed'                => 'Non riuscito',
        'denied'                => 'Rifiutato',
        'processed'             => 'Elaborato',
        'open'                  => 'Apri',
        'closed'                => 'Chiuso',
        'billed'                => 'Fatturato',
        'delivered'             => 'Consegnato',
        'returned'              => 'Reso',
        'drawn'                 => 'Disegnato',
        'not_billed'            => 'Non Fatturato',
        'issued'                => 'Emessa',
        'not_invoiced'          => 'Non Fatturato',
        'confirmed'             => 'Confermato',
        'not_confirmed'         => 'Non Confermato',
        'active'                => 'Attivo',
        'ended'                 => 'Terminato',
    ],

    'form_description' => [
        'companies'             => 'Modifica l\'indirizzo, il logo e altre informazioni della tua azienda.',
        'billing'               => 'I dettagli di fatturazione vengono visualizzati nel documento.',
        'advanced'              => 'Seleziona la categoria, aggiungi o modifica il piè di pagina e aggiungi allegati al tuo :type.',
        'attachment'            => 'Scarica i file allegati a questo :type',
    ],

    'slider' => [
        'create'            => ':user ha creato questo :type su :date',
        'create_recurring'  => ':user ha creato questo modello ricorrente il :date',
        'send'              => ':user ha inviato questo :type su :date',
        'schedule'          => 'Ripetere ogni :interval :frequency da :date',
        'children'          => ':count :type sono stati creati automaticamente',
        'cancel'            => ':user ha annullato questo :type su :date',
    ],

    'messages' => [
        'email_sent'            => ':type email è stata inviata!',
        'restored'              => ':type è stato restaurato!',
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
            'document_date'     => 'La data :type verrà assegnata automaticamente in base al programma e alla frequenza :type.',
            'document_number'   => 'Il numero :type verrà assegnato automaticamente quando viene generato ogni :type ricorrente.',
        ],
    ],

    'empty_attachments'         => 'Non ci sono file allegati a questo :type.',
];
