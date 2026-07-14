<?php

return [

    'auth'                  => 'Autenticación',
    'profile'               => 'Perfil',
    'logout'                => 'Salir',
    'login'                 => 'Iniciar sesión',
    'forgot'                => 'Olvidado',
    'login_to'              => 'Inicie sesión para comenzar',
    'remember_me'           => 'Recordarme',
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
        'personal'          => 'El enlace de invitación se enviará al nuevo usuario, así que asegúrese de que la dirección de correo electrónico es correcta. Podrán introducir su contraseña.',
        'assign'            => 'El usuario tendrá acceso a las empresas seleccionadas. Puede restringir los permisos usando <a href=":url" class="border-b border-black">roles</a>.',
        'preferences'       => 'Seleccione el idioma predeterminado del usuario. También puede establecer la página de inicio que se mostrará cuando inicie sesión.',
    ],

    'password' => [
        'pass'              => 'Contraseña',
        'pass_confirm'      => 'Confirmación de contraseña',
        'current'           => 'Contraseña actual',
        'current_confirm'   => 'Confirmación de la contraseña actual',
        'new'               => 'Nueva contraseña',
        'new_confirm'       => 'Confirmación de la contraseña nueva',
    ],

    'error' => [
        'self_delete'       => 'Error: no puede eliminar su propia cuenta.',
        'self_disable'      => 'Error: no puede deshabilitar su propia cuenta.',
        'unassigned'        => 'Error: no se puede desasignar la empresa. La empresa :company debe tener al menos un usuario asignado.',
        'no_company'        => 'Error: no hay ninguna empresa asignada a su cuenta. Póngase en contacto con el administrador del sistema.',
    ],

    'login_redirect'        => 'Verificación completada. Se le está redirigiendo...',
    'failed'                => 'Estas credenciales no coinciden con nuestros registros.',
    'throttle'              => 'Demasiados intentos de inicio de sesión. Inténtelo de nuevo dentro de :seconds segundos.',
    'disabled'              => 'Esta cuenta está deshabilitada. Póngase en contacto con el administrador del sistema.',

    'notification' => [
        'message_1'         => 'Ha recibido este correo porque hemos recibido una solicitud de recuperación de contraseña para su cuenta.',
        'message_2'         => 'Si no solicitó un restablecimiento de contraseña, no es necesaria ninguna acción de su parte.',
        'button'            => 'Restablecer contraseña',
    ],

    'invitation' => [
        'message_1'         => 'Ha recibido este correo electrónico porque se le ha invitado a unirse a Akaunting.',
        'message_2'         => 'Si no desea unirse, no se requiere ninguna acción adicional.',
        'button'            => 'Comenzar',
    ],

    'information' => [
        'invoice'           => 'Cree facturas fácilmente',
        'reports'           => 'Obtenga informes detallados',
        'expense'           => 'Controle todos sus gastos',
        'customize'         => 'Personalice su Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrador',
            'description'   => 'Tienen acceso completo a Akaunting, incluidos clientes, facturas, informes, configuración y aplicaciones.',
        ],
        'manager' => [
            'name'          => 'Gestor',
            'description'   => 'Tienen acceso completo a Akaunting, pero no pueden gestionar usuarios ni aplicaciones.',
        ],
        'customer' => [
            'name'          => 'Cliente',
            'description'   => 'Pueden acceder al portal del cliente y pagar sus facturas en línea mediante los métodos de pago configurados.',
        ],
        'accountant' => [
            'name'          => 'Contable',
            'description'   => 'Pueden acceder a facturas, transacciones e informes, y crear asientos contables.',
        ],
        'employee' => [
            'name'          => 'Empleado',
            'description'   => 'Pueden crear notas de gasto y registrar el tiempo de los proyectos asignados, pero solo pueden consultar su propia información.',
        ],
    ],

];
