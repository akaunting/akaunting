<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} faktura skapad',
        'body'          => 'Kära {customer_name},<br /><br />Vi har förberett följande faktura åt dig: <strong>{invoice_number}</strong>.<br /><br />Du kan se fakturauppgifterna och fortsätta med betalningen från följande länk: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Kontakta oss gärna för alla frågor.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} faktura förfallna meddelande',
        'body'          => 'Kära {customer_name},<br /><br />Detta är ett förfallna meddelande för <strong>{invoice_number}</strong> faktura.<br /><br />Faktureringssumman är {invoice_total} och förfaller <strong>{invoice_due_date}</strong>.<br /><br />Du kan se fakturauppgifterna och fortsätta med betalningen från följande länk: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} faktura förfallna meddelande',
        'body'          => 'Hej,<br /><br />{customer_name} har fått ett förfallna meddelande för <strong>{invoice_number}</strong> faktura.<br /><br />Faktureringssumman är {invoice_total} och förfaller <strong>{invoice_due_date}</strong>.<br /><br />Du kan se fakturauppgifterna från följande länk: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} återkommande faktura skapad',
        'body'          => 'Kära {customer_name},<br /><br />Baserat på din återkommande cirkel, vi har förberett följande faktura åt dig: <strong>{invoice_number}</strong>.<br /><br />Du kan se fakturauppgifterna och fortsätta med betalningen från följande länk: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Kontakta oss gärna för alla frågor.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} återkommande faktura skapad',
        'body'          => 'Hej,<br /><br /> Baserat på {customer_name} återkommande cirkel, <strong>{invoice_number}</strong> fakturan har skapats automatiskt.<br /><br />Du kan se fakturadetaljer från följande länk: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Betalning mottagen för {invoice_number} faktura',
        'body'          => 'Kära {customer_name},<br /><br />Tack för betalningen. Hitta betalningsdetaljerna nedan:<br /><br />-----------------------------------------------------<br /><br />Belopp: <strong>{transaction_total}<br /></strong>Datum: <strong>{transaction_paid_date}</strong><br />Fakturanummer: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Du kan alltid se fakturauppgifterna från följande länk: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Kontakta oss gärna för alla frågor.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Betalning mottagen för {invoice_number} faktura',
        'body'          => 'Hej,<br /><br />{customer_name} spelade in en betalning för <strong>{invoice_number}</strong> faktura.<br /><br />Du kan se fakturadetaljer från följande länk: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} notis om fakturan',
        'body'          => 'Hej,<br /><br />Detta är en påminnelse för <strong>{bill_number}</strong> räkningen till {vendor_name}.<br /><br />Räkningen är {bill_total} och ska <strong>{bill_due_date}</strong>.<br /><br />Du kan se räkningsdetaljer från följande länk: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} återkommande räkning skapad',
        'body'          => 'Hej,<br /><br /> Baserat på {vendor_name} återkommande cirkel, <strong>{bill_number}</strong> fakturan har skapats automatiskt.<br /><br />Du kan se räkningen detaljer från följande länk: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => '{revenue_date} betalning skapad',
        'body'          => 'Kära {customer_name},<br /><br />Vi har förberett följande betalning. <br /><br />Du kan se betalningsuppgifterna från följande länk: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />Kontakta oss gärna med några frågor..<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => '{revenue_date} betalning skapad',
        'body'          => 'Kära {customer_name},<br /><br />Vi har förberett följande betalning. <br /><br />Du kan se betalningsuppgifterna från följande länk: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />Kontakta oss gärna med några frågor..<br /><br />Vänliga hälsningar,<br />{company_name}',
    ],
];
