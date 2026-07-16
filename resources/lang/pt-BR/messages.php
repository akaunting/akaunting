<?php

return [

    'success' => [
        'added'             => ':type adicionado!',
        'created'           => ':type criado!',
        'updated'           => ':type atualizado!',
        'deleted'           => ':type excluído!',
        'duplicated'        => ':type duplicado!',
        'imported'          => ':type importado!',
        'import_queued'     => 'A importação de :type foi agendada! Você receberá um e-mail quando terminar.',
        'exported'          => ':type exportado!',
        'export_queued'     => 'A exportação de :type da página atual foi agendada! Você receberá um e-mail quando estiver pronto para baixar.',
        'download_queued'   => 'O download de :type da página atual foi agendado! Você receberá um e-mail quando estiver pronto para baixar.',
        'enabled'           => ':type habilitado!',
        'disabled'          => ':type desativado!',
        'connected'         => ':type conectado!',
        'invited'           => ':type convidado!',
        'ended'             => ':type finalizado!',

        'clear_all'         => 'Ótimo! Você limpou todos os :type.',
    ],

    'error' => [
        'over_payment'      => 'Erro: Pagamento não adicionado! O valor que você inseriu ultrapassa o total: :amount',
        'not_user_company'  => 'Erro: Você não tem permissão para gerenciar esta empresa!',
        'customer'          => 'Erro: Usuário não criado! :name já usa este endereço de e-mail.',
        'no_file'           => 'Erro: Nenhum arquivo selecionado!',
        'last_category'     => 'Erro: Não é possível excluir a última categoria de <b>:type</b>!',
        'transfer_category' => 'Erro: Não é possível excluir a categoria de transferência <b>:type</b>!',
        'change_type'       => 'Erro: Não é possível alterar o tipo porque possui :text relacionado!',
        'invalid_apikey'    => 'Erro: A chave de API inserida é inválida!',
        'empty_apikey'      => 'Erro: Você não inseriu sua chave de API! <a href=":url" class="font-bold underline underline-offset-4">Clique aqui</a> para inserir sua chave de API.',
        'import_column'     => 'Erro: :message Nome da coluna: :column. Número da linha: :line.',
        'import_sheet'      => 'Erro: O nome da planilha não é válido. Por favor, verifique o arquivo de exemplo.',
        'same_amount'       => 'Erro: O valor total da divisão deve ser exatamente o mesmo que o total da :transaction: :amount',
        'over_match'        => 'Erro: :type não conectado! O valor que você inseriu não pode ultrapassar o total do pagamento: :amount',
    ],

    'warning' => [
        'deleted'           => 'Aviso: Você não tem permissão para excluir <b>:name</b> porque possui :text relacionado.',
        'disabled'          => 'Aviso: Você não tem permissão para desativar <b>:name</b> porque possui :text relacionado.',
        'reconciled_tran'   => 'Aviso: Você não tem permissão para alterar/excluir a transação porque está reconciliada!',
        'reconciled_doc'    => 'Aviso: Você não tem permissão para alterar/excluir :type porque possui transações reconciliadas!',
        'disable_code'      => 'Aviso: Você não tem permissão para desativar ou alterar a moeda de <b>:name</b> porque possui :text relacionado.',
        'payment_cancel'    => 'Aviso: Você cancelou seu pagamento recente via :method!',
        'missing_transfer'  => 'Aviso: A transferência relacionada a esta transação está faltando. Considere excluir esta transação.',
        'connect_tax'       => 'Aviso: Este :type possui um valor de imposto. Impostos adicionados ao :type não podem ser conectados, portanto, o imposto será adicionado ao total e calculado adequadamente.',
        'contact_change'    => 'Aviso: Você não tem permissão para alterar o contato em um :type que já foi enviado, recebido ou pago!',
    ],

];
