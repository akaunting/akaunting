<?php

return [

    'bill_number'           => 'Número da conta',
    'bill_date'             => 'Data de Emissão',
    'total_price'           => 'Valor Total',
    'due_date'              => 'Data de Vencimento',
    'order_number'          => 'Número',
    'bill_from'             => 'Bill From',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
    'tax_total'             => 'Taxa',
    'total'                 => 'Total',

    'item_name'             => 'Nome(s) do(s) Item(s)',

    'show_discount'         => ':discount% desconto',
    'add_discount'          => 'Adicionar desconto',
    'discount_desc'         => 'subtotal',

    'payment_due'           => 'Valor Devido',
    'amount_due'            => 'Total Devido',
    'paid'                  => 'Pago',
    'histories'             => 'Histórico',
    'payments'              => 'Pagamentos',
    'add_payment'           => 'Novo Pagamento',
    'mark_received'         => 'Marcar como Recebida',
    'download_pdf'          => 'Baixar em PDF',
    'send_mail'             => 'Enviar E-mail',
    'create_bill'           => 'Criar fatura',
    'receive_bill'          => 'Receber fatura',
    'make_payment'          => 'Fazer pagamento',

    'status' => [
        'draft'             => 'Rascunho',
        'received'          => 'Recebido',
        'partial'           => 'Parcial',
        'paid'              => 'Pago',
    ],

    'messages' => [
        'received'          => 'Conta marcada como recebida com sucesso!',
        'draft'             => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'       => 'Criado em :date',
            'receive' => [
                'draft'     => 'Não enviado',
                'received'  => 'Recebido em :date',
            ],
            'paid' => [
                'await'     => 'Aguardando pagamento',
            ],
        ],
    ],

];
