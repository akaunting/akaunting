<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Fakturaer',
    'payments'              => 'Betalinger',
    'payment_received'      => 'Betaling modtaget, tak!',
    'create_your_invoice'   => 'Opret nu din egen faktura — det er gratis',
    'get_started'           => 'Kom gratis igang',
    'billing_address'       => 'Betalingsadresse',
    'see_all_details'       => 'Se alle kontooplysninger',
    'all_payments'          => 'Log ind for at se alle betalinger',
    'received_date'         => 'Modtaget den :date',
    'redirect_description'  => 'Du vil blive omdirigeret til :name hjemmeside for at foretage betalingen.',

    'last_payment'          => [
        'title'             => 'Sidste betaling foretaget',
        'description'       => 'Du har foretaget denne betaling den :date',
        'not_payment'       => 'Du har ikke foretaget nogen betaling, endnu.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Udestående saldo',
        'description'       => 'Din udestående saldo er:',
        'not_payment'       => 'Du har endnu ikke ingen udestående saldo.',
    ],

    'latest_invoices'       => [
        'title'             => 'Seneste Fakturaer',
        'description'       => ':date - Du blev faktureret med fakturanummer :invoice_number.',
        'no_data'           => 'Du har ingen fakturaer endnu.',
    ],

    'invoice_history'       => [
        'title'             => 'Faktura Historik',
        'description'       => ':date - Du blev faktureret med fakturanummer :invoice_number.',
        'no_data'           => 'Du har endnu ikke fakturahistorik.',
    ],

    'payment_history'       => [
        'title'             => 'Betalingshistorik',
        'description'       => ':date - Du har foretaget en betaling af :amount',
        'invoice_description'=> ':date - Du har betalt :amount for fakturanummer :invoice_number.',

        'no_data'           => 'Du har ikke betalingshistorik endnu.',
    ],

    'payment_detail'        => [
        'description'       => 'Du har foretaget en :amount betaling den :date for denne faktura.'
    ],

];
