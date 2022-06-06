<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} rēķins izveidots',
        'body'          => 'Dear {customer_name},<br /><br />We have prepared the following invoice for you: <strong>{invoice_number}</strong>.<br /><br />You can see the invoice details and proceed with the payment from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} nokavēts rēķins',
        'body'          => 'Cien. {customer_name},<br /><br />Šis ir paziņojums par nokavētu <strong>{invoice_number}</strong> rēķinu.<br /><br />Rēķina kopsumma ir {invoice_total} un bija pienācis<strong>{invoice_due_date}</strong>.<br /><br />Varat skatīt detalizētu informāciju par rēķinu un turpināt maksājumu no šīs saites: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} nokavēts rēķins',
        'body'          => 'Sveiki,<br /><br />{customer_name} ir saņēmts paziņojums nokavētam <strong>{invoice_number}</strong> rēķinam.<br /><br />Rēķina kopsumma ir {invoice_total} un ir jāapmaksā <strong>{invoice_due_date}</strong>.<br /><br />Rēķina detaļas var apskatīt no šādas saites: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} periodisks rēķins izveidots',
        'body'          => 'Dear {customer_name},<br /><br />Based on your recurring circle, we have prepared the following invoice for you: <strong>{invoice_number}</strong>.<br /><br />You can see the invoice details and proceed with the payment from the following link: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Feel free to contact us for any question.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} periodisks rēķins izveidots',
        'body'          => 'Sveiki,<br /><br />Pamatojoties uz {customer_name} periodiskumu, <strong>{invoice_number}</strong> rēķins ir izveidots automātiski.<br /><br />Rēķina detaļas var apskatīt šajā saitē: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Rēķins {invoice_number} skatīts',
        'body'          => 'Sveiki,<br /><br />{customer_name} ir apskatījis <strong>{invoice_number}</strong> rēķinu.<br /><br />Jūs varat apskatīt rēķina detaļas šajā saitē: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Payment received for {invoice_number} invoice',
        'body'          => 'Cien. {customer_name}!<br /><br />Paldies par maksājumu! Tālāk atrodiet maksājuma informāciju:<br /><br />------------------------------------- -------------<br />Summa: <strong>{transaction_total}</strong><br />Datums: <strong>{transaction_paid_date}</strong><br />Rēķins Numurs: <strong>{invoice_number}</strong><br />----------------------------------- ---------------<br /><br />Jūs vienmēr varat skatīt detalizētu informāciju par rēķinu, izmantojot šo saiti: <a href="{invoice_guest_link}">{invoice_number}</ a>.<br /><br />Ja jums ir kādi jautājumi, sazinieties ar mums.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Rēķina Nr. {invoice_number} apmaksa saņemta',
        'body'          => 'Sveiki,<br /><br />{customer_name} ierakstīja maksājumu par rēķinu<strong>{invoice_number}</strong>.<br /><br />Rēķina detaļas var apskatīt šajā saitē: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'pavadzīmes Nr.{bill_number} atgādinājuma paziņojums',
        'body'          => 'Sveiki,<br /><br />Šis ir atgādinājuma paziņojums par rēķinu<strong>{bill_number}</strong> {vendor_name}.<br /><br />Rēķina kopsumma ir {bill_total} un ir jāapmaksā <strong>{bill_due_date}</strong>.<br /><br />Rēķina detaļas var apskatīt šajā saitē: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Labākie vēlējumu,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Izveidots periodiskais rēķins Nr.{bill_number} ',
        'body'          => 'Hello,<br /><br />Based on {vendor_name} recurring circle, <strong>{bill_number}</strong> invoice has been automatically created.<br /><br />You can see the bill details from the following link: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Jūsu kvīts no {company_name}',
        'body'          => 'Cien. {contact_name}!<br /><br />Paldies par maksājumu! <br /><br />Maksājuma informāciju varat skatīt, izmantojot šo saiti: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Sazinieties ar mums par visiem jautājumiem.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Maksājumu veicis {company_name}',
        'body'          => 'Cien. {contact_name}!<br /><br />Mēs esam veikuši šādu maksājumu. <br /><br />Maksājuma informāciju varat skatīt, izmantojot šo saiti: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Sazinieties ar mums par visiem jautājumiem.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],
];
