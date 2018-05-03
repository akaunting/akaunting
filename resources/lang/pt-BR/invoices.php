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
    'discount'          => 'Discount',
    'tax_total'         => 'Valor da taxa',
    'total'             => 'Total',

    'item_name'         => 'Item|Itens',

    'show_discount'     => ':discount% Discount',
    'add_discount'      => 'Add Discount',
    'discount_desc'     => 'of subtotal',

    'payment_due'       => 'Pagamento vencido',
    'paid'              => 'Pago',
    'histories'         => 'Histórico',
    'payments'          => 'Pagamentos',
    'add_payment'       => 'Novo Pagamento',
    'mark_paid'         => 'Mark Paid',
    'mark_sent'         => 'Marcar Como Enviada',
    'download_pdf'      => 'Baixar em PDF',
    'send_mail'         => 'Enviar E-mail',

    'status' => [
        'draft'         => 'Rascunho',
        'sent'          => 'Enviar',
        'viewed'        => 'Visto',
        'approved'      => 'Aprovado',
        'partial'       => 'Parcial',
        'paid'          => 'Pago',
    ],

    'messages' => [
        'email_sent'     => 'Invoice email has been sent successfully!',
        'marked_sent'    => 'Fatura marcada como enviada com sucesso!',
        'email_required' => 'No email address for this customer!',
    ],

    'notification' => [
        'message'       => 'You are receiving this email because you have an upcoming :amount invoice to :customer customer.',
        'button'        => 'Pagar agora',
    ],

];
