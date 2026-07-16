<?php

return [

    'payment_received'      => 'Pago recibido',
    'payment_made'          => 'Pago realizado',
    'paid_by'               => 'Pagado por',
    'paid_to'               => 'Pagado a',
    'related_invoice'       => 'Factura relacionada',
    'related_bill'          => 'Factura de compra relacionada',
    'recurring_income'      => 'Ingreso recurrente',
    'recurring_expense'     => 'Gasto recurrente',
    'included_tax'          => 'Monto de impuesto incluido',
    'connected'             => 'Conectado',
    'connect_message'       => 'Los impuestos para este :type no se calcularon durante el proceso de conexión. Los impuestos no se pueden conectar.',

    'form_description' => [
        'general'           => 'Aquí puede ingresar la información general de la transacción, como fecha, monto, cuenta, descripción, etc.',
        'assign_income'     => 'Seleccione una categoría y un cliente para que sus reportes sean más detallados.',
        'assign_expense'    => 'Seleccione una categoría y un proveedor para que sus reportes sean más detallados.',
        'other'             => 'Introduzca un número y una referencia para mantener la transacción vinculada a sus registros.',
    ],

    'slider' => [
        'create'            => ':user creó esta transacción el :date',
        'attachments'       => 'Descargar los archivos adjuntos a esta transacción',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'schedule'          => 'Repetir cada :interval :frequency desde :date',
        'children'          => ':count transacciones fueron creadas automáticamente',
        'connect'           => 'Esta transacción está conectada a :count transacciones',
        'transfer_headline' => '<div> <span class="font-bold"> Desde: </span> :from_account </div> <div> <span class="font-bold"> a: </span> :to_account </div>',
        'transfer_desc'     => 'Transferencia creada el :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Su cliente puede ver la transacción en este enlace',
            'copy_link'     => 'Copie el enlace y compártalo con su cliente.',
        ],

        'expense' => [
            'show_link'     => 'Su proveedor puede ver la transacción en este enlace',
            'copy_link'     => 'Copie el enlace y compártalo con su proveedor.',
        ],
    ],

    'sticky' => [
        'description'       => 'Está previsualizando cómo su cliente verá la versión web de su pago.',
    ],

    'messages' => [
        'update_document_transaction' => 'Puede actualizar esta transacción. Debería ir al documento y editarlo allí.',
        'create_document_transaction_error' => 'Este endpoint no puede ser añadido a un documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Este endpoint no puede ser actualizado para un documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Este endpoint no puede ser eliminado de un documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ],

];
