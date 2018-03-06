<?php

return [

    'next'                  => 'Sljedeći',
    'refresh'               => 'Osvježi',

    'steps' => [
        'requirements'      => 'Molimo da zadovoljite sljedeće uvjete!',
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
        'extension'         => ':extension proširenje treba biti učitano!',
        'directory'         => ':directory direktorij mora biti omogućen za zapisivanje!',
    ],

    'database' => [
        'hostname'          => 'Naziv hosta',
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
