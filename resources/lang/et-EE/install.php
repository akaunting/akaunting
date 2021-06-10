<?php

return [

    'next'                  => 'Järgmine',
    'refresh'               => 'Värskenda',

    'steps' => [
        'requirements'      => 'Paluge oma hostimise pakkujal vead parandada!',
        'language'          => 'Samm 1/3 : Valige keel',
        'database'          => 'Samm 2/3 : Andmebaasi seadistamine',
        'settings'          => 'Samm 3/3 : Ettevõtte ja administraatori üksikasjad',
    ],

    'language' => [
        'select'            => 'Vali keel',
    ],

    'requirements' => [
        'enabled'           => ':feature peab olema lubatud!',
        'disabled'          => ':feature peab olema keelatud!',
        'extension'         => ':extension laiendus tuleb paigaldada ja laadida!',
        'directory'         => ':directory kataloog peab olema kirjutatav!',
        'executable'        => 'Käivitatav PHP CLI fail pole määratletud/ei tööta või selle versioon ei ole :php_version või uuem! Paluge oma hostfirmal seadistada keskkonnamuutuja PHP_BINARY või PHP_PATH õigesti.',
    ],

    'database' => [
        'hostname'          => 'Serveri nimi',
        'username'          => 'Kasutajanimi',
        'password'          => 'Salasõna',
        'name'              => 'Andmebaas',
    ],

    'settings' => [
        'company_name'      => 'Ärinimi',
        'company_email'     => 'Ettevõtte e-post',
        'admin_email'       => 'Admini e-post',
        'admin_password'    => 'Admini salasõna',
    ],

    'error' => [
        'php_version'       => 'Viga: Paluge oma hostimisteenuse pakkujal kasutada nii HTTP kui ka CLI jaoks PHP :php_version või uuemat.',
        'connection'        => 'Viga: Andmebaasiga ei saanud ühendust luua! Palun veenduge, et üksikasjad oleksid õiged.',
    ],

];
