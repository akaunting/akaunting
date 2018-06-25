<?php

return [

    'success' => [
        'added'             => '¡:type agregado!',
        'updated'           => '¡:type actualizado!',
        'deleted'           => '¡:type eliminado!',
        'duplicated'        => '¡:type duplicado!',
        'imported'          => '¡:type importado!',
        'enabled'           => ':type activado!',
        'disabled'          => ':type desactivado!',
    ],
    'error' => [
        'over_payment'      => 'Error: Pago no agregado! Cantidad sobre pasa el total.',
        'not_user_company'  => 'Error: ¡No tiene permisos para administrar esta empresa!',
        'customer'          => 'Error: Usuario no creado! :nombre ya utiliza esta dirección de correo.',
        'no_file'           => 'Error: ¡Ningún archivo se ha seleccionado!',
        'last_category'     => 'Error: No se pudo eliminar el ultimo No. :type category!',
        'invalid_token'     => 'Error: El token ingresado es invalido!',
    ],
    'warning' => [
        'deleted'           => 'Advertencia: No puede borrar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: No se permite desactivar <b>:name</b> porque tiene :text relacionado.',
    ],

];
