<?php

return [

    'invoice_number'        => 'Número de factura',
    'invoice_date'          => 'Fecha de factura',
    'total_price'           => 'Precio total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Número de pedido',
    'bill_to'               => 'Facturar a',

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

    'payment_due'           => 'Vencimiento de pago',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_sent'             => 'Marcar Como Enviada',
    'mark_viewed'           => 'Marcar como vista',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar Email',
    'all_invoices'          => 'Inicie sesión para ver todas las facturas',
    'create_invoice'        => 'Crear factura',
    'send_invoice'          => 'Enviar factura',
    'get_paid'              => 'Recibir Pago',
    'accept_payments'       => 'Aceptar pagos online',

    'messages' => [
        'email_required'    => '¡No hay dirección de correo electrónico para este cliente!',
        'draft'             => 'Esta es una factura <b>BORRADOR</b> y se reflejará en gráficos después de ser enviada.',

        'status' => [
            'created'       => 'Creada el :date',
            'viewed'        => 'Vista',
            'send' => [
                'draft'     => 'Sin enviar',
                'sent'      => 'Enviada el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
