<?php

return [

    'success' => [
        'added'             => '¡:type agregado!',
        'updated'           => '¡:type actualizado!',
        'deleted'           => '¡:type eliminado!',
        'duplicated'        => '¡:type duplicado!',
        'imported'          => '¡:type importado!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Error: ¡No tiene permisos para administrar esta empresa!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Error: ¡Ningún archivo se ha seleccionado!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Advertencia: No puede borrar <b>:name</b> porque tiene :text relacionado.',
        'disabled'          => 'Advertencia: No se permite desactivar <b>:name</b> porque tiene :text relacionado.',
    ],

];
