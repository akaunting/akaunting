<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} faktura je kreirana',
        'body'          => 'Poštovani, {customer_name}, <br /> <br /> Za vas smo pripremili slijedeću fakturu: <strong> {invoice_number} </strong>. <br /> <br /> Možete vidjeti detalje računa i nastaviti s plaćanje sa slijedeće veze: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Slobodno nas kontaktirajte po bilo kojem pitanju. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} obavijest o zakašnjenju računa',
        'body'          => 'Poštovani, {customer_name}, <br /><br /> Ovo je zakašnjelo upozorenje za <strong> {invoice_number} </strong> fakture. <br /> <br /> Ukupni račun fakture je {invoice_total} i dospio je < strong> {invoice_due_date} </strong>. <br /> <br />Pojedinosti o računu možete vidjeti i nastaviti s plaćanjem sa sljedeće veze: <a href="{invoice_guest_link}"> {invoice_number} </ a >. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} obavijest o neplaćanju fakture',
        'body'          => 'Poštovani, {customer_name}, <br /><br /> zaprimio je zakašnjelo upozorenje za <strong> {invoice_number} </strong> fakture. <br /> <br /> Ukupni račun fakture je {invoice_total} i dospio je < strong> {invoice_due_date} </strong>. <br /> <br />Pojedinosti o računu možete vidjeti i nastaviti s plaćanjem sa sljedeće veze: <a href="{invoice_guest_link}"> {invoice_number} </ a >. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} faktura je kreirana',
        'body'          => 'Poštovani, {customer_name}, <br /> <br /> Za vas smo pripremili slijedeću fakturu: <strong> {invoice_number} </strong>. <br /> <br /> Možete vidjeti detalje računa i nastaviti s plaćanje sa slijedeće veze: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Slobodno nas kontaktirajte po bilo kojem pitanju. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} faktura je kreirana',
        'body'          => 'Pozdrav, <br /> <br /> Na temelju {customer_name} ponavljajućeg kruga, <strong> {invoice_number} </strong> faktura je automatski stvorena. <br /> <br /> Pojedinosti računa možete vidjeti u odjeljku sljedeća veza: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Primanje plaćanja za {invoice_number} fakturu',
        'body'          => 'Poštovani {customer_name},<br /><br />Hvala na uplati. Pojedinosti o plaćanju potražite u nastavku:<br /><br />-------------------------------------------------<br />Iznos: <strong>{transaction_total}</strong><br />Datum: <strong>{transaction_paid_date}</strong><br />Invoice Number: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Pojedinosti računa uvijek možete vidjeti na sljedećoj poveznici: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />
Slobodno nas kontaktirajte za svako pitanje.<br /><br />Lijep Pozdrav,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Primanje plaćanja za {invoice_number} fakturu',
        'body'          => 'Pozdrav, <br /> <br /> Na temelju {customer_name} ponavljajućeg kruga, <strong> {invoice_number} </strong> faktura je automatski stvorena. <br /> <br /> Pojedinosti računa možete vidjeti u odjeljku sljedeća veza: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} obavijest o podjsetniku na račun',
        'body'          => 'Pozdrav,<br /><br />Ovo je podsjetnik za <strong>{bill_number}</strong> račun {vendor_name}.<br /><br />Ukupna vrijednost računa je {bill_total} i dospijeva<strong>{bill_due_date}</strong>.<br /><br />Pojedinosti o računu možete vidjeti na sljedećoj poveznici: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Lijep pozdrav,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} kreiran ponavljajući račun',
        'body'          => 'Pozdrav, <br /> <br /> Na temelju {vendor_name} ponavljajućeg kruga, <strong> {bill_number} </strong> faktura je automatski stvorena. <br /> <br /> Pojedinosti računa možete vidjeti u odjeljku sljedeća veza: <a href="{bill_admin_link}"> {bill_number} </a>. <br /> <br /> Srdačan pozdrav, <br /> {company_name}',
    ],

];
