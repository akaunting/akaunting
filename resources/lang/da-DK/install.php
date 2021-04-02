<?php

return [

    'next'                  => 'Næste',
    'refresh'               => 'Opdatér',

    'steps' => [
        'requirements'      => 'Venligst, opfyld følgende krav!',
        'language'          => 'Trin 1/3: Valg af sprog',
        'database'          => 'Trin 2/3: Database opsætning',
        'settings'          => 'Trin 3/3: Virksomheds- og administratoroplysninger',
    ],

    'language' => [
        'select'            => 'Vælg sprog',
    ],

    'requirements' => [
        'enabled'           => ':feature skal være aktiveret!',
        'disabled'          => ':feature skal være deaktiveret!',
        'extension'         => ':extension udvidelse skal være indlæst!',
        'directory'         => ':directory folderen skal være skrivbar!',
        'executable'        => 'PHP CLI eksekverbar file virker ikke! Venligst  anmod din host om at sætte PHP_BINARY eller PHP_PATH miljøvariablen korrekt.',
    ],

    'database' => [
        'hostname'          => 'Hostnavn',
        'username'          => 'Brugernavn',
        'password'          => 'Adgangskode',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Firmanavn',
        'company_email'     => 'E-mail',
        'admin_email'       => 'Administrator e-mail',
        'admin_password'    => 'Administratorpassword',
    ],

    'error' => [
        'php_version'       => 'Fejl: Anmod din host om at bruge PHP :php_version eller højere for både HTTP og CLI',
        'connection'        => 'Error: Kunne ikke forbinde til databasen! Kontroller, at oplysningerne er korrekte.',
    ],

];
