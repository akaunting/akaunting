<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Ciao!',
    'salutation'          => 'Cordiali saluti,<br> :company_name',
    'subcopy'             => 'Se hai problemi a fare clic sul pulsante ":text", copia e incolla l\'URL seguente nel tuo browser web: [:url](:url)',
    'mark_read'           => 'Contrassegna come letto',
    'mark_read_all'       => 'Contrassegna tutti come letti',
    'empty'               => 'Evviva, zero notifiche!',
    'new_apps'            => ':app è disponibile. <a href=":url">Controllala subito</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Aggiornamento non riuscito su :domain',
            'description'   => 'L\'aggiornamento di :alias da :current_version a :new_version non è riuscito nel passaggio <strong>:step</strong> con il seguente messaggio: :error_message',

        ],

        'slack' => [

            'description'   => 'Aggiornamento non riuscito su :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Il download è pronto',
            'description'   => 'Il file è pronto per il download dal seguente link:',

        ],

        'failed' => [

            'title'         => 'Download non riuscito',
            'description'   => 'Impossibile creare il file a causa del seguente problema:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importazione completata',
            'description'   => 'L\'importazione è stata completata e i record sono disponibili nel tuo pannello.',

        ],

        'failed' => [

            'title'         => 'Importazione non riuscita',
            'description'   => 'Impossibile importare il file a causa dei seguenti problemi:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'L\'esportazione è pronta',
            'description'   => 'Il file di esportazione è pronto per il download dal seguente link:',

        ],

        'failed' => [

            'title'         => 'Esportazione non riuscita',
            'description'   => 'Impossibile creare il file di esportazione a causa del seguente problema:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Email :type non valida',
            'description'   => 'L\'indirizzo email :email è stato segnalato come non valido e la persona è stata disabilitata. Controlla il seguente messaggio di errore e correggi l\'indirizzo email:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Il download è pronto',
            'description'   => 'Il tuo file <strong>:type</strong> è pronto per il <a href=":url" target="_blank"><strong>download</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Download non riuscito',
            'description'   => 'Impossibile creare il file a causa di diversi problemi. Controlla la tua email per i dettagli.',

        ],

        'export_completed' => [

            'title'         => 'L\'esportazione è pronta',
            'description'   => 'Il tuo file di esportazione <strong>:type</strong> è pronto per il <a href=":url" target="_blank"><strong>download</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Esportazione non riuscita',
            'description'   => 'Impossibile creare il file di esportazione a causa di diversi problemi. Controlla la tua email per i dettagli.',

        ],

        'import_completed' => [

            'title'         => 'Importazione completata',
            'description'   => 'I tuoi dati <strong>:type</strong> con <strong>:count</strong> elementi sono stati importati correttamente.',

        ],

        'import_failed' => [

            'title'         => 'Importazione non riuscita',
            'description'   => 'Impossibile importare il file a causa di diversi problemi. Controlla la tua email per i dettagli.',

        ],

        'new_apps' => [

            'title'         => 'Nuova app',
            'description'   => 'L\'app <strong>:name</strong> è disponibile. Puoi <a href=":url">cliccare qui</a> per vedere i dettagli.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nuova fattura',
            'description'   => 'La fattura <strong>:invoice_number</strong> è stata creata. Puoi <a href=":invoice_portal_link">cliccare qui</a> per vedere i dettagli e procedere con il pagamento.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Fattura scaduta',
            'description'   => 'La fattura <strong>:invoice_number</strong> aveva scadenza <strong>:invoice_due_date</strong>. Puoi <a href=":invoice_portal_link">cliccare qui</a> per vedere i dettagli e procedere con il pagamento.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Fattura scaduta',
            'description'   => 'La fattura <strong>:invoice_number</strong> aveva scadenza <strong>:invoice_due_date</strong>. Puoi <a href=":invoice_admin_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nuova fattura ricorrente',
            'description'   => 'La fattura <strong>:invoice_number</strong> è stata creata in base al tuo ciclo ricorrente. Puoi <a href=":invoice_portal_link">cliccare qui</a> per vedere i dettagli e procedere con il pagamento.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nuova fattura ricorrente',
            'description'   => 'La fattura <strong>:invoice_number</strong> è stata creata in base al ciclo ricorrente di <strong>:customer_name</strong>. Puoi <a href=":invoice_admin_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Fattura visualizzata',
            'description'   => '<strong>:customer_name</strong> ha visualizzato la fattura <strong>:invoice_number</strong>. Puoi <a href=":invoice_admin_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pagamento ricevuto',
            'description'   => 'Grazie per il pagamento della fattura <strong>:invoice_number</strong>. Puoi <a href=":invoice_portal_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pagamento ricevuto',
            'description'   => 'Grazie per il pagamento della fattura <strong>:invoice_number</strong>. Puoi <a href=":invoice_portal_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pagamento ricevuto',
            'description'   => ':customer_name ha registrato il pagamento per la fattura <strong>:invoice_number</strong>. Puoi <a href=":invoice_admin_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Fattura di acquisto scaduta',
            'description'   => 'La fattura di acquisto <strong>:bill_number</strong> aveva scadenza <strong>:bill_due_date</strong>. Puoi <a href=":bill_admin_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nuova fattura di acquisto ricorrente',
            'description'   => 'La fattura di acquisto <strong>:bill_number</strong> è stata creata in base al ciclo ricorrente di <strong>:vendor_name</strong>. Puoi <a href=":bill_admin_link">cliccare qui</a> per vedere i dettagli.',

        ],

        'invalid_email' => [

            'title'         => 'Email :type non valida',
            'description'   => 'L\'indirizzo email <strong>:email</strong> è stato segnalato come non valido e la persona è stata disabilitata. Controlla e correggi l\'indirizzo email.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type ha letto questa notifica!',
        'mark_read_all'         => ':type ha letto tutte le notifiche!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configurazione dell\'icona di Firefox',
            'description'  => '<span class="font-medium">Se le icone non vengono visualizzate:</span> <br /> <span class="font-medium">Consenti alle pagine di scegliere i propri caratteri, invece delle selezioni sopra</span> <br /><br /> <span class="font-bold"> Impostazioni (Preferenze) > Caratteri > Avanzate </span>',

        ],

    ],

];
