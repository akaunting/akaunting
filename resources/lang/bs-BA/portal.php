<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Fakture',
    'payments'              => 'Plaćanja',
    'payment_received'      => 'Uplata primljena, hvala!',
    'create_your_invoice'   => 'Sada kreirajte svoju fakturu — besplatno je',
    'get_started'           => 'Započnite besplatno',
    'billing_address'       => 'Adresa za fakturisanje',
    'see_all_details'       => 'Pogledajte sve detalje računa',
    'all_payments'          => 'Prijavite se za pregled svih uplata',
    'received_date'         => 'Datum primanja',

    'last_payment'          => [
        'title'             => 'Poslednji mod plaćanja',
        'description'       => 'Ovu uplatu ste izvršili :date',
        'not_payment'       => 'Još niste izvršili nikakvu uplatu.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Izvanredan balans',
        'description'       => 'Vaše neizmireno stanje je:',
        'not_payment'       => 'Još uvijek nemate nepodmireni dug.',
    ],

    'latest_invoices'       => [
        'title'             => 'Najnoviji fakture',
        'description'       => ':date - Naplaćeno vam je sa brojem fakture :invoice_number.',
        'no_data'           => 'Još nemate fakturu.',
    ],

    'invoice_history'       => [
        'title'             => 'Istorija fakture',
        'description'       => ':date - Naplaćeno vam je sa brojem fakture :invoice_number.',
        'no_data'           => 'Još nemate istoriju faktura.',
    ],

    'payment_history'       => [
        'title'             => 'Istorija plaćanja',
        'description'       => ':date - Uplatili ste :amount.',
        'invoice_description'=> ':date - Uplatili ste :iznos za broj računa : invoice_number.',

        'no_data'           => 'Još uvijek nemate historiju plaćanja.',
    ],

    'payment_detail'        => [
        'description'       => 'Za ovu fakturu izvršili ste uplatu :amount :date.'
    ],

];
