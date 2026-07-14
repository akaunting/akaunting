<?php

return [

    'invoice_number'        => 'Número de factura',
    'invoice_date'          => 'Fecha de la factura',
    'invoice_amount'        => 'Importe de la factura',
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
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo|Nombres de los artículos',
    'recurring_invoices'    => 'Factura recurrente|Facturas recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Añadir descuento',
    'discount_desc'         => 'de subtotal',

    'payment_due'           => 'Vencimiento de pago',
    'paid'                  => 'Pagado',
    'histories'             => 'Historial',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar como pagada',
    'mark_sent'             => 'Marcar como enviada',
    'mark_viewed'           => 'Marcar como vista',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar correo electrónico',
    'all_invoices'          => 'Inicie sesión para ver todas las facturas',
    'create_invoice'        => 'Crear factura',
    'send_invoice'          => 'Enviar factura',
    'get_paid'              => 'Cobrar',
    'accept_payments'       => 'Aceptar pagos en línea',
    'payments_received'     => 'Pagos recibidos',
    'over_payment'          => 'El importe introducido supera el total: :amount',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en su factura. La fecha de la factura se utiliza en el panel y en los informes. Seleccione como fecha de vencimiento aquella en la que espera cobrar.',
    ],

    'messages' => [
        'email_required'    => 'Este cliente no tiene ninguna dirección de correo electrónico.',
        'totals_required'   => 'Los totales de la factura son obligatorios. Edite el :type y guárdelo de nuevo.',
        'draft'             => 'Esta factura está en <b>BORRADOR</b> y se reflejará en los gráficos cuando se envíe.',

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
        'success_message'   => '¡Enlace compartido copiado al portapapeles!',
    ],

    'sticky' => [
        'description'       => 'Está previsualizando cómo verá su cliente la versión web de su factura.',
    ],

];
