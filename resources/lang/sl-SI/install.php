<?php

return [

    'next'                  => 'Naprej',
    'refresh'               => 'Osveži',

    'steps' => [
        'requirements'      => 'Prosimo vprašajte vašega ponudnika za odpravo napak!',
        'language'          => 'Korak 1/3 : Izbira jezika',
        'database'          => 'Step 2/3 : Ustvarjanje baze podatkov',
        'settings'          => 'Korak 3/3 : Podatki o podjetju in skrbniku sistema',
    ],

    'language' => [
        'select'            => 'Izberi jezik',
    ],

    'requirements' => [
        'enabled'           => ':feature mora biti omogočen!',
        'disabled'          => ':feature mora biti onemogočen!',
        'extension'         => ':extension razširitev mora niti nameščena in naložena!',
        'directory'         => ':direktorij mora biti zapisljiv!',
        'executable'        => 'Izvršljiva datoteka PHP CLI ni definirana / deluje ali njena različica ni :php_version ali novejša! Prosite svojega ponudnika gostovanja, da pravilno nastavi spremenljivko okolja PHP_BINARY ali PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Ime gostitelja',
        'username'          => 'Uporabniško ime',
        'password'          => 'Geslo',
        'name'              => 'Baza podatkov',
    ],

    'settings' => [
        'company_name'      => 'Podjetje',
        'company_email'     => 'Elektronski naslov podjetja',
        'admin_email'       => 'Elektronski naslov skrbnika',
        'admin_password'    => 'Geslo skrbnika',
    ],

    'error' => [
        'php_version'       => 'Napaka: ponudnika gostovanja prosite, naj za PHP :php_version ali novejši uporablja tako HTTP kot CLI.',
        'connection'        => 'Napaka: Povezava s podatkovno bazo ni mogoča! Prosimo preverite ali so podatki pravilni.',
    ],

];
