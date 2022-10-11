<?php

return [

    'next'                  => 'Tālāk',
    'refresh'               => 'Atjaunot',

    'steps' => [
        'requirements'      => 'Nepieciešams atbilst šādām tehniskajām prasībām!',
        'language'          => 'Solis 1/3 : Valodas izvēle',
        'database'          => 'Solis 2/3 : Datubāzes iestatīšana',
        'settings'          => 'Solis 3/3 : Uzņēmuma un administratora detaļas',
    ],

    'language' => [
        'select'            => 'Izvēlieties valodu',
    ],

    'requirements' => [
        'enabled'           => ':feature ir jāiespējo!',
        'disabled'          => ':iezīmee ir jāatspējo!',
        'extension'         => ':paplašinājumi nepieciešams ielādēt!',
        'directory'         => ':direktorijs direktorijai jābūt rakstīšanas tiesībām!',
        'executable'        => 'PHP CLI izpildāmais fails nav definēts/nedarbojas vai tā versija nav :php_version vai augstāka! Lūdziet savam uzturētājuzņēmumam uzstatīt PHP_BINARY vai PHP_PATH vides mainīgo pareizi.',
        'npm'               => '<b>Trūkst JavaScript failu !</b> <br><br><span>You should run <em class="underline">npm install</em> and <em class="underline">npm run dev</em> instalēšanas komandas.</span>', 
    ],

    'database' => [
        'hostname'          => 'Resursdators',
        'username'          => 'Lietotājs',
        'password'          => 'Parole',
        'name'              => 'Datubāze',
    ],

    'settings' => [
        'company_name'      => 'Uzņēmuma nosaukums',
        'company_email'     => 'Uzņēmuma e-pasts',
        'admin_email'       => 'Administratora e-pasts',
        'admin_password'    => 'Administratora parole',
    ],

    'error' => [
        'php_version'       => 'Kļūda: lūdziet uzturētājuzņēmumu izmantot PHP: php_version vai jaunāku versiju gan HTTP, gan CLI.',
        'connection'        => 'Kļūda: Nevar savienoties ar datubāzi. Lūdzu pārliecinies, ka dati ir pareizi.',
    ],

    'update' => [
        'core'              => 'Akaunting jauna versija ir pieejama! Lūdzu, atjauniniet savu instalāciju.',
        'module'            => ':module ir pieejama jaunā versija! Lūdzu, atjauniniet savu instalāciju.',
    ],
];
