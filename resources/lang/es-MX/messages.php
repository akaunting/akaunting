<?php

return [

    'success' => [
        'added'             => '¡:type agregado!',
        'created'           => '¡:type creado!',
        'updated'           => '¡:type actualizado!',
        'deleted'           => '¡:type eliminado!',
        'duplicated'        => '¡:type duplicado!',
        'imported'          => '¡:type importado!',
        'import_queued'     => '¡La importación de :type ha sido programada! Recibirá un correo electrónico cuando haya terminado.',
        'exported'          => '¡:type exportado!',
        'export_queued'     => '¡La exportación de :type de la página actual ha sido programada! Recibirá un correo electrónico cuando esté lista para descargar.',
        'download_queued'   => '¡La descarga de :type de la página actual ha sido programada! Recibirá un correo electrónico cuando esté lista para descargar.',
        'enabled'           => '¡:type habilitado!',
        'disabled'          => '¡:type deshabilitado!',
        'connected'         => '¡:type conectado!',
        'invited'           => '¡:type invitado!',
        'ended'             => '¡:type finalizado!',

        'clear_all'         => '¡Excelente! Ha eliminado todo su :type.',
    ],

    'error' => [
        'over_payment'      => 'Error: ¡Pago no agregado! El monto que introdujo supera el total: :amount',
        'not_user_company'  => 'Error: No tiene permiso para administrar esta empresa!',
        'customer'          => 'Error: ¡Usuario no creado! :name ya utiliza esta dirección de correo electrónico.',
        'no_file'           => 'Error: ¡Ningún archivo seleccionado!',
        'last_category'     => 'Error: No se puede eliminar la última categoría de <b>:type</b>!',
        'transfer_category' => 'Error: No se puede eliminar la categoría de transferencia <b>:type</b>!',
        'change_type'       => 'Error: No se puede cambiar el tipo porque tiene :text relacionado!',
        'invalid_apikey'    => 'Error: ¡La clave de API introducida no es válida!',
        'empty_apikey'      => 'Error: No ha introducido su clave de API. <a href=":url" class="font-bold underline underline-offset-4">Haga clic aquí</a> para introducir su clave de API.',
        'import_column'     => 'Error: :message Nombre de la columna: :column. Número de línea: :line.',
        'import_sheet'      => 'Error: El nombre de la hoja no es válido. Por favor, verifique el archivo de ejemplo.',
        'same_amount'       => 'Error: El monto total del reparto debe ser exactamente el mismo que el total de la :transaction: :amount',
        'over_match'        => 'Error: ¡:type no conectado! El monto introducido no puede exceder el total del pago: :amount',
    ],

    'warning' => [
        'deleted'           => 'Advertencia: no puede eliminar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: no se permite desactivar <b>:name</b> porque tiene :text relacionado.',
        'reconciled_tran'   => 'Advertencia: No se le permite cambiar/eliminar la transacción porque está conciliada!',
        'reconciled_doc'    => 'Advertencia: No se le permite modificar/eliminar :type porque tiene transacciones conciliadas!',
        'disable_code'      => 'Advertencia: no puede desactivar ni cambiar la moneda de <b>:name</b> porque tiene :text relacionado.',
        'payment_cancel'    => 'Advertencia: ¡Ha cancelado su reciente pago con :method!',
        'missing_transfer'  => 'Advertencia: falta la transferencia relacionada con esta transacción. Debería considerar eliminar esta transacción.',
        'connect_tax'       => 'Advertencia: este :type tiene un monto de impuesto. Los impuestos agregados al :type no se pueden conectar, por lo que el impuesto se agregará al total y se calculará en consecuencia.',
        'contact_change'    => 'Advertencia: No se le permite cambiar el contacto en un :type que ya ha sido enviado, recibido o pagado!',
    ],

];
