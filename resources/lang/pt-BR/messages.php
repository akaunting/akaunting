<?php

return [

    'success' => [
        'added'             => ':type adicionado!',
        'updated'           => ':type atualizado!',
        'deleted'           => ':type excluído!',
        'duplicated'        => ':type duplicado!',
        'imported'          => ':type importado!',
    ],
    'error' => [
        'over_payment'      => 'Erro: Pagamento não adicionado! Quantidade ultrapassa o total.',
        'not_user_company'  => 'Erro: você não tem permissão para gerenciar esta empresa!',
        'customer'          => 'Erro: Usuário não criado! :name já está utilizando este endereço de email.',
        'no_file'           => 'Erro: Nenhum arquivo selecionado!',
        'last_category'     => 'Erro: Não é possível excluir a última categoria do tipo :type!',
        'invalid_token'     => 'Erro: O token informado é inválido!',
    ],
    'warning' => [
        'deleted'           => 'Atenção: Você não tem perissão para excluir <b>:name</b> pois existe(m) :text relacionado(a).',
        'disabled'          => 'Atenção: Você não tem perissão para desabilitar <b>:name</b> pois existe(m) :text relacionado(a).',
    ],

];
