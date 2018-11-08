<?php

return [

    'next'                  => 'Neste',
    'refresh'               => 'Oppfrisk',

    'steps' => [
        'requirements'      => 'Please, ask your hosting provider to fix the errors!',
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
        'extension'         => ':extension extension needs to be installed and loaded!',
        'directory'         => 'Mappen :directory må være skrivbar.',
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
