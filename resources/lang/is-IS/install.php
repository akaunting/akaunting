<?php

return [

    'next'                  => 'Næst',
    'refresh'               => 'Endurhlaða',

    'steps' => [
        'requirements'      => 'Vinsamlega byðjið hýsingaraðila að laga villur!',
        'language'          => 'Skref 1/3 : Velja tungumál',
        'database'          => 'Skref 2/3 : Setja upp gagnagrunn',
        'settings'          => 'Skref 3/3 : Fyriræki og kerfisstjóri upplýsingar',
    ],

    'language' => [
        'select'            => 'Veldu tungumál',
    ],

    'requirements' => [
        'enabled'           => ':feature þarf að vera valinn!',
        'disabled'          => ':feature þarf að vera afvalinn!',
        'extension'         => ':extension viðbót þarf að vera uppsett og hlaðin inn!',
        'directory'         => ':directory mappa þarf að vera skrifanleg!',
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
    ],

    'database' => [
        'hostname'          => 'Hýsitölva',
        'username'          => 'Notendanafn',
        'password'          => 'Lykilorð',
        'name'              => 'Gagnagrunnur',
    ],

    'settings' => [
        'company_name'      => 'Fyrirtæki',
        'company_email'     => 'Tölvupóstur fyrirtækis',
        'admin_email'       => 'Kerfisstjóri tölvupóstur',
        'admin_password'    => 'Kerfisstjóri lykilorð',
    ],

    'error' => [
        'connection'        => 'Error: Get ekki tengst gagnagrunni! Athugið hvort að upplýsingar séu réttar.',
    ],

];
