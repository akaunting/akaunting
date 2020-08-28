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

    'statuses' => [
        'draft'             => 'Borrador',
        'sent'              => 'Enviada',
        'viewed'            => 'Vista',
        'approved'          => 'Aprobada',
        'partial'           => 'Pago parcial',
        'paid'              => 'Pagada',
        'overdue'           => 'Vencida',
        'unpaid'            => 'No pagada',
        'cancelled'         => 'Cancelada',
    ],

    'messages' => [
        'email_sent'        => '¡El correo electrónico de la factura ha sido enviado!',
        'marked_sent'       => '¡Factura marcada como enviada!',
        'marked_paid'       => '¡Factura marcada como pagada!',
        'marked_viewed'     => '¡Factura marcada como vista!',
        'marked_cancelled'  => '¡Factura marcada como cancelada!',
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
