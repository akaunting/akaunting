<?php

return [

    'next'                  => 'Siguiente',
    'refresh'               => 'Actualizar',

    'steps' => [
        'requirements'      => 'Por favor, pregúntele a su proveedor de hosting para corregir los errores!',
        'language'          => 'Paso 1/3: Selección del Idioma',
        'database'          => 'Paso 2/3: Configuración de la Base de Datos',
        'settings'          => 'Paso 3/3: Detalles de la Empresa y el Administrador',
    ],

    'language' => [
        'select'            => 'Seleccionar el Idioma',
    ],

    'requirements' => [
        'enabled'           => '¡:feature nencesita estar habilitado!',
        'disabled'          => '¡:feature debe estar deshabilitado!',
        'extension'         => 'La extensión :extension necesita ser instalada y cargada!',
        'directory'         => '¡El directorio :directorio necesita tener permiso de escritura!',
        'executable'        => 'El archivo ejecutable CLI de PHP no funciona! Por favor, pida a su compañía de hosting que configure correctamente la variable de entorno PHP_BINARY o PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Nombre del Host',
        'username'          => 'Nombre de Usuario',
        'password'          => 'Contraseña',
        'name'              => 'Base de Datos',
    ],

    'settings' => [
        'company_name'      => 'Nombre de la Empresa',
        'company_email'     => 'Dirección de Correo Electrónico de la Empresa',
        'admin_email'       => 'Dirección de Correo Electrónico del Administrador',
        'admin_password'    => 'Contraseña del Administrador',
    ],

    'error' => [
        'connection'        => 'Error: ¡No se pudo conectar a la base de datos! Por favor, asegúrese de que los datos son correctos.',
    ],

];
