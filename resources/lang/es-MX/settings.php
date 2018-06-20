<?php

return [

    'company' => [
        'name'              => 'Nombre',
        'email'             => 'Correo Electrónico',
        'phone'             => 'Teléfono',
        'address'           => 'Dirección',
        'logo'              => 'Logotipo',
    ],
    'localisation' => [
        'tab'               => 'Localización',
        'date' => [
            'format'        => 'Formato de Fecha',
            'separator'     => 'Separador de Fecha',
            'dash'          => 'Guión (-)',
            'dot'           => 'Punto (.)',
            'comma'         => 'Coma (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espacio ( )',
        ],
        'timezone'          => 'Zona Horaria',
        'percent' => [
            'title'         => 'Posición Porcentaje (%)',
            'before'        => 'Antes del Número',
            'after'         => 'Después del número',
        ],
    ],
    'invoice' => [
        'tab'               => 'Factura',
        'prefix'            => 'Prefijo de Número',
        'digit'             => 'Número de Dígitos',
        'next'              => 'Siguiente Número',
        'logo'              => 'Logotipo',
    ],
    'default' => [
        'tab'               => 'Valores Predeterminados',
        'account'           => 'Cuenta Predeterminada',
        'currency'          => 'Moneda Predeterminada',
        'tax'               => 'Tasa de Impuesto Predeterminado',
        'payment'           => 'Método de Pago Predeterminado',
        'language'          => 'Idioma Predeterminado',
    ],
    'email' => [
        'protocol'          => 'Protocolo',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Host SMTP',
            'port'          => 'Puerto SMTP',
            'username'      => 'Nombre de Usuario SMTP',
            'password'      => 'Contraseña SMTP',
            'encryption'    => 'Seguridad SMTP',
            'none'          => 'Ninguna',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Ruta de acceso de Sendmail',
        'log'               => 'Registrar Correos Electrónicos',
    ],
    'scheduling' => [
        'tab'               => 'Programación',
        'send_invoice'      => 'Enviar Recordatorio de Factura',
        'invoice_days'      => 'Enviar Después del vencimiento',
        'send_bill'         => 'Enviar Recordatorio de Recibo',
        'bill_days'         => 'Enviar Antes del Vencimiento',
        'cron_command'      => 'Comando de Cron',
        'schedule_time'     => 'Hora de Ejecución',
    ],
    'appearance' => [
        'tab'               => 'Apariencia',
        'theme'             => 'Tema',
        'light'             => 'Claro',
        'dark'              => 'Oscuro',
        'list_limit'        => 'Registros por Página',
        'use_gravatar'      => 'Usar Gravatar',
    ],
    'system' => [
        'tab'               => 'Sistema',
        'session' => [
            'lifetime'      => 'Duración de la Sesión (Minutos)',
            'handler'       => 'Gestor de Sesión',
            'file'          => 'Archivo',
            'database'      => 'Base de Datos',
        ],
        'file_size'         => 'Tamaño Máximo de Archivo (MB)',
        'file_types'        => 'Tipos de Archivo Permitidos',
    ],

];
