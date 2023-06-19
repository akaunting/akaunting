<?php

return [

    'company' => [
        'description'                   => 'Změnit název společnosti, e-mail, adresu, číslo daně atd',
        'search_keywords'               => 'společnost, jméno, e-mail, telefon, adresa, země, daňové číslo, logo, město, stát, okres, PSČ',
        'name'                          => 'Název',
        'email'                         => 'E-mail',
        'phone'                         => 'Telefon',
        'address'                       => 'Adresa',
        'edit_your_business_address'    => 'Upravit adresu Vaší společnosti',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Tyto informace jsou viditelné v záznamech, které vytváříte.',
            'address'                   => 'Adresa bude použita na fakturách, účtech a dalších záznamech, které vydáváte.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Nastavit fiskální rok, časové pásmo, formát data a další nastavení lokalizace',
        'search_keywords'               => 'finanční, rok, začátek, označení, čas, zóna, datum, formát, oddělovač, sleva, procenta',
        'financial_start'               => 'Začátek rozpočtového roku',
        'timezone'                      => 'Časové pásmo',
        'financial_denote' => [
            'title'                     => 'Označení účetního roku',
            'begins'                    => 'Podle roku, ve kterém začíná',
            'ends'                      => 'Podle roku, ve kterém končí',
        ],
        'preferred_date'                => 'Upřednostňované datum',
        'date' => [
            'format'                    => 'Formát data',
            'separator'                 => 'Oddělovač data',
            'dash'                      => 'Pomlčka (-)',
            'dot'                       => 'Tečka (.)',
            'comma'                     => 'Čárka (,)',
            'slash'                     => 'Lomítko (/)',
            'space'                     => 'Mezera ( )',
        ],
        'percent' => [
            'title'                     => 'Pozice (%) procenta',
            'before'                    => 'Před číslem',
            'after'                     => 'Za číslem',
        ],
        'discount_location' => [
            'name'                      => 'Umístění slevy',
            'item'                      => 'Na řádku',
            'total'                     => 'Celkem',
            'both'                      => 'Řádek i celkem',
        ],

        'form_description' => [
            'fiscal'                    => 'Nastavte účetní období, které vaše společnost používá pro zdaňování a vykazování.',
            'date'                      => 'Vyberte formát data, který chcete vidět všude v rozhraní.',
            'other'                     => 'Vyberte, kde je zobrazen procentuální znak pro daně. Můžete povolit slevy na řádkových položkách a na součtu faktur a přijatých faktur.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Přizpůsobit prefix faktury, číslo, termín, zápatí atd',
        'search_keywords'               => 'přizpůsobení, faktura, číslo, předčíslí, číslice, další, logo, jméno, cena, množství, šablona, titul, titul za jménem, zápatí, poznámka, schovat, splatnost, barva, platba, výrazy, sloupec',
        'prefix'                        => 'Předpona předčíslí',
        'digit'                         => 'Předčíslí',
        'next'                          => 'Následující číslo',
        'logo'                          => 'Logo',
        'custom'                        => 'Vlastní',
        'item_name'                     => 'Název položky',
        'item'                          => 'Položky',
        'product'                       => 'Produkty',
        'service'                       => 'Služby',
        'price_name'                    => 'Název ceny',
        'price'                         => 'Cena',
        'rate'                          => 'Sazba',
        'quantity_name'                 => 'Název množství',
        'quantity'                      => 'Množství',
        'payment_terms'                 => 'Platební podmínky',
        'title'                         => 'Název',
        'subheading'                    => 'Podtitul',
        'due_receipt'                   => 'Termín dle účtenky',
        'due_days'                      => 'Termín do :days',
        'choose_template'               => 'Zvolte šablonu faktury',
        'default'                       => 'Výchozí',
        'classic'                       => 'Klasické',
        'modern'                        => 'Moderní',
        'hide' => [
            'item_name'                 => 'Skrýt jméno položky',
            'item_description'          => 'Skrýt popisek položky',
            'quantity'                  => 'Skrýt množství',
            'price'                     => 'Skrýt cenu',
            'amount'                    => 'Skrýt částku',
        ],
        'column'                        => 'Sloupec | Sloupce',

        'form_description' => [
            'general'                   => 'Nastavte výchozí hodnoty pro formátování čísel faktur a platebních podmínek.',
            'template'                  => 'Vyberte jednu ze šablon níže pro vaše faktury.',
            'default'                   => 'Výběrem výchozích hodnot faktur budou předvyplněny titulky, podhlaví, poznámky a zápatí. Nepotřebujete tedy upravovat faktury pokaždé, abyste mohli vypadat profesionálněji.',
            'column'                    => 'Přizpůsobte si názvy sloupců faktur. Pokud chcete skrýt popisy a částky v řádcích, můžete je změnit zde.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Vyberte šablonu převodu',
        'second'                        => 'Druhý',
        'third'                         => 'Třetí',
    ],

    'default' => [
        'description'                   => 'Výchozí účet, měna, jazyk vaší společnosti',
        'search_keywords'               => 'účet, měna, jazyk, daň, platba, metoda, stránkování',
        'list_limit'                    => 'Záznamů na stránku',
        'use_gravatar'                  => 'Použít Gravatar',
        'income_category'               => 'Kategorie příjmu',
        'expense_category'              => 'Kategorie výdaje',

        'form_description' => [
            'general'                   => 'Vyberte výchozí účet, daň a způsob platby pro rychlé vytváření záznamů. Nástěnka a reporty jsou zobrazeny pod výchozí měnou.',
            'category'                  => 'Vyberte výchozí kategorie pro urychlení tvorby záznamů.',
            'other'                     => 'Přizpůsobte výchozí nastavení jazyka společnosti a jak funguje stránkování. ',
        ],
    ],

    'email' => [
        'description'                   => 'Změnit protokol a šablony e-mailů',
        'search_keywords'               => 'email, odesílnání, protokol, smtp, host, heslo',
        'protocol'                      => 'Protokol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP hostitel',
            'port'                      => 'SMTP port',
            'username'                  => 'SMTP uživatelské jméno',
            'password'                  => 'Heslo SMTP',
            'encryption'                => 'Zabezpečení SMTP',
            'none'                      => 'Žádný',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail cesta',
        'log'                           => 'Log e-mailů',
        'email_service'                 => 'E-mailová služba',
        'email_templates'               => 'Šablony e-mailů',

        'form_description' => [
            'general'                   => 'Posílejte pravidelnou emailovou adresu vašemu týmu a kontaktům. Nastavení protokolu a SMTP můžete nastavit.',
        ],

        'templates' => [
            'description'               => 'Změnit šablony e-mailů',
            'search_keywords'           => 'email, šablona, předmět, tělo, značka',
            'subject'                   => 'Předmět',
            'body'                      => 'Tělo zprávy',
            'tags'                      => '<strong>Dostupné štítky:</strong> :tag_list',
            'invoice_new_customer'      => 'Nová šablona faktury (posíláno zákazníkovi)',
            'invoice_remind_customer'   => 'Šablona připomenutí faktury (posíláno zákazníkovi)',
            'invoice_remind_admin'      => 'Šablona připomenutí faktury (posíláno správci)',
            'invoice_recur_customer'    => 'Šablona opakující se faktury (posíláno zákazníkovi)',
            'invoice_recur_admin'       => 'Šablona pro opakování faktury (posíláno správci)',
            'invoice_view_admin'        => 'Zobrazení šablony faktury (odeslána administrátorovi)',
            'invoice_payment_customer'  => 'Šablona příjmu platby (posíláno zákazníkovi)',
            'invoice_payment_admin'     => 'Šablona příjmu platby (posíláno správci)',
            'bill_remind_admin'         => 'Šablona připomenutí účtu (posíláno správci)',
            'bill_recur_admin'          => 'Šablona opakovaného účtu (posíláno správci)',
            'payment_received_customer' => 'Šablona potvrzení platby (odeslána zákazníkovi)',
            'payment_made_vendor'       => 'Šablona platby (odeslána prodejci)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Plánování',
        'description'                   => 'Automatické připomenutí a příkaz pro opakování',
        'search_keywords'               => 'automatické, připomenutí, opakování, cron, příkaz',
        'send_invoice'                  => 'Odesílat upozornění o fakturách',
        'invoice_days'                  => 'Odeslat po splatnosti (dnů)',
        'send_bill'                     => 'Odeslat upozornění na dodavatelské faktury',
        'bill_days'                     => 'Odeslat před splatností (dnů)',
        'cron_command'                  => 'Příkaz Cronu',
        'command'                       => 'Příkaz',
        'schedule_time'                 => 'Hodina spuštění',

        'form_description' => [
            'invoice'                   => 'Povolte nebo vypněte a nastavte připomenutí vašich faktur po splatnosti.',
            'bill'                      => 'Povolte nebo vypněte a nastavte připomenutí vašich přijatách faktur před jejich splatností.',
            'cron'                      => 'Zkopírujte cron příkaz, který by měl server spustit. Nastavte čas pro spuštění události.',
        ]
    ],

    'categories' => [
        'description'                   => 'Neomezené kategorie příjmů, nákladů a položek',
        'search_keywords'               => 'kategorie, příjmy, náklady, položka',
    ],

    'currencies' => [
        'description'                   => 'Vytvářejte a spravujte měny a nastavte kurzy',
        'search_keywords'               => 'výchozí, měna, měny, kód, kurz, symbol, přesnost, pozice, desetinná místa, tisíce, destiná čárka, oddělovač tisíců',
    ],

    'taxes' => [
        'description'                   => 'Pevné, normální, inkluzivní a složené daňové sazby',
        'search_keywords'               => 'daň, sazba, typ, pevná, zahrnující, složená, srážka',
    ],

];
