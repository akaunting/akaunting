<?php

return [

    'invoice_number'        => 'Número de Factura',
    'invoice_date'          => 'Fecha de la Factura',
    'total_price'           => 'Precio Total',
    'due_date'              => 'Fecha de Vencimiento',
    'order_number'          => 'Número de Orden',
    'bill_to'               => 'Facturar a',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Línea de descuento',
    'tax_total'             => 'Total de Impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del producto/servicio | Nombres de los productos/servicos',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Agregar Descuento',
    'discount_desc'         => 'de subtotal',

    'payment_due'           => 'Vencimiento del Pago',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir Pago',
    'mark_paid'             => 'Marcar Como Pagada',
    'mark_sent'             => 'Marcar Como Enviada',
    'mark_viewed'           => 'Marcar como visto',
    'mark_cancelled'        => 'Marcar Como Cancelada',
    'download_pdf'          => 'Descargar archivo PDF',
    'send_mail'             => 'Enviar Correo Electrónico',
    'all_invoices'          => 'Inicie sesión para ver todas las facturas',
    'create_invoice'        => 'Crear Factura',
    'send_invoice'          => 'Enviar Factura',
    'get_paid'              => 'Recibir Pago',
    'accept_payments'       => 'Aceptar pagos en línea',

    'statuses' => [
        'draft'             => 'Borrador',
        'sent'              => 'Enviada',
        'viewed'            => 'Visto',
        'approved'          => 'Aprobada',
        'partial'           => 'Pago Parcial',
        'paid'              => 'Pagada',
        'overdue'           => 'Vencida',
        'unpaid'            => 'No Pagada',
        'cancelled'         => 'Cancelada',
    ],

    'messages' => [
        'email_sent'        => '¡El correo electrónico de la factura ha sido enviado!',
        'marked_sent'       => '¡Factura marcada como enviada!',
        'marked_paid'       => '¡Factura marcada como pagada!',
        'marked_viewed'     => '¡Factura marcada como vista!',
        'marked_cancelled'  => '¡Factura marcada como cancelada!',
        'email_required'    => 'No se encontró una dirección de correo para este cliente!',
        'draft'             => 'Esta es una factura <b>BORRADOR</b> y se reflejará en gráficos después de ser enviada.',

        'status' => [
            'created'       => 'Creado el :date',
            'viewed'        => 'Vista',
            'send' => [
                'draft'     => 'No enviada',
                'sent'      => 'Enviada el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

];
