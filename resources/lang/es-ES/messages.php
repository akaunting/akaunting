<?php

return [

    'success' => [
        'added'             => ':type creado!',
        'updated'           => ':type actualizado!',
        'deleted'           => ':type borrado!',
        'duplicated'        => ': type duplicado!',
        'imported'          => ':type importado!',
        'enabled'           => ':type habilitado!',
        'disabled'          => ':type deshabilitado!',
    ],
    'error' => [
        'over_payment'      => 'Error: Pago no añadido! Importe mayor que el total.',
        'not_user_company'  => 'Error: No tiene permisos para administrar esta empresa!',
        'customer'          => 'Error: Usuario no creado! :name ya utiliza esta dirección de correo electrónico.',
        'no_file'           => 'Error: Ningún archivo seleccionado!',
        'last_category'     => 'Error: No puede eliminar la última :type categoría!',
        'invalid_token'     => 'Error: El token introducido es inválido!',
    ],
    'warning' => [
        'deleted'           => 'Advertencia: No puede borrar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: No se permite desactivar <b>:name</b> porque tiene :text relacionado.',
    ],

];
