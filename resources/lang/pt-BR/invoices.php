<?php

return [

    'invoice_number'        => 'Número da Fatura',
    'invoice_date'          => 'Data de Emissão',
    'invoice_amount'        => 'Valor da Fatura',
    'total_price'           => 'Valor total',
    'due_date'              => 'Data de Vencimento',
    'order_number'          => 'Número',
    'bill_to'               => 'Pagar para',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
    'item_discount'         => 'Linha de desconto',
    'tax_total'             => 'Valor da taxa',
    'total'                 => 'Total',

    'item_name'             => 'Item|Itens',

    'show_discount'         => ':discount% desconto',
    'add_discount'          => 'Adicionar desconto',
    'discount_desc'         => 'subtotal',

    'payment_due'           => 'Pagamento vencido',
    'paid'                  => 'Pago',
    'histories'             => 'Histórico',
    'payments'              => 'Pagamentos',
    'add_payment'           => 'Novo Pagamento',
    'mark_paid'             => 'Marcar como pago',
    'mark_sent'             => 'Marcar Como Enviada',
    'mark_viewed'           => 'Marcar como visualizado',
    'mark_cancelled'        => 'Marcar como cancelado',
    'download_pdf'          => 'Baixar em PDF',
    'send_mail'             => 'Enviar E-mail',
    'all_invoices'          => 'Faça login para ver todas as faturas',
    'create_invoice'        => 'Criar fatura',
    'send_invoice'          => 'Enviar fatura',
    'get_paid'              => 'Pagar',
    'accept_payments'       => 'Aceitar Pagamentos Online',

    'messages' => [
        'email_required'    => 'Nenhum endereço de e-mail para este cliente!',
        'draft'             => 'Isto é um <b>RASCUNHO</b> da fatura e será refletida nos gráficos depois de enviada.',

        'status' => [
            'created'       => 'Criado em :date',
            'viewed'        => 'Visualizado',
            'send' => [
                'draft'     => 'Não enviado',
                'sent'      => 'Enviado em :date',
            ],
            'paid' => [
                'await'     => 'Aguardando pagamento',
            ],
        ],
    ],

];
