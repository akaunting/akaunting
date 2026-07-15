<?php

return [

    'bill_number'           => 'N.º de factura de compra',
    'bill_date'             => 'Fecha de factura de compra',
    'bill_amount'           => 'Monto de la factura de compra',
    'total_price'           => 'Precio total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'N.º de pedido',
    'bill_from'             => 'Factura de compra de',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total de impuestos',
    'total'                 => 'Total',

    'item_name'             => 'Nombre del artículo|Nombres de artículo',
    'recurring_bills'       => 'Factura de compra recurrente|Facturas de compra recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Agregar descuento',
    'discount_desc'         => 'de subtotal',

    'payment_made'          => 'Pago realizado',
    'payment_due'           => 'Pago vencido',
    'amount_due'            => 'Monto pendiente',
    'paid'                  => 'Pagado',
    'histories'             => 'Historiales',
    'payments'              => 'Pagos',
    'add_payment'           => 'Agregar pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_received'         => 'Marcar como recibida',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar correo electrónico',
    'create_bill'           => 'Crear factura de compra',
    'receive_bill'          => 'Recibir factura de compra',
    'make_payment'          => 'Realizar pago',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en su factura de compra. La fecha de la factura de compra se utiliza en el tablero y los reportes. Seleccione la fecha en la que espera pagar como fecha de vencimiento.',
    ],

    'messages' => [
        'draft'             => 'Esta es una <b>BORRADOR</b> factura de compra y se reflejará en los gráficos después de que sea recibida.',

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
