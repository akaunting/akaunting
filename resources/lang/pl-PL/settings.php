<?php

return [

    'company' => [
        'description'                => 'Zmień nazwę firmy, e-mail, adres, numer NIP itp.',
        'name'                       => 'Nazwa firmy',
        'email'                      => 'E-mail',
        'phone'                      => 'Telefon',
        'address'                    => 'Adres',
        'edit_your_business_address' => 'Edytuj adres swojej firmy',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Ustaw rok podatkowy, strefę czasową, format daty i więcej lokalizacji',
        'financial_start'   => 'Początek roku finansowego',
        'timezone'          => 'Strefa czasowa',
        'financial_denote' => [
            'title'         => 'Początek roku finansowego',
            'begins'        => 'Do roku, w którym się zaczyna',
            'ends'          => 'Do roku, w którym się kończy',
        ],
        'date' => [
            'format'        => 'Format daty',
            'separator'     => 'Separator dat',
            'dash'          => 'Myślnik (-)',
            'dot'           => 'Kropka (.)',
            'comma'         => 'Przecinek (,)',
            'slash'         => 'Ukośnik (/)',
            'space'         => 'Spacja ( )',
        ],
        'percent' => [
            'title'         => 'Pozycja znaku procent (%)',
            'before'        => 'Przed numerem',
            'after'         => 'Po liczbie',
        ],
        'discount_location' => [
            'name'          => 'Pozycja rabatu',
            'item'          => 'W linii',
            'total'         => 'W podsumowaniu',
            'both'          => 'Zarówno linia, jak i w podsumowaniu',
        ],
    ],

    'invoice' => [
        'description'       => 'Dostosuj prefiks faktury, numer, warunki, stopkę itp.',
        'prefix'            => 'Prefiks numeru',
        'digit'             => 'Liczba cyfr',
        'next'              => 'Następny numer',
        'logo'              => 'Logo',
        'custom'            => 'Niestandardowe',
        'item_name'         => 'Nazwa pozycji',
        'item'              => 'Artykuły',
        'product'           => 'Produkty',
        'service'           => 'Usługi',
        'price_name'        => 'Nazwa ceny',
        'price'             => 'Cena',
        'rate'              => 'Stawka',
        'quantity_name'     => 'Nazwa ilości',
        'quantity'          => 'Ilość',
        'payment_terms'     => 'Warunki płatności',
        'title'             => 'Tytuł',
        'subheading'        => 'Podtytuł',
        'due_receipt'       => 'Płatne przy odbiorze ',
        'due_days'          => 'Zaległa po :days dniach',
        'choose_template'   => 'Wybierz szablon faktury',
        'default'           => 'Domyślne',
        'classic'           => 'Klasyczny',
        'modern'            => 'Nowoczesny',
        'hide'              => [
            'item_name'         => 'Ukryj nazwę pozycji',
            'item_description'  => 'Ukryj opis artykułu',
            'quantity'          => 'Ukryj ilość',
            'price'             => 'Ukryj cenę',
            'amount'            => 'Ukryj kwotę',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Wybierz szablon faktury',
        'second'            => 'Drugi',
        'third'             => 'Trzeci',
    ],

    'default' => [
        'description'       => 'Domyślne konto, waluta, język twojej firmy',
        'list_limit'        => 'Wyników na stronę',
        'use_gravatar'      => 'Użyj Gravatara',
        'income_category'   => 'Kategoria dochodu',
        'expense_category'  => 'Kategoria wydatków',
    ],

    'email' => [
        'description'       => 'Zmień sposób wysyłania i szablony wiadomości e-mail',
        'protocol'          => 'Protokół',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Serwer SMTP',
            'port'          => 'Port SMTP',
            'username'      => 'Nazwa użytkownika SMTP',
            'password'      => 'Hasło SMTP',
            'encryption'    => 'Bezpieczeństwo SMTP',
            'none'          => 'Brak',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Ścieżka Sendmail',
        'log'               => 'Loguj wiadomości e-mail',

        'templates' => [
            'subject'                   => 'Temat',
            'body'                      => 'Treść maila',
            'tags'                      => '<strong>Dostępne tagi:</strong> :tag_list',
            'invoice_new_customer'      => 'Nowy szablon faktury (wysyłany do klienta)',
            'invoice_remind_customer'   => 'Szablon przypomnienia o fakturze (wysyłany do klienta)',
            'invoice_remind_admin'      => 'Szablon przypomnienia o fakturze (wysyłany do administratora)',
            'invoice_recur_customer'    => 'Szablon cyklicznej faktury (wysyłany do klienta)',
            'invoice_recur_admin'       => 'Szablon cyklicznej faktury (wysyłany do administratora)',
            'invoice_payment_customer'  => 'Szablon otrzymanej płatności (wysyłany do klienta)',
            'invoice_payment_admin'     => 'Szablon otrzymanej płatności (wysyłany do administratora)',
            'bill_remind_admin'         => 'Szablon przypomnienia o rachunku (wysyłany do administratora)',
            'bill_recur_admin'          => 'Szablon cyklicznego rachunku (wysyłany do administratora)',
            'revenue_new_customer'      => 'Szablon informacji o otrzymanej płatności (wysyłany do klienta)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Harmonogram',
        'description'       => 'Automatyczne przypomnienia i polecenia dotyczące powtarzających się działań ',
        'send_invoice'      => 'Wyślij przypomnienie o fakturze',
        'invoice_days'      => 'Wyślij po terminie płatności (dni)',
        'send_bill'         => 'Wyślij przypomnienie o rachunku',
        'bill_days'         => 'Wyślij przed terminem płatności (dni)',
        'cron_command'      => 'Polecenie Cron',
        'schedule_time'     => 'Godzina rozpoczęcia',
    ],

    'categories' => [
        'description'       => 'Nieograniczone kategorie dochodów, kosztów i artykułów',
    ],

    'currencies' => [
        'description'       => 'Twórz waluty i zarządzaj nimi oraz ustawiaj ich kursy',
    ],

    'taxes' => [
        'description'       => 'Twórz stawki podatkowe i zarządzaj nimi ',
    ],

];
