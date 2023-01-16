<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Faktura {invoice_number} opprettet',
        'body'          => 'Kjære {customer_name},<br /><br />Vi har opprettet følgende faktura til deg: <strong>{invoice_number}</strong>.<br /><br />Du kan se fakturadetaljene og fortsette med betaling på følgende lenke: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Vennligst kontakt oss hvis du har spørsmål.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} Fakturapåminnelse',
        'body'          => 'Kjære {customer_name},<br /><br />Dette er en påminnelse om faktura <strong>{invoice_number}</strong>.<br /><br />Fakturabeløpet er {invoice_total} og forfalte  <strong>{invoice_due_date}</strong>.<br /><br />Du kan se fakturadetaljene og fortsette med betaling på følgende lenke: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} Fakturapåminnelse',
        'body'          => 'Hei,<br /><br />{customer_name} har mottatt en fakturapåminnelse for faktura <strong>{invoice_number}</strong>.<br /><br />Fakturabeløpet er {invoice_total} og forfalt <strong>{invoice_due_date}</strong>.<br /><br />Du kan se fakturadetaljene på følgende lenke: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} Gjentakende faktura opprettet',
        'body'          => 'Kjære {customer_name},<br /><br />Basert på din betalingsordning, har vi opprettet en faktura til deg: <strong>{invoice_number}</strong>.<br /><br />Du kan se fakturadetaljer og fortsette med betaling på følgende lenke: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Vennligst kontakt oss ved spørsmål.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} Gjentakende faktura opprettet',
        'body'          => 'Hei,<br /><br />Basert på {customer_name} sin betalingsordning, er faktura <strong>{invoice_number}</strong> automatisk opprettet.<br /><br />Du kan se fakturadetaljer på følgende lenke: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Betaling mottatt for faktura {invoice_number}',
        'body'          => 'Kjære {customer_name},<br /><br />Takk for betalingen. Finn betalingsdetaljer under:<br /><br />-------------------------------------------------<br />Beløp: <strong>{transaction_total}</strong><br />Dato: <strong>{transaction_paid_date}</strong><br />Fakturanummer: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Du kan se fakturadetaljene på følgende lenke: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Vennligst kontakt oss ved spørsmål.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Betaling mottatt for faktura {invoice_number}',
        'body'          => 'Hei,<br /><br />{customer_name} registrerte betaling for faktura <strong>{invoice_number}</strong>.<br /><br />Du kan se fakturadetaljene på følgende lenke: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} Betalingspåminnelse',
        'body'          => 'Hei,<br /><br />Dette er en betalingspåminnelse for faktura <strong>{bill_number}</strong> til {vendor_name}.<br /><br />Fakturabeløpet er {bill_total} og forfalte <strong>{bill_due_date}</strong>.<br /><br />Du kan se fakturadetaljene på følgende lenke: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} Gjentakende faktura opprettet',
        'body'          => 'Hei,<br /><br />Basert på {vendor_name} sin betalingsordning for deg, er faktura <strong>{bill_number}</strong> automatisk opprettet.<br /><br />Du kan se fakturadetaljene på følgende lenke: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => '{revenue_date} betaling opprettet',
        'body'          => 'Kjære {customer_name},<br /><br />Vi har opprettet følgende faktura til deg. <br /><br />Du kan se fakturadetaljene og fortsette med betaling på følgende lenke: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />Vennligst kontakt oss hvis du har spørsmål.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => '{revenue_date} betaling opprettet',
        'body'          => 'Kjære {customer_name},<br /><br />Vi har opprettet følgende faktura til deg: <strong>{invoice_number}</strong>.<br /><br />Du kan se fakturadetaljene og fortsette med betaling på følgende lenke: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Vennligst kontakt oss hvis du har spørsmål.<br /><br />Vennlig hilsen,<br />{company_name}',
    ],
];
