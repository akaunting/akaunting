<?php

return [

    'success' => [
        'added'             => ':type adicionado!',
        'updated'           => ':type atualizado!',
        'deleted'           => ':type excluído!',
        'duplicated'        => ':type duplicado!',
        'imported'          => ':type importado!',
        'enabled'           => ':type ativado!',
        'disabled'          => ': type desativado!',
    ],
    'error' => [
        'over_payment'      => 'Erro: Pagamento não adicionado! O valor passa o total.',
        'not_user_company'  => 'Erro: Não tem permissão para gerir esta empresa!',
        'customer'          => 'Erro: O utilizador não foi criado! :name já está a usar este e-mail.',
        'no_file'           => 'Erro: Nenhum ficheiro selecionado!',
        'last_category'     => 'Erro: Não pode excluir a última :type categoria!',
        'invalid_token'     => 'Erro: O token inserido é inválido!',
    ],
    'warning' => [
        'deleted'           => 'Aviso: Não está autorizado a excluir <b>:name</b> porque está relacionado com :text.',
        'disabled'          => 'Aviso: Não está autorizado a desativar <b>:name</b> porque está relacionado com :text.',
    ],

];
