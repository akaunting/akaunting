<?php

return [

    'next'                  => 'Další',
    'refresh'               => 'Aktualizovat',

    'steps' => [
        'requirements'      => 'Prosím, požádej svého poskytovatele hostingu o opravu chyb!',
        'language'          => 'Krok 1/3: Výběr jazyka',
        'database'          => 'Krok 2/3: Nastavení databáze',
        'settings'          => 'Krok 3/3: Údaje o společnosti a administrátorovi',
    ],

    'language' => [
        'select'            => 'Zvolte jazyk',
    ],

    'requirements' => [
        'enabled'           => 'Musíš povolit :feature!',
        'disabled'          => 'Musiš vypnout :feature!',
        'extension'         => 'Koncovka :extension musí být nainstalována nebo nahrána!',
        'directory'         => 'Do složky:directory se musí dát zapisovat!',
    ],

    'database' => [
        'hostname'          => 'Název hostitele',
        'username'          => 'Uživatelské jméno',
        'password'          => 'Heslo',
        'name'              => 'Databáze',
    ],

    'settings' => [
        'company_name'      => 'Název společnosti',
        'company_email'     => 'Email společnosti',
        'admin_email'       => 'Email administratora',
        'admin_password'    => 'Heslo administrátora',
    ],

    'error' => [
        'connection'        => 'Chyba: Nelze se připojit k databázi! Prosím ujistěte se, že údaje jsou správné.',
    ],

];
