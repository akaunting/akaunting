<?php

return [

    'payment_received'      => 'Pagamento recebido',
    'payment_made'          => 'Pagamento efetuado',
    'paid_by'               => 'Pago por',
    'paid_to'               => 'Pago para',
    'related_invoice'       => 'Fatura relacionada',
    'related_bill'          => 'Conta relacionada',
    'recurring_income'      => 'Receita recorrente',
    'recurring_expense'     => 'Despesa recorrente',
    'included_tax'          => 'Valor do imposto incluído',
    'connected'             => 'Conectado',
    'connect_message'       => 'Os impostos para este :type não foram calculados durante o processo de conexão. Os impostos não podem ser conectados.',

    'form_description' => [
        'general'           => 'Aqui você pode inserir as informações gerais da transação, como data, valor, conta, descrição, etc.',
        'assign_income'     => 'Selecione uma categoria e um cliente para tornar seus relatórios mais detalhados.',
        'assign_expense'    => 'Selecione uma categoria e um fornecedor para tornar seus relatórios mais detalhados.',
        'other'             => 'Digite um número e uma referência para manter a transação vinculada aos seus registros.',
    ],

    'slider' => [
        'create'            => ':user criou esta transação em :date',
        'attachments'       => 'Baixar os arquivos anexados a esta transação',
        'create_recurring'  => ':user criou este modelo recorrente em :date',
        'schedule'          => 'Repetir a cada :interval :frequency a partir de :date',
        'children'          => ':count transações foram criadas automaticamente',
        'connect'           => 'Esta transação está conectada a :count transações',
        'transfer_headline' => '<div> <span class="font-bold">De: </span> :from_account </div> <div> <span class="font-bold">Para: </span> :to_account </div>',
        'transfer_desc'     => 'Transferência criada em :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Seu cliente pode visualizar a transação neste link',
            'copy_link'     => 'Copie o link e compartilhe com seu cliente.',
        ],

        'expense' => [
            'show_link'     => 'Seu fornecedor pode visualizar a transação neste link',
            'copy_link'     => 'Copie o link e compartilhe com seu fornecedor.',
        ],
    ],

    'sticky' => [
        'description'       => 'Você está visualizando como seu cliente verá a versão web do seu pagamento.',
    ],

    'messages' => [
        'update_document_transaction' => 'Você pode atualizar esta transação. Você deve ir ao documento e editá-lo lá.',
        'create_document_transaction_error' => 'Este endpoint não pode ser adicionado a um documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'Este endpoint não pode ser atualizado para um documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'Este endpoint não pode ser excluído de um documento. Use {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ],

];
