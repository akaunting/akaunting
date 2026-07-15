<?php

return [

    'auth'                  => 'Autenticación',
    'profile'               => 'Perfil',
    'logout'                => 'Cerrar sesión',
    'login'                 => 'Iniciar sesión',
    'forgot'                => 'Olvidé',
    'login_to'              => 'Inicie sesión para comenzar',
    'remember_me'           => 'Recuérdeme',
    'forgot_password'       => 'Olvidé mi contraseña',
    'reset_password'        => 'Restablecer contraseña',
    'change_password'       => 'Cambiar contraseña',
    'enter_email'           => 'Introduzca su dirección de correo electrónico',
    'current_email'         => 'Correo electrónico actual',
    'reset'                 => 'Restablecer',
    'never'                 => 'nunca',
    'landing_page'          => 'Página de inicio',
    'personal_information'  => 'Información personal',
    'register_user'         => 'Registrar usuario',
    'register'              => 'Registrar',

    'form_description' => [
        'personal'          => 'El enlace de invitación se enviará al nuevo usuario, así que asegúrese de que la dirección de correo electrónico sea correcta. Podrán introducir su contraseña.',
        'assign'            => 'El usuario tendrá acceso a las empresas seleccionadas. Puede restringir los permisos desde la página de <a href=":url" class="border-b border-black">roles</a>.',
        'preferences'       => 'Seleccione el idioma predeterminado del usuario. También puede establecer la página de destino después de que el usuario inicie sesión.',
    ],

    'password' => [
        'pass'              => 'Contraseña',
        'pass_confirm'      => 'Confirmación de contraseña',
        'current'           => 'Contraseña actual',
        'current_confirm'   => 'Confirmación de contraseña actual',
        'new'               => 'Nueva contraseña',
        'new_confirm'       => 'Confirmación de nueva contraseña',
    ],

    'error' => [
        'self_delete'       => 'Error: No puede eliminarse a usted mismo!',
        'self_disable'      => 'Error: No puede desactivarse a usted mismo!',
        'unassigned'        => 'Error: No se puede desasignar la empresa! La empresa :company debe tener asignado al menos un usuario.',
        'no_company'        => 'Error: No hay empresas asignadas a su cuenta. Por favor, contacte al administrador del sistema.',
    ],

    'login_redirect'        => '¡Verificación realizada! Está siendo redirigido...',
    'failed'                => 'Estas credenciales no coinciden con nuestros registros.',
    'throttle'              => 'Demasiados intentos de inicio de sesión. Por favor, inténtelo de nuevo en :seconds segundos.',
    'disabled'              => 'Esta cuenta está deshabilitada. Por favor, contacte al administrador del sistema.',

    'notification' => [
        'message_1'         => 'Ha recibido este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para su cuenta.',
        'message_2'         => 'Si no solicitó un restablecimiento de contraseña, no se requiere ninguna acción adicional.',
        'button'            => 'Restablecer contraseña',
    ],

    'invitation' => [
        'message_1'         => 'Ha recibido este correo electrónico porque está invitado a unirse a Akaunting.',
        'message_2'         => 'Si no desea unirse, no se requiere ninguna acción adicional.',
        'button'            => 'Comenzar',
    ],

    'information' => [
        'invoice'           => 'Cree facturas fácilmente',
        'reports'           => 'Obtenga reportes detallados',
        'expense'           => 'Rastree cualquier gasto',
        'customize'         => 'Personalice su Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrador',
            'description'   => 'Obtendrán acceso total a Akaunting, incluyendo clientes, facturas, reportes, configuraciones y aplicaciones.',
        ],
        'manager' => [
            'name'          => 'Gerente',
            'description'   => 'Obtendrán acceso total a Akaunting, pero no pueden administrar usuarios ni aplicaciones.',
        ],
        'customer' => [
            'name'          => 'Cliente',
            'description'   => 'Pueden acceder al Portal del Cliente y pagar sus facturas en línea a través de los métodos de pago que configure.',
        ],
        'accountant' => [
            'name'          => 'Contador',
            'description'   => 'Pueden acceder a facturas, transacciones, reportes y crear asientos contables.',
        ],
        'employee' => [
            'name'          => 'Empleado',
            'description'   => 'Pueden crear reclamaciones de gastos y rastrear el tiempo para los proyectos asignados, pero solo pueden ver su propia información.',
        ],
    ],

];
