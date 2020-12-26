<?php

return [

    'next'                  => 'Další',
    'refresh'               => 'Aktualizovat',

    'steps' => [
        'requirements'      => 'Prosím, požádejte svého poskytovatele hostingu o opravu chyb!',
        'language'          => 'Krok 1/3: Výběr jazyka',
        'database'          => 'Krok 2/3: Nastavení databáze',
        'settings'          => 'Krok 3/3: Údaje o společnosti a administrátorovi',
    ],

    'language' => [
        'select'            => 'Zvolte jazyk',
    ],

    'requirements' => [
        'enabled'           => 'Musíte povolit :feature!',
        'disabled'          => 'Musíte zakázat :feature!',
        'extension'         => 'Rozšíření :extension musí být nainstalováno a načteno!',
        'directory'         => 'Složka :directory musí být zapisovatelná!',
        'executable'        => 'Spustitelný soubor PHP CLI není definovaný/funkční nebo jeho verze není :php_version nebo vyšší! Požádejte, prosím, poskytovatele hostingu, aby správně nastavila proměnnou PHP_BINARY nebo PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Název hostitele',
        'username'          => 'Uživatelské jméno',
        'password'          => 'Heslo',
        'name'              => 'Databáze',
    ],

    'settings' => [
        'company_name'      => 'Název společnosti',
        'company_email'     => 'E-mail společnosti',
        'admin_email'       => 'E-mail administrátora',
        'admin_password'    => 'Heslo administrátora',
    ],

    'error' => [
        'php_version'       => 'Chyba: Požádejte svého poskytovatele hostingu o aktualizaci na PHP :php_version nebo vyšší jak pro HTTP, tak i pro CLI.',
        'connection'        => 'Chyba: Nelze se připojit k databázi! Prosím, ujistěte se, že údaje jsou správné.',
    ],

];
