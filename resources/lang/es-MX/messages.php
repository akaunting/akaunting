<?php

return [

    'success' => [
        'added'             => '¡:type agregado!',
        'updated'           => '¡:type actualizado!',
        'deleted'           => '¡:type eliminado!',
        'duplicated'        => '¡:type duplicado!',
        'imported'          => '¡:type importado!',
        'exported'          => '¡:type exportado!',
        'enabled'           => ':type activado!',
        'disabled'          => ':type desactivado!',
    ],

    'error' => [
        'over_payment'      => 'Error: ¡Pago no añadido! La cantidad que ingresó pasa el total: :amount',
        'not_user_company'  => 'Error: ¡No tiene permisos para administrar esta empresa!',
        'customer'          => 'Error: Usuario no creado! :nombre ya utiliza esta dirección de correo.',
        'no_file'           => 'Error: ¡Ningún archivo se ha seleccionado!',
        'last_category'     => 'Error: No se pudo eliminar el ultimo No. :type category!',
        'change_type'       => 'Error: No se puede cambiar el tipo porque tiene :text relacionado!',
        'invalid_apikey'    => 'Error: ¡La clave de API introducida no es válida!',
        'import_column'     => 'Error: :message Nombre de la hoja: :sheet. Número de línea: :line.',
        'import_sheet'      => 'Error: El nombre de la hoja no es válido. Por favor, verifique el archivo de ejemplo.',
    ],

    'warning' => [
        'deleted'           => 'Advertencia: No puede borrar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: No se permite desactivar <b>:name</b> porque tiene :text relacionado.',
        'disable_code'      => 'Advertencia: No puede desactivar o cambiar la moneda <b>:name</b> porque tiene :text relacionado.',
        'payment_cancel'    => 'Advertencia: Ha cancelado su reciente método de pago :method!',
    ],

];
