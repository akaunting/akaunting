<?php

return [

    'edit_columns'              => 'Editar columnas',
    'empty_items'               => 'No ha agregado ningún artículo.',
    'grand_total'               => 'Total general',
    'accept_payment_online'     => 'Aceptar pagos en línea',
    'transaction'               => 'Se realizó un pago de :amount usando :account.',
    'portal_transaction'        => 'Se realizó un pago de :amount usando :payment_method.',
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
        'unpaid'                => 'No pagado',
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
        'not_invoiced'          => 'No facturado',
        'confirmed'             => 'Confirmado',
        'not_confirmed'         => 'No confirmado',
        'active'                => 'Activo',
        'ended'                 => 'Finalizado',
    ],

    'form_description' => [
        'companies'             => 'Cambie la dirección, el logotipo y otra información para su empresa.',
        'billing'               => 'Los detalles de facturación aparecen en su documento.',
        'advanced'              => 'Seleccione la categoría, agregue o edite el pie de página y agregue adjuntos a su :type.',
        'attachment'            => 'Descargue los archivos adjuntos a este :type',
    ],

    'slider' => [
        'create'            => ':user creó este :type el :date',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'send'              => ':user envió este :type el :date',
        'schedule'          => 'Repetir cada :interval :frequency desde :date',
        'children'          => ':count :type se crearon automáticamente',
        'cancel'            => ':user canceló este :type el :date',
    ],

    'messages' => [
        'email_sent'            => '¡Se ha enviado el correo de :type!',
        'restored'              => '¡:type ha sido restaurado!',
        'marked_as'             => '¡Se ha marcado :type como :status!',
        'marked_sent'           => '¡Se ha marcado :type como enviado!',
        'marked_paid'           => '¡Se ha marcado :type como pagado!',
        'marked_viewed'         => '¡Se ha marcado :type como visto!',
        'marked_cancelled'      => '¡Se ha marcado :type como cancelado!',
        'marked_received'       => '¡Se ha marcado :type como recibido!',
    ],

    'recurring' => [
        'auto_generated'        => 'Generado automáticamente',

        'tooltip' => [
            'document_date'     => 'La fecha del :type se asignará automáticamente según la programación y frecuencia del :type.',
            'document_number'   => 'El número del :type se asignará automáticamente cuando se genere cada :type recurrente.',
        ],
    ],

    'empty_attachments'         => 'No hay archivos adjuntos a este :type.',
];
