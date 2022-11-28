<?php

return [

    'company' => [
        'description'                   => 'Schimbă numele companiei, e-mail, adresă, cod fiscal etc',
        'search_keywords'               => 'Companie, nume, e-mail, telefon, adresă, țară, cod fiscal, siglă, oraș, oraș, stat, provincie, cod poștal',
        'name'                          => 'Nume',
        'email'                         => 'Email',
        'phone'                         => 'Telefon',
        'address'                       => 'Adresă',
        'edit_your_business_address'    => 'Editați adresa companiei',
        'logo'                          => 'Siglă',

        'form_description' => [
            'general'                   => 'Această informație este vizibilă în înregistrările pe care le creezi.',
            'address'                   => 'Adresa va fi folosită în facturi, note de plată și alte înregistrări pe care le emiți.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Setați anul fiscal, zona orară, formatul datei și alte localizări',
        'search_keywords'               => 'Financiar, an, început, denotă, oră, zonă, dată, format, separator, reducere, procent',
        'financial_start'               => 'Început an fiscal',
        'timezone'                      => 'Fus orar',
        'financial_denote' => [
            'title'                     => 'Început an fiscal',
            'begins'                    => 'Până în anul în care începe',
            'ends'                      => 'Până la anul în care se încheie',
        ],
        'preferred_date'                => 'Dată preferată',
        'date' => [
            'format'                    => 'Format dată',
            'separator'                 => 'Separator dată',
            'dash'                      => 'Cratimă (-)',
            'dot'                       => 'Punct (.)',
            'comma'                     => 'Virgulă (,)',
            'slash'                     => 'Slash (/)',
            'space'                     => 'Spaţiu ( )',
        ],
        'percent' => [
            'title'                     => 'Procent (%) Pozitie',
            'before'                    => 'Înainte de număr',
            'after'                     => 'După număr',
        ],
        'discount_location' => [
            'name'                      => 'Locație reducere',
            'item'                      => 'La rândul',
            'total'                     => 'La total',
            'both'                      => 'Atât pe linie cât și la total',
        ],

        'form_description' => [
            'fiscal'                    => 'Setează perioada anului financiar pe care compania ta o folosește pentru impozitare și raportare.',
            'date'                      => 'Selectează formatul de dată pe care dorești să-l vezi peste tot în interfață.',
            'other'                     => 'Selectează locul unde este afișat semnul procentual pentru taxe. Puteți activa reduceri la articolele de pe fiecare rând sau la total pentru facturi și note de plată.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizează seria facturii, numărul, termenii, subsolul etc.',
        'search_keywords'               => 'Personalizare, factură, număr, prefix, cifră, următorul, siglă, preț, cantitate, șablon, titlu, subsol, notă, ascunde, scadență, culoare, plată, termeni, coloană',
        'prefix'                        => 'Prefix',
        'digit'                         => 'Zecimale numar',
        'next'                          => 'Următorul număr',
        'logo'                          => 'Siglă',
        'custom'                        => 'Personalizat',
        'item_name'                     => 'Denumire articol',
        'item'                          => 'Articole',
        'product'                       => 'Produse',
        'service'                       => 'Servicii',
        'price_name'                    => 'Numele prețului',
        'price'                         => 'Preț',
        'rate'                          => 'Rată',
        'quantity_name'                 => 'Numele cantității',
        'quantity'                      => 'Cantitate',
        'payment_terms'                 => 'Termeni de plată',
        'title'                         => 'Titlu',
        'subheading'                    => 'Subantet',
        'due_receipt'                   => 'De achitat la primire',
        'due_days'                      => 'Scadentă în :days',
        'choose_template'               => 'Alegeți șablonul facturii',
        'default'                       => 'Implicit',
        'classic'                       => 'Clasic',
        'modern'                        => 'Modern',
        'hide' => [
            'item_name'                 => 'Ascunde Numele Articolului',
            'item_description'          => 'Ascunde Descrierea Articolului',
            'quantity'                  => 'Ascunde Cantitate',
            'price'                     => 'Ascunde Preț',
            'amount'                    => 'Ascunde Suma',
        ],
        'column'                        => 'Coloană|Coloane',

        'form_description' => [
            'general'                   => 'Setări implicite pentru formatarea numerelor facturii tale și a termenilor de plată',
            'template'                  => 'Selectează unul dintre șabloanele de mai jos pentru facturi.',
            'default'                   => 'Selectarea valorilor implicite pentru facturi va pre-popula titlurile, subtitlurile, notele și subsolurile. Așa că nu nevoie să editezi facturile de fiecare dată pentru a arăta mai profesionist.',
            'column'                    => 'Personalizează cum sunt denumite coloanele facturii. Dacă dorești să ascunzi descrierile articolelor și sumele în rânduri, le poți modifica aici.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Alege șablonul pentru transfer ',
        'second'                        => 'Al doilea',
        'third'                         => 'Al treilea',
    ],

    'default' => [
        'description'                   => 'Contul implicit, moneda, limba companiei dvs.',
        'search_keywords'               => 'Cont, monedă, limbă, taxă, plată, metodă, paginare',
        'list_limit'                    => 'Înregistrări pe pagină',
        'use_gravatar'                  => 'Foloseste Gravatar',
        'income_category'               => 'Venituri pe categorii',
        'expense_category'              => 'Cheltuieli pe categorii',

        'form_description' => [
            'general'                   => 'Selectează contul implicit, taxa și metoda de plată pentru a crea înregistrări rapid. Tabloul de bord și rapoartele sunt afișate în moneda implicită.',
            'category'                  => 'Selectează categoriile implicite pentru a accelera crearea înregistrărilor.',
            'other'                     => 'Personalizează setările implicite ale limbii companiei și modul în care funcționează paginarea.',
        ],
    ],

    'email' => [
        'description'                   => 'Modifică protocolul de trimitere și șabloanele de e-mail',
        'search_keywords'               => 'Email, trimite, protocol, SMTP, gazdă, parolă',
        'protocol'                      => 'Protocol',
        'php'                           => 'Mail PHP',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Gazdă SMTP',
            'port'                      => 'Port SMTP',
            'username'                  => 'Utilizator SMTP',
            'password'                  => 'Parolă SMTP',
            'encryption'                => 'Securitate SMTP',
            'none'                      => 'Nici una',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Cale Sendmail',
        'log'                           => 'Jurnal Email-uri',
        'email_service'                 => 'Serviciu email',
        'email_templates'               => 'Șabloane email',

        'form_description' => [
            'general'                   => 'Trimite emailuri regulate echipei și contactelor. Poți seta setările de protocol și SMTP.',
        ],

        'templates' => [
            'description'               => 'Schimbă șabloanele de email',
            'search_keywords'           => 'Email, șablon, subiect, corp, etichetă',
            'subject'                   => 'Subiect',
            'body'                      => 'Conţinut:',
            'tags'                      => '<strong>Etichete disponibile:</strong> :tag_list',
            'invoice_new_customer'      => 'Șablon nou de factură (trimis către client)',
            'invoice_remind_customer'   => 'Șablon memento factură (trimis către client)',
            'invoice_remind_admin'      => 'Șablon memento factură (trimis administratorului)',
            'invoice_recur_customer'    => 'Șablon recurent factură (trimis către client)',
            'invoice_recur_admin'       => 'Șablon memento factură (trimis administratorului)',
            'invoice_view_admin'        => 'Șablon vizualizare factură (trimis la administrator)',
            'invoice_payment_customer'  => 'Șablon plată primită (trimis către client)',
            'invoice_payment_admin'     => 'Șablon plată primită (trimis către administrator)',
            'bill_remind_admin'         => 'Șablon memento factură (trimis administratorului)',
            'bill_recur_admin'          => 'Șablon memento factură (trimis administratorului)',
            'payment_received_customer' => 'Șablon chitanță de plată (trimis către client)',
            'payment_made_vendor'       => 'Șablon plată efectuată (trimis către vânzător)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Planificare',
        'description'                   => 'Memento-uri automate și comanda pentru recurență',
        'search_keywords'               => 'automat, memento, recurent, cron, comandă',
        'send_invoice'                  => 'Trimite memento pentru factura',
        'invoice_days'                  => 'Trimite dupa Zilele Cuvenite',
        'send_bill'                     => 'Trimite memento pentru factura',
        'bill_days'                     => 'Trimite Inainte de Zilele Cuvenite',
        'cron_command'                  => 'Comanda Cron',
        'command'                       => 'Comandă',
        'schedule_time'                 => 'Ora la care ruleaza',

        'form_description' => [
            'invoice'                   => 'Activează sau dezactivează, și setează mementouri pentru facturile tale atunci când acestea sunt întârziate.',
            'bill'                      => 'Activează sau dezactivează, și setează mementouri pentru facturile tale înainte ca acestea să fie restante.',
            'cron'                      => 'Copiați comanda cron pe care serverul ar trebui să o ruleze. Setați timpul pentru a declanșa evenimentul.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorii nelimitate de venituri, cheltuieli și articole',
        'search_keywords'               => 'categorie, venit, cheltuială, articol',
    ],

    'currencies' => [
        'description'                   => 'Creați și gestionați valutele și stabiliți ratele lor de schimb',
        'search_keywords'               => 'implicit, monedă, monede, cod, rata de schimb, simbol, precizie, poziție, zecimale, mii, marcaj, separator',
    ],

    'taxes' => [
        'description'                   => 'Cote de impozitare fixe, normale, totale și compuse',
        'search_keywords'               => 'impozit, rată, tip, fix, inclusiv, compus, reţinere la sursă',
    ],

];
