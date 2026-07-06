<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Faktury',
    'payments'              => 'Płatności',
    'payment_received'      => 'Płatność otrzymana, dziękujemy!',
    'create_your_invoice'   => 'Teraz utwórz własną fakturę — jest darmowa',
    'get_started'           => 'Rozpocznij za darmo',
    'billing_address'       => 'Adres rozliczeniowy',
    'see_all_details'       => 'Zobacz wszystkie szczegóły konta',
    'all_payments'          => 'Zaloguj się, aby zobaczyć wszystkie płatności',
    'received_date'         => 'Data otrzymania',
    'redirect_description'  => 'Zostaniesz przekierowany na stronę :name, aby dokonać płatności.',

    'last_payment'          => [
        'title'             => 'Ostatnia płatność',
        'description'       => 'Dokonałeś tej płatności w dniu :date',
        'not_payment'       => 'Nie dokonałeś jeszcze żadnej płatności.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Zadłużenie',
        'description'       => 'Twoje zadłużenie wynosi:',
        'not_payment'       => 'Nie masz jeszcze zadłużenia.',
    ],

    'latest_invoices'       => [
        'title'             => 'Najnowsze faktury',
        'description'       => ':date - Otrzymałeś fakturę numer :invoice_number.',
        'no_data'           => 'Nie masz jeszcze żadnych faktur.',
    ],

    'invoice_history'       => [
        'title'             => 'Historia faktur',
        'description'       => ':date - Otrzymałeś fakturę numer :invoice_number.',
        'no_data'           => 'Nie masz jeszcze historii faktur.',
    ],

    'payment_history'       => [
        'title'             => 'Historia płatności',
        'description'       => ':date - Dokonałeś płatności w wysokości :amount.',
        'invoice_description'=> ':date - Dokonałeś płatności w wysokości :amount za fakturę numer :invoice_number.',

        'no_data'           => 'Nie masz jeszcze historii płatności.',
    ],

    'payment_detail'        => [
        'description'       => 'Dokonałeś płatności w wysokości :amount w dniu :date za tę fakturę.'
    ],

];
