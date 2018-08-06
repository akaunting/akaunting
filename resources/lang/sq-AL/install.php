<?php

return [

    'next'                  => 'Vijues',
    'refresh'               => 'Rifresko',

    'steps' => [
        'requirements'      => 'Please, ask your hosting provider to fix the errors!',
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
        'extension'         => ':extension extension needs to be installed and loaded!',
        'directory'         => ':directory lista duhet të jetë e shkrueshme!',
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
        'connection'        => 'Gabim: Nuk mund të lidhej me database! Ju lutemi, sigurohuni që të dhënat janë të sakta.',
    ],

];
