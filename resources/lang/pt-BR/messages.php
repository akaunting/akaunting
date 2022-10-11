<?php

return [

    'success' => [
        'added'             => ':type adicionado!',
        'updated'           => ':type atualizado!',
        'deleted'           => ':type excluído!',
        'duplicated'        => ':type duplicado!',
        'imported'          => ':type importado!',
        'import_queued'     => ':type importação foi agendada! Você receberá um e-mail quando terminar.',
        'exported'          => ':type exportado!',
        'export_queued'     => ':type exportação foi agendada! Você receberá um e-mail quando estiver pronto para baixar.',
        'enabled'           => ': tipo habilitado!',
        'disabled'          => ': tipo desativado!',
        'connected'         => ':type conectado!',
        'invited'           => ':type convidado!',
        'ended'             => ':type finalizado!',

        'clear_all'         => 'Ótimo! Você limpou todos os :type.',
    ],

    'error' => [
        'over_payment'      => 'Erro: Pagamento não adicionado! O valor que você inseriu é maior que o total: :amount',
        'not_user_company'  => 'Erro: você não tem permissão para gerenciar esta empresa!',
        'customer'          => 'Erro: Endereço de email :name já esta sendo utilizado.',
        'no_file'           => 'Erro: Nenhum arquivo selecionado!',
        'last_category'     => 'Erro: Não foi possível excluir a última :type categoria!',
        'change_type'       => 'Erro: não é possível alterar o tipo porque tem :text relacionado!',
        'invalid_apikey'    => 'Erro: A chave de API inserida é inválida!',
        'import_column'     => 'Erro: :message Planilha: :sheet. Número da linha: :line.',
        'import_sheet'      => 'Erro: Planilha não é válida. Por favor, verifique o arquivo de exemplo.',
        'same_amount'       => 'Erro: O valor total da divisão deve ser exatamente o mesmo que o :transaction total: :amount',
        'over_match'        => 'Erro: :type não conectado! O valor inserido não pode exceder o total do pagamento: :valor',
    ],

    'warning' => [
        'deleted'           => 'Aviso: Você não têm permissão para excluir <b>:name</b>, porque possui o :text relacionado.',
        'disabled'          => 'Aviso: Você não tem permissão para desativar <b>:name</b>, porque tem :text relacionado.',
        'reconciled_tran'   => 'Aviso: Você não tem permissão para alterar/excluir transações porque elas estão reconciliadas!',
        'reconciled_doc'    => 'Aviso: Você não tem permissão para alterar/excluir :type, porque as transações estão reconciliadas!',
        'disable_code'      => 'Aviso: você não tem permissão para desativar ou alterar a moeda de <b>:name</b> porque possui :text relacionado.',
        'payment_cancel'    => 'Aviso: Você cancelou recentemente o método de pagamento :method!',
        'missing_transfer'  => 'Aviso: A transferência relacionada a esta transação está faltando. Considere a exclusão desta transação.',
    ],

];
