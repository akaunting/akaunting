<?php

return [

    'company' => [
        'name'              => 'Name',
        'email'             => 'E-Mail',
        'phone'             => 'Telefon',
        'address'           => 'Adresse',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Lokalisation',
        'date' => [
            'format'        => 'Datumsformat',
            'separator'     => 'Datumstrennzeichen',
            'dash'          => 'Bindestrich (-)',
            'dot'           => 'Punkt (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Schrägstrich (/)',
            'space'         => 'Leerzeichen ( )',
        ],
        'timezone'          => 'Zeitzone',
    ],
    'invoice' => [
        'tab'               => 'Rechnung',
        'prefix'            => 'Zahlenprefix',
        'digit'             => 'Nachkommastellen',
        'next'              => 'Nächste Nummer',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Standardeinstellungen',
        'account'           => 'Standardkonto',
        'currency'          => 'Standardwährung',
        'tax'               => 'Standard-Steuersatz',
        'payment'           => 'Standard-Zahlungsart',
        'language'          => 'Standardsprache',
    ],
    'email' => [
        'protocol'          => 'Protokoll',
        'php'               => 'PHP-Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP-Port',
            'username'      => 'SMTP Benutzername',
            'password'      => 'SMTP-Passwort',
            'encryption'    => 'SMTP-Sicherheit',
            'none'          => 'Keine',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Pfad',
        'log'               => 'Protokoll E-Mails',
    ],
    'scheduling' => [
        'tab'               => 'Zeitpläne',
        'send_invoice'      => 'Rechnung Erinnerung senden',
        'invoice_days'      => 'Senden nach Überfälligkeit von',
        'send_bill'         => 'Rechnungserinnerung senden',
        'bill_days'         => 'Senden vor Fälligkeit (Tage)',
        'cron_command'      => 'Cron-Befehl',
        'schedule_time'     => 'Stunde (Laufzeit)',
    ],
    'appearance' => [
        'tab'               => 'Darstellung',
        'theme'             => 'Theme',
        'light'             => 'Light',
        'dark'              => 'Dark',
        'list_limit'        => 'Datensätze pro Seite',
        'use_gravatar'      => 'Gravatar verwenden',
    ],
    'system' => [
        'tab'               => 'System',
        'session' => [
            'lifetime'      => 'Die Sitzungsdauer (Minuten)',
            'handler'       => 'Session-Handler',
            'file'          => 'Datei',
            'database'      => 'Datenbank',
        ],
        'file_size'         => 'Max. Dateigröße (MB)',
        'file_types'        => 'Erlaubte Dateitypen',
    ],

];
