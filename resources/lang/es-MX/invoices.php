<?php

return [

    'invoice_number'        => 'Número de factura',
    'invoice_date'          => 'Fecha de factura',
    'invoice_amount'        => 'Monto de la factura',
    'total_price'           => 'Precio total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'N.º de pedido',
    'bill_to'               => 'Facturar a',
    'cancel_date'           => 'Fecha de cancelación',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total de impuestos',
    'total'                 => 'Total',

    'item_name'             => 'Nombre del artículo|Nombres de artículo',
    'recurring_invoices'    => 'Factura recurrente|Facturas recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Agregar descuento',
    'discount_desc'         => 'de subtotal',

    'payment_due'           => 'Pago vencido',
    'paid'                  => 'Pagado',
    'histories'             => 'Historiales',
    'payments'              => 'Pagos',
    'add_payment'           => 'Agregar pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_sent'             => 'Marcar como enviada',
    'mark_viewed'           => 'Marcar como vista',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar correo electrónico',
    'all_invoices'          => 'Inicie sesión para ver todas las facturas',
    'create_invoice'        => 'Crear factura',
    'send_invoice'          => 'Enviar factura',
    'get_paid'              => 'Recibir pago',
    'accept_payments'       => 'Aceptar pagos en línea',
    'payments_received'     => 'Pagos recibidos',
    'over_payment'          => 'El monto introducido supera el total: :amount',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en su factura. La fecha de factura se utiliza en el tablero y los reportes. Seleccione la fecha en la que espera que se le pague como fecha de vencimiento.',
    ],

    'messages' => [
        'email_required'    => '¡No hay dirección de correo electrónico para este cliente!',
        'totals_required'   => 'Los totales de la factura son obligatorios. Por favor, edite el :type y guárdelo de nuevo.',

        'draft'             => 'Esta es una factura <b>BORRADOR</b> y se reflejará en los gráficos después de que sea enviada.',

        'status' => [
            'created'       => 'Creada el :date',
            'viewed'        => 'Vista',
            'send' => [
                'draft'     => 'No enviada',
                'sent'      => 'Enviada el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],

        'name_or_description_required' => 'Su factura debe mostrar al menos uno de los siguientes: <b>:name</b> o <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Su cliente puede ver la factura en este enlace',
        'copy_link'         => 'Copie el enlace y compártalo con su cliente.',
        'success_message'   => '¡Enlace de uso compartido copiado al portapapeles!',
    ],

    'sticky' => [
        'description'       => 'Está previsualizando cómo su cliente verá la versión web de su factura.',
    ],

];
