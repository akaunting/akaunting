<?php

return [

    'company' => [
        'description'                => 'Spremeni podatke podjetja',
        'name'                       => 'Ime',
        'email'                      => 'Elektronski naslov',
        'phone'                      => 'Telefon',
        'address'                    => 'Naslov',
        'edit_your_business_address' => 'Uredi naslov podjetja',
        'logo'                       => 'Logotip',
    ],

    'localisation' => [
        'description'       => 'Nastavite proračunsko leto, časovni pas, obliko datuma in ostale nastavitve',
        'financial_start'   => 'Začetek finančnega leta',
        'timezone'          => 'Časovni pas',
        'financial_denote' => [
            'title'         => 'Označitev proračunskega leta',
            'begins'        => 'Leto, v katerem se začne',
            'ends'          => 'Leto, v katerem se konča',
        ],
        'date' => [
            'format'        => 'Zapis datuma',
            'separator'     => 'Besedilno ločilo za datum',
            'dash'          => 'Pomišljaj (-)',
            'dot'           => 'Pika (.)',
            'comma'         => 'Vejica (,)',
            'slash'         => 'Desna poševnica (/)',
            'space'         => 'Presledek ( )',
        ],
        'percent' => [
            'title'         => 'Odstotek (%) Pozicija',
            'before'        => 'Pred številko',
            'after'         => 'Za številko',
        ],
        'discount_location' => [
            'name'          => 'Lokacija popusta',
            'item'          => 'V vrstici',
            'total'         => 'Skupaj',
            'both'          => 'V vrstici in skupaj',
        ],
    ],

    'invoice' => [
        'description'       => 'Prilagodi račun',
        'prefix'            => 'Predpona za številko',
        'digit'             => 'Številskih mest',
        'next'              => 'Naslednja številka',
        'logo'              => 'Logotip',
        'custom'            => 'Po meri',
        'item_name'         => 'Ime Artikla',
        'item'              => 'Artikli',
        'product'           => 'Izdelki',
        'service'           => 'Storitve',
        'price_name'        => 'Naziv cene',
        'price'             => 'Cena',
        'rate'              => 'Tarifa',
        'quantity_name'     => 'Ime količine',
        'quantity'          => 'Količina',
        'payment_terms'     => 'Plačilni pogoji',
        'title'             => 'Naslov',
        'subheading'        => 'Podnaslov',
        'due_receipt'       => 'Za plačilo do izdaje računa',
        'due_days'          => 'Za plačilo v :days dneh',
        'choose_template'   => 'Izberi predlogo računa',
        'default'           => 'Privzeto',
        'classic'           => 'Klasično',
        'modern'            => 'Sodobno',
        'hide'              => [
            'item_name'         => 'Skrij ime',
            'item_description'  => 'Skrij opis',
            'quantity'          => 'Skrij količino',
            'price'             => 'Skrij ceno',
            'amount'            => 'Skrij znesek',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Izberi predlogo za prenos',
        'second'            => 'Drugi',
        'third'             => 'Tretji',
    ],

    'default' => [
        'description'       => 'Privzeti račun, valuta in jezik podjetja',
        'list_limit'        => 'Rezultatov na stran',
        'use_gravatar'      => 'Uporabi Gravatar',
        'income_category'   => 'Kategorija prihodkov',
        'expense_category'  => 'Stroški po kategorijah',
    ],

    'email' => [
        'description'       => 'Spremeni protokol za pošiljanje in predloge elektronskih pošt',
        'protocol'          => 'Protokol',
        'php'               => 'PHP pošta',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP gostitelj',
            'port'          => 'SMTP vrata',
            'username'      => 'SMTP Uporabniško ime',
            'password'      => 'SMTP Geslo',
            'encryption'    => 'SMTP varnostni model',
            'none'          => 'Brez',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Pot za Sendmail',
        'log'               => 'Dnevnik elektronske pošte',

        'templates' => [
            'subject'                   => 'Zadeva',
            'body'                      => 'Telo',
            'tags'                      => '<strong>Razpoložljive oznake:</strong> :tag_list',
            'invoice_new_customer'      => 'Nova predloga računa (poslana stranki)',
            'invoice_remind_customer'   => '
Predloga za opomnik računa (poslana stranki)',
            'invoice_remind_admin'      => '
Predloga za opomnik računa (poslana skrbniku)',
            'invoice_recur_customer'    => '
Predloga za ponavljajoče se račune (poslana stranki)',
            'invoice_recur_admin'       => '
Predloga za ponavljajoče se račune (poslana skrbniku)',
            'invoice_payment_customer'  => '
Predloga za prejeto plačilo (poslano stranki)',
            'invoice_payment_admin'     => '
Predloga za prejeto plačilo (poslano skrbniku)',
            'bill_remind_admin'         => '
Predloga za opomin za račun (poslano skrbniku)',
            'bill_recur_admin'          => '
Predloga za ponavljajoče se račune (poslana skrbniku)',
            'revenue_new_customer'      => 'Predloga za prejem prihodka (poslana stranki)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Načrtovanje',
        'description'       => '
Samodejni opomniki in ukazi za ponavljajoče se',
        'send_invoice'      => 'Pošlji opomnik za plačilo računa',
        'invoice_days'      => 'Pošlji po dnevu zapadlosti',
        'send_bill'         => 'Pošlji opomnik za plačilo računa',
        'bill_days'         => 'Pošlji pred zapadlim dnem',
        'cron_command'      => 'Cron ukaz',
        'schedule_time'     => 'Čas zagona',
    ],

    'categories' => [
        'description'       => 'Neomejene kategorije za dohodke, odhodke in postavke',
    ],

    'currencies' => [
        'description'       => 'Ustvarite in upravljajte valute ter nastavite njihove tečaje',
    ],

    'taxes' => [
        'description'       => 'Fiksne, običajne, vključujoče in sestavljene davčne stopnje',
    ],

];
