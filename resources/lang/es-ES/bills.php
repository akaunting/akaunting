<?php

return [

    'bill_number'           => 'N.º de factura de compra',
    'bill_date'             => 'Fecha de la factura de compra',
    'bill_amount'           => 'Importe de la factura de compra',
    'total_price'           => 'Precio total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Número de pedido',
    'bill_from'             => 'Factura de compra de',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total de impuestos',
    'total'                 => 'Total',

    'item_name'             => 'Nombre del artículo|Nombres de los artículos',
    'recurring_bills'       => 'Factura de compra recurrente|Facturas de compra recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Añadir descuento',
    'discount_desc'         => 'de subtotal',

    'payment_made'          => 'Pago realizado',
    'payment_due'           => 'Vencimiento de pago',
    'amount_due'            => 'Importe pendiente',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_received'         => 'Marcar como recibida',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar correo electrónico',
    'create_bill'           => 'Crear factura de compra',
    'receive_bill'          => 'Recibir factura de compra',
    'make_payment'          => 'Pagar',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en su factura de compra. La fecha de la factura se utiliza en el panel y en los informes. Seleccione como fecha de vencimiento aquella en la que espera pagar.',
    ],

    'messages' => [
        'draft'             => 'Esta factura de compra está en <b>BORRADOR</b> y se reflejará en los gráficos cuando se reciba.',

        'status' => [
            'created'       => 'Creada el :date',
            'receive' => [
                'draft'     => 'No recibida',
                'received'  => 'Recibida el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
