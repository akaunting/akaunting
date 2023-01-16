<?php

return [

    'next'                  => 'Sekantis',
    'refresh'               => 'Atnaujinti',

    'steps' => [
        'requirements'      => 'Prašome kreiptis į savo talpinimo paslaugų teikėją, kad ištaisytų klaidas!',
        'language'          => 'Žingsnis 1/3: Kalbos pasirinkimas',
        'database'          => 'Žingsnis 2/3: Duombazės nustatymai',
        'settings'          => 'Žingsnis 3/3: Įmonės ir administratoriaus nustatymai',
    ],

    'language' => [
        'select'            => 'Pasirinkite kalbą',
    ],

    'requirements' => [
        'enabled'           => ': feature turi būti įjungta!',
        'disabled'          => ': feature turi būti išjungta!',
        'extension'         => ':extension turi būti įrašytas ir įjungtas!',
        'directory'         => ':directory direktorijoje turi būti leidžiama įrašyti!',
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
    ],

    'database' => [
        'hostname'          => 'Serverio adresas',
        'username'          => 'Vartotojo vardas',
        'password'          => 'Slaptažodis',
        'name'              => 'Duomenų bazė',
    ],

    'settings' => [
        'company_name'      => 'Įmonės pavadinimas',
        'company_email'     => 'Įmonės el. paštas',
        'admin_email'       => 'Administratoriaus el. paštas',
        'admin_password'    => 'Administratoriaus slaptažodis',
    ],

    'error' => [
        'connection'        => 'Klaida: Nepavyko prisijungti prie duomenų bazės! Prašome įsitikinkite, kad informacija yra teisinga.',
    ],

];
