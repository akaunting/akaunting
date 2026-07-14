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
    'included_tax'          => 'Importe de impuesto incluido',
    'connected'             => 'Conectado',
    'connect_message'       => 'Los impuestos para este :type no se calcularon durante el proceso de conexión. Los impuestos no se pueden conectar.',

    'form_description' => [
        'general'           => 'Aquí puede introducir los datos generales de la transacción, como la fecha, el importe, la cuenta y la descripción.',
        'assign_income'     => 'Seleccione una categoría y cliente para que sus informes sean más detallados.',
        'assign_expense'    => 'Seleccione una categoría y proveedor para que sus informes sean más detallados.',
        'other'             => 'Introduzca un número y una referencia para mantener la transacción vinculada a sus registros.',
    ],

    'slider' => [
        'create'            => ':user creó esta transacción el :date',
        'attachments'       => 'Descargue los archivos adjuntos a esta transacción',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'schedule'          => 'Se repite cada :interval :frequency desde :date',
        'children'          => 'Se crearon automáticamente :count transacciones',
        'connect'           => 'Esta transacción está conectada a :count transacciones',
        'transfer_headline' => '<div> <span class="font-bold"> De: </span> :from_account </div> <div> <span class="font-bold"> A: </span> :to_account </div>',
        'transfer_desc'     => 'Transferencia creada en :date.',
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
        'description'       => 'Está previsualizando cómo verá su cliente la versión web del pago.',
    ],

    'messages' => [
        'update_document_transaction' => 'Puede actualizar esta transacción. Vaya al documento y edítela allí.',
        'create_document_transaction_error' => 'No se puede añadir este endpoint a un documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'No se puede actualizar este endpoint en un documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'No se puede eliminar este endpoint de un documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ],

];
