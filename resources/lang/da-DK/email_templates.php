<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Faktura {invoice_number} genereret',
        'body'          => 'Kære {customer_name},<br /><br />Vi har forberedt følgende faktura til dig:
<strong>{invoice_number}</strong>.<br /><br />Du se fakturadetaljerne og fortsætte til betaling på følgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Du er velkommen til at kontakte os, hvis du skulle have spørgsmål til denne faktura.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Din faktura nr. {invoice_number} er forfalden',
        'body'          => 'Kære {customer_name},<br /><br />Vi gør opmærksom på, at faktura nr. {invoice_number} på {invoice_total}, hvor sidste betalingsdag var den {invoice_due_date}, ikke er betalt.<br /><br />Du kan se detaljerne og fortsætte til betaling på dette link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Betalingen af faktura nr. {invoice_number} er overskredet',
        'body'          => 'Hej,<br /><br />{customer_name} har modtaget en forfaldsmeddelelse for <strong>{invoice_number}</strong> faktura.<br /><br />Fakturaens samlede beløb er {invoice_total} og skulle betales < strong>{invoice_due_date}</strong>.<br /><br />Du kan se fakturaoplysningerne fra følgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br / ><br />Med venlig hilsen<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Faktura nr. {invoice_number} fra {company_name}',
        'body'          => 'Kære {customer_name},<br /><br />Vi har forberedt følgende faktura til dig:
<strong>{invoice_number}</strong>.<br /><br />Du se fakturadetaljerne og fortsætte til betaling på følgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Du er velkommen til at kontakte os, hvis du skulle have spørgsmål til denne faktura.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Faktura til {customer_name} er oprettet',
        'body'          => 'Hej,<br /><br />Baseret på den tilbagevendende faktura til {customer_name} er fakturanr. <strong>{invoice_number}</strong> automatisk blevet genereret.<br /><br />Du kan se fakturaoplysningerne på følgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br / ><br />Med venlig hilsen<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Betaling for faktura nr. {invoice_number} er modtaget',
        'body'          => 'Kære {customer_name},<br /><br />Tak for din betaling. Find betalingsdetaljerne herunder:<br /><br />-------------------------------------------------<br /><br />Antal: <strong>{transaction_total}<br /></strong>Dato: <strong>{transaction_paid_date}</strong><br />Fakturanummer: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Du kan altid se betalingsdetaljerne på dette link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Har du spørgsmål, er du velkommen til at kontakte os.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Betaling for faktura nr. {invoice_number} er modtaget',
        'body'          => 'Hej,<br /><br />{customer_name} har betalt faktura nr. <strong>{invoice_number}</strong><br /><br />Du kan se betalingsdetaljerne på dette link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Påmindelse for regning nr. {bill_number}',
        'body'          => 'Hej,<br /><br />Dette er en påmindelse for regning <strong>{bill_number}</strong> til {vendor_name}.<br /><br />Regningen er på {bill_total} og skal betales den <strong>{bill_due_date}</strong>.<br /><br />Du kan se den betalingsinformationen på dette link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} tilbagevendende regning er oprettet',
        'body'          => 'Hej,<br /><br /> Baseret på {vendor_name}\'s tilbagevendende betaling, er faktura nummer <strong>{bill_number}</strong> automatisk genereret.<br /><br />Du kan se fakturadetaljerne på dette link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => '{revenue_date} betaling oprettet',
        'body'          => 'Kære {customer_name},<br /><br />Vi har forberedt følgende betaling. <br /><br />Du kan se betalingsdetaljerne på følgende link: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />Du er velkommen til at kontakte os, hvis du har spørgsmål til betalingen.<br /><br />Venlig hilsen,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => '{revenue_date} betaling oprettet',
        'body'          => 'Kære {vendor_name},<br /><br />Vi har forberedt følgende betaling. <br /><br />Du kan se betalingsdetaljerne på følgende link: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />Du er velkommen til at kontakte os, hvis du har spørgsmål.<br /><br />Venlig hilsen,<br />{company_name}
',
    ],
];
