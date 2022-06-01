<?php

return [

    'company' => [
        'description'       => 'Změnit název společnosti, e-mail, adresu, číslo daně atd',
        'name'              => 'Název',
        'email'             => 'E-mail',
        'phone'             => 'Telefon',
        'address'           => 'Adresa',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Nastavit fiskální rok, časové pásmo, formát data a další lokální',
        'financial_start'   => 'Začátek rozpočtového roku',
        'timezone'          => 'Časové pásmo',
        'date' => [
            'format'        => 'Formát data',
            'separator'     => 'Oddělovač data',
            'dash'          => 'Pomlčka (-)',
            'dot'           => 'Tečka (.)',
            'comma'         => 'Čárka (,)',
            'slash'         => 'Lomítko (/)',
            'space'         => 'Mezera ( )',
        ],
        'percent' => [
            'title'         => 'Pozice (%) procenta',
            'before'        => 'Před číslem',
            'after'         => 'Za číslem',
        ],
        'discount_location' => [
            'name'          => 'Umístění slevy',
            'item'          => 'Na řádku',
            'total'         => 'Celkem',
            'both'          => 'Řádek i celkem',
        ],
    ],

    'invoice' => [
        'description'       => 'Přizpůsobit prefix faktury, číslo, termín, zápatí atd',
        'prefix'            => 'Předpona předčíslí',
        'digit'             => 'Předčíslí',
        'next'              => 'Následující číslo',
        'logo'              => 'Logo',
        'custom'            => 'Vlastní',
        'item_name'         => 'Název položky',
        'item'              => 'Položky',
        'product'           => 'Produkty',
        'service'           => 'Služby',
        'price_name'        => 'Název ceny',
        'price'             => 'Cena',
        'rate'              => 'Sazba',
        'quantity_name'     => 'Název množství',
        'quantity'          => 'Množství',
        'payment_terms'     => 'Platební podmínky',
        'title'             => 'Název',
        'subheading'        => 'Podtitul',
        'due_receipt'       => 'Termín dle účtenky',
        'due_days'          => 'Termín do :days',
        'choose_template'   => 'Zvolte šablonu faktury',
        'default'           => 'Výchozí',
        'classic'           => 'Klasické',
        'modern'            => 'Moderní',
        'hide'              => [
            'item_name'         => 'Skrýt jméno položky',
            'item_description'  => 'Skrýt popisek položky',
            'quantity'          => 'Skrýt množství',
            'price'             => 'Skrýt cenu',
            'amount'            => 'Skrýt částku',
        ],
    ],

    'default' => [
        'description'       => 'Výchozí účet, měna, jazyk vaší společnosti',
        'list_limit'        => 'Záznamů na stránku',
        'use_gravatar'      => 'Použít Gravatar',
        'income_category'   => 'Kategorie příjmu',
        'expense_category'  => 'Kategorie výdaje',
    ],

    'email' => [
        'description'       => 'Změnit protokol a šablony e-mailů',
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

        'templates' => [
            'subject'                   => 'Předmět',
            'body'                      => 'Tělo zprávy',
            'tags'                      => '<strong>Dostupné štítky:</strong> :tag_list',
            'invoice_new_customer'      => 'Nová šablona faktury (posíláno zákazníkovi)',
            'invoice_remind_customer'   => 'Šablona připomenutí faktury (posíláno zákazníkovi)',
            'invoice_remind_admin'      => 'Šablona připomenutí faktury (posíláno správci)',
            'invoice_recur_customer'    => 'Šablona opakující se faktury (posíláno zákazníkovi)',
            'invoice_recur_admin'       => 'Šablona pro opakování faktury (posíláno správci)',
            'invoice_payment_customer'  => 'Šablona příjmu platby (posíláno zákazníkovi)',
            'invoice_payment_admin'     => 'Šablona příjmu platby (posíláno správci)',
            'bill_remind_admin'         => 'Šablona připomenutí účtu (posíláno správci)',
            'bill_recur_admin'          => 'Šablona opakovaného účtu (posíláno správci)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Plánování',
        'description'       => 'Automatické připomenutí a příkaz pro opakování',
        'send_invoice'      => 'Odesílat upozornění o fakturách',
        'invoice_days'      => 'Odeslat po splatnosti (dnů)',
        'send_bill'         => 'Odeslat upozornění na dodavatelské faktury',
        'bill_days'         => 'Odeslat před splatností (dnů)',
        'cron_command'      => 'Příkaz Cronu',
        'schedule_time'     => 'Hodina spuštění',
    ],

    'categories' => [
        'description'       => 'Neomezené kategorie příjmů, nákladů a položek',
    ],

    'currencies' => [
        'description'       => 'Vytvářejte a spravujte měny a nastavte kurzy',
    ],

    'taxes' => [
        'description'       => 'Pevné, normální, inkluzivní a složené daňové sazby',
    ],

];
