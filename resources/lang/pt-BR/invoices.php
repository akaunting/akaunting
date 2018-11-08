<?php

return [

    'invoice_number'    => 'Número da Fatura',
    'invoice_date'      => 'Data de Emissão',
    'total_price'       => 'Valor total',
    'due_date'          => 'Data de Vencimento',
    'order_number'      => 'Número',
    'bill_to'           => 'Pagar para',

    'quantity'          => 'Quantidade',
    'price'             => 'Preço',
    'sub_total'         => 'Subtotal',
    'discount'          => 'Desconto',
    'tax_total'         => 'Valor da taxa',
    'total'             => 'Total',

    'item_name'         => 'Item|Itens',

    'show_discount'     => ':discount% desconto',
    'add_discount'      => 'Adicionar desconto',
    'discount_desc'     => 'subtotal',

    'payment_due'       => 'Pagamento vencido',
    'paid'              => 'Pago',
    'histories'         => 'Histórico',
    'payments'          => 'Pagamentos',
    'add_payment'       => 'Novo Pagamento',
    'mark_paid'         => 'Marcar como pago',
    'mark_sent'         => 'Marcar Como Enviada',
    'download_pdf'      => 'Baixar em PDF',
    'send_mail'         => 'Enviar E-mail',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => 'Rascunho',
        'sent'          => 'Enviar',
        'viewed'        => 'Visto',
        'approved'      => 'Aprovado',
        'partial'       => 'Parcial',
        'paid'          => 'Pago',
    ],

    'messages' => [
        'email_sent'     => 'O e-mail foi enviado com sucesso!',
        'marked_sent'    => 'Fatura marcada como enviada com sucesso!',
        'email_required' => 'Nenhum endereço de e-mail para este cliente!',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'       => 'Você está recebendo este e-mail porque tem :amount fatura a vencer.',
        'button'        => 'Pagar agora',
    ],

];
