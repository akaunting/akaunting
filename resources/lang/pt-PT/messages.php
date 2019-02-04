<?php

return [

    'success' => [
        'added'             => ':type adicionado(a)!',
        'updated'           => ':type atualizada(s)!',
        'deleted'           => ':type excluído(a)!',
        'duplicated'        => ':type duplicado(a)!',
        'imported'          => ':type importado(a)!',
        'enabled'           => ':type ativado(a)!',
        'disabled'          => ': type desativado(a)!',
    ],

    'error' => [
        'over_payment'      => 'Erro: Pagamento não adicionado! O valor que inseriu passa o total: :amount',
        'not_user_company'  => 'Erro: Não tem permissão para gerir esta empresa!',
        'customer'          => 'Erro: O utilizador não foi criado! :name já está a usar este e-mail.',
        'no_file'           => 'Erro: Nenhum ficheiro selecionado!',
        'last_category'     => 'Erro: Não pode excluir a última :type categoria!',
        'invalid_token'     => 'Erro: O token inserido é inválido!',
        'import_column'     => 'Erro: :message Nome da folha: :sheet. Linha número: :line.',
        'import_sheet'      => 'Erro: O nome da folha não é válido. Por favor, verifique o ficheiro de exemplo.',
    ],

    'warning' => [
        'deleted'           => 'Aviso: Não está autorizado a excluir <b>:name</b> porque está relacionado com :text.',
        'disabled'          => 'Aviso: Não está autorizado a desativar <b>:name</b> porque está relacionado com :text.',
        'disable_code'      => 'Aviso: Não está autorizado a desativar ou modificar a moeda <b>:name</b> porque está relacionada com :text.',
    ],

];
