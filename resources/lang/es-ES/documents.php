<?php

return [

    'edit_columns'              => 'Editar Columnas',
    'empty_items'               => 'No ha agregado ningún artículo.',
    'grand_total'               => 'Suma total',
    'accept_payment_online'     => 'Aceptar pagos en línea',
    'transaction'               => 'Un pago por :amount se realizó usando :account.',
    'portal_transaction'        => 'Un pago por :amount se realizó usando :payment_method.',
    'billing'                   => 'Facturación',
    'advanced'                  => 'Avanzado',

    'item_price_hidden'         => 'Esta columna está oculta en su :type.',

    'actions' => [
        'cancel'                => 'Cancelar',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Tú</b> has marcado esta factura como',
        'services'              => 'Servicios',
        'another_item'          => 'Otro elemento',
        'another_description'   => 'y otra descripción',
        'more_item'             => '+:count más elementos',
    ],

    'statuses' => [
        'draft'                 => 'Borrador',
        'sent'                  => 'Enviado',
        'expired'               => 'Vencido',
        'viewed'                => 'Visto',
        'approved'              => 'Aprobada',
        'received'              => 'Recibido',
        'refused'               => 'Rechazado',
        'restored'              => 'Restaurado',
        'reversed'              => 'Reversado',
        'partial'               => 'Parcial',
        'paid'                  => 'Pago',
        'pending'               => 'Pendiente',
        'invoiced'              => 'Facturado',
        'overdue'               => 'Vencida',
        'unpaid'                => 'No paga',
        'cancelled'             => 'Cancelada',
        'voided'                => 'Anulada',
        'completed'             => 'Finalizado',
        'shipped'               => 'Enviado',
        'refunded'              => 'Reembolsado',
        'failed'                => 'Fallo',
        'denied'                => 'Denegado',
        'processed'             => 'Procesado',
        'open'                  => 'Abrir',
        'closed'                => 'Cerrado',
        'billed'                => 'Facturado',
        'delivered'             => 'Entregado',
        'returned'              => 'Devuelto',
        'drawn'                 => 'Dibujado',
        'not_billed'            => 'No facturado',
        'issued'                => 'Emitido',
        'not_invoiced'          => 'No Facturado',
        'confirmed'             => 'Confirmado',
        'not_confirmed'         => 'No confirmado',
        'active'                => 'Activo',
        'ended'                 => 'Terminado',
    ],

    'form_description' => [
        'companies'             => 'Cambie la dirección, el logotipo y otra información para su empresa.',
        'billing'               => 'Los detalles de facturación aparecen en su documento.',
        'advanced'              => 'Selecciona la categoría, añade o edita el pie de página y añade adjuntos a tu :type.',
        'attachment'            => 'Descargar los archivos adjuntos a este :type',
    ],

    'slider' => [
        'create'            => ':user creó este :type el :date',
        'create_recurring'  => ':user creó esta plantilla recurrente el :date',
        'send'              => ':user envió este :type el :date',
        'schedule'          => 'Repetir cada :interval :frequency desde el :date',
        'children'          => ':count :type se crearon automáticamente',
        'cancel'            => ':user canceló este :type el :date',
    ],

    'messages' => [
        'email_sent'            => '¡Se ha enviado :type por correo!',
        'restored'              => '¡:type ha sido restaurado/a!',
        'marked_as'             => '¡:type marcado/a como :status!',
        'marked_sent'           => '¡:type marcado/a como enviado!',
        'marked_paid'           => '¡:type marcado/a como pagado/a!',
        'marked_viewed'         => '¡:type marcado/a como visto/a!',
        'marked_cancelled'      => '¡:type marcado/a como cancelado/a!',
        'marked_received'       => '¡:type marcado/a como recibido/a!',
    ],

    'recurring' => [
        'auto_generated'        => 'Generado automáticamente',

        'tooltip' => [
            'document_date'     => 'La fecha :type se asignará automáticamente basándose en el calendario :type y la frecuencia.',
            'document_number'   => 'El número :type se asignará automáticamente cuando se genere cada :type recurrente.',
        ],
    ],

    'empty_attachments'         => 'No hay archivos adjuntos a este :type.',
];
