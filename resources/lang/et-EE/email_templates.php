<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} arve loodud',
        'body'          => 'Lugupeetud {customer_name},<br /><br />Oleme teile koostanud järgmise arve: <strong>{invoice_number}</strong>.<br /><br />Arve üksikasjade vaatamiseks ja tasumiseks vajutage alljärgnevale lingile: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Küsimuste korral võtke julgelt ühendust.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Tasumata arve {invoice_number} meeldetuletus',
        'body'          => 'Lugupeetud {customer_name},<br /><br />See on tasumata arve <strong>{invoice_number}</strong> meeldetuletus.<br /><br />Arve kogusumma on {invoice_total} ja maksetähtaeg on ületatud. <strong>{invoice_due_date}</strong>.<br /><br />Arve üksikasjade vaatamiseks ja tasumiseks vajutage alljärgnevale lingile: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Tasumata arve {invoice_number} meeldetuletus',
        'body'          => 'Tere,<br /><br />{customer_name} sai arve <strong>{invoice_number}</strong> maksetähtaja ületamise teate.<br /><br />Arve kogusumma on {invoice_total} ja maksetähtaeg oli <strong>{invoice_due_date}</strong>.<br /><br />Arve üksikasju näete järgmiselt lingilt: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Kordusarve {invoice_number} loodud',
        'body'          => 'Lugupeetud {customer_name},<br /><br />Oleme teile koostanud kordusarve: <strong>{invoice_number}</strong>.<br /><br /> Arve üksikasjade vaatamiseks ja tasumiseks vajutage alljärgnevale lingile: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br /> Küsimuste korral võtke julgelt ühendust. <br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Kordusarve {invoice_number} loodud',
        'body'          => 'Tere,<br /><br />Oleme koostanud {customer_name} , <strong>{invoice_number}</strong> automaatselt kordusarve.<br /><br />Arve üksikasju näete järgmiselt lingilt: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Arve {invoice_number} makse on laekunud',
        'body'          => 'Lugupeetud {customer_name},<br /><br />Täname makse eest. Makse üksikasjad leiate allpool:<br /><br />-------------------------------------------------<br />Summa: <strong>{transaction_total}</strong><br />Kuupäev: <strong>{transaction_paid_date}</strong><br />Arve number: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Arve üksikasju näete alati järgmiselt lingilt: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Küsimuste korral võtke julgelt ühendust.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Arve {invoice_number} makse on laekunud',
        'body'          => 'Tere,<br /><br />{customer_name} registreeris arve <strong>{invoice_number}</strong> tasumise.<br /><br />Arve üksikasju näete alati järgmiselt lingilt: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Ostuarve {bill_number} maksmise meeldetuletus',
        'body'          => 'Tere,<br /><br />See on meeldetuletus {vendor_name} ostuarve <strong>{bill_number}</strong>maksmiseks.<br /><br />Ostuarve kogusumma on {bill_total} ja maksetähtaeg on <strong>{bill_due_date}</strong>.<br /><br />Ostuarve üksikasju näete järgmiselt lingilt: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Kordusostuarve {bill_number} loodud',
        'body'          => 'Tere,<br /><br /> Seoses korduvusega {vendor_name} tegevuses, oleme automaatselt loonud korduva arve <strong>{bill_number}</strong>.  Arve üksikasju näete järgmiselt lingilt: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Parimate soovidega,<br />{company_name}',
    ],

];
