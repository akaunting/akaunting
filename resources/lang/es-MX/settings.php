<?php

return [

    'company' => [
        'description'                   => 'Cambiar el nombre de la empresa, correo electrónico, dirección, RFC, etc',
        'search_keywords'               => 'empresa, nombre, correo electrónico, teléfono, dirección, país, RFC, logotipo, ciudad, estado, código postal',
        'name'                          => 'Nombre',
        'email'                         => 'Correo electrónico',
        'phone'                         => 'Teléfono',
        'address'                       => 'Dirección',
        'edit_your_business_address'    => 'Edite la dirección de su empresa',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Esta información es visible en los registros que crea.',
            'address'                   => 'La dirección se utilizará en las facturas, facturas de compra y otros registros que emita.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Establecer año fiscal, zona horaria, formato de fecha y más configuraciones locales',
        'search_keywords'               => 'financiero, año, inicio, denominación, hora, zona, fecha, formato, separador, descuento, porcentaje',
        'financial_start'               => 'Inicio del año fiscal',
        'timezone'                      => 'Zona horaria',
        'financial_denote' => [
            'title'                     => 'Denominación del año fiscal',
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
            'name'                      => 'Ubicación de descuento',
            'item'                      => 'En línea',
            'total'                     => 'En total',
            'both'                      => 'Línea y total',
        ],

        'form_description' => [
            'fiscal'                    => 'Establezca el periodo del año fiscal que su empresa utiliza para impuestos y reportes.',
            'date'                      => 'Seleccione el formato de fecha que desea ver en toda la interfaz.',
            'other'                     => 'Seleccione dónde desea mostrar el símbolo de porcentaje para los impuestos. Puede activar los descuentos en artículos y en el total de las facturas.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizar prefijo de factura, número, condiciones, pie de página, etc',
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
        'price_name'                    => 'Nombre de precio',
        'price'                         => 'Precio',
        'rate'                          => 'Tasa',
        'quantity_name'                 => 'Nombre de cantidad',
        'quantity'                      => 'Cantidad',
        'payment_terms'                 => 'Condiciones de pago',
        'title'                         => 'Título',
        'subheading'                    => 'Subtítulo',
        'due_receipt'                   => 'Vence al recibir',
        'due_days'                      => 'Vence dentro de :days días',
        'due_custom'                    => 'Día(s) personalizado(s)',
        'due_custom_day'                => 'después del día',
        'choose_template'               => 'Elija la plantilla de factura',
        'default'                       => 'Predeterminado',
        'classic'                       => 'Clásica',
        'modern'                        => 'Moderna',
        'logo_size_width'               => 'Ancho del logotipo',
        'logo_size_height'              => 'Alto del logotipo',
        'hide' => [
            'item_name'                 => 'Ocultar el nombre del artículo',
            'item_description'          => 'Ocultar la descripción del artículo',
            'quantity'                  => 'Ocultar cantidad',
            'price'                     => 'Ocultar precio',
            'amount'                    => 'Ocultar monto',
        ],
        'column'                        => 'Columna|Columnas',

        'form_description' => [
            'general'                   => 'Establezca los valores predeterminados para el formato de los números de factura y las condiciones de pago.',
            'template'                  => 'Seleccione una de las plantillas para sus facturas.',
            'default'                   => 'Al seleccionar los valores predeterminados para las facturas, se rellenarán automáticamente los títulos, subtítulos, notas y pies de página. Así no necesita editar las facturas cada vez para verse más profesional.',
            'column'                    => 'Personalice el nombre de las columnas de la factura. Si desea ocultar las descripciones de los artículos y los montos en las líneas, puede cambiarlo aquí.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Elija la plantilla de transferencia',
        'second'                        => 'Segundo',
        'third'                         => 'Tercero',
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
            'general'                   => 'Seleccione la cuenta, los impuestos y el método de pago predeterminados para crear registros rápidamente. El tablero y los reportes se muestran en la moneda predeterminada.',
            'category'                  => 'Seleccione las categorías predeterminadas para agilizar la creación de registros.',
            'other'                     => 'Personalice la configuración predeterminada del idioma de la empresa y el funcionamiento de la paginación.',
        ],
    ],

    'email' => [
        'description'                   => 'Cambiar el protocolo de envío',
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
        'sendmail_path'                 => 'Ruta de Sendmail',
        'log'                           => 'Registrar correos',
        'email_service'                 => 'Servicio de correo electrónico',
        'email_templates'               => 'Plantillas de correo electrónico',

        'form_description' => [
            'general'                   => 'Envíe correos electrónicos periódicos a su equipo y contactos. Puede establecer el protocolo y la configuración SMTP.',
        ],

        'templates' => [
            'description'               => 'Cambiar las plantillas de correo electrónico',
            'search_keywords'           => 'correo electrónico, plantilla, asunto, cuerpo, etiqueta',
            'subject'                   => 'Asunto',
            'body'                      => 'Cuerpo',
            'tags'                      => '<strong>Etiquetas disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Plantilla de nueva factura (enviada al cliente)',
            'invoice_remind_customer'   => 'Plantilla de recordatorio de factura (enviada al cliente)',
            'invoice_remind_admin'      => 'Plantilla de recordatorio de factura (enviada al administrador)',
            'invoice_recur_customer'    => 'Plantilla de factura recurrente (enviada al cliente)',
            'invoice_recur_admin'       => 'Plantilla de factura recurrente (enviada al administrador)',
            'invoice_view_admin'        => 'Plantilla de factura vista (enviada al administrador)',
            'invoice_payment_customer'  => 'Plantilla de recibo de pago (enviada al cliente)',
            'invoice_payment_admin'     => 'Plantilla de pago recibido (enviada al administrador)',
            'bill_remind_admin'         => 'Plantilla de recordatorio de factura de compra (enviada al administrador)',
            'bill_recur_admin'          => 'Plantilla de factura de compra recurrente (enviada al administrador)',
            'payment_received_customer' => 'Plantilla de recibo de pago (enviada al cliente)',
            'payment_made_vendor'       => 'Plantilla de pago realizado (enviada al proveedor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Programación',
        'description'                   => 'Recordatorios automáticos y comando para recurrentes',
        'search_keywords'               => 'automático, recordatorio, recurrente, cron, comando',
        'send_invoice'                  => 'Enviar recordatorio de factura',
        'invoice_days'                  => 'Enviar después del vencimiento',
        'send_bill'                     => 'Enviar recordatorio de factura de compra',
        'bill_days'                     => 'Enviar antes del vencimiento',
        'cron_command'                  => 'Comando Cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Hora de ejecución',

        'form_description' => [
            'invoice'                   => 'Habilite o deshabilite y establezca recordatorios para sus facturas cuando estén vencidas.',
            'bill'                      => 'Habilite o deshabilite y establezca recordatorios para sus facturas de compra antes de que estén vencidas.',
            'cron'                      => 'Copie el comando cron que su servidor debe ejecutar. Establezca la hora para activar el evento.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorías ilimitadas para ingresos, gastos y artículos',
        'search_keywords'               => 'categoría, ingreso, gasto, artículo',
    ],

    'currencies' => [
        'description'                   => 'Crear y administrar monedas y establecer sus tasas',
        'search_keywords'               => 'predeterminado, moneda, monedas, código, tasa, símbolo, precisión, posición, decimal, miles, marca, separador',
    ],

    'taxes' => [
        'description'                   => 'Tasas de impuestos fijas, normales, inclusivas y compuestas',
        'search_keywords'               => 'impuesto, tasa, tipo, fijo, inclusivo, compuesto, retención',
    ],

];
