<?php

return [

    'company' => [
        'description'       => 'Cambiar el nombre de la empresa, correo electrónico, dirección, RFC, etc',
        'name'              => 'Nombre',
        'email'             => 'Correo Electrónico',
        'phone'             => 'Teléfono',
        'address'           => 'Dirección',
        'logo'              => 'Logotipo',
    ],

    'localisation' => [
        'description'       => 'Establecer año fiscal, zona horaria, formato de fecha y más configuraciones locales',
        'financial_start'   => 'Inicio del ejercicio financiero',
        'timezone'          => 'Zona Horaria',
        'date' => [
            'format'        => 'Formato de Fecha',
            'separator'     => 'Separador de Fecha',
            'dash'          => 'Guión (-)',
            'dot'           => 'Punto (.)',
            'comma'         => 'Coma (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espacio ( )',
        ],
        'percent' => [
            'title'         => 'Posición Porcentaje (%)',
            'before'        => 'Antes del Número',
            'after'         => 'Después del número',
        ],
        'discount_location' => [
            'name'          => 'Ubicación del descuento',
            'item'          => 'En línea',
            'total'         => 'En total',
            'both'          => 'Tanto línea como total',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalizar prefijo de factura, número, términos, pie de página etc',
        'prefix'            => 'Prefijo de Número',
        'digit'             => 'Número de Dígitos',
        'next'              => 'Siguiente Número',
        'logo'              => 'Logotipo',
        'custom'            => 'Personalizado',
        'item_name'         => 'Nombre del elemento',
        'item'              => 'Elementos',
        'product'           => 'Productos',
        'service'           => 'Servicios',
        'price_name'        => 'Nombre del Precio',
        'price'             => 'Precio',
        'rate'              => 'Tasa',
        'quantity_name'     => 'Nombre de la Cantidad',
        'quantity'          => 'Cantidad',
        'payment_terms'     => 'Condiciones de pago',
        'title'             => 'Título',
        'subheading'        => 'Subtítulo',
        'due_receipt'       => 'Vence después de la recepción',
        'due_days'          => 'Vencimiento dentro de :days días',
        'choose_template'   => 'Elegir plantilla de factura',
        'default'           => 'Predeterminado',
        'classic'           => 'Clásica',
        'modern'            => 'Moderna',
    ],

    'default' => [
        'description'       => 'Cuenta predeterminada, moneda, idioma de su empresa',
        'list_limit'        => 'Registros Por Página',
        'use_gravatar'      => 'Usar Gravatar',
    ],

    'email' => [
        'description'       => 'Cambiar el protocolo de envío y plantillas de correo electrónico',
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

        'templates' => [
            'subject'                   => 'Asunto',
            'body'                      => 'Contenido',
            'tags'                      => '<strong>Etiquetas disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Nueva Plantilla de Factura (enviada al cliente)',
            'invoice_remind_customer'   => 'Plantilla de Recordatorio de Factura (enviada al cliente)',
            'invoice_remind_admin'      => 'Plantilla de Recordatorio de Factura (enviado al administrador)',
            'invoice_recur_customer'    => 'Plantilla de Factura Recurrente (enviada al cliente)',
            'invoice_recur_admin'       => 'Plantilla de Factura Recurrente (enviada al administrador)',
            'invoice_payment_customer'  => 'Plantilla de Pago Recibido (enviada al cliente)',
            'invoice_payment_admin'     => 'Plantilla de Pago Recibido (enviada al administrador)',
            'bill_remind_admin'         => 'Plantilla de Recordatorio de Factura por pagar (enviada a administrador)',
            'bill_recur_admin'          => 'Plantilla de Factura Recurrente por pagar (enviada al administrador)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Programación',
        'description'       => 'Recordatorios y comandos automáticos para recurrentes',
        'send_invoice'      => 'Enviar Recordatorio de Factura',
        'invoice_days'      => 'Enviar Después del vencimiento',
        'send_bill'         => 'Enviar Recordatorio de Recibo',
        'bill_days'         => 'Enviar Antes del Vencimiento',
        'cron_command'      => 'Comando de Cron',
        'schedule_time'     => 'Hora de Ejecución',
    ],

    'categories' => [
        'description'       => 'Categorías ilimitadas para ingresos, gastos y artículos',
    ],

    'currencies' => [
        'description'       => 'Crear, administrar monedas y establecer sus tarifas',
    ],

    'taxes' => [
        'description'       => 'Tasas de impuestos fijas, normales, inclusivas y compuestas',
    ],

];
