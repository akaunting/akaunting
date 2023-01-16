<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Utworzono fakturę {invoice_number}',
        'body'          => 'Szanowny(a) {customer_name},<br /><br />Przygotowaliśmy dla Ciebie następującą fakturę: <strong>{invoice_number}</strong>.<br /><br />Szczegóły faktury i płatność dostępne w poniższym linku: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Jeżeli masz jakieś pytania zapraszamy do kontaktu z nami.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Informacja o przekroczeniu terminu płatności faktury {invoice_number}',
        'body'          => 'Szanowny(a) {customer_name},<br /><br />To jest informacja o przekroczeniu terminu płatności faktury <strong>{invoice_number}</strong>.<br /><br />Termin płatności faktury o wartości {invoice_total} minął <strong>{invoice_due_date}</strong>.<br /><br />Szczegóły faktury i płatność dostępne w poniższym linku: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Informacja o przekroczeniu terminu płatności faktury {invoice_number}',
        'body'          => 'Witaj,<br /><br />{customer_name} otrzymał informację o zaległym terminie płatności faktury <strong>{invoice_number}</strong>.<br /><br />Termin płatności faktury o wartości {invoice_total} minął <strong>{invoice_due_date}</strong>.<br /><br />Szczegóły faktury dostępne w poniższym linku:: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Utworzono fakturę cykliczną {invoice_number}',
        'body'          => 'Szanowny(a) {customer_name},<br /><br />Przygotowaliśmy dla Ciebie fakturę cykliczną: <strong>{invoice_number}</strong>.<br /><br />Szczegóły faktury i płatność dostępne w poniższym linku: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Jeżeli masz jakieś pytania zapraszamy do kontaktu z nami.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Utworzono fakturę cykliczną {invoice_number}',
        'body'          => 'Witaj,<br /><br />Cykliczna faktura <strong>{invoice_number}</strong>  dla {customer_name} została wygenerowana automatycznie.<br /><br />Szczegóły faktury dostępne w poniższym linku: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Płatność otrzymana za fakturę {invoice_number}',
        'body'          => 'Szanowny(a) {customer_name},<br /><br />Dziękujemy za płatność. Poniżej znajdziesz szczegóły płatności:<br /><br />-------------------------------------------------<br />Kwota: <strong>{transaction_total}</strong><br />Data: <strong>{transaction_paid_date}</strong><br />Numer faktury: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Zawsze możesz zobaczyć dane faktury pod linkiem: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Jeżeli masz jakieś pytania zapraszamy do kontaktu z nami.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Płatność otrzymana za fakturę {invoice_number}',
        'body'          => 'Witaj,<br /><br />{customer_name} uiścił zapłatę za fakturę <strong>{invoice_number}</strong>.<br /><br />Szczegóły faktury dostępne w poniższym linku: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Rachunku {bill_number} jest zaległy (wydatki) ',
        'body'          => 'Witaj,<br /><br />To jest przypomnienie o rachunku <strong>{bill_number}</strong> od {vendor_name}.<br /><br />Wysokość rachunku wynosi {bill_total} i jest płatna do <strong>{bill_due_date}</strong>.<br /><br />Szczegóły rachunku dostępne są w poniższym linku: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Utworzono rachunek cykliczny {bill_number}',
        'body'          => 'Witaj,<br /><br />Cykliczny rachunek <strong>{bill_number}</strong>  dla {vendor_name} został wygenerowany automatycznie.<br /><br />Szczegóły rachunku są dostępne w poniższym linku: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => 'Płatność uworzono dnia {revenue_date}
',
        'body'          => 'Szanowny(a) {customer_name},<br /><br />Przygotowaliśmy dla Ciebie płatność za fakturę: <strong>{invoice_number}</strong>.<br /><br />Szczegóły płatności dostępne w poniższym linku: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Jeżeli masz jakieś pytania zapraszamy do kontaktu.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => 'Płatność uworzono dnia {revenue_date}
',
        'body'          => '
Szanowny {vendor_name},<br /><br />Przygotowaliśmy dla Ciebie płatność za fakturę. <br /><br />Szczegóły płatności dostępne w poniższym linku: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />Jeżeli masz jakieś pytania zapraszamy do kontaktu.<br /><br />Pozdrawiamy,<br />{company_name}',
    ],
];
