<?php

return [

    'company' => [
        'description'                   => 'Zmień nazwę firmy, e-mail, adres, NIP itp.',
        'search_keywords'               => 'firma, nazwa, e-mail, telefon, adres, kraj, NIP, logo, miasto, stan, kod pocztowy',
        'name'                          => 'Nazwa',
        'email'                         => 'E-mail',
        'phone'                         => 'Telefon',
        'address'                       => 'Adres',
        'edit_your_business_address'    => 'Edytuj adres swojej firmy',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Te informacje są widoczne w rekordach, które tworzysz.',
            'address'                   => 'Adres będzie używany w fakturach, rachunkach i innych rekordach, które wystawiasz.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Ustaw rok obrotowy, strefę czasową, format daty i więcej',
        'search_keywords'               => 'finansowy, rok, początek, oznaczanie, czas, strefa, data, format, separator, rabat, procent',
        'financial_start'               => 'Początek roku obrotowego',
        'timezone'                      => 'Strefa czasowa',
        'financial_denote' => [
            'title'                     => 'Oznaczanie roku obrotowego',
            'begins'                    => 'Według roku, w którym się zaczyna',
            'ends'                      => 'Według roku, w którym się kończy',
        ],
        'preferred_date'                => 'Preferowana data',
        'date' => [
            'format'                    => 'Format daty',
            'separator'                 => 'Separator daty',
            'dash'                      => 'Myślnik (-)',
            'dot'                       => 'Kropka (.)',
            'comma'                     => 'Przecinek (,)',
            'slash'                     => 'Ukośnik (/)',
            'space'                     => 'Spacja ( )',
        ],
        'percent' => [
            'title'                     => 'Pozycja znaku procenta (%)',
            'before'                    => 'Przed liczbą',
            'after'                     => 'Po liczbie',
        ],
        'discount_location' => [
            'name'                      => 'Lokalizacja rabatu',
            'item'                      => 'W linii',
            'total'                     => 'W sumie',
            'both'                      => 'W linii i w sumie',
        ],

        'form_description' => [
            'fiscal'                    => 'Ustaw okres roku obrotowego, którego Twoja firma używa do opodatkowania i raportowania.',
            'date'                      => 'Wybierz format daty, który chcesz widzieć wszędzie w interfejsie.',
            'other'                     => 'Wybierz, gdzie wyświetlany jest znak procenta dla podatków. Możesz włączyć rabaty w pozycjach i w sumie dla faktur i rachunków.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Dostosuj prefiks faktury, numer, warunki, stopkę itp.',
        'search_keywords'               => 'dostosuj, faktura, numer, prefiks, cyfra, następny, logo, nazwa, cena, ilość, szablon, tytuł, podtytuł, stopka, notatka, ukryj, termin, kolor, płatność, warunki, kolumna',
        'prefix'                        => 'Prefiks numeru',
        'digit'                         => 'Liczba cyfr',
        'next'                          => 'Następny numer',
        'logo'                          => 'Logo',
        'custom'                        => 'Niestandardowy',
        'item_name'                     => 'Nazwa pozycji',
        'item'                          => 'Pozycje',
        'product'                       => 'Produkty',
        'service'                       => 'Usługi',
        'price_name'                    => 'Nazwa ceny',
        'price'                         => 'Cena',
        'rate'                          => 'Stawka',
        'quantity_name'                 => 'Nazwa ilości',
        'quantity'                      => 'Ilość',
        'payment_terms'                 => 'Warunki płatności',
        'title'                         => 'Tytuł',
        'subheading'                    => 'Podtytuł',
        'due_receipt'                   => 'Termin po otrzymaniu',
        'due_days'                      => 'Termin w ciągu :days dni',
        'due_custom'                    => 'Niestandardowe dzień/dni',
        'due_custom_day'                => 'po dniu',
        'choose_template'               => 'Wybierz szablon faktury',
        'default'                       => 'Domyślny',
        'classic'                       => 'Klasyczny',
        'modern'                        => 'Nowoczesny',
        'logo_size_width'               => 'Szerokość logo',
        'logo_size_height'              => 'Wysokość logo',
        'hide' => [
            'item_name'                 => 'Ukryj nazwę pozycji',
            'item_description'          => 'Ukryj opis pozycji',
            'quantity'                  => 'Ukryj ilość',
            'price'                     => 'Ukryj cenę',
            'amount'                    => 'Ukryj kwotę',
        ],
        'column'                        => 'Kolumna|Kolumny',

        'form_description' => [
            'general'                   => 'Ustaw domyślne wartości dla formatowania numerów faktur i warunków płatności.',
            'template'                  => 'Wybierz jeden z poniższych szablonów dla swoich faktur.',
            'default'                   => 'Wybranie ustawień domyślnych dla faktur spowoduje wstępne wypełnienie tytułów, podtytułów, notatek i stopek. Dzięki temu nie musisz za każdym razem edytować faktur, aby wyglądały bardziej profesjonalnie.',
            'column'                    => 'Dostosuj nazewnictwo kolumn faktury. Jeśli chcesz ukryć opisy pozycji i kwoty w wierszach, możesz to zmienić tutaj.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Wybierz szablon transferu',
        'second'                        => 'Drugi',
        'third'                         => 'Trzeci',
    ],

    'default' => [
        'description'                   => 'Domyślne konto, waluta, język Twojej firmy',
        'search_keywords'               => 'konto, waluta, język, podatek, płatność, metoda, paginacja',
        'list_limit'                    => 'Rekordów na stronę',
        'use_gravatar'                  => 'Użyj Gravatar',
        'income_category'               => 'Kategoria dochodu',
        'expense_category'              => 'Kategoria wydatku',
        'address_format'                => 'Format adresu',
        'address_tags'                  => '<strong>Dostępne tagi:</strong> :tags',

        'form_description' => [
            'general'                   => 'Wybierz domyślne konto, podatek i metodę płatności, aby szybko tworzyć rekordy. Panel i raporty są wyświetlane w domyślnej walucie.',
            'category'                  => 'Wybierz domyślne kategorie, aby przyspieszyć tworzenie rekordów.',
            'other'                     => 'Dostosuj domyślne ustawienia języka firmy i sposobu działania paginacji.',
        ],
    ],

    'email' => [
        'description'                   => 'Zmień protokół wysyłania',
        'search_keywords'               => 'e-mail, wyślij, protokół, smtp, host, hasło',
        'protocol'                      => 'Protokół',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Host SMTP',
            'port'                      => 'Port SMTP',
            'username'                  => 'Nazwa użytkownika SMTP',
            'password'                  => 'Hasło SMTP',
            'encryption'                => 'Zabezpieczenie SMTP',
            'none'                      => 'Brak',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Ścieżka Sendmail',
        'log'                           => 'Loguj e-maile',
        'email_service'                 => 'Usługa e-mail',
        'email_templates'               => 'Szablony e-mail',

        'form_description' => [
            'general'                   => 'Wysyłaj regularne e-maile do swojego zespołu i kontaktów. Możesz ustawić protokół i ustawienia SMTP.',
        ],

        'templates' => [
            'description'               => 'Zmień szablony e-mail',
            'search_keywords'           => 'e-mail, szablon, temat, treść, tag',
            'subject'                   => 'Temat',
            'body'                      => 'Treść',
            'tags'                      => '<strong>Dostępne tagi:</strong> :tag_list',
            'invoice_new_customer'      => 'Szablon nowej faktury (wysyłany do klienta)',
            'invoice_remind_customer'   => 'Szablon przypomnienia o fakturze (wysyłany do klienta)',
            'invoice_remind_admin'      => 'Szablon przypomnienia o fakturze (wysyłany do administratora)',
            'invoice_recur_customer'    => 'Szablon faktury cyklicznej (wysyłany do klienta)',
            'invoice_recur_admin'       => 'Szablon faktury cyklicznej (wysyłany do administratora)',
            'invoice_view_admin'        => 'Szablon wyświetlenia faktury (wysyłany do administratora)',
            'invoice_payment_customer'  => 'Szablon potwierdzenia płatności faktury (wysyłany do klienta)',
            'invoice_payment_admin'     => 'Szablon otrzymania płatności faktury (wysyłany do administratora)',
            'bill_remind_admin'         => 'Szablon przypomnienia o rachunku (wysyłany do administratora)',
            'bill_recur_admin'          => 'Szablon rachunku cyklicznego (wysyłany do administratora)',
            'payment_received_customer' => 'Szablon potwierdzenia płatności (wysyłany do klienta)',
            'payment_made_vendor'       => 'Szablon dokonanej płatności (wysyłany do kontrahenta)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Harmonogram',
        'description'                   => 'Automatyczne przypomnienia i polecenia dla cykliczności',
        'search_keywords'               => 'automatyczny, przypomnienie, cykliczny, cron, polecenie',
        'send_invoice'                  => 'Wyślij przypomnienie o fakturze',
        'invoice_days'                  => 'Wyślij po dniach od terminu',
        'send_bill'                     => 'Wyślij przypomnienie o rachunku',
        'bill_days'                     => 'Wyślij przed dniami od terminu',
        'cron_command'                  => 'Polecenie Cron',
        'command'                       => 'Polecenie',
        'schedule_time'                 => 'Godzina uruchomienia',

        'form_description' => [
            'invoice'                   => 'Włącz lub wyłącz i ustaw przypomnienia dla faktur, gdy są przeterminowane.',
            'bill'                      => 'Włącz lub wyłącz i ustaw przypomnienia dla rachunków, zanim станут przeterminowane.',
            'cron'                      => 'Skopiuj polecenie cron, które powinien uruchomić Twój serwer. Ustaw czas wyzwolenia zdarzenia.',
        ]
    ],

    'categories' => [
        'description'                   => 'Nieograniczone kategorie dla dochodu, wydatku i pozycji',
        'search_keywords'               => 'kategoria, dochód, wydatek, pozycja',
    ],

    'currencies' => [
        'description'                   => 'Twórz i zarządzaj walutami oraz ustawiaj ich kursy',
        'search_keywords'               => 'domyślny, waluta, kod, kurs, symbol, precyzja, pozycja, dziesiętny, tysiące, znak, separator',
    ],

    'taxes' => [
        'description'                   => 'Stawki podatkowe stałe, zwykłe, wliczone i złożone',
        'search_keywords'               => 'podatek, stawka, typ, stały, wliczony, złożony, potrącenie',
    ],

];
