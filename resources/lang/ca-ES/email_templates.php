<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'S\'ha creat la factura {invoice_number}',
        'body'          => 'Benvolugut/da {customer_name},<br /><br />Hem emès la següent factura a nom teu: <strong>{invoice_number}</strong>.<br /><br />Pots veure els detalls de la factura i fer-ne el pagament accedint a l\'enllaç: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dubtis a contactar amb nosaltres per qualsevol dubte.<br /><br />Atentament,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Notificació d\'expiració de la data de pagament de la factura {invoice_number}',
        'body'          => 'Benvolgut/da {customer_name},<br /><br />Això és un recordatori sobre el venciment de la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura és {invoice_total} i el seu pagament va vèncer el dia <strong>{invoice_due_date}</strong>.<br /><br />Pots veure els detalls de la factura i fer-ne el pagament accedint a l\'enllaç: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Atentament,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Notificació de venciment de la factura {invoice_number}',
        'body'          => 'Hola,<br /><br />el client {customer_name} ha rebut un recordatori sobre el venciment de la factura <strong>{invoice_number}</strong>.<br /><br />El total de la factura és {invoice_total} i el seu pagament va vèncer el dia <strong>{invoice_due_date}</strong>.<br /><br />Pots veure els detalls de la factura accedint a l\'enllaç: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salutacions,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'S\'ha creat la factura recurrent {invoice_number}',
        'body'          => 'Benvolgut/da {customer_name},<br /><br />En base al cobrament recurrent establert hem emès la factura següent: <strong>{invoice_number}</strong>.<br /><br />Pots veure els detalls de la factura i fer-ne el pagament accedint a l\'enllaç següent: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dubtis a contactar amb nosaltres per qualsevol dubte.<br /><br />Atentament,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'S\'ha creat la factura recurrent {invoice_number}',
        'body'          => 'Hola,<br /><br />En base al cobrament recurrent establert amb el client {customer_name}, s\'ha generat la factura <strong>{invoice_number}</strong> de manera automàtica.<br /><br />Pots veure els detalls de la factura accedint a l\'enllaç: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salutacions,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'S\'ha rebut el cobrament de la factura {invoice_number}',
        'body'          => 'Benvolgut/da {customer_name},<br /><br />He rebut el teu pagament, gràcies. Pots veure els detalls del pagament a continuació:<br /><br />-------------------------------------------------<br />Quantitat: <strong>{transaction_total}</strong><br />Data: <strong>{transaction_paid_date}</strong><br />Número de factura: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Sempre pots veure els detalls de la factura accedint a l\'enllaç: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />No dubtis a contactar amb nosaltres per qualsevol dubte.<br /><br />Atentament,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'S\'ha rebut el pagament de la factura {invoice_number}',
        'body'          => 'Hola,<br /><br />el client {customer_name} ha registrat un pagament per la factura <strong>{invoice_number}</strong>.<br /><br />Pots veure els detalls de la factura seguint l\'enllaç: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Salutacions,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Recordatori sobre la factura {bill_number}',
        'body'          => 'Hola,<br /><br />Això és un recordatori sobre la factura <strong>{bill_number}</strong> de {vendor_name}.<br /><br />El total de la factura és {bill_total} i venç el <strong>{bill_due_date}</strong>.<br /><br />Pots veure els detalls de la factura seguint l\'enllaç: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Salutacions,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'S\'ha creat el cobrament recurrent de la factura {bill_number}',
        'body'          => 'Hola,<br /><br />En base al pagament recurrent a {vendor_name}, s\'ha creat automàticament la factura de proveïdor <strong>{bill_number}</strong>.<br /><br />Pots veure els detalls de la factura seguint l\'enllaç: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Salutacions,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => 'S\'ha creat el pagament {revenue_date}',
        'body'          => 'Benvolgut/da {customer_name},<br /><br />Hem preparat el pagament següent. <br /><br />Pots veure els detalls del pagament a l\'enllaç següent:  <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />No dubtis a contactar amb nosaltres per qualsevol dubte.<br /><br />Salutacions,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => 'Pagament creat el {revenue_date}',
        'body'          => 'Benvolgut/da {vendor_name},<br /><br />Hem preparat el següent pagament. <br /><br />Pots veure\'n els detalls accedint a l\'enllaç: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />No dubtis a contactar amb nosaltres per qualsevol dubte..<br /><br />Salutacions,<br />{company_name}',
    ],
];
