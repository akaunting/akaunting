<?php

return [

    'success' => [
        'added'             => ':type adicionado(a)!',
        'updated'           => ':type atualizada(s)!',
        'deleted'           => ':type eliminado(a)!',
        'duplicated'        => ':type duplicado(a)!',
        'imported'          => ':type importado(a)!',
        'exported'          => ':type importado(a)!',
        'enabled'           => ':type ativado(a)!',
        'disabled'          => ':type desativado(a)!',
    ],

    'error' => [
        'over_payment'      => 'Erro: Pagamento não adicionado! O valor que inseriu passa o total: :amount',
        'not_user_company'  => 'Erro: Não tem permissão para gerir esta empresa!',
        'customer'          => 'Erro: O utilizador não foi criado! :name já está a usar este e-mail.',
        'no_file'           => 'Erro: Nenhum ficheiro selecionado!',
        'last_category'     => 'Erro: Não pode eliminara última :type categoria!',
        'change_type'       => 'Erro: Não é possível alterar o tipo porque tem :text relacionado!',
        'invalid_apikey'    => 'Erro: A chave de API inserida é inválida!',
        'import_column'     => 'Erro: :message Nome da folha: :sheet. Linha número: :line.',
        'import_sheet'      => 'Erro: O nome da folha não é válido. Por favor, verifique o ficheiro de exemplo.',
    ],

    'warning' => [
        'deleted'           => 'Aviso: Não é permitido eliminar<b>:name</b> porque está relacionado com :text.',
        'disabled'          => 'Aviso: Não é permitido desativar <b>:name</b> porque está relacionado com :text.',
        'reconciled_tran'   => 'Aviso: Não é permitido alterar/eliminar transações porque estão reconciliadas!',
        'reconciled_doc'    => 'Aviso: Não é permitido alterar/eliminar :type, porque tem transações reconciliadas!',
        'disable_code'      => 'Aviso: Não é permitido desativar ou modificar a moeda <b>:name</b> porque está relacionada com :text.',
        'payment_cancel'    => 'Aviso: Cancelou o método de pagamento recente :method!',
    ],

];
