<?php

return [

    'next'                  => 'Siguiente',
    'refresh'               => 'Actualizar',

    'steps' => [
        'requirements'      => 'Por favor, pregúntele a su proveedor de hosting para corregir los errores!',
        'language'          => 'Paso 1/3: Selección de idioma',
        'database'          => 'Paso 2/3: Configuración de la base de datos',
        'settings'          => 'Paso 3/3: Detalles de la Empresa y el Administrador',
    ],

    'language' => [
        'select'            => 'Seleccione el idioma',
    ],

    'requirements' => [
        'enabled'           => ':feature debe estar habilitado!',
        'disabled'          => ':feature debe estar deshabilitado!',
        'extension'         => 'La extensión :extension necesita ser instalada y cargada!',
        'directory'         => 'El directorio :directorio necesita tener permiso de escritura!',
    ],

    'database' => [
        'hostname'          => 'Nombre del servidor',
        'username'          => 'Nombre de usuario',
        'password'          => 'Contraseña',
        'name'              => 'Base de datos',
    ],

    'settings' => [
        'company_name'      => 'Nombre de la empresa',
        'company_email'     => 'Correo electrónico de la Empresa',
        'admin_email'       => 'Correo electrónico del Administrador',
        'admin_password'    => 'Contraseña de Administrador',
    ],

    'error' => [
        'connection'        => 'Error: No se pudo conectar a la base de datos! Por favor, asegúrese de que los datos son correctos.',
    ],

];
