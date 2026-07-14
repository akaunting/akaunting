<?php

return [

    'whoops'              => '¡Vaya!',
    'hello'               => '¡Hola!',
    'salutation'          => 'Saludos,<br> :company_name',
    'subcopy'             => 'Si tiene problemas para hacer clic en el botón «:text», copie y pegue la siguiente URL en su navegador: [:url](:url)',
    'mark_read'           => 'Marcar como leído',
    'mark_read_all'       => 'Marcar todos como leídos',
    'empty'               => '¡Vaya, cero notificaciones!',
    'new_apps'            => ':app ya está disponible. <a href=":url">¡Consúltela ahora!</a>',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Actualización fallida en :domain',
            'description'   => 'La actualización de :alias de :current_version a :new_version falló en el paso <strong>:step</strong> con el siguiente mensaje: :error_message',

        ],

        'slack' => [

            'description'   => 'Error al actualizar en :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'La descarga está lista',
            'description'   => 'El archivo está listo para descargar desde el siguiente enlace:',

        ],

        'failed' => [

            'title'         => 'Error en la descarga',
            'description'   => 'No se pudo crear el archivo debido al siguiente problema:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importación completada',
            'description'   => 'La importación se ha completado y los registros están disponibles en su panel.',

        ],

        'failed' => [

            'title'         => 'Importación fallida',
            'description'   => 'No se ha podido importar el archivo debido a los siguientes problemas:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'La exportación está lista',
            'description'   => 'El archivo de exportación está listo para descargar desde el siguiente enlace:',

        ],

        'failed' => [

            'title'         => 'Exportación fallida',
            'description'   => 'No se ha podido crear el archivo de exportación debido al siguiente problema:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Correo electrónico :type no válido',
            'description'   => 'Se ha notificado que la dirección de correo electrónico :email no es válida y se ha deshabilitado a la persona. Revise el siguiente mensaje de error y corrija la dirección:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'La descarga está lista',
            'description'   => 'Su archivo <strong>:type</strong> está listo para <a href=":url" target="_blank"><strong>descargar</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Error en la descarga',
            'description'   => 'No se ha podido crear el archivo debido a varios problemas. Consulte los detalles en su correo electrónico.',

        ],

        'export_completed' => [

            'title'         => 'La exportación está lista',
            'description'   => 'Su archivo de exportación de <strong>:type</strong> está listo para <a href=":url" target="_blank"><strong>descargarlo</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Exportación fallida',
            'description'   => 'No se ha podido crear el archivo de exportación debido a varios problemas. Consulte los detalles en su correo electrónico.',

        ],

        'import_completed' => [

            'title'         => 'Importación completada',
            'description'   => 'Se han importado correctamente <strong>:count</strong> registros de <strong>:type</strong>.',

        ],

        'import_failed' => [

            'title'         => 'Importación fallida',
            'description'   => 'No se ha podido importar el archivo debido a varios problemas. Consulte los detalles en su correo electrónico.',

        ],

        'new_apps' => [

            'title'         => 'Nueva aplicación',
            'description'   => 'La aplicación <strong>:name</strong> ya está disponible. Puede <a href=":url">hacer clic aquí</a> para consultar los detalles.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nueva factura',
            'description'   => 'Se ha creado la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para consultar los datos y realizar el pago.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Factura vencida',
            'description'   => 'La factura <strong>:invoice_number</strong> venció el <strong>:invoice_due_date</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para consultar los datos y realizar el pago.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Factura vencida',
            'description'   => 'La factura <strong>:invoice_number</strong> venció el <strong>:invoice_due_date</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nueva factura recurrente',
            'description'   => 'Se ha creado la factura <strong>:invoice_number</strong> según la periodicidad configurada. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para consultar los datos y realizar el pago.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nueva factura recurrente',
            'description'   => 'Se ha creado la factura <strong>:invoice_number</strong> según la periodicidad configurada para <strong>:customer_name</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Factura vista',
            'description'   => '<strong>:customer_name</strong> ha consultado la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pago recibido',
            'description'   => 'Gracias por pagar la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pago recibido',
            'description'   => 'Gracias por pagar la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pago recibido',
            'description'   => ':customer_name ha registrado un pago de la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Factura de compra vencida',
            'description'   => 'La factura de compra <strong>:bill_number</strong> venció el <strong>:bill_due_date</strong>. Puede <a href=":bill_admin_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nueva factura de compra recurrente',
            'description'   => 'Se ha creado la factura de compra <strong>:bill_number</strong> según la periodicidad configurada para <strong>:vendor_name</strong>. Puede <a href=":bill_admin_link">hacer clic aquí</a> para consultar los datos.',

        ],

        'invalid_email' => [

            'title'         => 'Correo electrónico :type no válido',
            'description'   => 'Se ha notificado que la dirección de correo electrónico <strong>:email</strong> no es válida y se ha deshabilitado a la persona. Revise y corrija la dirección.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type ha marcado esta notificación como leída.',
        'mark_read_all'         => ':type ha marcado todas las notificaciones como leídas.',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configuración de iconos de Firefox',
            'description'  => '<span class="font-medium">Si los iconos no aparecen, por favor:</span> <br /> <span class="font-medium">Permita que las páginas elijan sus propias fuentes en lugar de las seleccionadas arriba.</span> <br /><br /> <span class="font-bold"> Ajustes (Preferencias) > Fuentes > Avanzado </span>',

        ],

    ],

];
