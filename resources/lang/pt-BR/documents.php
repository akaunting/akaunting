<?php

return [

    'edit_columns'              => 'Editar colunas',
    'empty_items'               => 'Você não adicionou nenhum item.',
    'grand_total'               => 'Total geral',
    'accept_payment_online'     => 'Aceitar pagamentos online',
    'transaction'               => 'Um pagamento de :amount foi feito usando :account.',
    'portal_transaction'        => 'Um pagamento de :amount foi feito usando :payment_method.',
    'billing'                   => 'Faturamento',
    'advanced'                  => 'Avançado',

    'item_price_hidden'         => 'Esta coluna está oculta no seu :type.',

    'actions' => [
        'cancel'                => 'Cancelar',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Você</b> marcou esta fatura como',
        'services'              => 'Serviços',
        'another_item'          => 'Outro item',
        'another_description'   => 'e outra descrição',
        'more_item'             => '+:count item a mais',
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
        'overdue'               => 'Atrasado',
        'unpaid'                => 'Não pago',
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
        'drawn'                 => 'Emitido',
        'not_billed'            => 'Não faturado',
        'issued'                => 'Emitido',
        'not_invoiced'          => 'Não faturado',
        'confirmed'             => 'Confirmado',
        'not_confirmed'         => 'Não confirmado',
        'active'                => 'Ativo',
        'ended'                 => 'Finalizado',
    ],

    'form_description' => [
        'companies'             => 'Altere o endereço, logotipo e outras informações da sua empresa.',
        'billing'               => 'Os detalhes de faturamento aparecem no seu documento.',
        'advanced'              => 'Selecione a categoria, adicione ou edite o rodapé e adicione anexos ao seu :type.',
        'attachment'            => 'Baixar os arquivos anexados a este :type',
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
        'email_sent'            => 'E-mail do :type foi enviado!',
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
            'document_date'     => 'A data do :type será automaticamente atribuída com base no agendamento e na frequência do :type.',
            'document_number'   => 'O número do :type será automaticamente atribuído quando cada :type recorrente for gerado.',
        ],
    ],

    'empty_attachments'         => 'Não há arquivos anexados a este :type.',
];
