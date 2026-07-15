<?php

return [

    'bill_number'           => 'Número da conta',
    'bill_date'             => 'Data de emissão',
    'bill_amount'           => 'Valor da conta',
    'total_price'           => 'Preço total',
    'due_date'              => 'Data de vencimento',
    'order_number'          => 'Número do pedido',
    'bill_from'             => 'Conta de',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
    'item_discount'         => 'Desconto da linha',
    'tax_total'             => 'Total de impostos',
    'total'                 => 'Total',

    'item_name'             => 'Nome do item|Nomes dos itens',
    'recurring_bills'       => 'Conta recorrente|Contas recorrentes',

    'show_discount'         => ':discount% de desconto',
    'add_discount'          => 'Adicionar desconto',
    'discount_desc'         => 'do subtotal',

    'payment_made'          => 'Pagamento efetuado',
    'payment_due'           => 'Pagamento devido',
    'amount_due'            => 'Valor devido',
    'paid'                  => 'Pago',
    'histories'             => 'Histórico',
    'payments'              => 'Pagamentos',
    'add_payment'           => 'Adicionar pagamento',
    'mark_paid'             => 'Marcar como pago',
    'mark_received'         => 'Marcar como recebida',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Baixar PDF',
    'send_mail'             => 'Enviar e-mail',
    'create_bill'           => 'Criar conta',
    'receive_bill'          => 'Receber conta',
    'make_payment'          => 'Fazer pagamento',

    'form_description' => [
        'billing'           => 'Os detalhes de faturamento aparecem na sua conta. A data de emissão é usada no painel e nos relatórios. Selecione a data em que você espera pagar como data de vencimento.',
    ],

    'messages' => [
        'draft'             => 'Esta é uma <b>RASCUNHO</b> conta e será refletida nos gráficos após ser recebida.',

        'status' => [
            'created'       => 'Criada em :date',
            'receive' => [
                'draft'     => 'Não recebida',
                'received'  => 'Recebida em :date',
            ],
            'paid' => [
                'await'     => 'Aguardando pagamento',
            ],
        ],
    ],

];
