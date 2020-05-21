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
    'add_payment'           => 'Pagar Conta',
    'mark_paid'             => 'Mark Paid',
    'mark_received'         => 'Marcar como Recebida',
    'mark_cancelled'        => 'Mark Cancelled',
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
        'cancelled'         => 'Cancelled',
    ],

    'messages' => [
        'marked_received'   => 'Bill marked as received!',
        'marked_paid'       => 'Bill marked as paid!',
        'marked_cancelled'  => 'Bill marked as cancelled!',
        'draft'             => 'Isto é um <b>RASCUNHO</b> da conta e será refletida nos gráficos depois de recebida.',

        'status' => [
            'created'       => 'Criado em :date',
            'receive' => [
                'draft'     => 'Não enviado',
                'received'  => 'Recebido em :date',
            ],
            'paid' => [
                'await'     => 'Aguarda pagamento',
            ],
        ],
    ],

];
