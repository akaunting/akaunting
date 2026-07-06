<?php

return [

    'invoice_number'        => 'Número de Factura',
    'invoice_date'          => 'Fecha de Factura',
    'invoice_amount'        => 'Importe de la factura',
    'total_price'           => 'Precio Total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Nº Pedido',
    'bill_to'               => 'Facturar a',
    'cancel_date'           => 'Fecha de cancelación',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total Impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo|Nombres de artículo',
    'recurring_invoices'    => 'Factura recurrente|Facturas recurrentes',

    'show_discount'         => ':discount% Descuento',
    'add_discount'          => 'Agregar Descuento',
    'discount_desc'         => 'de subtotal',

    'payment_due'           => 'Vencimiento de pago',
    'paid'                  => 'Pagado',
    'histories'             => 'Historias',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar Como Pagada',
    'mark_sent'             => 'Marcar Como Enviada',
    'mark_viewed'           => 'Marcar como visto',
    'mark_cancelled'        => 'Marcar como Cancelada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar Email',
    'all_invoices'          => 'Inicie sesión para ver todas las facturas',
    'create_invoice'        => 'Crear Factura',
    'send_invoice'          => 'Enviar Factura',
    'get_paid'              => 'Recibir Pago',
    'accept_payments'       => 'Aceptar Pagos Online',
    'payments_received'     => 'Pagos recibidos',
    'over_payment'          => 'El importe introducido supera el total: :amount',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en su factura. La fecha de la factura se utiliza en el panel de control e informes. Seleccione la fecha que usted espera que se le pague como fecha de vencimiento.',
    ],

    'messages' => [
        'email_required'    => 'Ninguna dirección de correo electrónico para este cliente!',
        'totals_required'   => 'Los totales de la factura son obligatorios. Por favor, edite el :type y guárdelo de nuevo.',
        'draft'             => 'Esta es una factura <b>BORRADOR</b> y se reflejará en los gráficos luego de que sea enviada.',

        'status' => [
            'created'       => 'Creada el :date',
            'viewed'        => 'Visto',
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

    'slider' => [
        'create'            => ':user creó esta factura el :date',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'schedule'          => 'Repetir cada :intervalo :frequency desde :date',
        'children'          => ':count facturas fueron creadas automáticamente',
    ],

    'share' => [
        'show_link'         => 'Su cliente puede ver la factura en este enlace',
        'copy_link'         => 'Copie el enlace y compártelo con su cliente.',
        'success_message'   => '¡Enlace compartido copiado al portapapeles!',
    ],

    'sticky' => [
        'description'       => 'Está previsualizando cómo verá su cliente la versión web de su factura.',
    ],

];
