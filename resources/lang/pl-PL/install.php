<?php

return [

    'next'                  => 'Dalej',
    'refresh'               => 'Odśwież',

    'steps' => [
        'requirements'      => 'Proszę, poproś swojego usługodawcę hostingowego o naprawienie błędów!',
        'language'          => 'Krok 1/3: Wybór języka',
        'database'          => 'Krok 2/3: Konfiguracja bazy danych',
        'settings'          => 'Krok 3/3: Dane firmy i administratora',
    ],

    'language' => [
        'select'            => 'Wybierz język',
    ],

    'requirements' => [
        'enabled'           => ':feature musi być włączony!',
        'disabled'          => ':feature musi być wyłączony!',
        'extension'         => ':extension musi być zainstalowany i załadowany!',
        'directory'         => 'Katalog :directory musi być zapisywalny!',
        'executable'        => 'Plik wykonywalny CLI PHP nie jest zdefiniowany/działający lub jego wersja nie jest :php_version lub wyższa! Poproś swoją firmę hostingową o poprawne ustawienie zmiennej środowiskowej PHP_BINARY lub PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Nazwa hosta',
        'username'          => 'Nazwa użytkownika',
        'password'          => 'Hasło',
        'name'              => 'Baza danych',
    ],

    'settings' => [
        'company_name'      => 'Nazwa firmy',
        'company_email'     => 'Adres e-mail firmy',
        'admin_email'       => 'E-mail administratora',
        'admin_password'    => 'Hasło administratora',
    ],

    'error' => [
        'php_version'       => 'Błąd: Poproś swojego dostawcę hostingu o używanie PHP :php_version lub wyższej zarówno dla HTTP jak i CLI.',
        'connection'        => 'Błąd: Nie można połączyć się z bazą danych! Upewnij się, że dane są poprawne.',
    ],

];
