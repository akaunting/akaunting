<?php

return [

    'success' => [
        'added'             => ':type adicionado!',
        'updated'           => ':type atualizado!',
        'deleted'           => ':type excluído!',
        'duplicated'        => ':type duplicated!',
        'imported'          => ':type imported!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Erro: você não tem permissão para gerenciar esta empresa!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Error: No file selected!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Warning: You are not allowed to delete <b>:name</b> because it has :text related.',
        'disabled'          => 'Warning: You are not allowed to disable <b>:name</b> because it has :text related.',
    ],

];
