<?php

return [

    'company' => [
        'description'       => 'Cambiar el nombre de la empresa, correo electrónico, dirección, número de impuestos, etc',
        'name'              => 'Nombre',
        'email'             => 'Correo electrónico',
        'phone'             => 'Teléfono',
        'address'           => 'Dirección',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Establecer año fiscal, zona horaria, formato de fecha y más locales',
        'financial_start'   => 'Comienzo año Fiscal',
        'timezone'          => 'Zona horaria',
        'date' => [
            'format'        => 'Formato de Fecha',
            'separator'     => 'Separador de fecha',
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
            'name'          => 'Ubicación de descuento',
            'item'          => 'En línea',
            'total'         => 'En total',
            'both'          => 'Línea y total',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalizar prefijo de factura, número, términos, pie de página etc',
        'prefix'            => 'Prefijo de número',
        'digit'             => 'Número de dígitos',
        'next'              => 'Siguiente número',
        'logo'              => 'Logo',
        'custom'            => 'Personalizado',
        'item_name'         => 'Nombre del ítem',
        'item'              => 'Items',
        'product'           => 'Productos',
        'service'           => 'Servicios',
        'price_name'        => 'Nombre de precio',
        'price'             => 'Precio',
        'rate'              => 'Tasa',
        'quantity_name'     => 'Cantidad nombre',
        'quantity'          => 'Cantidad',
        'payment_terms'     => 'Condiciones de pago',
        'title'             => 'Título',
        'subheading'        => 'Subtítulo',
        'due_receipt'       => 'Vence a la recepción',
        'due_days'          => 'Vencimiento dentro de :days días',
        'choose_template'   => 'Elegir plantilla de factura',
        'default'           => 'Por defecto',
        'classic'           => 'Clásica',
        'modern'            => 'Moderna',
    ],

    'default' => [
        'description'       => 'Cuenta, moneda, idioma por defecto de su empresa',
        'list_limit'        => 'Registros Por Página',
        'use_gravatar'      => 'Usar Gravatar',
    ],

    'email' => [
        'description'       => 'Cambiar las plantillas de protocolo de envío y correo electrónico',
        'protocol'          => 'Protocolo',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'Puerto SMTP',
            'username'      => 'Nombre de usuario SMTP',
            'password'      => 'Contraseña SMTP',
            'encryption'    => 'Seguridad SMTP',
            'none'          => 'Ninguna',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Ruta de acceso de sendmail',
        'log'               => 'Registrar Correos',

        'templates' => [
            'subject'                   => 'Asunto',
            'body'                      => 'Cuerpo',
            'tags'                      => '<strong>Etiquetas disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Nueva Plantilla de Factura (enviada al cliente)',
            'invoice_remind_customer'   => 'Plantilla de Recordatorio de Factura (enviada al cliente)',
            'invoice_remind_admin'      => 'Plantilla de Recordatorio de Factura (enviado al administrador)',
            'invoice_recur_customer'    => 'Plantilla de Factura Recurrente (enviada al cliente)',
            'invoice_recur_admin'       => 'Plantilla de Factura Recurrente (enviada al administrador)',
            'invoice_payment_customer'  => 'Plantilla de Pago Recibido (enviada al cliente)',
            'invoice_payment_admin'     => 'Plantilla de Pago Recibido (enviada al administrador)',
            'bill_remind_admin'         => 'Plantilla de Recordatorio de Factura (enviada a administrador)',
            'bill_recur_admin'          => 'Plantilla de Factura Recurrente (enviada al administrador)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Programación',
        'description'       => 'Recordatorios y comandos automáticos para repetir',
        'send_invoice'      => 'Enviar Recordatorio de Factura',
        'invoice_days'      => 'Enviar después del vencimiento',
        'send_bill'         => 'Enviar Recordatorio de Recibo',
        'bill_days'         => 'Enviar Antes del Vencimiento',
        'cron_command'      => 'Comando Cron',
        'schedule_time'     => 'Hora de ejecución',
    ],

    'categories' => [
        'description'       => 'Categorías ilimitadas para ingresos, gastos e items',
    ],

    'currencies' => [
        'description'       => 'Crear y administrar monedas y establecer sus tasas',
    ],

    'taxes' => [
        'description'       => 'Tasas de impuestos fijas, normales, inclusivas y compuestas',
    ],

];
