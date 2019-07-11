<?php

return [

    'bill_number'           => 'Nº de Recibo',
    'bill_date'             => 'Fecha Recibo',
    'total_price'           => 'Precio Total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Número de pedido',
    'bill_from'             => 'Recibo de',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'tax_total'             => 'Total Impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo | Nombres de artículo',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Agregar Descuento',
    'discount_desc'         => 'de subtotal',

    'payment_due'           => 'Vencimiento de pago',
    'amount_due'            => 'Importe Vencido',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_received'         => 'Marcar como recibido',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar Email',
    'create_bill'           => 'Crear Recibo',
    'receive_bill'          => 'Recibe Recibo',
    'make_payment'          => 'Hacer Pago',

    'status' => [
        'draft'             => 'Borrador',
        'received'          => 'Recibido',
        'partial'           => 'Parcial',
        'paid'              => 'Pagado',
    ],

    'messages' => [
        'received'          => 'Recibo marcado como recibido con éxito!',
        'draft'             => 'Este es un recibo <b>BORRADOR</b> y se reflejará en los gráficos luego de recibirse.',

        'status' => [
            'created'       => 'Creado el :date',
            'receive' => [
                'draft'     => 'No enviado',
                'received'  => 'Recibido el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
