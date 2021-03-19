<?php

return [

    'next'                  => 'Següent',
    'refresh'               => 'Actualitza',

    'steps' => [
        'requirements'      => 'Si us plau, demana al teu proveïdor d\'allotjament que arregli els errors!',
        'language'          => 'Pas 1/3: Selecció d\'idioma',
        'database'          => 'Pas 2/3: Configuració de la base de dades',
        'settings'          => 'Pas 3/3: Empresa i detalls de l\'administrador',
    ],

    'language' => [
        'select'            => 'Selecciona l\'idioma',
    ],

    'requirements' => [
        'enabled'           => 'Cal activar: :feature',
        'disabled'          => 'Cal desactivar: :feature',
        'extension'         => 'Cal instal·lar i carregar l\'extensió :extension',
        'directory'         => 'El directori :directory ha de tenir permisos d\'escriptura',
        'executable'        => 'El fitxer de l\'executable PHP CLI (consola de PHP) no està definit o no funciona amb la versió :php_version o superior. Si us plau, demana al teu proveïdor d\'allotjament que configuri les variables d\'entorn PHP_BINARY o PHP_PATH correctament.',
    ],

    'database' => [
        'hostname'          => 'Nom del servidor',
        'username'          => 'Nom d\'usuari',
        'password'          => 'Contrasenya',
        'name'              => 'Base de dades',
    ],

    'settings' => [
        'company_name'      => 'Nom de l\'empresa',
        'company_email'     => 'Correu electrònic de l\'empresa',
        'admin_email'       => 'Correu electrònic de l\'administrador',
        'admin_password'    => 'Contrasenya de l\'administrador',
    ],

    'error' => [
        'php_version'       => 'Error: Demana al teu proveïdor d\'allotjament d\'actualitzar la versió de PHP a la versió :php_version o superior tant per HTTP com per CLI.',
        'connection'        => 'Error: No és possible realitzar la connexió a la base de dades. Si us plau, revisa la configuració.',
    ],

];
