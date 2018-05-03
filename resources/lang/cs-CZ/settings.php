<?php

return [

    'company' => [
        'name'              => 'Jméno',
        'email'             => 'E-mail',
        'phone'             => 'Telefon',
        'address'           => 'Adresa',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Lokalizace',
        'date' => [
            'format'        => 'Formát data',
            'separator'     => 'Oddělovač data',
            'dash'          => 'Pomlčka (-)',
            'dot'           => 'Tečka (.)',
            'comma'         => 'Čárka ()',
            'slash'         => 'Lomítko (/)',
            'space'         => 'Mezera ( )',
        ],
        'timezone'          => 'Časové pásmo',
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
    ],
    'invoice' => [
        'tab'               => 'Faktura',
        'prefix'            => 'Předpona předčísli',
        'digit'             => 'Předčíslí',
        'next'              => 'Další číslo',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Výchozí',
        'account'           => 'Výchozí účet',
        'currency'          => 'Výchozí měna',
        'tax'               => 'Výchozí daňová sazba',
        'payment'           => 'Výchozí způsob platby',
        'language'          => 'Výchozí jazyk',
    ],
    'email' => [
        'protocol'          => 'Protokol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP hostitel',
            'port'          => 'SMTP port',
            'username'      => 'SMTP uživatelské jméno',
            'password'      => 'Heslo SMTP',
            'encryption'    => 'Zabezpečení SMTP',
            'none'          => 'Žádný',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail cesta',
        'log'               => 'Log e-mailů',
    ],
    'scheduling' => [
        'tab'               => 'Plánování',
        'send_invoice'      => 'Odesílat upozornění o fakturách',
        'invoice_days'      => 'Odeslat po splatnosti',
        'send_bill'         => 'Odeslat upomínku',
        'bill_days'         => 'Odeslat před splatností',
        'cron_command'      => 'Příkaz Cronu',
        'schedule_time'     => 'Hodina spuštění',
    ],
    'appearance' => [
        'tab'               => 'Vzhled',
        'theme'             => 'Téma',
        'light'             => 'Světlý',
        'dark'              => 'Tmavý',
        'list_limit'        => 'Záznamů na stránku',
        'use_gravatar'      => 'Použít Gravatar',
    ],
    'system' => [
        'tab'               => 'Systém',
        'session' => [
            'lifetime'      => 'Životnost relace (minuty)',
            'handler'       => 'Popisovač relace',
            'file'          => 'Soubor',
            'database'      => 'Databáze',
        ],
        'file_size'         => 'Max. velikost souboru (MB)',
        'file_types'        => 'Povolené typy souborů',
    ],

];
