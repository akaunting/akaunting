<?php

return [

    'bill_number'       => 'Conta nº',
    'bill_date'         => 'Data de Emissão',
    'total_price'       => 'Valor Total',
    'due_date'          => 'Data de vencimento',
    'order_number'      => 'Encomenda nº',
    'bill_from'         => 'Conta de',

    'quantity'          => 'Quantidade',
    'price'             => 'Preço',
    'sub_total'         => 'Subtotal',
    'discount'          => 'Desconto',
    'tax_total'         => 'Imposto',
    'total'             => 'Total',

    'item_name'         => 'Nome do Item | Nome dos Items',

    'show_discount'     => ':discount% de desconto',
    'add_discount'      => 'Adicionar desconto',
    'discount_desc'     => 'do subtotal',

    'payment_due'       => 'Valor Devido',
    'amount_due'        => 'Total Devido',
    'paid'              => 'Pago',
    'histories'         => 'Histórico',
    'payments'          => 'Pagamentos',
    'add_payment'       => 'Pagar Conta',
    'mark_received'     => 'Marcar como Recebida',
    'download_pdf'      => 'Descarregar em PDF',
    'send_mail'         => 'Enviar e-mail',

    'status' => [
        'draft'         => 'Rascunho',
        'received'      => 'Recebido',
        'partial'       => 'Parcial',
        'paid'          => 'Pago',
    ],

    'messages' => [
        'received'      => 'Conta marcada como recebida com sucesso!',
        'draft'          => 'This is a <b>DRAFT</b> bill and will be reflected to charts after it gets received.',

        'status' => [
            'created'   => 'Criado em :date',
            'receive'      => [
                'draft'     => 'Não enviado',
                'received'  => 'Recebido em :date',
            ],
            'paid'      => [
                'await'     => 'Aguarda pagamento',
            ],
        ],
    ],

];
