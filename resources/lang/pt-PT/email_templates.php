<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fatura criada',
        'body'          => 'Exmo(a) Sr(a) {customer_name},<br /><br />Preparamos a seguinte Fatura para si: <strong>{invoice_number}</strong>.<br /><br />Pode ver os detalhes da Fatura e prosseguir com o pagamento a partir do seguinte link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sinta-se à vontade para entrar em contacto connosco para qualquer duvida.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} aviso de fatura vencida',
        'body'          => 'Exmo(a) Sr(a) {customer_name},<br /><br />Este é um aviso de Fatura em atraso referente ao documento <strong>{invoice_number}</strong>.<br /><br />O total da Fatura é {invoice_total} e venceu em <strong>{invoice_due_date}</strong>.<br /><br />Pode ver os detalhes da Fatura e prosseguir com o pagamento a partir do seguinte link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} aviso de fatura vencida',
        'body'          => 'Olá,<br /><br />{customer_name} recebeu um aviso de Fatura em atraso <strong>{invoice_number}</strong>.<br /><br />O total da Fatura é {invoice_total} e venceu em <strong>{invoice_due_date}</strong>.<br /><br />Pode ver os detalhes da Fatura a partir do seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Fatura recorrente {invoice_number} criada',
        'body'          => 'Exmo(a) Sr(a) {customer_name},<br /><br />Baseado no seu ciclo recorrente, preparamos a seguinte Fatura para si: <strong>{invoice_number}</strong>.<br /><br />Pode ver os detalhes da Fatura e prosseguir com o pagamento a partir do seguinte link:  <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sinta-se à vontade para entrar em contacto connosco para qualquer duvida.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Fatura recorrente {invoice_number} criada',
        'body'          => 'Olá,<br /><br />Baseado no ciclo recorrente de {customer_name}, a Fatura <strong>{invoice_number}</strong> foi criada automaticamente.<br /><br />Pode ver os detalhes da Fatura a partir do seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pagamento recebido da fatura {invoice_number}',
        'body'          => 'Exmo(a) Sr(a) {customer_name},<br /><br />Obrigado pelo pagamento. Encontra os detalhes do pagamento abaixo:<br /><br />-------------------------------------------------<br />Valor: <strong>{transaction_total}</strong><br />Data: <strong>{transaction_paid_date}</strong><br />Fatura nº: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Pode sempre ver os detalhes da Fatura no seguinte link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sinta-se à vontade para entrar em contacto connosco para qualquer duvida.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pagamento recebido da fatura {invoice_number}',
        'body'          => 'Olá,<br /><br />{customer_name} registou um pagamento para a Fatura <strong>{invoice_number}</strong>.<br /><br />Pode ver os detalhes da Fatura a partir do seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Aviso de lembrete de conta {bill_number}',
        'body'          => 'Olá,<br /><br />Este é um lembrete de vencimento da Conta <strong>{bill_number}</strong> do Fornecedor {vendor_name}.<br /><br />O total da Conta é {bill_total} e vence em <strong>{bill_due_date}</strong>.<br /><br />Pode ver os detalhes da Conta a partir do seguinte link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Conta recorrente {bill_number} criada',
        'body'          => 'Olá,<br /><br />Baseado no ciclo recorrente do Fornecedor {vendor_name}, a Conta <strong>{bill_number}</strong> foi criada automaticamente.<br /><br />Pode ver os detalhes da Conta a partir do seguinte link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

];
