<?php

return [

    'company' => [
        'description'       => 'Cambia il nome dell\'azienda, l\'e-mail, il numero di tasse ecc',
        'name'              => 'Nome',
        'email'             => 'Email',
        'phone'             => 'Telefono',
        'address'           => 'Indirizzo',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Imposta anno fiscale, fuso orario, formato data e altri locali',
        'financial_start'   => 'Inizio Anno finanziario',
        'timezone'          => 'Fuso Orario',
        'date' => [
            'format'        => 'Formato data',
            'separator'     => 'Separatore di data',
            'dash'          => 'Trattino (-)',
            'dot'           => 'Punto (.)',
            'comma'         => 'Virgola (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Spazio ( )',
        ],
        'percent' => [
            'title'         => 'Percentuale (%) Posizione',
            'before'        => 'Prima del Numero',
            'after'         => 'Dopo il Numero',
        ],
        'discount_location' => [
            'name'          => 'Posizione di sconto',
            'item'          => 'Alla linea',
            'total'         => 'In totale',
            'both'          => 'Sia la linea che il totale',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalizza prefisso fattura, numero, termini, piè di pagina ecc',
        'prefix'            => 'Prefisso del numero',
        'digit'             => 'Numero cifre',
        'next'              => 'Codice fiscale',
        'logo'              => 'Logo',
        'custom'            => 'Personalizzato',
        'item_name'         => 'Nome dell\'elemento',
        'item'              => 'Elementi',
        'product'           => 'Prodotti',
        'service'           => 'Servizi',
        'price_name'        => 'Nome del prezzo',
        'price'             => 'Prezzo',
        'rate'              => 'Valutazione',
        'quantity_name'     => 'Quantità Nome',
        'quantity'          => 'Quantità',
        'payment_terms'     => 'Condizioni di pagamento',
        'title'             => 'Titolo',
        'subheading'        => 'Sottotitolo',
        'due_receipt'       => 'Scaduto al ricevimento',
        'due_days'          => 'Scade entro :days',
        'choose_template'   => 'Scegli modello di fattura',
        'default'           => 'Predefinito',
        'classic'           => 'Classico',
        'modern'            => 'Moderno',
    ],

    'default' => [
        'description'       => 'Account predefinito, valuta, lingua della tua azienda',
        'list_limit'        => 'Risultati per Pagina',
        'use_gravatar'      => 'Utilizzare Gravatar',
    ],

    'email' => [
        'description'       => 'Modifica il protocollo di invio e i modelli email',
        'protocol'          => 'Protocollo',
        'php'               => 'Mail PHP',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Host SMTP',
            'port'          => 'Porta SMTP',
            'username'      => 'Nome utente SMTP',
            'password'      => 'Password SMTP',
            'encryption'    => 'Protezione SMTP',
            'none'          => 'Nessuno',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Percorso Sendmail',
        'log'               => 'Log Emails',

        'templates' => [
            'subject'                   => 'Oggetto',
            'body'                      => 'Corpo',
            'tags'                      => '<strong>Tag Disponibili:</strong> :tag_list',
            'invoice_new_customer'      => 'Nuovo modello di fattura (inviato al cliente)',
            'invoice_remind_customer'   => 'Nuovo Promemoria di fattura (inviato al cliente)',
            'invoice_remind_admin'      => 'Nuovo Promemoria di fattura (inviato al proprietario)',
            'invoice_recur_customer'    => 'Nuova Ricorrenza di fattura (inviato al cliente)',
            'invoice_recur_admin'       => 'Nuova Ricorrenza di fattura (inviato al proprietario)',
            'invoice_payment_customer'  => 'Modello di pagamento ricevuto (inviato al cliente)',
            'invoice_payment_admin'     => 'Modello di pagamento ricevuto (inviato all\'amministratore)',
            'bill_remind_admin'         => 'Modello promemoria fattura (inviato all\'amministratore)',
            'bill_recur_admin'          => 'Modello di fatturazione ricorrente (inviato all\'amministratore)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Pianificazioni',
        'description'       => 'Promemoria e comando automatici per le ricorrenze',
        'send_invoice'      => 'Inviare promemoria fattura',
        'invoice_days'      => 'Inviare dopo Due giorni',
        'send_bill'         => 'Inviare promemoria pagamenti',
        'bill_days'         => 'Inviare entro Due giorni',
        'cron_command'      => 'Comando cron',
        'schedule_time'     => 'Ora di esecuzione',
    ],

    'categories' => [
        'description'       => 'Categorie illimitate per reddito, spese e articolo',
    ],

    'currencies' => [
        'description'       => 'Crea e gestisci le valute e imposta i loro tassi',
    ],

    'taxes' => [
        'description'       => 'Aliquote fiscali fisse, normali, inclusive e composte',
    ],

];
