<?php

return [

    'invoice_number'        => 'Número de Factura',
    'invoice_date'          => 'Fecha de factura',
    'invoice_amount'        => 'Importe de la factura',
    'total_price'           => 'Precio total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Número de pedido',
    'bill_to'               => 'Facturar a',
    'cancel_date'           => 'Fecha de cancelación',

    'quantity'              => 'Cantidad',
    'price'                 => 'Precio',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Descuento',
    'item_discount'         => 'Descuento de línea',
    'tax_total'             => 'Total de impuestos',
    'total'                 => 'Total ',

    'item_name'             => 'Nombre del artículo | Nombres de artículo',
    'recurring_invoices'    => 'Factura recurrente|Facturas recurrentes',

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
    'payment_received'      => 'Pago recibido',

    'form_description' => [
        'billing'           => 'Los datos de facturación aparecen en su factura. La fecha de la factura se utiliza en el panel de control e informes. Seleccione la fecha que usted espera que se le pague como fecha de vencimiento.',
    ],

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
