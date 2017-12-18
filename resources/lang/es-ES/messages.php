<?php

return [

    'success' => [
        'added'             => ':type creado!',
        'updated'           => ':type actualizado!',
        'deleted'           => ':type borrado!',
        'duplicated'        => ': type duplicado!',
        'imported'          => ':type importado!',
    ],
    'error' => [
        'payment_add'       => 'Error: No puede agregar pago! Usted debe comprobar cantidad agregada.',
        'not_user_company'  => 'Error: No tiene permisos para administrar esta empresa!',
        'customer'          => 'Error: No se puede crear el usuario! :name usa esta dirección de correo electrónico.',
        'no_file'           => 'Error: Ningún archivo seleccionado!',
    ],
    'warning' => [
        'deleted'           => 'Advertencia: No puede borrar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: No se permite desactivar <b>:name</b> porque tiene :text relacionado.',
    ],

];
