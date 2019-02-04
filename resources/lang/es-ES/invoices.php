<?php

return [

    'invoice_number'        => 'Número de Factura',
    'invoice_date'          => 'Fecha de Factura',
    'total_price'           => 'Precio Total',
    'due_date'              => 'Fecha de vencimiento',
    'order_number'          => 'Nº Pedido',
    'bill_to'               => 'Facturar a',

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
    'paid'                  => 'Pagado',
    'histories'             => 'Historias',
    'payments'              => 'Pagos',
    'add_payment'           => 'Añadir pago',
    'mark_paid'             => 'Marcar Como Pagada',
    'mark_sent'             => 'Marcar Como Enviada',
    'download_pdf'          => 'Descargar PDF',
    'send_mail'             => 'Enviar Email',
    'all_invoices'          => 'Inicie sesión para ver todas las facturas',
    'create_invoice'        => 'Crear Factura',
    'send_invoice'          => 'Enviar Factura',
    'get_paid'              => 'Recibir Pago',
    'accept_payments'       => 'Aceptar Pagos Online',

    'status' => [
        'draft'             => 'Borrador',
        'sent'              => 'Enviado',
        'viewed'            => 'Visto',
        'approved'          => 'Aprobado',
        'partial'           => 'Parcial',
        'paid'              => 'Pagado',
    ],

    'messages' => [
        'email_sent'        => 'El email de la factura se ha enviado correctamente!',
        'marked_sent'       => 'Factura marcada como enviada con éxito!',
        'email_required'    => 'Ninguna dirección de correo electrónico para este cliente!',
        'draft'             => 'Esta es una factura <b>BORRADOR</b> y se reflejará en los gráficos luego de que sea enviada.',

        'status' => [
            'created'       => 'Creada el :date',
            'send' => [
                'draft'     => 'No enviada',
                'sent'      => 'Enviada el :date',
            ],
            'paid' => [
                'await'     => 'Pendiente de pago',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Usted está recibiendo este correo electrónico porque usted tiene una factura de :amount para el cliente :cliente .',
        'button'            => 'Pagar Ahora',
    ],

];
