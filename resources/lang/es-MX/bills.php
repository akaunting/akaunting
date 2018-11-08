<?php

return [

    'bill_number'       => 'Número de Recibo',
    'bill_date'         => 'Fecha del Recibo',
    'total_price'       => 'Precio Total',
    'due_date'          => 'Fecha de Vencimiento',
    'order_number'      => 'Número de Orden',
    'bill_from'         => 'Recibo De',

    'quantity'          => 'Cantidad',
    'price'             => 'Precio',
    'sub_total'         => 'Subtotal',
    'discount'          => 'Descuento',
    'tax_total'         => 'Total de Impuestos',
    'total'             => 'Total ',

    'item_name'         => 'Nombre del producto/servicio | Nombres de los productos/servicos',

    'show_discount'     => ':discount% Descuento',
    'add_discount'      => 'Agregar Descuento',
    'discount_desc'     => 'de subtotal',

    'payment_due'       => 'Vencimiento del Pago',
    'amount_due'        => 'Importe Vencido',
    'paid'              => 'Pagado',
    'histories'         => 'Historial',
    'payments'          => 'Pagos',
    'add_payment'       => 'Añadir pago',
    'mark_received'     => 'Marcar como Recibido',
    'download_pdf'      => 'Descargar archivo PDF',
    'send_mail'         => 'Enviar Correo Electrónico',

    'status' => [
        'draft'         => 'Borrador',
        'received'      => 'Recibido',
        'partial'       => 'Parcial',
        'paid'          => 'Pagado',
    ],

    'messages' => [
        'received'      => '¡Recibo marcado como recibido con exitosamente!',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Created on :date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
