<?php

return [

    'company' => [
        'description'                   => 'Cambiar el nombre, el correo electrónico, la dirección y el número de identificación fiscal de la empresa, entre otros datos',
        'search_keywords'               => 'empresa, nombre, correo electrónico, teléfono, dirección, país, identificación fiscal, logotipo, ciudad, localidad, estado, provincia, código postal',
        'name'                          => 'Nombre',
        'email'                         => 'Correo electrónico',
        'phone'                         => 'Teléfono',
        'address'                       => 'Dirección',
        'edit_your_business_address'    => 'Editar la dirección de su negocio',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Esta información es visible en los registros que crea.',
            'address'                   => 'La dirección se utilizará en las facturas de venta, las facturas de compra y otros registros que emita.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Configurar el ejercicio fiscal, la zona horaria, el formato de fecha y otras opciones regionales',
        'search_keywords'               => 'fiscal, ejercicio, año, inicio, identificación, hora, zona, fecha, formato, separador, descuento, porcentaje',
        'financial_start'               => 'Inicio del ejercicio fiscal',
        'timezone'                      => 'Zona horaria',
        'financial_denote' => [
            'title'                     => 'Identificación del ejercicio fiscal',
            'begins'                    => 'Por el año en que comienza',
            'ends'                      => 'Por el año en que termina',
        ],
        'preferred_date'                => 'Fecha preferida',
        'date' => [
            'format'                    => 'Formato de fecha',
            'separator'                 => 'Separador de fecha',
            'dash'                      => 'Guion (-)',
            'dot'                       => 'Punto (.)',
            'comma'                     => 'Coma (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Espacio ( )',
        ],
        'percent' => [
            'title'                     => 'Posición del porcentaje (%)',
            'before'                    => 'Antes del número',
            'after'                     => 'Después del número',
        ],
        'discount_location' => [
            'name'                      => 'Ubicación del descuento',
            'item'                      => 'En cada línea',
            'total'                     => 'En el total',
            'both'                      => 'En cada línea y en el total',
        ],

        'form_description' => [
            'fiscal'                    => 'Configure el periodo del ejercicio fiscal que utiliza su empresa para los impuestos y los informes.',
            'date'                      => 'Seleccione el formato de fecha que desea ver en todas partes de la interfaz.',
            'other'                     => 'Seleccione dónde se muestra el símbolo de porcentaje de los impuestos. Puede habilitar descuentos por línea y sobre el total de las facturas de venta y de compra.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizar el prefijo, la numeración, las condiciones y el pie de página de las facturas, entre otros datos',
        'search_keywords'               => 'personalizar, factura, número, prefijo, dígito, siguiente, logotipo, nombre, precio, cantidad, plantilla, título, subtítulo, pie de página, nota, ocultar, vencimiento, color, pago, condiciones, columna',
        'prefix'                        => 'Prefijo de número',
        'digit'                         => 'Número de dígitos',
        'next'                          => 'Siguiente número',
        'logo'                          => 'Logo',
        'custom'                        => 'Personalizado',
        'item_name'                     => 'Nombre del artículo',
        'item'                          => 'Artículos',
        'product'                       => 'Productos',
        'service'                       => 'Servicios',
        'price_name'                    => 'Nombre del precio',
        'price'                         => 'Precio',
        'rate'                          => 'Tarifa',
        'quantity_name'                 => 'Nombre de la cantidad',
        'quantity'                      => 'Cantidad',
        'payment_terms'                 => 'Condiciones de pago',
        'title'                         => 'Título',
        'subheading'                    => 'Subtítulo',
        'due_receipt'                   => 'Pago al recibirla',
        'due_days'                      => 'Vencimiento dentro de :days días',
        'due_custom'                    => 'Días personalizados',
        'due_custom_day'                => 'días después',
        'choose_template'               => 'Elegir plantilla de factura',
        'default'                       => 'Predeterminada',
        'classic'                       => 'Clásica',
        'modern'                        => 'Moderna',
        'logo_size_width'               => 'Ancho del logotipo',
        'logo_size_height'              => 'Alto del logotipo',
        'hide' => [
            'item_name'                 => 'Ocultar el nombre del artículo',
            'item_description'          => 'Ocultar la descripción del artículo',
            'quantity'                  => 'Ocultar cantidad',
            'price'                     => 'Ocultar precio',
            'amount'                    => 'Ocultar importe',
        ],
        'column'                        => 'Columna|Columnas',

        'form_description' => [
            'general'                   => 'Establezca los valores predeterminados del formato de los números de factura y de las condiciones de pago.',
            'template'                  => 'Seleccione una de las siguientes plantillas para sus facturas.',
            'default'                   => 'Los valores predeterminados rellenan automáticamente los títulos, subtítulos, notas y pies de página. Así no tendrá que editar cada factura para que tenga un aspecto profesional.',
            'column'                    => 'Personalice el nombre de las columnas de la factura. Aquí también puede ocultar las descripciones y los importes de las líneas.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Elija la plantilla de transferencia',
        'second'                        => 'Segunda',
        'third'                         => 'Tercera',
    ],

    'default' => [
        'description'                   => 'Cuenta, moneda e idioma predeterminados de su empresa',
        'search_keywords'               => 'cuenta, moneda, idioma, impuesto, pago, método, paginación',
        'list_limit'                    => 'Registros por página',
        'use_gravatar'                  => 'Usar Gravatar',
        'income_category'               => 'Categoría de ingresos',
        'expense_category'              => 'Categoría de gastos',
        'address_format'                => 'Formato de dirección',
        'address_tags'                  => '<strong>Etiquetas disponibles:</strong> :tags',

        'form_description' => [
            'general'                   => 'Seleccione la cuenta, el impuesto y el método de pago predeterminados para crear registros con rapidez. El panel y los informes se muestran en la moneda predeterminada.',
            'category'                  => 'Seleccione las categorías predeterminadas para agilizar la creación de registros.',
            'other'                     => 'Personalice el idioma predeterminado de la empresa y el funcionamiento de la paginación.',
        ],
    ],

    'email' => [
        'description'                   => 'Cambiar el protocolo de envío',
        'search_keywords'               => 'correo electrónico, enviar, protocolo, smtp, servidor, contraseña',
        'protocol'                      => 'Protocolo',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Servidor SMTP',
            'port'                      => 'Puerto SMTP',
            'username'                  => 'Nombre de usuario SMTP',
            'password'                  => 'Contraseña SMTP',
            'encryption'                => 'Seguridad SMTP',
            'none'                      => 'Ninguno',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Ruta de Sendmail',
        'log'                           => 'Registrar correos electrónicos',
        'email_service'                 => 'Servicio de correo electrónico',
        'email_templates'               => 'Plantillas de correo electrónico',

        'form_description' => [
            'general'                   => 'Envíe correos electrónicos a su equipo y sus contactos. Puede configurar el protocolo y los parámetros de SMTP.',
        ],

        'templates' => [
            'description'               => 'Cambiar las plantillas de correo electrónico',
            'search_keywords'           => 'correo electrónico, plantilla, asunto, cuerpo, etiqueta',
            'subject'                   => 'Asunto',
            'body'                      => 'Cuerpo',
            'tags'                      => '<strong>Etiquetas disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Plantilla de factura nueva (enviada al cliente)',
            'invoice_remind_customer'   => 'Plantilla de recordatorio de factura (enviada al cliente)',
            'invoice_remind_admin'      => 'Plantilla de recordatorio de factura (enviada al administrador)',
            'invoice_recur_customer'    => 'Plantilla de factura recurrente (enviada al cliente)',
            'invoice_recur_admin'       => 'Plantilla de factura recurrente (enviada al administrador)',
            'invoice_view_admin'        => 'Plantilla de consulta de factura (enviada al administrador)',
            'invoice_payment_customer'  => 'Plantilla de recibo de pago de factura (enviada al cliente)',
            'invoice_payment_admin'     => 'Plantilla de pago de factura recibido (enviada al administrador)',
            'bill_remind_admin'         => 'Plantilla de recordatorio de factura de compra (enviada al administrador)',
            'bill_recur_admin'          => 'Plantilla de factura de compra recurrente (enviada al administrador)',
            'payment_received_customer' => 'Plantilla de recibo de pago (enviada al cliente)',
            'payment_made_vendor'       => 'Plantilla de pago realizado (enviada al proveedor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Programación',
        'description'                   => 'Recordatorios automáticos y comando para elementos recurrentes',
        'search_keywords'               => 'automático, recordatorio, recurrente, cron, comando',
        'send_invoice'                  => 'Enviar recordatorio de factura',
        'invoice_days'                  => 'Días después del vencimiento',
        'send_bill'                     => 'Enviar recordatorio de factura de compra',
        'bill_days'                     => 'Días antes del vencimiento',
        'cron_command'                  => 'Comando cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Hora de ejecución',

        'form_description' => [
            'invoice'                   => 'Habilite o deshabilite los recordatorios y defina cuándo enviarlos si las facturas están vencidas.',
            'bill'                      => 'Habilite o deshabilite los recordatorios y defina cuándo enviarlos antes de que venzan las facturas de compra.',
            'cron'                      => 'Copie el comando cron que debe ejecutar el servidor. Establezca la hora a la que se activará el evento.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorías ilimitadas para ingresos, gastos y artículos',
        'search_keywords'               => 'categoría, ingreso, gasto, artículo',
    ],

    'currencies' => [
        'description'                   => 'Crear y gestionar monedas y establecer sus tipos de cambio',
        'search_keywords'               => 'predeterminado, moneda, monedas, código, tipo de cambio, símbolo, precisión, posición, decimal, miles, marca, separador',
    ],

    'taxes' => [
        'description'                   => 'Tipos impositivos fijos, normales, incluidos y compuestos',
        'search_keywords'               => 'impuesto, tipo impositivo, tipo, fijo, incluido, compuesto, retención',
    ],

];
