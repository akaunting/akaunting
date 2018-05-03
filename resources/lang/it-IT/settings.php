<?php

return [

    'company' => [
        'name'              => 'Nome',
        'email'             => 'Email',
        'phone'             => 'Telefono',
        'address'           => 'Indirizzo',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Localizzazione',
        'date' => [
            'format'        => 'Formato data',
            'separator'     => 'Separatore di data',
            'dash'          => 'Trattino (-)',
            'dot'           => 'Punto (.)',
            'comma'         => 'Virgola (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Spazio ( )',
        ],
        'timezone'          => 'Fuso Orario',
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
    ],
    'invoice' => [
        'tab'               => 'Fattura',
        'prefix'            => 'Prefisso del numero',
        'digit'             => 'Numero cifre',
        'next'              => 'Codice fiscale',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Predefiniti',
        'account'           => 'Conto predefinito',
        'currency'          => 'Valuta Predefinita',
        'tax'               => 'Aliquota d\'imposta predefinito',
        'payment'           => 'Metodo di pagamento predefinito',
        'language'          => 'Lingua predefinita',
    ],
    'email' => [
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
    ],
    'scheduling' => [
        'tab'               => 'Pianificazioni',
        'send_invoice'      => 'Inviare promemoria fattura',
        'invoice_days'      => 'Inviare dopo Due giorni',
        'send_bill'         => 'Inviare promemoria pagamenti',
        'bill_days'         => 'Inviare entro Due giorni',
        'cron_command'      => 'Comando cron',
        'schedule_time'     => 'Ora di esecuzione',
    ],
    'appearance' => [
        'tab'               => 'Aspetto',
        'theme'             => 'Tema',
        'light'             => 'Chiaro',
        'dark'              => 'Scuro',
        'list_limit'        => 'Risultati per Pagina',
        'use_gravatar'      => 'Utilizzare Gravatar',
    ],
    'system' => [
        'tab'               => 'Sistema',
        'session' => [
            'lifetime'      => 'Durata della sessione (minuti)',
            'handler'       => 'Gestore di sessione',
            'file'          => 'File',
            'database'      => 'Database',
        ],
        'file_size'         => 'Dimensione massima del file(MB)',
        'file_types'        => 'Tipi di File consentiti',
    ],

];
