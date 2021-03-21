<?php

return [

    'next'                  => 'Dalje',
    'refresh'               => 'Osvježi',

    'steps' => [
        'requirements'      => 'Molimo da zatražite svog davatelja hosting usluge da ispravi pogreške!',
        'language'          => 'Korak 1/3: Odabir jezika',
        'database'          => 'Korak 2/3: Postavka baze podataka',
        'settings'          => 'Korak 3/3: Tvrtka i admin detalji',
    ],

    'language' => [
        'select'            => 'Odaberite jezik',
    ],

    'requirements' => [
        'enabled'           => ':feature mora biti omogućeno!',
        'disabled'          => ':feature mora biti onemogućeno!',
        'extension'         => ':extension proširenje mora biti instalirano i učitano!',
        'directory'         => ':directory direktorij mora biti omogućen za zapisivanje!',
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
    ],

    'database' => [
        'hostname'          => 'Server',
        'username'          => 'Korisničko ime',
        'password'          => 'Lozinka',
        'name'              => 'Baza podataka',
    ],

    'settings' => [
        'company_name'      => 'Naziv tvrtke',
        'company_email'     => 'E-mail tvrtke',
        'admin_email'       => 'Admin e-mail',
        'admin_password'    => 'Admin lozinka',
    ],

    'error' => [
        'connection'        => 'Pogreška: Nije moguće povezati se na bazu podataka! Provjerite jesu li podaci ispravni.',
    ],

];
