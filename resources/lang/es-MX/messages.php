<?php

return [

    'success' => [
        'added'             => ':type añadido!',
        'created'           => ':type creado!',
        'updated'           => ':type actualizado!',
        'deleted'           => ':type borrado!',
        'duplicated'        => ': type duplicado!',
        'imported'          => ':type importado!',
        'import_queued'     => 'La importación de :type ha sido programada! Recibirás un correo electrónico cuando haya terminado.',
        'exported'          => ':type exportado!',
        'export_queued'     => 'La exportación de :type ha sido programada! Recibirás un correo electrónico cuando haya terminado.',
        'download_queued'   => 'La descarga de :type de la página actual ha sido programada! Recibirás un correo electrónico cuando esté lista para descargar.',
        'enabled'           => ':type habilitado!',
        'disabled'          => ':type deshabilitado!',
        'connected'         => ':type conectado!',
        'invited'           => ':type invitado!',
        'ended'             => ':type terminado!',

        'clear_all'         => '¡Genial! Has borrado todo tu :type.',
    ],

    'error' => [
        'over_payment'      => 'Error: Pago no añadido! La cantidad que escribiste pasa total: :amount',
        'not_user_company'  => 'Error: No tiene permisos para administrar esta empresa!',
        'customer'          => 'Error: Usuario no creado! :name ya utiliza esta dirección de correo electrónico.',
        'no_file'           => 'Error: Ningún archivo seleccionado!',
        'last_category'     => 'Error: No puede eliminar la última :type categoría!',
        'transfer_category' => 'Error: No puede eliminar la categoría de transferencia <b>:type</b>!',
        'change_type'       => 'Error: No se puede cambiar el tipo porque tiene :text relacionado!',
        'invalid_apikey'    => 'Error: ¡La clave de API introducida no es válida!',
        'empty_apikey'      => 'Error: No ha introducido su clave de API! <a href=":url" class="font-bold underline underline-offset-4">Haga clic aquí</a> para introducir su clave de API.',
        'import_column'     => 'Error: :message Nombre de la hoja: :sheet. Número de línea: :line.',
        'import_sheet'      => 'Error: El nombre de la hoja no es válido. Por favor, verifique el archivo de ejemplo.',
        'same_amount'       => 'Error: La cantidad total de división debe ser exactamente la misma que el :transaction total: :amount',
        'over_match'        => 'Error: :type no conectado! La cantidad introducida no puede exceder el total del pago: :amount',
    ],

    'warning' => [
        'deleted'           => 'Advertencia: No puede borrar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: No se permite desactivar <b>:name</b> porque tiene :text relacionado.',
        'reconciled_tran'   => 'Advertencia: No puedes cambiar/eliminar la transacción porque está reconciliada!',
        'reconciled_doc'    => 'Advertencia: No puedes modificar/eliminar :type porque tiene transacciones reconciliadas!',
        'disable_code'      => 'Advertencia: No puede desactivar o cambiar la moneda <b>:name</b> porque tiene :text relacionado.',
        'payment_cancel'    => 'Advertencia: Ha cancelado su reciente pago de :method!',
        'missing_transfer'  => 'Advertencia: Falta la transferencia relacionada con esta transacción. Debería considerar eliminar esta transacción.',
        'connect_tax'       => 'Advertencia: Este :type tiene un importe de impuesto. Los impuestos añadidos al :type no se pueden conectar, por lo que el impuesto se añadirá al total y se calculará en consecuencia.',
        'contact_change'    => 'Advertencia: No se le permite cambiar el contacto en un :type que ya ha sido enviado, recibido o pagado!',
    ],

];
