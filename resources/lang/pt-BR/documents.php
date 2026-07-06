<?php

return [

    'edit_columns'              => 'Editar colunas',
    'empty_items'               => 'Você não adicionou nenhum item.',
    'grand_total'               => 'Total Geral',
    'accept_payment_online'     => 'Aceite Pagamentos Online',
    'transaction'               => 'Um pagamento para :amount foi feito usando :account.',
    'portal_transaction'        => 'Um pagamento para :amount foi feito usando :payment_method.',
    'billing'                   => 'Cobrança',
    'advanced'                  => 'Avançado',

    'item_price_hidden'         => 'Esta coluna está oculta no seu :type.',

    'actions' => [
        'cancel'                => 'Cancelar',
    ],

    'invoice_detail' => [
        'marked'                => '<b> Você </b> marcou esta fatura como',
        'services'              => 'Serviços',
        'another_item'          => 'Outro item',
        'another_description'   => 'e outra descrição',
        'more_item'             => '+:count mais itens',
    ],

    'statuses' => [
        'draft'                 => 'Rascunho',
        'sent'                  => 'Enviado',
        'expired'               => 'Expirado',
        'viewed'                => 'Visualizado',
        'approved'              => 'Aprovado',
        'received'              => 'Recebido',
        'refused'               => 'Recusado',
        'restored'              => 'Restaurado',
        'reversed'              => 'Revertido',
        'partial'               => 'Parcial',
        'paid'                  => 'Pago',
        'pending'               => 'Pendente',
        'invoiced'              => 'Faturado',
        'overdue'               => 'Vencido',
        'unpaid'                => 'Não Pago',
        'cancelled'             => 'Cancelado',
        'voided'                => 'Anulado',
        'completed'             => 'Concluído',
        'shipped'               => 'Enviado',
        'refunded'              => 'Reembolsado',
        'failed'                => 'Falhou',
        'denied'                => 'Negado',
        'processed'             => 'Processado',
        'open'                  => 'Aberto',
        'closed'                => 'Fechado',
        'billed'                => 'Faturado',
        'delivered'             => 'Entregue',
        'returned'              => 'Devolvido',
        'drawn'                 => 'Rascunho',
        'not_billed'            => 'Não faturado',
        'issued'                => 'Emitido',
        'not_invoiced'          => 'Não faturado',
        'confirmed'             => 'Confirmado',
        'not_confirmed'         => 'Não confirmado',
        'active'                => 'Ativo',
        'ended'                 => 'Finalizado',
    ],

    'form_description' => [
        'companies'             => 'Altere o endereço, logotipo, e outras informações para sua empresa.',
        'billing'               => 'Detalhes de faturamento aparecem no seu documento.',
        'advanced'              => 'Selecione a categoria, adicione ou edite o rodapé e adicione anexos ao seu :type.',
        'attachment'            => 'Baixar os arquivos anexados a esse :type',
    ],

    'slider' => [
        'create'            => ':user criou este :type em :date',
        'create_recurring'  => ':user criou este modelo recorrente em :date',
        'send'              => ':user enviou este :type em :date',
        'schedule'          => 'Repetir a cada :interval :frequency desde :date',
        'children'          => ':count :type foram criados automaticamente',
        'cancel'            => ':user cancelou este :type em :date',
    ],

    'messages' => [
        'email_sent'            => 'E-mail :type foi enviado!',
        'restored'              => ':type foi restaurado!',
        'marked_as'             => ':type marcado como :status!',
        'marked_sent'           => ':type marcado como enviado!',
        'marked_paid'           => ':type marcado como pago!',
        'marked_viewed'         => ':type marcado como visualizado!',
        'marked_cancelled'      => ':type marcado como cancelado!',
        'marked_received'       => ':type marcado como recebido!',
    ],

    'recurring' => [
        'auto_generated'        => 'Gerado automaticamente',

        'tooltip' => [
            'document_date'     => 'A data :type será automaticamente atribuída com base no agendamento e frequência :type.',
            'document_number'   => 'O número :type será automaticamente atribuído quando cada :type recorrente é gerado.',
        ],
    ],

    'empty_attachments'         => 'Não há arquivos anexados a este :type.',
];
