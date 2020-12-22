<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Factuur {invoice_number} aangemaakt',
        'body'          => 'Geachte {customer_name},<br /><br />De volgende factuur staat voor u klaar <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken en de betaling verrichten via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neem gerust contact met ons op indien u vragen heeft.<br /><br />Met vriendelijke groet.<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Factuur {invoice_numer} is vervallen',
        'body'          => 'Geachte {customer_name},<br /><br />Dit is een tijdige kennisgeving voor factuur: <strong>{invoice_number}</strong>.<br /><br />Het totaalbedrag van de factuur is {invoice_total} en was vervallen op <strong>{invoice_due_date}</strong>.<br /><br />U kunt de factuurgegevens zien de betaling verichten via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Betalingsherinnering factuur: {invoice_number}',
        'body'          => 'Hallo,<br /><br />Dit is een herinneringsmelding voor rekening <strong>{bill_number}</strong> aan {vendor_name}.<br /><br />Het totaal van de rekening is {bill_total} en vervalt op <strong>{bill_due_date}</strong>.<br /><br />Je kunt de rekeninggegevens zien van de volgende link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Abonnementsfactuur {invoice_number} aangemaakt',
        'body'          => 'Geachte {customer_name},<br /><br />gebaseerd op uw abonnement, we hebben de volgende factuur voor u klaargezet: <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken en een betaling uitvoeren via de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neem gerust contact met ons op voor elke vraag.<br /><br />Met vriendelijke groet.<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Abonnementsfactuur {invoice_number} aangemaakt',
        'body'          => 'Hallo,<br /><br />{customer_name} heeft een betaling uitgevoerd van factuur <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken via de volgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Betaling ontvangen voor factuur: {invoice_number}',
        'body'          => 'Geachte {customer_name},<br /><br />Bedankt voor uw betaling. U vind de betalingsgegevens hieronder:<br /><br />-------------------------------------------------<br />Bedrag: <strong>{transaction_total}</strong><br />Datum: <strong>{transaction_paid_date}</strong><br />Factuurnummer: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Je kunt altijd de factuurgegevens zien van de volgende link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Neem gerust contact met ons op indien u vragen heeft.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Betaling ontvangen voor factuur: {invoice_number}',
        'body'          => 'Hallo,<br /><br />{customer_name} heeft betaling uitgevoerd van factuur <strong>{invoice_number}</strong>.<br /><br />U kunt de factuurgegevens bekijken via de volgende link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Rekening herinnering voor: {bill_number}',
        'body'          => 'Hallo,<br /><br />Dit is een herinneringsmelding voor rekening <strong>{bill_number}</strong> aan {vendor_name}.<br /><br />Het totaal van de rekening is {bill_total} en vervalt op <strong>{bill_due_date}</strong>.<br /><br />Je kunt de rekeninggegevens zien van de volgende link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Periodieke rekening {bill_number} aangemaakt',
        'body'          => 'Hallo,<br /><br />gebaseerd op {vendor_name} terugkerende betalingen, is rekening <strong>{bill_number}</strong>  automatisch aangemaakt.<br /><br />Je kunt de rekeninggegevens bekijken via de volgende link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Met vriendelijke groeten,<br />{company_name}',
    ],

];
