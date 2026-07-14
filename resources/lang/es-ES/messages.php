<?php

return [

    'success' => [
        'added'             => 'Se ha añadido :type.',
        'created'           => 'Se ha creado :type.',
        'updated'           => 'Se ha actualizado :type.',
        'deleted'           => 'Se ha eliminado :type.',
        'duplicated'        => 'Se ha duplicado :type.',
        'imported'          => 'Se ha importado :type.',
        'import_queued'     => 'Se ha programado la importación de :type. Recibirá un correo electrónico cuando termine.',
        'exported'          => 'Se ha exportado :type.',
        'export_queued'     => 'Se ha programado la exportación de :type de la página actual. Recibirá un correo electrónico cuando esté lista para descargar.',
        'download_queued'   => 'Se ha programado la descarga de :type de la página actual. Recibirá un correo electrónico cuando esté lista.',
        'enabled'           => 'Se ha habilitado :type.',
        'disabled'          => 'Se ha deshabilitado :type.',
        'connected'         => 'Se ha conectado :type.',
        'invited'           => 'Se ha invitado a :type.',
        'ended'             => 'Ha finalizado :type.',

        'clear_all'         => 'Se han borrado todos sus :type.',
    ],

    'error' => [
        'over_payment'      => 'Error: no se ha añadido el pago. El importe introducido supera el total: :amount',
        'not_user_company'  => 'Error: no tiene permiso para gestionar esta empresa.',
        'customer'          => 'Error: no se ha creado el usuario. :name ya utiliza esta dirección de correo electrónico.',
        'no_file'           => 'Error: no ha seleccionado ningún archivo.',
        'last_category'     => 'Error: no se puede eliminar la última categoría de <b>:type</b>.',
        'transfer_category' => 'Error: no se puede eliminar la categoría de transferencia <b>:type</b>.',
        'change_type'       => 'Error: no se puede cambiar el tipo porque tiene :text asociados.',
        'invalid_apikey'    => 'Error: ¡La clave de API introducida no es válida!',
        'empty_apikey'      => 'Error: no ha introducido su clave de API. <a href=":url" class="font-bold underline underline-offset-4">Haga clic aquí</a> para introducirla.',
        'import_column'     => 'Error: :message Nombre de la columna: :column. Número de línea: :line.',
        'import_sheet'      => 'Error: el nombre de la hoja no es válido. Compruebe el archivo de ejemplo.',
        'same_amount'       => 'Error: el importe total de la división debe coincidir exactamente con el total de :transaction: :amount',
        'over_match'        => 'Error: no se ha conectado :type. El importe introducido no puede superar el total del pago: :amount',
    ],

    'warning' => [
        'deleted'           => 'Advertencia: no puede eliminar <b>:name</b> porque tiene :text asociados.',
        'disabled'          => 'Advertencia: no puede deshabilitar <b>:name</b> porque tiene :text asociados.',
        'reconciled_tran'   => 'Advertencia: no puede modificar ni eliminar la transacción porque está conciliada.',
        'reconciled_doc'    => 'Advertencia: no puede modificar ni eliminar :type porque tiene transacciones conciliadas.',
        'disable_code'      => 'Advertencia: no puede deshabilitar <b>:name</b> ni cambiar su moneda porque tiene :text asociados.',
        'payment_cancel'    => 'Advertencia: ha cancelado su pago reciente mediante :method.',
        'missing_transfer'  => 'Advertencia: Falta la transferencia relacionada con esta transacción. Debería considerar eliminar esta transacción.',
        'connect_tax'       => 'Advertencia: Este :type tiene un importe de impuesto. Los impuestos añadidos al :type no se pueden conectar, por lo que el impuesto se añadirá al total y se calculará en consecuencia.',
        'contact_change'    => 'Advertencia: no puede cambiar el contacto de :type si ya se ha enviado, recibido o pagado.',
    ],

];
