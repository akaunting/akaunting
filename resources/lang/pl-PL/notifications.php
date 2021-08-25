<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Witaj!',
    'salutation'          => 'Pozdrawiamy,<br> :company_name',
    'subcopy'             => 'Jeśli masz problem z kliknięciem przycisku ":text", skopiuj i wklej poniższy adres URL do swojej przeglądarki: [:url](:url)',
    'reads'               => 'Czyta|Odczyty',
    'read_all'            => 'Przeczytaj wszystkie',
    'mark_read'           => 'Oznacz jako Przeczytane',
    'mark_read_all'       => 'Oznacz Wszystko Jako Przeczytane',
    'new_apps'            => 'Nowa aplikacja|Nowe aplikacje',
    'upcoming_bills'      => 'Nadchodzące Płatności',
    'recurring_invoices'  => 'Faktury Cykliczne',
    'recurring_bills'     => 'Płatności Cykliczne',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Aktualizacja nie powiodła się :domain',
            'message' => 'Aktualizacja :alias z :current_version do :new_version nie powiodła się w <strong>:step</strong> kroku z następującą wiadomością: :error_message',

        ],

        'slack' => [

            'message' => 'Aktualizacja nie powiodła się na :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Import zakończony',
            'description'       => 'Import został zakończony, a rekordy są dostępne w Twoim panelu.',

        ],

        'failed' => [

            'subject'           => 'Import nie powiódł się',
            'description'       => 'Nie można zaimportować pliku z powodu następujących problemów:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Eksport jest gotowy',
            'description'       => 'Plik eksportu jest gotowy do pobrania z następującego linku:',

        ],

        'failed' => [

            'subject'           => 'Eksport nie powiódł się',
            'description'       => 'Nie można utworzyć pliku eksportu z powodu następującego problemu:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type odczytuje to powiadomienie!',
        'mark_read_all'         => ':type odczytuje wszystkie powiadomienia!',
        'new_app'               => ':type aplikacja została opublikowana.',
        'export'                => 'Twój plik <b>:type</b> jest gotowy do <a href=":url" target="_blank"><b>pobrania</b></a>.
',
        'import'                => 'Twoje dane <b>:type</b> w wierszu <b>:count</b> zostały pomyślnie zaimportowane.',

    ],
];
