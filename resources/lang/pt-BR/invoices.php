<?php

return [

    'invoice_number'        => 'Número da Fatura',
    'invoice_date'          => 'Data de Emissão',
    'total_price'           => 'Valor total',
    'due_date'              => 'Data de Vencimento',
    'order_number'          => 'Número',
    'bill_to'               => 'Pagar para',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
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
    'download_pdf'          => 'Baixar em PDF',
    'send_mail'             => 'Enviar E-mail',
    'all_invoices'          => 'Faça login para ver todas as faturas',
    'create_invoice'        => 'Criar fatura',
    'send_invoice'          => 'Enviar fatura',
    'get_paid'              => 'Pagar',
    'accept_payments'       => 'Aceitar os Termos de Pagamento Online',

    'status' => [
        'draft'             => 'Rascunho',
        'sent'              => 'Enviar',
        'viewed'            => 'Visto',
        'approved'          => 'Aprovado',
        'partial'           => 'Parcial',
        'paid'              => 'Pago',
    ],

    'messages' => [
        'email_sent'        => 'O e-mail foi enviado com sucesso!',
        'marked_sent'       => 'Fatura marcada como enviada com sucesso!',
        'email_required'    => 'Nenhum endereço de e-mail para este cliente!',
        'draft'             => 'Este é um <b>RASCUNHO</b> de fatura e será refletida nos gráficos depois que ela for enviada.',

        'status' => [
            'created'       => 'Criado em :date',
            'send' => [
                'draft'     => 'Não enviado',
                'sent'      => 'Enviado em :date',
            ],
            'paid' => [
                'await'     => 'Aguardando pagamento',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Você está recebendo este e-mail porque tem :amount fatura a vencer.',
        'button'            => 'Pagar agora',
    ],

];
