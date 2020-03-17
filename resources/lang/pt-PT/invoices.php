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
    'mark_paid'             => 'Marcar como Pago',
    'mark_sent'             => 'Marcar como Enviada',
    'mark_viewed'           => 'Mark Viewed',
    'download_pdf'          => 'Transferir em PDF',
    'send_mail'             => 'Enviar E-mail',
    'all_invoices'          => 'Faça o login para ver todas as faturas',
    'create_invoice'        => 'Criar Fatura',
    'send_invoice'          => 'Enviar Factura',
    'get_paid'              => 'Obter Pagamento',
    'accept_payments'       => 'Aceitar Pagamentos Online',

    'statuses' => [
        'draft'             => 'Rascunho',
        'sent'              => 'Sent',
        'viewed'            => 'Viewed',
        'approved'          => 'Approved',
        'partial'           => 'Parcial',
        'paid'              => 'Paid',
        'overdue'           => 'Overdue',
        'unpaid'            => 'Por Pagar',
    ],

    'messages' => [
        'email_sent'        => 'Invoice email has been sent!',
        'marked_sent'       => 'Invoice marked as sent!',
        'marked_paid'       => 'Invoice marked as paid!',
        'email_required'    => 'Nenhum endereço de e-mail para este cliente!',
        'draft'             => 'Isto é um <b>RASCUNHO</b> da fatura e será refletida nos gráficos depois de enviada.',

        'status' => [
            'created'       => 'Criada em :date',
            'viewed'        => 'Viewed',
            'send' => [
                'draft'     => 'Não Enviada',
                'sent'      => 'Enviada em :data',
            ],
            'paid' => [
                'await'     => 'Aguarda pagamento',
            ],
        ],
    ],

];
