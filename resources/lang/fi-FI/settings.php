<?php

return [

    'company' => [
        'description'       => 'Vaihda yhtiön nimi, sähköposti, osoite, vero numero jne.',
        'name'              => 'Nimi',
        'email'             => 'Sähköposti',
        'phone'             => 'Puhelin',
        'address'           => 'Osoite',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Aseta tilikausi, aikavyöhyke, päivämäärämuoto ja muut paikalliset',
        'financial_start'   => 'Tilikauden alku',
        'timezone'          => 'Aikavyöhyke',
        'date' => [
            'format'        => 'Päivämäärämuoto',
            'separator'     => 'Päivämäärän erotin',
            'dash'          => 'Väliviiva (-)',
            'dot'           => 'Piste (.)',
            'comma'         => 'Pilkku (,)',
            'slash'         => 'Kauttaviiva (/)',
            'space'         => 'Välilyönti ( )',
        ],
        'percent' => [
            'title'         => 'Prosenttimerkin (%) sijainti',
            'before'        => 'Ennen numeroa',
            'after'         => 'Numeron jälkeen',
        ],
        'discount_location' => [
            'name'          => 'Alennuksen sijainti',
            'item'          => 'Rivillä',
            'total'         => 'Yhteensä',
            'both'          => 'Sekä rivi että yhteensä',
        ],
    ],

    'invoice' => [
        'description'       => 'Mukauta laskun etuliite, numero, ehdot, alatunniste jne.',
        'prefix'            => 'Numeron etuliite',
        'digit'             => 'Laskunumeron pituus',
        'next'              => 'Seuraava numero',
        'logo'              => 'Logo',
        'custom'            => 'Mukautettu',
        'item_name'         => 'Tuotteen nimi',
        'item'              => 'Tuotteet',
        'product'           => 'Tuotteet',
        'service'           => 'Palvelut',
        'price_name'        => 'Hinnan nimi',
        'price'             => 'Hinta',
        'rate'              => 'Yksikköhinta',
        'quantity_name'     => 'Yksikön nimi',
        'quantity'          => 'Määrä',
        'payment_terms'     => 'Maksuehdot',
        'title'             => 'Otsikko',
        'subheading'        => 'Väliotsikko',
        'due_receipt'       => 'Erääntynyt kuittauksen jälkeen',
        'due_days'          => 'Erääntyy :days päivän päästä',
        'choose_template'   => 'Valitse laskupohja',
        'default'           => 'Oletus',
        'classic'           => 'Perinteinen',
        'modern'            => 'Moderni',
        'hide'              => [
            'item_name'         => 'Piilota tuotteen nimi',
            'item_description'  => 'Piilota tuotteen kuvaus',
            'quantity'          => 'Piilota määrä',
            'price'             => 'Piilota hinta',
            'amount'            => 'Piilota summa',
        ],
    ],

    'default' => [
        'description'       => 'Oletustili, valuutta, yrityksen kieli',
        'list_limit'        => 'Tuloksia per sivu',
        'use_gravatar'      => 'Käytä gravataria',
        'income_category'   => 'Tuloluokka',
        'expense_category'  => 'Menoluokka',
    ],

    'email' => [
        'description'       => 'Muuta lähettävän protokollan ja sähköpostin malleja',
        'protocol'          => 'Protokolla',
        'php'               => 'PHP-posti',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Isäntä',
            'port'          => 'SMTP Portti',
            'username'      => 'SMTP Käyttäjänimi',
            'password'      => 'SMTP Salasana',
            'encryption'    => 'SMTP Suojaus',
            'none'          => 'Ei mitään',
        ],
        'sendmail'          => 'Lähetäposti',
        'sendmail_path'     => 'Lähetäposti Polku',
        'log'               => 'Kirjaa sähköpostit',

        'templates' => [
            'subject'                   => 'Aihe',
            'body'                      => 'Viesti',
            'tags'                      => '<strong>Käytettävissä olevat tunnisteet:</strong> :tag_list',
            'invoice_new_customer'      => 'Uuden laskun malli (asiakkaalle lähetettävä)',
            'invoice_remind_customer'   => 'Muistutuslaskun malli (asiakkaalle lähetettävä)',
            'invoice_remind_admin'      => 'Muistutuslaskun malli (järjestelmänvalvojalle)',
            'invoice_recur_customer'    => 'Toistuvan laskun pohja (asiakkaalle lähetettävä)',
            'invoice_recur_admin'       => 'Toistuvan laskun pohja (adminille lähetettävä)',
            'invoice_payment_customer'  => 'Maksu vastaanotettu malli (lähetetty asiakkaalle)',
            'invoice_payment_admin'     => 'Maksu vastaanotettu malli (lähetetty ylläpitäjälle)',
            'bill_remind_admin'         => 'Muistutuslaskun malli (järjestelmänvalvojalle)',
            'bill_recur_admin'          => 'Toistuvan laskun pohja (adminille lähetettävä)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Aikataulutus',
        'description'       => 'Automaattiset muistutukset ja toistuvat komennot',
        'send_invoice'      => 'Lähetä maksumuistutus',
        'invoice_days'      => 'Lähetä eräpäivien jälkeen',
        'send_bill'         => 'Lähetä Maksumuistutus',
        'bill_days'         => 'Lähetä Ennen Eräpäivää',
        'cron_command'      => 'Cron Komento',
        'schedule_time'     => 'Suoritus kello',
    ],

    'categories' => [
        'description'       => 'Rajoittamaton luokka tuloille, kuluille ja tuotteille',
    ],

    'currencies' => [
        'description'       => 'Luo ja hallitse valuuttoja ja aseta niiden kurssit',
    ],

    'taxes' => [
        'description'       => 'Kiinteät, normaalit, sisältyvät ja lisättävät verokannat',
    ],

];
