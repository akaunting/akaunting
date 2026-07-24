<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fatura criada',
        'body'          => 'Caro {customer_name},<br /><br />Preparamos a seguinte fatura: <strong>{invoice_number}</strong>.<br /><br />É possível ver os detalhes da fatura e prosseguir com o pagamento a partir do seguinte link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sinta-se a vontade para entrar em contato conosco para qualquer pergunta.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} aviso de fatura vencida',
        'body'          => 'Caro {customer_name},<br /><br />Este é um aviso de fatura em atraso <strong>{invoice_number}</strong>.<br /><br />O total da fatura é {invoice_total} e venceu em <strong>{invoice_due_date}</strong>.<br /><br />É possível ver os detalhes da fatura e prosseguir com o pagamento a partir do seguinte link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} aviso de fatura vencida',
        'body'          => 'Olá,<br /><br />{customer_name} recebeu um aviso de fatura em atraso <strong>{invoice_number}</strong>.<br /><br />O total da fatura é {invoice_total} e venceu em <strong>{invoice_due_date}</strong>.<br /><br />Você pode ver os detalhes da fatura do seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Fatura recorrente {invoice_number} criada',
        'body'          => 'Caro {customer_name},<br /><br />Baseado no seu ciclo recorrente, preparamos a seguinte fatura: <strong>{invoice_number}</strong>.<br /><br />É possível ver os detalhes da fatura e prosseguir com o pagamento a partir do seguinte link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sinta-se a vontade para entrar em contato conosco para qualquer pergunta.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Fatura recorrente {invoice_number} criada',
        'body'          => 'Olá,<br /><br /> Baseado no círculo recorrente de {customer_name}, a fatura <strong>{invoice_number}</strong> foi criada automaticamente.<br /><br />É possível ver os detalhes da fatura a partir do seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => '{invoice_number} fatura visualizada',
        'body'          => 'Olá,<br /><br />{customer_name} visualizou a fatura <strong>{invoice_number}</strong>. <br /><br />Veja os detalhes da fatura a partir do seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pagamento recebido para fatura {invoice_number}',
        'body'          => 'Caro {customer_name},<br /><br />Obrigado pelo pagamento. Veja abaixo os detalhes do pagamento:<br /><br />-------------------------------------------------<br /><br />Valor: <strong>{transaction_total}<br /></strong>Data: <strong>{transaction_paid_date}</strong><br />Número da fatura: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Os detalhes da fatura sempre pode ser visualizado no link:<a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sinta-se à vontade para entrar em contato conosco para qualquer pergunta.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pagamento recebido para fatura {invoice_number}',
        'body'          => 'Olá,<br /><br />{customer_name} registrou um pagamento para a fatura <strong>{invoice_number}</strong>.<br /><br />É possível ver os detalhes da fatura no seguinte link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Notificação lembrete de cobrança {bill_number}',
        'body'          => 'Olá,<br /><br /> Este é um lembrete de vencimento da fatura <strong>{bill_number}</strong> de {vendor_name}.<br /><br />O total da fatura é {bill_total} e vence em <strong>{bill_due_date} </strong>.<br /><br />É possível visualizar os detalhes da fatura no seguinte link: <a href="{bill_admin_link}"> {bill_number} </a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Fatura recorrente {bill_number} criada',
        'body'          => 'Olá,<br /><br /> Baseado no círculo recorrente de {vendor_name}, a fatura <strong>{bill_number}</strong> foi criada automaticamente.<br /><br />É possível ver os detalhes da fatura a partir do seguinte link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Seu recibo de {company_name}',
        'body'          => 'Caro {contact_name},<br /><br />Obrigado pelo seu pagamento. <br /><br />Veja os detalhes do pagamento no seguinte link: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Sinta-se à vontade para nos contatar com qualquer dúvida.<br /><br />Atenciosamente,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Pagamento efetuado por {company_name}',
        'body'          => 'Caro {contact_name},<br /><br />Efetuamos o seguinte pagamento. <br /><br />Veja os detalhes do pagamento no seguinte link: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Sinta-se à vontade para nos contatar com qualquer dúvida.<br /><br />Atenciosamente,<br />{company_name}',
    ],
];
