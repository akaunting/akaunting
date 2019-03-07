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
    'download_pdf'          => 'Descargar archivo PDF',
    'send_mail'             => 'Enviar Correo Electrónico',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',

    'status' => [
        'draft'             => 'Borrador',
        'sent'              => 'Enviado',
        'viewed'            => 'Visto',
        'approved'          => 'Aprobado',
        'partial'           => 'Parcial',
        'paid'              => 'Pagado',
    ],

    'messages' => [
        'email_sent'        => '¡El correo electrónico de la factura se ha enviado correctamente!',
        'marked_sent'       => '¡Factura marcada como enviada con éxito!',
        'email_required'    => 'No se encontró una dirección de correo para este cliente!',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Created on :date',
            'send' => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Estimado :cliente, usted está recibiendo este correo electrónico debido a que tiene una factura pendiente de pago por la cantidad de :amount, es de suma importancia que se contacte de inmediato con nosotros. Evite la suspensión de los servicios que tiene contratados o la entrega de los productos solicitados, además de posibles recargos y gastos de cobranza.',
        'button'            => 'Realizar el Pago Ahora',
    ],

];
