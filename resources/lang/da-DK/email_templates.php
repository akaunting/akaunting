<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Faktura {invoice_number} genereret',
        'body'          => 'Kære {customer_name},<br /><br />Vi har forberedt følgende faktura til dig:
<strong>{invoice_number}</strong>.<br /><br />Du se faktura detaljerne og fortsætte med betaling på følgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Du er velkommen til at kontakte os med hvilket som helst spørgsmål.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} forfalden faktura notifikation',
        'body'          => 'Kære {customer_name},<br /><br />Vi gør opmærksom på at fakturaen på {invoice_total} hvor sidste betalingsdag var den <strong>{invoice_due_date}</strong> med faktura nr. <strong>{invoice_number}</strong> ikke er betalt.<br /><br />Du kan se faktura detaljerne og betale på dette link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} faktura overskredet notificering',
        'body'          => 'Hej,<br /><br />{customer_name} har modtaget en forfaldsmeddelelse for <strong>{invoice_number}</strong> faktura.<br /><br />Fakturaens samlede beløb er {invoice_total} og skulle betales < strong>{invoice_due_date}</strong>.<br /><br />Du kan se fakturaoplysningerne fra følgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br / ><br />Med venlig hilsen<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} Tilbagevendende fakturaer oprettet',
        'body'          => 'Kære {customer_name},<br /><br />Her har du fakturaen for dit køb: <strong>{invoice_number}</strong>.<br /><br />Her kan du se faktura detaljerne og fortsætte med betaling på følgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Kontakt os ved spørgsmål.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} Tilbagevendende fakturaer oprettet',
        'body'          => 'Hej,<br /><br />Baseret på {customer_name} tilbagevendende betalinger, er faktura nummer <strong>{invoice_number}</strong> automatisk blevet genereret.<br /><br />Du kan se fakturaoplysningerne på følgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br / ><br />Med venlig hilsen<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Betaling for fakturernummer {invoice_number} er modtaget',
        'body'          => 'Kære {customer_name},<br /><br />Tak for din betaling. Find betalingsdetaljerne herunder:<br /><br />-------------------------------------------------<br /><br />Antal: <strong>{transaction_total}<br /></strong>Dato: <strong>{transaction_paid_date}</strong><br />Faktura nummer: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Du kan altid se betalings detaljerne på dette link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Har du spørgsmål er du mere end velkommen til at kontakte os.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Betaling for fakturanummer {invoice_number} er modtaget',
        'body'          => 'Hej,<br /><br />{customer_name} har betalt <strong>{invoice_number}</strong> invoice.<br /><br />Du kan se betalingsdetaljerne på dette link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} regnings påmindelse',
        'body'          => 'Hej,<br /><br />Dette er en reminder for regning <strong>{bill_number}</strong> til {vendor_name}.<br /><br />Regningen er på {bill_total} og skal betales den <strong>{bill_due_date}</strong>.<br /><br />Du kan se den betalingsinformationen på dette link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Med venlig hilsen,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} tilbagevendende regning er oprettet',
        'body'          => 'Hej,<br /><br /> Baseret på {vendor_name} tilbagevendende betaling, er regnings nummer <strong>{bill_number}</strong> automatisk genereret.<br /><br />Du kan se regningsdetaljerne på dette link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Med venlig,<br />{company_name}',
    ],

];
