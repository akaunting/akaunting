<?php

return [

    'bill_number'           => 'Nº de Recibo',
    'bill_date'             => 'Fecha Recibo',
    'bill_amount'           => 'Monto de la factura',
    'total_price'           => 'Precio Total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Número de pedido',
    'bill_from'             => 'Recibo de',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total Impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo | Nombres de artículo',
    'recurring_bills'       => 'Factura recurrente|Facturas recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Agregar Descuento',
    'discount_desc'         => 'de subtotal',

    'payment_made'          => 'Pago realizado',
    'payment_due'           => 'Vencimiento de pago',
    'amount_due'            => 'Importe Vencido',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar como paga',
    'mark_received'         => 'Marcar como recibido',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar Email',
    'create_bill'           => 'Crear Recibo',
    'receive_bill'          => 'Recibir factura',
    'make_payment'          => 'Pagar',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en tu factura. La fecha de factura se utiliza en el panel de control y en los informes. Seleccione la fecha en la que espera pagar como fecha de vencimiento.',
    ],

    'messages' => [
        'draft'             => 'Este es un<b>BORRADOR</b> de factura y se reflejará en los gráficos luego de que sea enviada.',

        'status' => [
            'created'       => 'Creado el :date',
            'receive' => [
                'draft'     => 'No recibido',
                'received'  => 'Recibido el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
