<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Fakturaer',
    'payments'              => 'Betalinger',
    'payment_received'      => 'Betaling mottatt, takk!',
    'create_your_invoice'   => 'Nå kan du lage din egen faktura - den er gratis',
    'get_started'           => 'Kom i gang gratis',
    'billing_address'       => 'Faktuaadresse',
    'see_all_details'       => 'Se alle detaljer om kontoen',
    'all_payments'          => 'Logg inn for å se alle betalinger',
    'received_date'         => 'Mottatt dato',

    'last_payment'          => [
        'title'             => 'Siste betaling utført',
        'description'       => 'Du har utført denne betalingen den :date',
        'not_payment'       => 'Du har ikke utført noen betalinger ennå.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Utestående balanse',
        'description'       => 'Din utestående balanse er:',
        'not_payment'       => 'Du har ikke utestående balanse ennå.',
    ],

    'latest_invoices'       => [
        'title'             => 'Siste fakturaer',
        'description'       => ':date - Du ble fakturert med fakturanummer :invoice_number.',
        'no_data'           => 'Du har ennå ikke faktura.',
    ],

    'invoice_history'       => [
        'title'             => 'Fakturahistorikk',
        'description'       => ':date - Du ble fakturert med fakturanummer :invoice_number.',
        'no_data'           => 'Du har ennå ikke fakturahistorikk.',
    ],

    'payment_history'       => [
        'title'             => 'Betalingshistorikk',
        'description'       => ':date - Du utførte en betaling på :amount.',
        'invoice_description'=> ':date - Du har betalt :amount for fakturanummer :invoice_number.',

        'no_data'           => 'Du har ennå ikke betalingshistorikk.',
    ],

    'payment_detail'        => [
        'description'       => 'Du har betalt :amount den :date for denne fakturaen.'
    ],

];
