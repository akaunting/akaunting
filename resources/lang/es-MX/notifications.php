<?php

return [

    'whoops'              => '¡Ups!',
    'hello'               => '¡Hola!',
    'salutation'          => 'Saludos,<br> :company_name',
    'subcopy'             => 'Si tiene problemas para hacer clic en el botón ":text", copie y pegue la dirección URL en su navegador: [:url](:url)',
    'mark_read'           => 'Marcar como leído',
    'mark_read_all'       => 'Marcar todo como leído',
    'empty'               => '¡Genial, cero notificaciones!',
    'new_apps'            => ':app está disponible. <a href=":url">¡Échele un vistazo ahora</a>!',

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
            'description'   => 'La importación se ha completado y los registros están disponibles en su tablero.',

        ],

        'failed' => [

            'title'         => 'Importación fallida',
            'description'   => 'No se puede importar el archivo debido a los siguientes problemas:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'La exportación está lista',
            'description'   => 'El archivo de exportación está listo para descargar desde el siguiente enlace:',

        ],

        'failed' => [

            'title'         => 'Exportación fallida',
            'description'   => 'No se puede crear el archivo de exportación debido al siguiente problema:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Correo electrónico :type no válido',
            'description'   => 'La dirección de correo electrónico :email ha sido reportada como no válida y la persona ha sido desactivada. Por favor, revise el siguiente mensaje de error y corrija la dirección de correo electrónico:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'La descarga está lista',
            'description'   => 'Su archivo <strong>:type</strong> está listo para <a href=":url" target="_blank"><strong>descargar</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Error en la descarga',
            'description'   => 'No se pudo crear el archivo debido a varios problemas. Revise su correo electrónico para los detalles.',

        ],

        'export_completed' => [

            'title'         => 'La exportación está lista',
            'description'   => 'Su archivo de exportación <strong>:type</strong> está listo para <a href=":url" target="_blank"><strong>descargar</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Exportación fallida',
            'description'   => 'No se pudo crear el archivo de exportación debido a varios problemas. Revise su correo electrónico para los detalles.',

        ],

        'import_completed' => [

            'title'         => 'Importación completada',
            'description'   => 'Sus datos de <strong>:type</strong> con <strong>:count</strong> registros se han importado correctamente.',

        ],

        'import_failed' => [

            'title'         => 'Importación fallida',
            'description'   => 'No se pudo importar el archivo debido a varios problemas. Revise su correo electrónico para los detalles.',

        ],

        'new_apps' => [

            'title'         => 'Nueva aplicación',
            'description'   => 'La aplicación <strong>:name</strong> ya está disponible. Puede <a href=":url">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nueva factura',
            'description'   => 'La factura <strong>:invoice_number</strong> ha sido creada. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles y proceder con el pago.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Factura vencida',
            'description'   => 'La factura <strong>:invoice_number</strong> venció el <strong>:invoice_due_date</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles y proceder con el pago.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Factura vencida',
            'description'   => 'La factura <strong>:invoice_number</strong> venció el <strong>:invoice_due_date</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nueva factura recurrente',
            'description'   => 'La factura <strong>:invoice_number</strong> ha sido creada según su ciclo recurrente. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles y proceder con el pago.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nueva factura recurrente',
            'description'   => 'La factura <strong>:invoice_number</strong> ha sido creada según el ciclo recurrente de <strong>:customer_name</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Factura vista',
            'description'   => '<strong>:customer_name</strong> ha visto la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pago recibido',
            'description'   => 'Gracias por el pago de la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pago recibido',
            'description'   => 'Gracias por el pago de la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pago recibido',
            'description'   => ':customer_name registró un pago para la factura <strong>:invoice_number</strong>. Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Factura de compra vencida',
            'description'   => 'La factura de compra <strong>:bill_number</strong> venció el <strong>:bill_due_date</strong>. Puede <a href=":bill_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nueva factura de compra recurrente',
            'description'   => 'La factura de compra <strong>:bill_number</strong> ha sido creada según el ciclo recurrente de <strong>:vendor_name</strong>. Puede <a href=":bill_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invalid_email' => [

            'title'         => 'Correo electrónico :type no válido',
            'description'   => 'La dirección de correo electrónico <strong>:email</strong> ha sido reportada como no válida y la persona ha sido desactivada. Por favor, revise y corrija la dirección de correo electrónico.',

        ],

    ],

    'messages' => [

        'mark_read'             => '¡:type ha leído esta notificación!',
        'mark_read_all'         => '¡:type ha leído todas las notificaciones!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configuración de iconos de Firefox',
            'description'  => '<span class="font-medium">Si sus iconos no aparecen, por favor:</span> <br /> <span class="font-medium">Permita que las páginas elijan sus propias fuentes, en lugar de sus selecciones anteriores</span> <br /><br /> <span class="font-bold"> Configuración (Preferencias) > Fuentes > Avanzado </span>',

        ],

    ],

];