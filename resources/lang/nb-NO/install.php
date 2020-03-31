<?php

return [

    'next'                  => 'Neste',
    'refresh'               => 'Oppfrisk',

    'steps' => [
        'requirements'      => 'Vennligst anmod din tjenestetilbyder om å rette opp problemene!',
        'language'          => 'Steg 1/3: Språkvalg',
        'database'          => 'Steg 2/3: Databaseoppsett',
        'settings'          => 'Steg 3/3: Foretak- og administratordetaljer',
    ],

    'language' => [
        'select'            => 'Velg språk',
    ],

    'requirements' => [
        'enabled'           => ':feature må være aktivert.',
        'disabled'          => ':feature må være deaktivert.',
        'extension'         => 'Utvidelsen :extension må installeres og aktiveres!',
        'directory'         => 'Mappen :directory må være skrivbar.',
        'executable'        => 'Den kjørbare PHP CLI-filen virker ikke! Vennligst be din tjenesteleverandør om å sette riktig PHP_BINARY eller PHP_PATH miljøvariabel.',
    ],

    'database' => [
        'hostname'          => 'Tjenernavn',
        'username'          => 'Brukernavn',
        'password'          => 'Passord',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Foretaksnavn',
        'company_email'     => 'Foretaks e-post',
        'admin_email'       => 'Admins e-post',
        'admin_password'    => 'Admins passord',
    ],

    'error' => [
        'connection'        => 'Feil: Kunne ikke koble til databasen. Påse at opplysningene er riktig.',
    ],

];
