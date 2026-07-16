<?php

return [

    'next'                  => 'Siguiente',
    'refresh'               => 'Actualizar',

    'steps' => [
        'requirements'      => 'Pida a su proveedor de alojamiento que corrija los errores.',
        'language'          => 'Paso 1/3: Selección de idioma',
        'database'          => 'Paso 2/3: Configuración de la base de datos',
        'settings'          => 'Paso 3/3: Datos de la empresa y del administrador',
    ],

    'language' => [
        'select'            => 'Seleccione el idioma',
    ],

    'requirements' => [
        'enabled'           => ':feature debe estar habilitado.',
        'disabled'          => ':feature debe estar deshabilitado.',
        'extension'         => 'La extensión :extension debe estar instalada y cargada.',
        'directory'         => 'El directorio :directory debe tener permisos de escritura.',
        'executable'        => 'El ejecutable de PHP CLI no está definido, no funciona o su versión es anterior a :php_version. Pida a su proveedor de alojamiento que configure correctamente la variable de entorno PHP_BINARY o PHP_PATH.',
        'npm'               => '<b>¡Faltan archivos de JavaScript!</b> <br><br><span>Debería ejecutar los comandos <em class="underline">npm install</em> y <em class="underline">npm run dev</em>.</span>',
    ],

    'database' => [
        'hostname'          => 'Nombre del servidor',
        'username'          => 'Nombre de usuario',
        'password'          => 'Contraseña',
        'name'              => 'Base de datos',
    ],

    'settings' => [
        'company_name'      => 'Nombre de la empresa',
        'company_email'     => 'Correo electrónico de la empresa',
        'admin_email'       => 'Correo electrónico del administrador',
        'admin_password'    => 'Contraseña del administrador',
    ],

    'error' => [
        'php_version'       => 'Error: pida a su proveedor de alojamiento que utilice PHP :php_version o una versión posterior tanto para HTTP como para CLI.',
        'connection'        => 'Error: no se ha podido conectar a la base de datos. Asegúrese de que los datos sean correctos.',
    ],

    'update' => [
        'core'              => 'Hay una nueva versión de Akaunting disponible. <a href=":url">Actualice su instalación.</a>',
        'module'            => 'Hay una nueva versión de :module disponible. <a href=":url">Actualice su instalación.</a>',
    ],
];
