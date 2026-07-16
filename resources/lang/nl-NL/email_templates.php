<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Factuur {invoice_number} aangemaakt',
        'body'          => 'Geachte {customer_name},<br /><br />De volgende factuur staat voor u klaar: <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken en de betaling verrichten via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neem gerust contact met ons op indien u vragen heeft.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Factuur {invoice_number} betalingsherinnering',
        'body'          => 'Geachte {customer_name},<br /><br />Dit is een betalingsherinnering voor factuur: <strong>{invoice_number}</strong>.<br /><br />Het totaalbedrag van de factuur is {invoice_total} en was vervallen op <strong>{invoice_due_date}</strong>.<br /><br />U kunt de factuurgegevens bekijken en de betaling verrichten via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Betalingsherinnering factuur: {invoice_number}',
        'body'          => 'Hallo,<br /><br />{customer_name} heeft een betalingsherinnering ontvangen voor factuur <strong>{invoice_number}</strong>.<br /><br />Het totaal van de factuur is {invoice_total} en was vervallen op <strong>{invoice_due_date}</strong>.<br /><br />U kunt de factuurgegevens bekijken via de volgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Terugkerende factuur {invoice_number} aangemaakt',
        'body'          => 'Geachte {customer_name},<br /><br />Op basis van uw terugkerende cyclus hebben we de volgende factuur voor u klaargezet: <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken en een betaling uitvoeren via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neem gerust contact met ons op voor elke vraag.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Terugkerende factuur {invoice_number} aangemaakt',
        'body'          => 'Hallo,<br /><br />Op basis van de terugkerende cyclus van {customer_name} is factuur <strong>{invoice_number}</strong> automatisch aangemaakt.<br /><br />U kunt de factuurgegevens bekijken via de volgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Factuur {invoice_number} bekeken',
        'body'          => 'Hallo,<br /><br />{customer_name} heeft factuur <strong>{invoice_number}</strong> bekeken.<br /><br />U kunt de factuurgegevens bekijken via de volgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Uw ontvangstbewijs voor factuur: {invoice_number}',
        'body'          => 'Geachte {customer_name},<br /><br />Bedankt voor uw betaling. Hieronder vindt u de betalingsgegevens:<br /><br />-------------------------------------------------<br />Bedrag: <strong>{transaction_total}</strong><br />Datum: <strong>{transaction_paid_date}</strong><br />Factuurnummer: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />U kunt de factuurgegevens altijd bekijken via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neem gerust contact met ons op indien u vragen heeft.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Betaling ontvangen voor factuur: {invoice_number}',
        'body'          => 'Hallo,<br /><br />{customer_name} heeft een betaling geregistreerd voor factuur <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken via de volgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Inkoopfactuur {bill_number} herinnering',
        'body'          => 'Hallo,<br /><br />Dit is een herinnering voor inkoopfactuur <strong>{bill_number}</strong> van {vendor_name}.<br /><br />Het totaal van de inkoopfactuur is {bill_total} en vervalt op <strong>{bill_due_date}</strong>.<br /><br />U kunt de inkoopfactuurgegevens bekijken via de volgende link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Terugkerende inkoopfactuur {bill_number} aangemaakt',
        'body'          => 'Hallo,<br /><br />Op basis van de terugkerende cyclus van {vendor_name} is inkoopfactuur <strong>{bill_number}</strong> automatisch aangemaakt.<br /><br />U kunt de inkoopfactuurgegevens bekijken via de volgende link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Uw ontvangstbewijs van {company_name}',
        'body'          => 'Geachte {contact_name},<br /><br />Bedankt voor de betaling. <br /><br />U kunt de betalingsgegevens bekijken via de volgende link: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Aarzel niet om contact met ons op te nemen als u vragen heeft.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Betaling uitgevoerd door {company_name}',
        'body'          => 'Geachte {contact_name},<br /><br />We hebben de volgende betaling uitgevoerd. <br /><br />U kunt de betalingsgegevens bekijken via de volgende link: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Neem gerust contact met ons op als u vragen heeft.<br /><br />Met vriendelijke groet,<br />{company_name}',
    ],
];
