<?php

return [

    'bill_number'           => 'Nº de Recibo',
    'bill_date'             => 'Fecha Recibo',
    'bill_amount'           => 'Monto de la factura',
    'total_price'           => 'Precio Total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Nº Pedido',
    'bill_from'             => 'Recibo de',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Línea de Descuento',
    'tax_total'             => 'Total de Impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo | Artículos',
    'recurring_bills'       => 'Cuenta recurrente|Cuentas recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Añadir descuento',
    'discount_desc'         => 'de subtotal',

    'payment_made'          => 'Pago realizado',
    'payment_due'           => 'Vencimiento del pago',
    'amount_due'            => 'Saldo Pendiente',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_received'         => 'Marcar como recibido',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar en PDF',
    'send_mail'             => 'Enviar correo electrónico',
    'create_bill'           => 'Crear Recibo',
    'receive_bill'          => 'Recibe Recibo',
    'make_payment'          => 'Realizar el pago',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en tu factura. La fecha de factura se utiliza en el panel de control y en los informes. Seleccione la fecha en la que espera pagar como fecha de vencimiento.',
    ],

    'messages' => [
        'draft'             => 'Este es un recibo <b>BORRADOR</b> y se reflejará en los gráficos luego de recibirse.',

        'status' => [
            'created'       => 'Creada el :Fecha',
            'receive' => [
                'draft'     => 'No recibido',
                'received'  => 'Recibido el :Fecha',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
