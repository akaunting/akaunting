<?php

return [

    'next'                  => 'Seuraava',
    'refresh'               => 'Päivitä',

    'steps' => [
        'requirements'      => 'Pyydä palveluntarjoajaasi korjaamaan virheet!',
        'language'          => 'Vaihe 1/3: Kielen valinta',
        'database'          => 'Vaihe 2/3: Tietokannan asennus',
        'settings'          => 'Vaihe 3/3 : Yrityksen ja ylläpitäjän tiedot',
    ],

    'language' => [
        'select'            => 'Valitse Kieli',
    ],

    'requirements' => [
        'enabled'           => ':feature täytyy olla käytössä!',
        'disabled'          => ':feature täytyy olla pois käytöstä!',
        'extension'         => ':extension laajennus on asennettava ja ladattava!',
        'directory'         => ':directory hakemisto ei saa olla kirjoitussuojattu!',
        'executable'        => 'PHP CLI suoritettavaa tiedostoa ei ole määritelty/työstetty tai sen versio ei ole :php_version tai uudempi! Pyydä hosting-yhtiötäsi asettamaan PHP_BINARY tai PHP_PATH ympäristömuuttuja oikein.',
    ],

    'database' => [
        'hostname'          => 'Palvelin',
        'username'          => 'Käyttäjätunnus',
        'password'          => 'Salasana',
        'name'              => 'Tietokanta',
    ],

    'settings' => [
        'company_name'      => 'Yrityksen nimi',
        'company_email'     => 'Yrityksen sähköpostiosoite',
        'admin_email'       => 'Ylläpitäjän sähköpostiosoite',
        'admin_password'    => 'Ylläpitäjän salasana',
    ],

    'error' => [
        'php_version'       => 'Virhe: Pyydä hosting-palveluntarjoajaa käyttämään PHP :php_version tai uudempi sekä HTTP että CLI.',
        'connection'        => 'Virhe: Tietokantaan ei saatu yhteyttä! Varmista, että tiedot ovat oikein.',
    ],

];
