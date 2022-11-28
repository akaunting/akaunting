<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Fakturor',
    'payments'              => 'Betalningar',
    'payment_received'      => 'Betalning mottagen, tack!',
    'create_your_invoice'   => 'Du kan nu skapa din egen faktura – det är gratis',
    'get_started'           => 'Kom igång helt gratis',
    'billing_address'       => 'Faktureringsadress',
    'see_all_details'       => 'Se alla kontouppgifter',
    'all_payments'          => 'Logga in för att se alla betalningar',
    'received_date'         => 'Datum för mottagande',
    'redirect_description'  => 'Du kommer att omdirigeras till :name webbplats för att göra betalningen.',

    'last_payment'          => [
        'title'             => 'Senaste betalningen gjordes',
        'description'       => 'Du gjorde denna betalning :date',
        'not_payment'       => 'Du har inte gjort någon betalning, ännu.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Utestående balans',
        'description'       => 'Ditt utestående saldo är:',
        'not_payment'       => 'Du har inget utestående saldo, ännu.',
    ],

    'latest_invoices'       => [
        'title'             => 'Senaste fakturorna',
        'description'       => ':date - Du blev fakturerad med fakturanummer :invoice_nummer.',
        'no_data'           => 'Du har inga fakturor, ännu.',
    ],

    'invoice_history'       => [
        'title'             => 'Fakturahistorik',
        'description'       => ':date - Du blev fakturerad med fakturanummer :invoice_nummer.',
        'no_data'           => 'Du har ingen fakturahistorik, ännu.',
    ],

    'payment_history'       => [
        'title'             => 'Betalningshistorik',
        'description'       => ':date - Du betalade :amount.',
        'invoice_description'=> ':date - Du betalade :amount för fakturanummer :invoice_number.',

        'no_data'           => 'Du har ingen betalningshistorik, ännu.',
    ],

    'payment_detail'        => [
        'description'       => ':date gjorde du en betalning på :amount för denna faktura.'
    ],

];
