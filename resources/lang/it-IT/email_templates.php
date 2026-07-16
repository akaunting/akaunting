<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fattura creata',
        'body'          => 'Gentile {customer_name},<br /><br />Abbiamo preparato la seguente fattura per te: <strong>{invoice_number}</strong>.<br /><br />Puoi vedere i dettagli della fattura e procedere con il pagamento dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Non esitare a contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} avviso di fattura scaduta',
        'body'          => 'Gentile {customer_name},<br /><br />Questo è un avviso di scadenza per la fattura <strong>{invoice_number}</strong>.<br /><br />Il totale della fattura è {invoice_total} con scadenza <strong>{invoice_due_date}</strong>.<br /><br />Puoi vedere i dettagli della fattura e procedere con il pagamento dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} avviso di fattura scaduta',
        'body'          => 'Salve,<br /><br />{customer_name} ha ricevuto un avviso di scadenza per la fattura <strong>{invoice_number}</strong>.<br /><br />Il totale della fattura è {invoice_total} con scadenza <strong>{invoice_due_date}</strong>.<br /><br />Puoi vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} fattura ricorrente creata',
        'body'          => 'Gentile {customer_name},<br /><br />In base al tuo ciclo ricorrente, abbiamo preparato la seguente fattura per te: <strong>{invoice_number}</strong>.<br /><br />Puoi vedere i dettagli della fattura e procedere con il pagamento dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Non esitare a contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} fattura ricorrente creata',
        'body'          => 'Salve,<br /><br />In base al ciclo ricorrente di {customer_name}, la fattura <strong>{invoice_number}</strong> è stata creata automaticamente.<br /><br />Puoi vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => '{invoice_number} fattura visualizzata',
        'body'          => 'Salve,<br /><br />{customer_name} ha visualizzato la fattura <strong>{invoice_number}</strong>.<br /><br />Puoi vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'La tua ricevuta per la fattura {invoice_number}',
        'body'          => 'Gentile {customer_name},<br /><br />Grazie per il pagamento. Di seguito sono riportati i dettagli del pagamento:<br /><br />-------------------------------------------------<br />Importo: <strong>{transaction_total}</strong><br />Data: <strong>{transaction_paid_date}</strong><br />Numero fattura: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Puoi sempre visualizzare i dettagli della fattura dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Non esitare a contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pagamento ricevuto per la fattura {invoice_number}',
        'body'          => 'Salve,<br /><br />{customer_name} ha registrato un pagamento per la fattura <strong>{invoice_number}</strong>.<br /><br />Puoi vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} promemoria fattura di acquisto',
        'body'          => 'Salve,<br /><br />Questo è un promemoria per la fattura di acquisto <strong>{bill_number}</strong> verso {vendor_name}.<br /><br />Il totale è {bill_total} con scadenza <strong>{bill_due_date}</strong>.<br /><br />Puoi vedere i dettagli della fattura di acquisto dal seguente link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} fattura di acquisto ricorrente creata',
        'body'          => 'Salve,<br /><br />In base al ciclo ricorrente di {vendor_name}, la fattura di acquisto <strong>{bill_number}</strong> è stata creata automaticamente.<br /><br />Puoi vedere i dettagli della fattura di acquisto dal seguente link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'La tua ricevuta da {company_name}',
        'body'          => 'Gentile {contact_name},<br /><br />Grazie per il pagamento. <br /><br />Puoi vedere i dettagli del pagamento dal seguente link: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Non esitare a contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Pagamento effettuato da {company_name}',
        'body'          => 'Gentile {contact_name},<br /><br />Abbiamo effettuato il seguente pagamento. <br /><br />Puoi vedere i dettagli del pagamento dal seguente link: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Non esitare a contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],
];
