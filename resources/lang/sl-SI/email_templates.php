<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Račun {invoice_number} ustvarjen',
        'body'          => 'Spoštovani {customer_name}, <br /> <br /> Za vas smo pripravili naslednji račun: <strong> {invoice_number} </strong>. <br /> <br /> Ogledate si lahko podatke o računu in nadaljujete z plačilom na tej povezavi: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Za vsa vprašanja nas lahko kontaktirate. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Opomin za račun {invoice_number}',
        'body'          => 'Spoštovani {customer_name}, <br /> <br /> To je obvestilo za zapadli račun <strong> {invoice_number} </strong>. <br /> <br /> Skupni znesek je {invoice_total} in je zapadel < močan> {invoice_due_date} </strong>. <br /> <br /> Podrobnosti računa si lahko ogledate in nadaljujete s plačilom na naslednji povezavi: <a href="{invoice_guest_link}"> {invoice_number} </ a >. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Opomin za račun {invoice_number}',
        'body'          => 'Pozdravljeni, <br /> <br /> {customer_name} je prejel opomin za račun <strong> {invoice_number} </strong>. <br /> <br /> Skupni znesek je {invoice_total} in je zapadel < strong> {invoice_due_date} </strong>. <br /> <br /> Podrobnosti o računu si lahko ogledate na naslednji povezavi: <a href="{invoice_admin_link}"> {number_account_ </a>}. <br / > <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Ponavljajoči račun {invoice_number} ustvarjen.',
        'body'          => 'Spoštovani {customer_name}, <br /> <br /> Na podlagi vašega ponavljajočega se kroga smo za vas pripravili naslednji račun: <strong> {invoice_number} </strong>. <br /> <br /> Podatke o računu si lahko ogledate in nadaljujte s plačilom na naslednji povezavi: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Za vsa vprašanja nas lahko kontaktirate. /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Ponavljajoči račun {invoice_number} ustvarjen.',
        'body'          => 'Pozdravljeni, <br /> <br /> Na podlagi ponavljajočega se obračunavanja {customer_name} je bil račun <strong>{invoice_number} </strong> samodejno ustvarjen. <br /> <br /> Podrobnosti računa si lahko ogledate na naslednji povezavi: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Prejeto plačilo za račun {invoice_number}',
        'body'          => '
590/5000
Spoštovani {customer_name}, <br /> <br /> Zahvaljujemo se vam za plačilo. Podrobnosti o plačilu poiščite spodaj: <br /> <br /> ------------------------------------ ------------- <br /> Znesek: <strong> {transaction_tota} </strong> <br /> Datum: <strong>{transaction_paid_date} </strong> <br /> Račun številka: <strong> {invoice_number} </strong> <br /> ---------------------------------- --------------- <br /> <br /> Podrobnosti računa si lahko vedno ogledate na naslednji povezavi: <a href="{invoice_guest_link}"> {invoice_number}} </ a>. <br /> <br /> Za vsa vprašanja nas lahko kontaktirate. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Prejeto plačilo za račun {invoice_number}',
        'body'          => 'Pozdravljeni, <br /> <br /> {customer_name} je zabeležil plačilo za račun <strong> {invoice_number}} </strong>. <br /> <br /> Podrobnosti računa si lahko ogledate na naslednji povezavi: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Opomin za račun {bill_number}',
        'body'          => 'Pozdravljeni, <br /> <br /> To je obvestilo za račun <strong> {bill_number}} </strong> za {vendor_name}. <br /> <br /> Skupni znesek računa je {bill_total} in je zapadel <strong> {bill_due_date} </strong>. <br /> <br /> Podrobnosti računa si lahko ogledate na naslednji povezavi: <a href="{bill_admin_link}"> {bill_number} </a>. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Ponavljajoči račun {invoice_number} ustvarjen.',
        'body'          => 'Pozdravljeni, <br /> <br /> Na podlagi ponavljajočega se obračunavanja {vendor_name} je bil račun <strong>{bill_number}</strong> samodejno ustvarjen. <br /> <br /> Podrobnosti računa si lahko ogledate na naslednji povezavi: <a href="<a href="{bill_admin_link}">"> {bill_number} </a>. <br /> <br /> Lep pozdrav, <br /> {company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => 'Plačilo {revenue_date} ustvarjeno',
        'body'          => 'Spoštovani {customer_name}, <br /><br />Za vas smo pripravili naslednji račun. <br /><br /> Ogledate si lahko podatke o računu na tej povezavi: <a href="{revenue_guest_link}">{revenue_date}</a>.<br /><br /> Za vsa vprašanja nas lahko kontaktirate.<br /><br /> Lep pozdrav,<br /> {company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => 'Plačilo {revenue_date} ustvarjeno',
        'body'          => 'Spoštovani {vendor_name}, <br /><br />Za vas smo pripravili naslednji račun. <br /><br /> Ogledate si lahko podatke o računu na tej povezavi: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br /> Za vsa vprašanja nas lahko kontaktirate.<br /><br /> Lep pozdrav,<br /> {company_name}',
    ],
];
