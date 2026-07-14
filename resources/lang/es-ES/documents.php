<?php

return [

    'edit_columns'              => 'Editar columnas',
    'empty_items'               => 'No ha añadido ningún artículo.',
    'grand_total'               => 'Total general',
    'accept_payment_online'     => 'Aceptar pagos en línea',
    'transaction'               => 'Se realizó un pago de :amount mediante la cuenta :account.',
    'portal_transaction'        => 'Se realizó un pago de :amount mediante :payment_method.',
    'billing'                   => 'Facturación',
    'advanced'                  => 'Avanzado',

    'item_price_hidden'         => 'Esta columna está oculta en su :type.',

    'actions' => [
        'cancel'                => 'Cancelar',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Usted</b> ha marcado esta factura como',
        'services'              => 'Servicios',
        'another_item'          => 'Otro artículo',
        'another_description'   => 'y otra descripción',
        'more_item'             => '+:count artículo más',
    ],

    'statuses' => [
        'draft'                 => 'Borrador',
        'sent'                  => 'Enviado',
        'expired'               => 'Expirado',
        'viewed'                => 'Visto',
        'approved'              => 'Aprobado',
        'received'              => 'Recibido',
        'refused'               => 'Rechazado',
        'restored'              => 'Restaurado',
        'reversed'              => 'Revertido',
        'partial'               => 'Parcial',
        'paid'                  => 'Pagado',
        'pending'               => 'Pendiente',
        'invoiced'              => 'Facturado',
        'overdue'               => 'Vencido',
        'unpaid'                => 'Pendiente de pago',
        'cancelled'             => 'Cancelado',
        'voided'                => 'Anulado',
        'completed'             => 'Completado',
        'shipped'               => 'Despachado',
        'refunded'              => 'Reembolsado',
        'failed'                => 'Fallido',
        'denied'                => 'Denegado',
        'processed'             => 'Procesado',
        'open'                  => 'Abierto',
        'closed'                => 'Cerrado',
        'billed'                => 'Facturado',
        'delivered'             => 'Entregado',
        'returned'              => 'Devuelto',
        'drawn'                 => 'Girado',
        'not_billed'            => 'No facturado',
        'issued'                => 'Emitido',
        'not_invoiced'          => 'Sin facturar',
        'confirmed'             => 'Confirmado',
        'not_confirmed'         => 'No confirmado',
        'active'                => 'Activo',
        'ended'                 => 'Finalizado',
    ],

    'form_description' => [
        'companies'             => 'Cambie la dirección, el logotipo y otra información para su empresa.',
        'billing'               => 'Los detalles de facturación aparecen en su documento.',
        'advanced'              => 'Seleccione la categoría, añada o edite el pie de página y adjunte archivos a :type.',
        'attachment'            => 'Descargue los archivos adjuntos a :type.',
    ],

    'slider' => [
        'create'            => ':user creó este :type el :date',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'send'              => ':user envió este :type el :date',
        'schedule'          => 'Se repite cada :interval :frequency desde :date',
        'children'          => ':count :type se crearon automáticamente',
        'cancel'            => ':user canceló este :type el :date',
    ],

    'messages' => [
        'email_sent'            => 'Se ha enviado el correo de :type.',
        'restored'              => 'Se ha restaurado :type.',
        'marked_as'             => 'Se ha marcado :type como :status.',
        'marked_sent'           => 'Se ha marcado :type como enviado.',
        'marked_paid'           => 'Se ha marcado :type como pagado.',
        'marked_viewed'         => 'Se ha marcado :type como visto.',
        'marked_cancelled'      => 'Se ha marcado :type como cancelado.',
        'marked_received'       => 'Se ha marcado :type como recibido.',
    ],

    'recurring' => [
        'auto_generated'        => 'Generado automáticamente',

        'tooltip' => [
            'document_date'     => 'La fecha de :type se asignará automáticamente según la programación y la frecuencia de :type.',
            'document_number'   => 'El número de :type se asignará automáticamente cuando se genere cada :type recurrente.',
        ],
    ],

    'empty_attachments'         => 'No hay archivos adjuntos a este :type.',
];
