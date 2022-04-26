<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} rēķins izveidots',
        'body'          => 'Cien. {customer_name},<br /><br />Mēs jums esam sagatavojuši šādu rēķinu: <strong>{invoice_number}</strong>.<br /><br />Varat skatīt rēķina informāciju un turpināt maksājumu no šādas saites: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Varat sazināties ar mums, uzdodot jebkādus jautājumus.<br /><br />Labākie vēlējumi,<br />{company_name}',
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
        'body'          => 'Cien. {customer_name},<br /><br />Pamatojoties uz jūsu periodiskumu, esam jums sagatavojuši šādu rēķinu:
 <strong>{invoice_number}</strong>.<br /><br />Varat skatīt rēķina informāciju un turpināt maksājumu no šādas saites: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Varat sazināties ar mums, uzdodot jebkādus jautājumus.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} periodisks rēķins izveidots',
        'body'          => 'Sveiki,<br /><br />Pamatojoties uz {customer_name} periodiskumu, <strong>{invoice_number}</strong> rēķins ir izveidots automātiski.<br /><br />Rēķina detaļas var apskatīt šajā saitē: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Rēķina Nr. {invoice_number} apmaksa saņemta',
        'body'          => 'Cien., {customer_name},<br /><br />Paldies par maksājumu. Maksājuma rekvizīti atrodas zemāk:<br /><br />-------------------------------------------------<br />Summa: <strong>{transaction_total}</strong><br />Datums: <strong>{transaction_paid_date}</strong><br />Rēķina numurs: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Rēķina detaļas vienmēr var skatīt šajā saitē: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Varat sazināties ar mums, uzdodot jebkādus jautājumus.<br /><br />Labākie vēlējumi,<br />{company_name}',
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

    'revenue_new_customer' => [
        'subject'       => 'Maksājums izveidots:{revenue_date}',
        'body'          => 'Cien. {customer_name},<br /><br />Esam sagatavojuši šādu maksājumu. <br /><br />Maksājuma informāciju varat skatīt šajā saitē: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br />Varat sazināties ar mums, uzdodot jebkādus jautājumus.<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => 'Maksājums izveidots:{revenue_date}',
        'body'          => 'Cien., {vendor_name},<br /><br />Esam sagatavojuši šādu maksājumu. <br /><br />Maksājuma informāciju varat skatīt šajā saitē: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />Varat sazināties ar mums, uzdodot jebkādus jautājumus..<br /><br />Labākie vēlējumi,<br />{company_name}',
    ],
];
