<?php

return [

    'company' => [
        'description'                   => 'Cambiar el nombre de la empresa, correo electrónico, dirección, número de impuestos, etc',
        'search_keywords'               => 'empresa, nombre, correo electrónico, teléfono, dirección, país, identificación fiscal, logotipo, ciudad, pueblo, estado, provincia, código postal',
        'name'                          => 'Nombre',
        'email'                         => 'Correo electrónico',
        'phone'                         => 'Teléfono',
        'address'                       => 'Dirección',
        'edit_your_business_address'    => 'Editar la dirección de tu negocio',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Esta información es visible en los registros que crea.',
            'address'                   => 'La dirección se utilizará en las facturas, cuentas y otros registros que emita.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Establecer año fiscal, zona horaria, formato de fecha y más locales',
        'search_keywords'               => 'financiero, año, comienzo, denotar, hora, zona, fecha, formato, separador, descuento, porcentaje',
        'financial_start'               => 'Comienzo año Fiscal',
        'timezone'                      => 'Zona horaria',
        'financial_denote' => [
            'title'                     => 'Denotar año financiero',
            'begins'                    => 'Para el año en que empieza',
            'ends'                      => 'Para el año en que termina',
        ],
        'preferred_date'                => 'Fecha preferida',
        'date' => [
            'format'                    => 'Formato de Fecha',
            'separator'                 => 'Separador de fecha',
            'dash'                      => 'Guión (-)',
            'dot'                       => 'Punto (.)',
            'comma'                     => 'Coma (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Espacio ( )',
        ],
        'percent' => [
            'title'                     => 'Posición Porcentaje (%)',
            'before'                    => 'Antes del Número',
            'after'                     => 'Después del número',
        ],
        'discount_location' => [
            'name'                      => 'Ubicación de descuento',
            'item'                      => 'En línea',
            'total'                     => 'En total',
            'both'                      => 'Línea y total',
        ],

        'form_description' => [
            'fiscal'                    => 'Establezca el período financiero que su empresa utiliza para gravar e informar.',
            'date'                      => 'Seleccione el formato de fecha que desea ver en todas partes de la interfaz.',
            'other'                     => 'Seleccione dónde desea mostrar el símbolo de porcentaje para los impuestos. Puede activar los descuentos para cada artículo en el total de las facturas.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizar prefijo de factura, número, términos, pie de página etc',
        'search_keywords'               => 'personalizar, factura, número, prefijo, dígito, siguiente, logotipo, nombre, precio, cantidad, plantilla, título, subtítulo, pie de página, nota, ocultar, vencimiento, color, pago, términos, columna',
        'prefix'                        => 'Prefijo de número',
        'digit'                         => 'Número de dígitos',
        'next'                          => 'Siguiente número',
        'logo'                          => 'Logo',
        'custom'                        => 'Personalizado',
        'item_name'                     => 'Nombre del ítem',
        'item'                          => 'Items',
        'product'                       => 'Productos',
        'service'                       => 'Servicios',
        'price_name'                    => 'Nombre de precio',
        'price'                         => 'Precio',
        'rate'                          => 'Tasa',
        'quantity_name'                 => 'Cantidad nombre',
        'quantity'                      => 'Cantidad',
        'payment_terms'                 => 'Condiciones de pago',
        'title'                         => 'Título',
        'subheading'                    => 'Subtítulo',
        'due_receipt'                   => 'Vence a la recepción',
        'due_days'                      => 'Vencimiento dentro de :days días',
        'choose_template'               => 'Elegir plantilla de factura',
        'default'                       => 'Por defecto',
        'classic'                       => 'Clásica',
        'modern'                        => 'Moderna',
        'hide' => [
            'item_name'                 => 'Ocultar el nombre del artículo',
            'item_description'          => 'Ocultar la descripción del artículo',
            'quantity'                  => 'Ocultar cantidad',
            'price'                     => 'Ocultar precio',
            'amount'                    => 'Ocultar total',
        ],
        'column'                        => 'Columna|Columnas',

        'form_description' => [
            'general'                   => 'Establecer los valores predeterminados para los números de factura y los términos de pago.',
            'template'                  => 'Seleccione una de las plantillas para las facturas.',
            'default'                   => 'Seleccionando los valores predeterminados para las facturas se pre-rellenarán títulos, subtítulos, notas y pie de página. Así que no necesita editar las facturas cada vez para parecer más profesional.',
            'column'                    => 'Personalice el nombre de las columnas de la factura. Si desea ocultar las descripciones de los artículos y las cantidades en las líneas, puede cambiarlo aquí.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Elija la plantilla de transferencia',
        'second'                        => 'Segundo',
        'third'                         => 'Tercero',
    ],

    'default' => [
        'description'                   => 'Cuenta, moneda, idioma por defecto de su empresa',
        'search_keywords'               => 'cuenta, moneda, idioma, impuesto, pago, método, paginación',
        'list_limit'                    => 'Registros Por Página',
        'use_gravatar'                  => 'Usar Gravatar',
        'income_category'               => 'Categoría de ingresos',
        'expense_category'              => 'Categoría de gastos',

        'form_description' => [
            'general'                   => 'Seleccione la cuenta por defecto, los impuestos y el método de pago para crear registros rápidamente. El panel de control y los informes se muestran bajo la moneda predeterminada.',
            'category'                  => 'Seleccione las categorías por defecto para acelerar la creación de registros.',
            'other'                     => 'Personalice la configuración predeterminada del idioma de la empresa y cómo funciona la paginación. ',
        ],
    ],

    'email' => [
        'description'                   => 'Cambiar las plantillas de protocolo de envío y correo electrónico',
        'search_keywords'               => 'correo electrónico, enviar, protocolo, smtp, servidor, contraseña',
        'protocol'                      => 'Protocolo',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP Host',
            'port'                      => 'Puerto SMTP',
            'username'                  => 'Nombre de usuario SMTP',
            'password'                  => 'Contraseña SMTP',
            'encryption'                => 'Seguridad SMTP',
            'none'                      => 'Ninguna',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Ruta de acceso de sendmail',
        'log'                           => 'Registrar Correos',
        'email_service'                 => 'Servicio de correo electrónico',
        'email_templates'               => 'Plantillas de correo electrónico',

        'form_description' => [
            'general'                   => 'Envía correos electrónicos regulares a tu equipo y contactos. Puedes establecer la configuración del protocolo y SMTP.',
        ],

        'templates' => [
            'description'               => 'Cambiar las plantillas de correo electrónico',
            'search_keywords'           => 'correo electrónico, plantilla, asunto, cuerpo, etiqueta',
            'subject'                   => 'Asunto',
            'body'                      => 'Cuerpo',
            'tags'                      => '<strong>Etiquetas disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Nueva Plantilla de Factura (enviada al cliente)',
            'invoice_remind_customer'   => 'Plantilla de Recordatorio de Factura (enviada al cliente)',
            'invoice_remind_admin'      => 'Plantilla de Recordatorio de Factura (enviado al administrador)',
            'invoice_recur_customer'    => 'Plantilla de Factura Recurrente (enviada al cliente)',
            'invoice_recur_admin'       => 'Plantilla de Factura Recurrente (enviada al administrador)',
            'invoice_view_admin'        => 'Plantilla de vista de factura (enviar a administrador)',
            'invoice_payment_customer'  => 'Plantilla de Pago Recibido (enviada al cliente)',
            'invoice_payment_admin'     => 'Plantilla de Pago Recibido (enviada al administrador)',
            'bill_remind_admin'         => 'Plantilla de Recordatorio de Factura (enviada a administrador)',
            'bill_recur_admin'          => 'Plantilla de Factura Recurrente (enviada al administrador)',
            'payment_received_customer' => 'Plantilla de recibo de pago (enviada a cliente)',
            'payment_made_vendor'       => 'Plantilla de pago realizado (enviada a proveedor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Programación',
        'description'                   => 'Recordatorios y comandos automáticos para repetir',
        'search_keywords'               => 'automático, recordatorio, recurrente, cron, comando',
        'send_invoice'                  => 'Enviar Recordatorio de Factura',
        'invoice_days'                  => 'Enviar después del vencimiento',
        'send_bill'                     => 'Enviar Recordatorio de Recibo',
        'bill_days'                     => 'Enviar Antes del Vencimiento',
        'cron_command'                  => 'Comando Cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Hora de ejecución',

        'form_description' => [
            'invoice'                   => 'Habilita o deshabilita y establece recordatorios para tus facturas cuando estén retrasadas.',
            'bill'                      => 'Habilita o deshabilita y establece recordatorios para tus facturas antes de que se retrasen.',
            'cron'                      => 'Copia el comando cron que tu servidor debe ejecutar. Establece el tiempo para activar el evento.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorías ilimitadas para ingresos, gastos e items',
        'search_keywords'               => 'categoría, ingreso, gasto, elemento',
    ],

    'currencies' => [
        'description'                   => 'Crear y administrar monedas y establecer sus tasas',
        'search_keywords'               => 'predeterminado, moneda, monedas, código, tasa, símbolo, precisión, posición, decimal, miles, marca, separador',
    ],

    'taxes' => [
        'description'                   => 'Tasas de impuestos fijas, normales, inclusivas y compuestas',
        'search_keywords'               => 'impuesto, tasa, tipo, fijo, incluido, compuesto, retención',
    ],

];
