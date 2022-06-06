<?php

return [

    'company' => [
        'description'                   => 'Promijenite naziv firme, e-mail, adresu, porezni broj itd',
        'search_keywords'               => 'firma, ime, email, telefon, adresa, drzava, poreski broj, logo, grad, provincija, poštanski broj',
        'name'                          => 'Naziv firme',
        'email'                         => 'E-mail',
        'phone'                         => 'Telefon',
        'address'                       => 'Adresa',
        'edit_your_business_address'    => 'Izmjenite vašu boznis adresu',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Ove informacije su vidljive u unosima koje kreirate.',
            'address'                   => 'Adresa će se koristiti u fakturama, računima i drugim evidencijama koje izdajete.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Postavite fiskalnu godinu, vremensku zonu, format datuma i više',
        'search_keywords'               => 'financijski, godina, početak, označavanje, vrijeme, zona, datum, format, separator, popust, postotak',
        'financial_start'               => 'Početak fiskalne godine',
        'timezone'                      => 'Vremenska zona',
        'financial_denote' => [
            'title'                     => 'Početak fiskalne godine',
            'begins'                    => 'Do godine u kojoj se započinje',
            'ends'                      => 'Do godine u kojoj završava',
        ],
        'preferred_date'                => 'Željeni datum',
        'date' => [
            'format'                    => 'Format datuma',
            'separator'                 => 'Separator datuma',
            'dash'                      => 'Crtica (-)',
            'dot'                       => 'Tačka (.)',
            'comma'                     => 'Zarez (,)',
            'slash'                     => 'Kosa crta (/)',
            'space'                     => 'Razmak ( )',
        ],
        'percent' => [
            'title'                     => 'Pozicija postotka (%)',
            'before'                    => 'Ispred broja',
            'after'                     => 'Nakon broja',
        ],
        'discount_location' => [
            'name'                      => 'Lokacija popusta',
            'item'                      => 'Na stavci',
            'total'                     => 'Na ukupnom iznosu',
            'both'                      => 'Na stavci i na ukupnom iznosu',
        ],

        'form_description' => [
            'fiscal'                    => 'Postavite period finansijske godine koji vaša kompanija koristi za oporezivanje i izvještavanje.',
            'date'                      => 'Odaberite format datuma koji želite da vidite svuda u interfejsu.',
            'other'                     => 'Odaberite mjesto gdje se prikazuje znak postotka za poreze. Možete omogućiti popuste na stavke i na ukupno za fakture i račune.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Prilagodite prefiks fakture, broj, uvjete, podnožje itd',
        'search_keywords'               => 'prilagođavanja, fakture, broj, prefiks, cifra, sljedeći, logotip, naziv, cijena, količina, šablon, naslov, podnaslov, podnožje, bilješka, skraćenja, rok, boja, plaćanje, uslovi, kolona',
        'prefix'                        => 'Prefiks proja',
        'digit'                         => 'Broj znamenki',
        'next'                          => 'Sljedeći broj',
        'logo'                          => 'Logo',
        'custom'                        => 'Prilagođeno',
        'item_name'                     => 'Ime stavke',
        'item'                          => 'Stavke',
        'product'                       => 'Proizvodi',
        'service'                       => 'Usluge',
        'price_name'                    => 'Naziv cijene',
        'price'                         => 'Cijena',
        'rate'                          => 'Stopa',
        'quantity_name'                 => 'Naziv količine',
        'quantity'                      => 'Količina',
        'payment_terms'                 => 'Uvjeti plaćanja',
        'title'                         => 'Naslov',
        'subheading'                    => 'Podnaslov',
        'due_receipt'                   => 'Rok za primanje',
        'due_days'                      => 'Rok dospijeća: nekoliko dana',
        'choose_template'               => 'Odaberite drugi predložak',
        'default'                       => 'Zadano',
        'classic'                       => 'Klasično',
        'modern'                        => 'Moderno',
        'hide' => [
            'item_name'                 => 'Sakrij naziv stavke',
            'item_description'          => 'Sakrij opis stavke',
            'quantity'                  => 'Sakrij količinu',
            'price'                     => 'Sakrij cijenu',
            'amount'                    => 'Sakrij iznos',
        ],
        'column'                        => 'Kolona|Kolone',

        'form_description' => [
            'general'                   => 'Postavite zadana podešavanja za formatiranje brojeva faktura i uslova plaćanja.',
            'template'                  => 'Odaberite jedan od šablona u nastavku za svoje fakture.',
            'default'                   => 'Odabirom zadanih podešavanja za fakture unaprijed će se popuniti naslovi, podnaslovi, bilješke i podnožja. Dakle, ne morate svaki put uređivati fakture da biste izgledali profesionalnije.',
            'column'                    => 'Prilagodite kako se kolone fakture nazivaju. Ako želite da sakrijete opise stavki i iznose u redovima, možete to promijeniti ovdje.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Odaberite šablon prenosa',
        'second'                        => 'Drugi',
        'third'                         => 'Treći',
    ],

    'default' => [
        'description'                   => 'Zadani račun, valuta, jezik vaše tvrtke',
        'search_keywords'               => 'račun, valuta, jezik, porez, plaćanje, metod, paginacija',
        'list_limit'                    => 'Zapisa po stranici',
        'use_gravatar'                  => 'Koristi Gravatar',
        'income_category'               => 'Prihodi po kategorijama',
        'expense_category'              => 'Troškovi po kategorijama',

        'form_description' => [
            'general'                   => 'Odaberite zadani račun, porez i način plaćanja za brzo kreiranje zapisa. Kontrolna tabla i Izveštaji su prikazani pod podrazumevanom valutom.',
            'category'                  => 'Odaberite zadane kategorije da biste ubrzali kreiranje zapisa.',
            'other'                     => 'Prilagodite zadane postavke jezika firme i kako funkcioniše paginacija.',
        ],
    ],

    'email' => [
        'description'                   => 'Promijenite protokol za slanje i e-mail predloške',
        'search_keywords'               => 'email, slanje, protokol, smtp, host, lozinka',
        'protocol'                      => 'Protokol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP Host',
            'port'                      => 'SMTP Port',
            'username'                  => 'SMTP Korisničko Ime',
            'password'                  => 'SMTP Lozinka',
            'encryption'                => 'SMTP sigurnost',
            'none'                      => 'Ništa',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail putanja',
        'log'                           => 'E-mail evidentiranje',
        'email_service'                 => 'Servis za email',
        'email_templates'               => 'Email šabloni',

        'form_description' => [
            'general'                   => 'Šaljite redovne emailovie svom timu i kontaktima. Možete postaviti protokol i SMTP podešavanja.',
        ],

        'templates' => [
            'description'               => 'Izmjenite šablon e-maila',
            'search_keywords'           => 'email, šablon, predmet, tijelo, oznaka',
            'subject'                   => 'Predmet',
            'body'                      => 'Sadržaj',
            'tags'                      => '<strong>Dostupne oznake:</strong> :tag_list',
            'invoice_new_customer'      => 'Predložak primljenog plaćanja (poslano kupcu)',
            'invoice_remind_customer'   => 'Predložak podsjetnika za fakturu (poslano kupcu)',
            'invoice_remind_admin'      => 'Predložak podsjetnika za fakturu (poslan administratoru)',
            'invoice_recur_customer'    => 'Predložak ponavljajućeg računa (poslano kupcu)',
            'invoice_recur_admin'       => 'Predložak ponavljajućeg računa (poslano administratoru)',
            'invoice_view_admin'        => 'Predložak šablona za fakturu (poslan administratoru)',
            'invoice_payment_customer'  => 'Predložak primljenog plaćanja (poslano kupcu)',
            'invoice_payment_admin'     => 'Predložak primljenog plaćanja (poslano administratoru)',
            'bill_remind_admin'         => 'Predložak podsjetnika za račun (poslano administratoru)',
            'bill_recur_admin'          => 'Ponavljajući predložak računa (poslan administratoru)',
            'payment_received_customer' => 'Šablon primljenog plaćanja (poslano kupcu)',
            'payment_made_vendor'       => 'Šablon primljenog plaćanja (poslano kupcu)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Zakazivanje',
        'description'                   => 'Automatski podsjetnici i naredba za ponavljanje',
        'search_keywords'               => 'automatski, podsjetnik, ponavljajući, cron, komanda',
        'send_invoice'                  => 'Slanje podsjetnika faktura',
        'invoice_days'                  => 'Slanje prije datuma dospijeća',
        'send_bill'                     => 'Slanje podsjetnika računa',
        'bill_days'                     => 'Slanje prije datuma dospijeća',
        'cron_command'                  => 'Cron naredba',
        'command'                       => 'Komanda',
        'schedule_time'                 => 'Vrijeme pokretanja',

        'form_description' => [
            'invoice'                   => 'Omogućite ili onemogućite i postavite podsjetnike za svoje fakture kada kasne.',
            'bill'                      => 'Omogućite ili onemogućite i postavite podsjetnike za svoje račune prije nego se proglase za kasna plaćanja.',
            'cron'                      => 'Kopirajte cron naredbu koju bi vaš server trebao pokrenuti. Postavite vrijeme za pokretanje događaja.',
        ]
    ],

    'categories' => [
        'description'                   => 'Neograničene kategorije za prihod, rashod i stavke',
        'search_keywords'               => 'kategorija, prihod, rashod, stavka',
    ],

    'currencies' => [
        'description'                   => 'Kreirajte i upravljajte valutama i postavite njihove tečajeve',
        'search_keywords'               => 'zadano, valuta, valute, kod, stopa, simbol, preciznost, pozicija, decimala, hiljade, oznaka, separator',
    ],

    'taxes' => [
        'description'                   => 'Fiksne, normalne, uključive i složene porezne stope',
        'search_keywords'               => 'porez, stopa, vrsta, fiksno, uključujući, složeno, zadržavanje',
    ],

];
