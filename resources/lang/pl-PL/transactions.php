<?php

return [

    'payment_received'      => 'Płatność otrzymana',
    'payment_made'          => 'Płatność dokonana',
    'paid_by'               => 'Zapłacone przez',
    'paid_to'               => 'Zapłacone dla',
    'related_invoice'       => 'Powiązana faktura',
    'related_bill'          => 'Powiązany rachunek',
    'recurring_income'      => 'Dochód cykliczny',
    'recurring_expense'     => 'Wydatek cykliczny',
    'included_tax'          => 'Kwota podatku wliczona',
    'connected'             => 'Połączone',
    'connect_message'       => 'Podatki dla tego :type nie zostały obliczone podczas procesu łączenia. Podatki nie mogą być połączone.',

    'form_description' => [
        'general'           => 'Tutaj możesz wprowadzić ogólne informacje o transakcji, takie jak data, kwota, konto, opis itp.',
        'assign_income'     => 'Wybierz kategorię i klienta, aby Twoje raporty były bardziej szczegółowe.',
        'assign_expense'    => 'Wybierz kategorię i kontrahenta, aby Twoje raporty były bardziej szczegółowe.',
        'other'             => 'Wprowadź numer i odniesienie, aby zachować powiązanie transakcji z Twoimi rekordami.',
    ],

    'slider' => [
        'create'            => ':user utworzył tę transakcję w dniu :date',
        'attachments'       => 'Pobierz pliki załączone do tej transakcji',
        'create_recurring'  => ':user utworzył ten szablon cykliczny w dniu :date',
        'schedule'          => 'Powtarzaj co :interval :frequency od :date',
        'children'          => ':count transakcji zostało utworzonych automatycznie',
        'connect'           => 'Ta transakcja jest połączona z :count transakcjami',
        'transfer_headline' => '<div> <span class="font-bold"> Od: </span> :from_account </div> <div> <span class="font-bold"> do: </span> :to_account </div>',
        'transfer_desc'     => 'Transfer utworzony w dniu :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Twój klient może zobaczyć transakcję pod tym linkiem',
            'copy_link'     => 'Skopiuj link i udostępnij go swojemu klientowi.',
        ],

        'expense' => [
            'show_link'     => 'Twój kontrahent może zobaczyć transakcję pod tym linkiem',
            'copy_link'     => 'Skopiuj link i udostępnij go swojemu kontrahentowi.',
        ],
    ],

    'sticky' => [
        'description'       => 'Podglądasz, jak Twój klient zobaczy wersję internetową Twojej płatności.',
    ],

    'messages' => [
        'update_document_transaction' => 'Możesz zaktualizować tę transakcję. Powinieneś przejść do dokumentu i edytować go tam.',
        'create_document_transaction_error' => 'Ten punkt końcowy nie może zostać dodany do dokumentu. Użyj {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Ten punkt końcowy nie może zostać zaktualizowany dla dokumentu. Użyj {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Ten punkt końcowy nie może zostać usunięty z dokumentu. Użyj {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
