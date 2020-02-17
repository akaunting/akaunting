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
    'receive_bill'          => 'Recibir factura',
    'make_payment'          => 'Pagar',

    'statuses' => [
        'draft'             => 'Borrador',
        'received'          => 'Recibido',
        'partial'           => 'Parcial',
        'paid'              => 'Pagado',
        'overdue'           => 'Vencido',
        'unpaid'            => 'No Pagado',
    ],

    'messages' => [
        'received'          => 'Recibo marcado como recibido con éxito!',
        'draft'             => 'Este es un<b>BORRADOR</b> de factura y se reflejará en los gráficos luego de que sea enviada.',

        'status' => [
            'created'       => 'Creado el :date',
            'receive' => [
                'draft'     => 'Sin enviar',
                'received'  => 'Recibido el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
