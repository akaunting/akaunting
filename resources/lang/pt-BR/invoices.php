<?php

return [

    'invoice_number'        => 'Número da fatura',
    'invoice_date'          => 'Data de emissão',
    'invoice_amount'        => 'Valor da fatura',
    'total_price'           => 'Preço total',
    'due_date'              => 'Data de vencimento',
    'order_number'          => 'Número do pedido',
    'bill_to'               => 'Faturar para',
    'cancel_date'           => 'Data de cancelamento',

    'quantity'              => 'Quantidade',
    'price'                 => 'Preço',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Desconto',
    'item_discount'         => 'Desconto da linha',
    'tax_total'             => 'Total de impostos',
    'total'                 => 'Total',

    'item_name'             => 'Nome do item|Nomes dos itens',
    'recurring_invoices'    => 'Fatura recorrente|Faturas recorrentes',

    'show_discount'         => ':discount% de desconto',
    'add_discount'          => 'Adicionar desconto',
    'discount_desc'         => 'do subtotal',

    'payment_due'           => 'Pagamento devido',
    'paid'                  => 'Pago',
    'histories'             => 'Histórico',
    'payments'              => 'Pagamentos',
    'add_payment'           => 'Adicionar pagamento',
    'mark_paid'             => 'Marcar como paga',
    'mark_sent'             => 'Marcar como enviada',
    'mark_viewed'           => 'Marcar como visualizada',
    'mark_cancelled'        => 'Marcar como cancelada',
    'download_pdf'          => 'Baixar PDF',
    'send_mail'             => 'Enviar e-mail',
    'all_invoices'          => 'Faça login para ver todas as faturas',
    'create_invoice'        => 'Criar fatura',
    'send_invoice'          => 'Enviar fatura',
    'get_paid'              => 'Receber pagamento',
    'accept_payments'       => 'Aceitar pagamentos online',
    'payments_received'     => 'Pagamentos recebidos',
    'over_payment'          => 'O valor que você inseriu ultrapassa o total: :amount',

    'form_description' => [
        'billing'           => 'Os detalhes de faturamento aparecem na sua fatura. A data de emissão é usada no painel e nos relatórios. Selecione a data em que você espera receber o pagamento como data de vencimento.',
    ],

    'messages' => [
        'email_required'    => 'Nenhum endereço de e-mail para este cliente!',
        'totals_required'   => 'Os totais da fatura são obrigatórios. Por favor, edite o :type e salve novamente.',
        'draft'             => 'Esta é uma fatura <b>RASCUNHO</b> e será refletida nos gráficos após ser enviada.',

        'status' => [
            'created'       => 'Criada em :date',
            'viewed'        => 'Visualizada',
            'send' => [
                'draft'     => 'Não enviada',
                'sent'      => 'Enviada em :date',
            ],
            'paid' => [
                'await'     => 'Aguardando pagamento',
            ],
        ],

        'name_or_description_required' => 'Sua fatura deve conter pelo menos um destes: <b>:name</b> ou <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Seu cliente pode visualizar a fatura neste link',
        'copy_link'         => 'Copie o link e compartilhe com seu cliente.',
        'success_message'   => 'Link de compartilhamento copiado para a área de transferência!',
    ],

    'sticky' => [
        'description'       => 'Você está visualizando como seu cliente verá a versão web da sua fatura.',
    ],

];
