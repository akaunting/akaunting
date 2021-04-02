<?php

return [

    'bill_number'           => 'Conta nº',
    'bill_date'             => 'Data de Emissão',
    'total_price'           => 'Valor Total',
    'due_date'              => 'Data de Vencimento',
    'order_number'          => 'Encomenda nº',
    'bill_from'             => 'Conta de',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'Imposto',
    'total'                 => 'Total',

    'item_name'             => 'Nome do Item|Nome dos Itens',

    'show_discount'         => ':discount% de desconto',
    'add_discount'          => 'Adicionar desconto',
    'discount_desc'         => 'do subtotal',

    'payment_due'           => 'Valor Devido',
    'amount_due'            => 'Total Devido',
    'paid'                  => 'Pago',
    'histories'             => 'Histórico',
    'payments'              => 'Pagamentos',
    'add_payment'           => 'Adicionar Pagamento',
    'mark_paid'             => 'Marcar como Paga',
    'mark_received'         => 'Marcar como Recebida',
    'mark_cancelled'        => 'Marcar como Cancelada',
    'download_pdf'          => 'Transferir em PDF',
    'send_mail'             => 'Enviar e-mail',
    'create_bill'           => 'Criar Conta',
    'receive_bill'          => 'Receber Conta',
    'make_payment'          => 'Fazer Pagamento',

    'statuses' => [
        'draft'             => 'Rascunho',
        'received'          => 'Recebida',
        'partial'           => 'Parcial',
        'paid'              => 'Paga',
        'overdue'           => 'Vencida',
        'unpaid'            => 'Por Pagar',
        'cancelled'         => 'Cancelada',
    ],

    'messages' => [
        'marked_received'   => 'Conta marcada como recebida!',
        'marked_paid'       => 'Conta marcada como paga!',
        'marked_cancelled'  => 'Conta marcada como cancelada!',
        'draft'             => 'Isto é um <b>RASCUNHO</b> da conta e será refletida nos gráficos depois de recebida.',

        'status' => [
            'created'       => 'Criada em :date',
            'receive' => [
                'draft'     => 'Não Recebida',
                'received'  => 'Recebida em :date',
            ],
            'paid' => [
                'await'     => 'Aguarda pagamento',
            ],
        ],
    ],

];
