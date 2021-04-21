<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Witaj!',
    'salutation'          => 'Pozdrawiamy,<br> :company_name',
    'subcopy'             => 'Jeśli masz problem z kliknięciem przycisku ":text", skopiuj i wklej poniższy adres URL do swojej przeglądarki: [:url](:url)',

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

];
