<?php

return [

    'bill_number'           => 'Número de factura',
    'bill_date'             => 'Fecha de factura',
    'total_price'           => 'Precio total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Número de pedido',
    'bill_from'             => 'Factura de',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total de impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo | Nombres de artículo',

    'show_discount'         => ':discount% de descuento',
    'add_discount'          => 'Agregar descuento',
    'discount_desc'         => 'de subtotal',

    'payment_due'           => 'Vencimiento del pago',
    'amount_due'            => 'Importe vencido',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_received'         => 'Marcar como recibida',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar Email',
    'create_bill'           => 'Crear factura',
    'receive_bill'          => 'Recibir factura',
    'make_payment'          => 'Realizar pago',

    'messages' => [
        'draft'             => 'Este es un <b>BORRADOR</b> de factura y se reflejará en los gráficos luego de que sea enviada.',

        'status' => [
            'created'       => 'Creada el :date',
            'receive' => [
                'draft'     => 'Sin enviar',
                'received'  => 'Recibida el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
