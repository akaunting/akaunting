<?php

return [

    'company' => [
        'description'                => 'Cambiar el nombre de la empresa, correo electrónico, dirección, CUIT, etc',
        'name'                       => 'Nombre',
        'email'                      => 'Correo electrónico',
        'phone'                      => 'Teléfono',
        'address'                    => 'Dirección',
        'edit_your_business_address' => 'Editar la dirección de su empresa',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Establecer año fiscal, zona horaria, formato de fecha y más locales',
        'financial_start'   => 'Inicio del ejercicio financiero',
        'timezone'          => 'Zona horaria',
        'financial_denote' => [
            'title'         => 'Año fiscal indicado',
            'begins'        => 'Por el año en que comienza',
            'ends'          => 'Por el año en que termina',
        ],
        'date' => [
            'format'        => 'Formato de fecha',
            'separator'     => 'Separador de fecha',
            'dash'          => 'Guión (-)',
            'dot'           => 'Punto (.)',
            'comma'         => 'Coma (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espacio ( )',
        ],
        'percent' => [
            'title'         => 'Posición Porcentaje (%)',
            'before'        => 'Antes del número',
            'after'         => 'Después del número',
        ],
        'discount_location' => [
            'name'          => 'Ubicación del descuento',
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
        'quantity_name'     => 'Nombre de cantidad',
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
        'hide'              => [
            'item_name'         => 'Ocultar nombre del artículo',
            'item_description'  => 'Ocultar descripción del artículo',
            'quantity'          => 'Ocultar cantidad',
            'price'             => 'Ocultar precio',
            'amount'            => 'Ocultar monto',
        ],
    ],

    'default' => [
        'description'       => 'Cuenta, moneda, idioma por defecto de su empresa',
        'list_limit'        => 'Registros por página',
        'use_gravatar'      => 'Usar Gravatar',
        'income_category'   => 'Categoría de ingresos',
        'expense_category'  => 'Categoría de gastos',
    ],

    'email' => [
        'description'       => 'Cambiar el protocolo de envío y plantillas de correo electrónico',
        'protocol'          => 'Protocolo',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Servidor SMTP',
            'port'          => 'Puerto SMTP',
            'username'      => 'Usuario SMTP',
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
            'invoice_new_customer'      => 'Nueva plantilla de factura (enviada al cliente)',
            'invoice_remind_customer'   => 'Plantilla de recordatorio de factura (enviado al cliente)',
            'invoice_remind_admin'      => 'Plantilla de recordatorio de factura (enviada al administrador)',
            'invoice_recur_customer'    => 'Plantilla recurrente de factura (enviada al cliente)',
            'invoice_recur_admin'       => 'Plantilla de factura recurrente (enviada al administrador)',
            'invoice_payment_customer'  => 'Plantilla de pago recibido (enviada al cliente)',
            'invoice_payment_admin'     => 'Plantilla de pago recibido (enviada al administrador)',
            'bill_remind_admin'         => 'Plantilla de recordatorio de factura (enviada a administrador)',
            'bill_recur_admin'          => 'Plantilla de factura recurrente (enviada al administrador)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Programación',
        'description'       => 'Recordatorios y comandos automáticos para repetir',
        'send_invoice'      => 'Enviar recordatorio de factura',
        'invoice_days'      => 'Enviar después del vencimiento',
        'send_bill'         => 'Enviar recordatorio de factura',
        'bill_days'         => 'Enviar antes del vencimiento',
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
