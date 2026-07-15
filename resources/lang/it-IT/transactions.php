<?php

return [

    'payment_received'      => 'Pagamento ricevuto',
    'payment_made'          => 'Pagamento effettuato',
    'paid_by'               => 'Pagato da',
    'paid_to'               => 'Pagato a',
    'related_invoice'       => 'Fattura correlata',
    'related_bill'          => 'Fattura di acquisto correlata',
    'recurring_income'      => 'Entrata ricorrente',
    'recurring_expense'     => 'Uscita ricorrente',
    'included_tax'          => 'Importo imposta inclusa',
    'connected'             => 'Connesso',
    'connect_message'       => 'Le imposte per questo :type non sono state calcolate durante il processo di connessione. Le imposte non possono essere collegate.',

    'form_description' => [
        'general'           => 'Qui è possibile inserire le informazioni generali della transazione come data, importo, conto, descrizione, ecc.',
        'assign_income'     => 'Seleziona una categoria e un cliente per rendere i tuoi report più dettagliati.',
        'assign_expense'    => 'Seleziona una categoria e un fornitore per rendere i tuoi report più dettagliati.',
        'other'             => 'Inserisci un numero e un riferimento per mantenere la transazione collegata ai tuoi record.',
    ],

    'slider' => [
        'create'            => ':user ha creato questa transazione il :date',
        'attachments'       => 'Scarica i file allegati a questa transazione',
        'create_recurring'  => ':user ha creato questo modello ricorrente il :date',
        'schedule'          => 'Ripeti ogni :interval :frequency a partire dal :date',
        'children'          => ':count transazioni sono state create automaticamente',
        'connect'           => 'Questa transazione è connessa a :count transazioni',
        'transfer_headline' => '<div> <span class="font-bold"> Da: </span> :from_account </div> <div> <span class="font-bold"> a: </span> :to_account </div>',
        'transfer_desc'     => 'Trasferimento creato il :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Il tuo cliente può visualizzare la transazione a questo link',
            'copy_link'     => 'Copia il link e condividilo con il tuo cliente.',
        ],

        'expense' => [
            'show_link'     => 'Il tuo fornitore può visualizzare la transazione a questo link',
            'copy_link'     => 'Copia il link e condividilo con il tuo fornitore.',
        ],
    ],

    'sticky' => [
        'description'       => 'Stai visualizzando l\'anteprima di come il tuo cliente vedrà la versione web del tuo pagamento.',
    ],

    'messages' => [
        'update_document_transaction' => 'Puoi aggiornare questa transazione. Dovresti andare al documento e modificarlo lì.',
        'create_document_transaction_error' => 'Questo endpoint non può essere aggiunto a un documento. Usa {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Questo endpoint non può essere aggiornato per un documento. Usa {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Questo endpoint non può essere eliminato per un documento. Usa {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
