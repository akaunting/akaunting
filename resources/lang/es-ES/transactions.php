<?php

return [

    'payment_received'      => 'Pagos Recibidos',
    'payment_made'          => 'Pago Realizado',
    'paid_by'               => 'Pagado por',
    'paid_to'               => 'Pagado a',
    'related_invoice'       => 'Facturas relacionadas',
    'related_bill'          => 'Cuenta relacionada',
    'recurring_income'      => 'Ingresos recurrentes',
    'recurring_expense'     => 'Gastos recurrentes',

    'form_description' => [
        'general'           => 'Aquí puede ingresar la información general de la transacción, como fecha, monto, cuenta, descripción, etc.',
        'assign_income'     => 'Seleccione una categoría y cliente para que sus informes sean más detallados.',
        'assign_expense'    => 'Seleccione una categoría y proveedor para que sus informes sean más detallados.',
        'other'             => 'Introduzca un número y una referencia para mantener la transacción vinculada a sus registros.',
    ],

    'slider' => [
        'create'            => ':user creó esta transacción el :date',
        'attachments'       => 'Descargar los archivos adjuntos a esta transacción',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'schedule'          => 'Repetir cada :intervalo :frequency desde :date',
        'children'          => ':count transacciones fueron creadas automáticamente',
        'transfer_headline' => 'De :from_account a :to_account',
        'transfer_desc'     => 'Transferencia creada en :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Su cliente puede ver la transacción en este enlace',
            'copy_link'     => 'Copie el enlace y compártelo con su cliente.',
        ],

        'expense' => [
            'show_link'     => 'Su proveedor puede ver la transacción en este enlace',
            'copy_link'     => 'Copie el enlace y compártelo con su proveedor.',
        ],
    ],

    'sticky' => [
        'description'       => 'Usted está previsualizando cómo su cliente verá la versión web de su pago.',
    ],

];
