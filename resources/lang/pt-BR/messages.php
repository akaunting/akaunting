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
        'not_user_company'  => 'Erro: você não tem permissão para gerenciar esta empresa!',
        'customer'          => 'Error: You can not created user! :name use this email address.',
        'no_file'           => 'Error: No file selected!',
    ],
    'warning' => [
        'deleted'           => 'Warning: You are not allowed to delete <b>:name</b> because it has :text related.',
        'disabled'          => 'Warning: You are not allowed to disable <b>:name</b> because it has :text related.',
    ],

];
