<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} factură creată',
        'body'          => 'Dragă {customer_name},<br /><br />Am pregătit pentru dumneavoatră următoarea factură: <strong>{invoice_number}</strong>.<br /><br />Puteți vedea detaliile facturii și puteți continua cu plata din următorul link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Nu ezitați să ne contactați pentru orice întrebare.<br /><br />Toate cele bune, <br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} notificare factură restantă',
        'body'          => 'Stimate {customer_name},<br /><br />Aceasta este o notificare de întârziere pentru factura <strong>{invoice_number}</strong>. <br /><br />Totalul facturii este {invoice_total} și a fost scavent în <strong>{invoice_due_date}</strong>.<br /><br />Puteți vedea detaliile facturii și continua plata accesând următorul link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Toate cele bune,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => ' notificare factură restantă cu numărul {invoice_number}',
        'body'          => 'Bună,<br /><br />{customer_name} a primit o notificare de întârziere pentru factura <strong>{invoice_number}</strong>. <br /><br />Totalul facturii este {invoice_total} și a fost scadentă în <strong>{invoice_due_date}</strong>.<br /><br />Puteţi vedea detaliile facturii la următorul link: <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />Toate cele bune,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'factura recurentă cu numărul {invoice_number} a fost creată',
        'body'          => 'Dragă {customer_name},<br /><br />Conform recurenței pentru care ați optat, am pregătit pentru dumneavoastră următoarea factură: <strong>{invoice_number}</strong>.<br /><br />Puteți vedea detaliile facturii și puteți continua cu plata în următorul link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Nu ezitați să ne contactați pentru orice întrebare.<br /><br />Toate cele bune, <br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'factura recurentă cu numărul {invoice_number} a fost creată',
        'body'          => 'Salut,<br /><br />Conform recurenței pentru care a optat {customer_name}, factura <strong>{invoice_number}</strong> a fost creată automat.<br /><br />Puteți vedea detaliile facturii la următorul link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Toate cele bune,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Plată primită pentru factura {invoice_number}',
        'body'          => 'Dragă {customer_name},<br /><br />Vă mulțumim pentru plată. Găsiți detaliile plății mai jos:<br /><br />-------------------------------------------------<br />Suma: <strong>{transaction_total}</strong><br />Dată: <strong>{transaction_paid_date}</strong><br />Număr factură: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Puteți vedea întotdeauna detaliile facturii la următorul link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Puteți să ne contactați pentru orice întrebare.<br /><br />Toate cele bune,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Plată primită pentru factura {invoice_number}',
        'body'          => 'Salut,<br /><br />{customer_name} a înregistrat o plată pentru factura  <strong>{invoice_number}</strong>. <br /><br />Puteți vedea detaliile facturii la următorul link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Toate cele bune,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Notificare reamintire factură {bill_number}',
        'body'          => 'Bună,<br /><br />Aceasta este o notificare de reamintire pentru factura <strong>{bill_number}</strong> către {vendor_name}.<br /><br />Totalul facturii este {bill_total} și are scadența în <strong>{bill_due_date}</strong>.<br /><br />Puteţi vedea detaliile facturii la următorul link: <a href="{bill_admin_link}">{bill_number}</a><br /><br />Toate cele bune,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'factura recurentă cu numărul {bill_number} a fost creată',
        'body'          => 'Salut,<br /><br />Conform recurenței pentru care a optat {vendor_name}, factura <strong>{bill_number}</strong> a fost creată automat.<br /><br />Poți vedea detaliile facturii la următorul link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Toate cele bune,<br />{company_name}',
    ],

];
