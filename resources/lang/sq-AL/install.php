<?php

return [

    'next'                  => 'Vijues',
    'refresh'               => 'Rifresko',

    'steps' => [
        'requirements'      => 'Ju lutemi, pyesni ofruesin tuaj të hosting për të rregulluar gabimet!',
        'language'          => 'Hapi 1/3: Përzgjedhja e Gjuhës',
        'database'          => 'Hapi 2/3: Vendosja e Database',
        'settings'          => 'Hapi 3/3: Detajet e Kompanisë dhe Adminit',
    ],

    'language' => [
        'select'            => 'Zgjidh Gjuhen',
    ],

    'requirements' => [
        'enabled'           => ':feature duhet të aktivizohet!',
        'disabled'          => ':feature duhet të çaktivizohet!',
        'extension'         => ':extension shtesa duhet të instalohet dhe të ngarkohet!',
        'directory'         => ':directory lista duhet të jetë e shkrueshme!',
        'executable'        => 'Skedari i ekzekutueshëm i PHP CLI nuk po funksionon ose versioni i tij nuk është :php_version ose më i lartë! Ju lutemi, pyetni kompaninë tuaj hosting që të vendosin në mënyrë korrekte vlerat e mjedisit PHP_BINARY ose PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Hostname',
        'username'          => 'Emri Përdorues',
        'password'          => 'Fjalëkalimi',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Emri i Kompanisë',
        'company_email'     => 'Email i Kompanisë',
        'admin_email'       => 'Email i Adminit',
        'admin_password'    => 'Fjalëkalimi i Adminit',
    ],

    'error' => [
        'php_version'       => 'Gabim: Kërkoni nga ofruesi juaj i hosting të përdorë PHP :php_version ose më të lartë si për HTTP dhe CLI.',
        'connection'        => 'Gabim: Nuk mund të lidhej me database! Ju lutemi, sigurohuni që të dhënat janë të sakta.',
    ],

];
