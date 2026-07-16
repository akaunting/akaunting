<?php

return [

    'next'                  => 'Siguiente',
    'refresh'               => 'Actualizar',

    'steps' => [
        'requirements'      => 'Por favor, pida a su proveedor de alojamiento que corrija los errores!',
        'language'          => 'Paso 1/3: Selección de idioma',
        'database'          => 'Paso 2/3: Configuración de la base de datos',
        'settings'          => 'Paso 3/3: Detalles de la empresa y del administrador',
    ],

    'language' => [
        'select'            => 'Seleccione el idioma',
    ],

    'requirements' => [
        'enabled'           => '¡:feature debe estar habilitado!',
        'disabled'          => '¡:feature debe estar deshabilitado!',
        'extension'         => 'La extensión :extension necesita ser instalada y cargada!',
        'directory'         => '¡El directorio :directory necesita permiso de escritura!',
        'executable'        => '¡El archivo ejecutable PHP CLI no está definido/funcionando o su versión no es :php_version o superior! Por favor, pida a su proveedor de alojamiento que configure correctamente la variable de entorno PHP_BINARY o PHP_PATH.',
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
        'php_version'       => 'Error: Pida a su proveedor de alojamiento que use PHP :php_version o superior tanto para HTTP como para CLI.',
        'connection'        => 'Error: No se pudo conectar a la base de datos! Por favor, asegúrese de que los datos son correctos.',
    ],

    'update' => [
        'core'              => '¡Una nueva versión de Akaunting está disponible! Por favor, actualice <a href=":url">su instalación.</a>',
        'module'            => '¡Una nueva versión de :module está disponible! Por favor, actualice <a href=":url">su instalación.</a>',
    ],
];
