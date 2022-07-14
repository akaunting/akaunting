<?php

return [

    'whoops'              => 'Vaya!',
    'hello'               => 'Hola!',
    'salutation'          => 'Saludos,<br> :company_name',
    'subcopy'             => 'Si tiene problemas haciendo clic en el boton “:text”, copie y pegue la dirección URL en su navegador: [:url] (:url)',
    'mark_read'           => 'Marcar como leído',
    'mark_read_all'       => 'Marcar todos como leídos',
    'empty'               => 'Sin notificaciones',
    'new_apps'            => ':app está disponible. <a href=":url">¡Échale un vistazo ahora</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Actualización fallida en :domain',
            'description'   => 'La actualización de :alias de :current_version a :new_version falló en <strong>:step</strong> paso con el siguiente mensaje: :error_message',

        ],

        'slack' => [

            'description'   => 'Error al actualizar en :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importación completada',
            'description'   => 'La importación se ha completado y los registros están disponibles en su panel.',

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

    'menu' => [

        'export_completed' => [

            'title'         => 'La exportación está lista',
            'description'   => 'Tu archivo de exportación <strong>:type</strong> está listo para <a href=":url" target="_blank"><strong>descargar</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Exportación fallida',
            'description'   => 'No se puede crear el archivo de exportación debido al siguiente problema: :issues',

        ],

        'import_completed' => [

            'title'         => 'Importación completada',
            'description'   => 'Tus datos <strong>:type</strong> alineados <strong>:count</strong> se han importado correctamente.',

        ],

        'new_apps' => [

            'title'         => 'Nueva aplicación',
            'description'   => 'La aplicación <strong>:name</strong> está lista. Puedes <a href=":url">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nueva Factura',
            'description'   => '<strong>:invoice_number</strong> factura creada. Puedes <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles y proceder con el pago.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Factura atrasada',
            'description'   => '<strong>:invoice_number</strong> factura venció <strong>:invoice_due_date</strong>. Puedes <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles y proceder con el pago.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Factura atrasada',
            'description'   => '<strong>:invoice_number</strong> factura venció <strong>:invoice_due_date</strong>. Puedes <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nueva factura recurrente',
            'description'   => '<strong>:invoice_number</strong> factura creada. Puedes <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles y proceder con el pago.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nueva factura recurrente',
            'description'   => 'La factura <strong>:invoice_number</strong> se crea en función del círculo recurrente de <strong>:customer_name</strong> . Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Factura vista',
            'description'   => '<strong>:customer_name</strong> ha visto la factura <strong>:invoice_number</strong> . Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pago recibido',
            'description'   => 'Gracias por el pago de la factura <strong>:invoice_number</strong> . Puedes <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pago recibido',
            'description'   => 'Gracias por el pago de la factura <strong>:invoice_number</strong> . Puedes <a href=":invoice_portal_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pago recibido',
            'description'   => ':customer_name pago registrado para la factura <strong>:invoice_number</strong> . Puede <a href=":invoice_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Factura atrasada',
            'description'   => '<strong>:bill_number</strong> la factura venció <strong>:bill_due_date</strong>. Puedes <a href=":bill_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nueva factura recurriente',
            'description'   => '<strong>:bill_number</strong> factura se crea basado en <strong>:vendor_name</strong> círculo recurrente. Puede <a href=":bill_admin_link">hacer clic aquí</a> para ver los detalles.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type has leído todas las notificaciones!',
        'mark_read_all'         => ':type has leído todas las notificaciones!',

    ],
];
