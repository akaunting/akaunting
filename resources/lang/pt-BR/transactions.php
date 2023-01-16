<?php

return [

    'payment_received'      => 'Pagamento recebido',
    'payment_made'          => 'Pagamento efetuado',
    'paid_by'               => 'Pago por',
    'paid_to'               => 'Pago para',
    'related_invoice'       => 'Fatura Relacionada',
    'related_bill'          => 'Conta Relacionada',
    'recurring_income'      => 'Receita recorrente',
    'recurring_expense'     => 'Despesa recorrente',

    'form_description' => [
        'general'           => 'Aqui você pode digitar as informações gerais da transação tais como data, valor, conta, descrição, etc.',
        'assign_income'     => 'Selecione uma categoria e um cliente para tornar seus relatórios mais detalhados.',
        'assign_expense'    => 'Selecione uma categoria e um fornecedor para tornar seus relatórios mais detalhados.',
        'other'             => 'Digite uma referência para manter a transação ligada aos seus registros.',
    ],

    'slider' => [
        'create'            => ':user criou essa transação em :date',
        'attachments'       => 'Baixar os arquivos anexados a essa transação',
        'create_recurring'  => ':user criou esse modelo recorrente em :date',
        'schedule'          => 'Repetir a cada :interval :frequency a partir de :date',
        'children'          => ':count transações foram criadas automaticamente',
        'transfer_headline' => 'De :from_account para :to_account',
        'transfer_desc'     => 'Transferência criada em :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Seu cliente pode visualizar a transação nesse link',
            'copy_link'     => 'Copiar o link e compartilhá-lo com seu cliente.',
        ],

        'expense' => [
            'show_link'     => 'Seu fornecedor pode visualizar a transação nesse link',
            'copy_link'     => 'Copie o link e compartilhe-o com seu fornecedor.',
        ],
    ],

    'sticky' => [
        'description'       => 'Você está pré-visualizando como seu cliente verá a versão web de seu pagamento.',
    ],

];
