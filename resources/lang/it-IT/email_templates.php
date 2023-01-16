<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fattura creata',
        'body'          => 'Gentile {customer_name},<br /><br />Abbiamo preparato la seguente fattura per Lei: <strong>{invoice_number}</strong>.<br /><br />Può vedere i dettagli della fattura e procedere con il pagamento dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sentitevi liberi di contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} avviso di fattura scaduto',
        'body'          => 'Gentile {customer_name},<br /><br />Questo è un avviso in ritardo per <strong>{invoice_number}</strong> fattura.<br /><br />Il totale della fattura è di {invoice_total} ed è dovuto a <strong>{invoice_due_date}</strong>.<br /><br />Puoi vedere i dettagli della fattura e procedere con il pagamento dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} avviso di fattura scaduto',
        'body'          => 'Gentile,<br /><br />{customer_name} ha ricevuto un avviso di ritardo per <strong>{invoice_number}</strong> fattura.<br /><br />Il totale della fattura è di {invoice_total} ed è dovuto a <strong>{invoice_due_date}</strong>.<br /><br />Puoi vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} fattura ricorrente creata',
        'body'          => 'Gentile {customer_name},<br /><br />Basato sul suo giro ricorrente, abbiamo preparato la seguente fattura per Lei: <strong>{invoice_number}</strong>.<br /><br />Puoi vedere i dettagli della fattura e procedere con il pagamento dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sentitevi liberi di contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} fattura ricorrente creata',
        'body'          => 'Gentile,<br /><br /> Basato sul cerchio ricorrente di {customer_name}, <strong>{invoice_number}</strong> la fattura è stata creata automaticamente.<br /><br />Puoi vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pagamento ricevuto per la fattura {invoice_number}',
        'body'          => 'Gentile {customer_name},<br /><br />Grazie per il pagamento. Troverà qui sotto i dettagli del pagamento:<br /><br />-----------------------------------------<br /><br />Importo: <strong>{transaction_total}<br /></strong>Data: <strong>{transaction_paid_date}</strong><br />Numero Fattura: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Potrà sempre vedere i dettagli della fattura dal seguente link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Sentitevi liberi di contattarci per qualsiasi domanda.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pagamento ricevuto per {invoice_number} fattura',
        'body'          => 'Gentile,<br /><br />{customer_name} ha registrato un pagamento per <strong>{invoice_number}</strong> fattura.<br /><br />Può vedere i dettagli della fattura dal seguente link: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} avviso di promemoria di fattura',
        'body'          => 'Gentile,<br /><br />questo è un avviso di promemoria per <strong>{bill_number}</strong> bolletta verso {vendor_name}.<br /><br />Il conto totale è di {bill_total} ed è in scadenza <strong>{bill_due_date}</strong>.<br /><br />puoi vedere i dettagli della bolletta dal seguente link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} bollette ricorrenti create',
        'body'          => 'Gentile,<br /><br /> Basato sul cerchio ricorrente di {vendor_name}, <strong>{bill_number}</strong> la fattura è stata creata automaticamente.<br /><br />Puoi vedere i dettagli della bolletta dal seguente link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Cordiali saluti,<br />{company_name}',
    ],

];
