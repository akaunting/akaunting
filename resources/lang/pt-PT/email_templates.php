<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fatura criada',
        'body'          => 'Dear {customer_name},<br /><br />We have prepared the following invoice for you: <strong>{invoice_number}</strong>.<br /><br />You can see the invoice details and proceed with the payment from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} aviso de fatura vencida',
        'body'          => 'Dear {customer_name},<br /><br />This is an overdue notice for <strong>{invoice_number}</strong> invoice.<br /><br />The invoice total is {invoice_total} and was due <strong>{invoice_due_date}</strong>.<br /><br />You can see the invoice details and proceed with the payment from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} aviso de fatura vencida',
        'body'          => 'Hello,<br /><br />{customer_name} has received an overdue notice for <strong>{invoice_number}</strong> invoice.<br /><br />The invoice total is {invoice_total} and was due <strong>{invoice_due_date}</strong>.<br /><br />You can see the invoice details from the following link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Fatura recorrente {invoice_number} criada',
        'body'          => 'Dear {customer_name},<br /><br />Based on your recurring circle, we have prepared the following invoice for you: <strong>{invoice_number}</strong>.<br /><br />You can see the invoice details and proceed with the payment from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Fatura recorrente {invoice_number} criada',
        'body'          => 'Hello,<br /><br />Based on {customer_name} recurring circle, <strong>{invoice_number}</strong> invoice has been automatically created.<br /><br />You can see the invoice details from the following link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pagamento recebido da fatura {invoice_number}',
        'body'          => 'Dear {customer_name},<br /><br />Thank you for the payment. Find the payment details below:<br /><br />-------------------------------------------------<br />Amount: <strong>{transaction_total}</strong><br />Date: <strong>{transaction_paid_date}</strong><br />Invoice Number: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />You can always see the invoice details from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pagamento recebido da fatura {invoice_number}',
        'body'          => 'Hello,<br /><br />{customer_name} recorded a payment for <strong>{invoice_number}</strong> invoice.<br /><br />You can see the invoice details from the following link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Aviso de lembrete de conta {bill_number}',
        'body'          => 'Hello,<br /><br />This is a reminding notice for <strong>{bill_number}</strong> bill to {vendor_name}.<br /><br />The bill total is {bill_total} and is due <strong>{bill_due_date}</strong>.<br /><br />You can see the bill details from the following link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Conta recorrente {bill_number} criada',
        'body'          => 'Hello,<br /><br />Based on {vendor_name} recurring circle, <strong>{bill_number}</strong> invoice has been automatically created.<br /><br />You can see the bill details from the following link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

];
