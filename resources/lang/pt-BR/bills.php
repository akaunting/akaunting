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
    'item_discount'         => 'Linha de desconto',
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
    'mark_paid'             => 'Marcar como pago',
    'mark_received'         => 'Marcar como Recebida',
    'mark_cancelled'        => 'Marcar como cancelado',
    'download_pdf'          => 'Baixar em PDF',
    'send_mail'             => 'Enviar E-mail',
    'create_bill'           => 'Criar fatura',
    'receive_bill'          => 'Receber fatura',
    'make_payment'          => 'Fazer pagamento',

    'statuses' => [
        'draft'             => 'Rascunho',
        'received'          => 'Recebido',
        'partial'           => 'Parcial',
        'paid'              => 'Pago',
        'overdue'           => 'Vencido',
        'unpaid'            => 'Não Pago',
        'cancelled'         => 'Cancelado',
    ],

    'messages' => [
        'marked_received'   => 'Fatura marcada como paga',
        'marked_paid'       => 'Fatura marcada como paga!',
        'marked_cancelled'  => 'Fatura marcada como cancelada',
        'draft'             => 'Este é um <b>RASCUNHO</b> de fatura e será refletida nos gráficos depois que ela for recebida.',

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
