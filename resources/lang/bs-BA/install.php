<?php

return [

    'next'                  => 'Sljedeće',
    'refresh'               => 'Osvježi',

    'steps' => [
        'requirements'      => 'Molimo upitajte hosting provahjdera da ispravi greške!',
        'language'          => 'Korak 1/3: Odabir jezika',
        'database'          => 'Korak 2/3: Podešavanje baze',
        'settings'          => 'Korak 3/3: Podaci o administratoru i firmi/ama',
    ],

    'language' => [
        'select'            => 'Odabirite jezik',
    ],

    'requirements' => [
        'enabled'           => ':feature mora biti omogućena!',
        'disabled'          => ':feature mora biti onemogućena!',
        'extension'         => ':extension ekstenzija mora biti instalirana, omogućena i učitana!',
        'directory'         => ':directory putanja/direktorij mora biti upisiva (writable)',
        'executable'        => 'PHP CLI izvršna datoteka nije definisana/ne radi ili verzija programskog jezika nije :php_version ili viša! Molimo upitajte podršku hosting provajdera da omoguće  PHP_BINARY  ili PHP_PATH ',
    ],

    'database' => [
        'hostname'          => 'Host',
        'username'          => 'Korisničko ime',
        'password'          => 'Lozinka',
        'name'              => 'Naziv baze',
    ],

    'settings' => [
        'company_name'      => 'Naziv firme',
        'company_email'     => 'Email adresa firme',
        'admin_email'       => 'Email adresa administratora',
        'admin_password'    => 'Lozinka administratora',
    ],

    'error' => [
        'php_version'       => 'Greška: Kontaktirajte podršku hosting provajdera i zatražite da oimoguće korištenje PHP :php_version za HTTP i CLI.',
        'connection'        => 'Greška: Ne može se spojiti na bazu podataka! Provjerite da li su tačni unešeni podaci.',
    ],

];
