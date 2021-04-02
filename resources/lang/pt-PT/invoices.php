<?php

return [

    'invoice_number'        => 'Fatura nº',
    'invoice_date'          => 'Data de Emissão',
    'total_price'           => 'Valor total',
    'due_date'              => 'Data de Vencimento',
    'order_number'          => 'Encomenda nº',
    'bill_to'               => 'Cobrar a',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
    'item_discount'         => 'Line Discount',
    'tax_total'             => 'Total de imposto',
    'total'                 => 'Total',

    'item_name'             => 'Nome do Item|Nome dos Itens',

    'show_discount'         => ':discount% de desconto',
    'add_discount'          => 'Adicionar Desconto',
    'discount_desc'         => 'do subtotal',

    'payment_due'           => 'Pagamento vencido',
    'paid'                  => 'Pago',
    'histories'             => 'Histórico',
    'payments'              => 'Pagamentos',
    'add_payment'           => 'Adicionar Pagamento',
    'mark_paid'             => 'Marcar como Paga',
    'mark_sent'             => 'Marcar como Enviada',
    'mark_viewed'           => 'Marcar como Visualizada',
    'mark_cancelled'        => 'Marcar como Cancelada',
    'download_pdf'          => 'Transferir em PDF',
    'send_mail'             => 'Enviar E-mail',
    'all_invoices'          => 'Iniciar sessão para ver todas as faturas',
    'create_invoice'        => 'Criar Fatura',
    'send_invoice'          => 'Enviar Factura',
    'get_paid'              => 'Obter Pagamento',
    'accept_payments'       => 'Aceitar Pagamentos Online',

    'statuses' => [
        'draft'             => 'Rascunho',
        'sent'              => 'Enviada',
        'viewed'            => 'Visualizada',
        'approved'          => 'Aprovada',
        'partial'           => 'Parcial',
        'paid'              => 'Paga',
        'overdue'           => 'Vencida',
        'unpaid'            => 'Por Pagar',
        'cancelled'         => 'Cancelada',
    ],

    'messages' => [
        'email_sent'        => 'A fatura foi enviada!',
        'marked_sent'       => 'Fatura marcada como enviada!',
        'marked_paid'       => 'Fatura marcada como paga!',
        'marked_viewed'     => 'Fatura marcada como visualizada!',
        'marked_cancelled'  => 'Fatura marcada como cancelada!',
        'email_required'    => 'Nenhum endereço de e-mail para este cliente!',
        'draft'             => 'Isto é um <b>RASCUNHO</b> da fatura e será refletida nos gráficos depois de enviada.',

        'status' => [
            'created'       => 'Criada em :date',
            'viewed'        => 'Visualizada',
            'send' => [
                'draft'     => 'Não Enviada',
                'sent'      => 'Enviada em :date',
            ],
            'paid' => [
                'await'     => 'Aguarda pagamento',
            ],
        ],
    ],

];
