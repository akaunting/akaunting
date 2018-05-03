<?php

return [

    'company' => [
        'name'              => 'Naam',
        'email'             => 'Email',
        'phone'             => 'Telefoonnummer',
        'address'           => 'Adres',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Lokalisatie',
        'date' => [
            'format'        => 'Datum formaat',
            'separator'     => 'Datumscheidingsteken',
            'dash'          => 'Streepje (-)',
            'dot'           => 'Punt (.)',
            'comma'         => 'Komma \',\'',
            'slash'         => 'Slash (/)',
            'space'         => 'Ruimte()',
        ],
        'timezone'          => 'Tijdzone',
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
    ],
    'invoice' => [
        'tab'               => 'Factuur',
        'prefix'            => 'Nummer voorvoegsel',
        'digit'             => 'Aantal cijfers',
        'next'              => 'Volgende nummer',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Standaardwaarden',
        'account'           => 'Standaard account',
        'currency'          => 'Standaard Valuta',
        'tax'               => 'Standaard Btw-tarief',
        'payment'           => 'Standaard betalingsmethode',
        'language'          => 'Standaard Taal',
    ],
    'email' => [
        'protocol'          => 'Protocol',
        'php'               => 'PHP mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP host',
            'port'          => 'SMTP-poort',
            'username'      => 'SMTP gebruikersnaam',
            'password'      => 'SMTP wachtwoord',
            'encryption'    => 'SMTP beveiliging',
            'none'          => 'Geen',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail pad',
        'log'               => 'Log E-mails',
    ],
    'scheduling' => [
        'tab'               => 'Planning',
        'send_invoice'      => 'Factuur herinnering sturen',
        'invoice_days'      => 'Stuur na vervaldatum dagen',
        'send_bill'         => 'Factuur herinnering sturen',
        'bill_days'         => 'Stuur voor vervaldatum dagen',
        'cron_command'      => 'Cron opdracht',
        'schedule_time'     => 'Uren duurtijd',
    ],
    'appearance' => [
        'tab'               => 'Uiterlijk',
        'theme'             => 'Thema',
        'light'             => 'Licht',
        'dark'              => 'Donker',
        'list_limit'        => 'Records Per pagina',
        'use_gravatar'      => 'Gebruik Gravatar',
    ],
    'system' => [
        'tab'               => 'Systeem',
        'session' => [
            'lifetime'      => 'Sessie levensduur (minuten)',
            'handler'       => 'Sessie Beheerder',
            'file'          => 'Bestand',
            'database'      => 'Database',
        ],
        'file_size'         => 'Maximale bestandsgrootte (MB)',
        'file_types'        => 'Toegestane bestandstypes',
    ],

];
