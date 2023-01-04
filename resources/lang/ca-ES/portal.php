<?php

return [

    'profile'               => 'Perfil',
    'invoices'              => 'Factures',
    'payments'              => 'Pagaments',
    'payment_received'      => 'S\'ha rebut el pagament, gràcies!',
    'create_your_invoice'   => 'Ara crea les teves pròpies factures — de franc',
    'get_started'           => 'Comença de franc',
    'billing_address'       => 'Adreça de facturació',
    'see_all_details'       => 'Veure els detalls de tots els comptes',
    'all_payments'          => 'Inicia sessió per veure tots els pagaments',
    'received_date'         => 'Data de recepció',
    'redirect_description'  => 'Seràs redirigit a la web :name per fer el pagament.',

    'last_payment'          => [
        'title'             => 'Últim pagament fet',
        'description'       => 'Has fet aquest pagament el :date',
        'not_payment'       => 'Encara no has fet el pagament',
    ],

    'outstanding_balance'   => [
        'title'             => 'Saldo pendent',
        'description'       => 'El teu saldo pendent és:',
        'not_payment'       => 'Encara no tens saldo pendent',
    ],

    'latest_invoices'       => [
        'title'             => 'Últimes factures',
        'description'       => ':date - Se\'t va facturar la factura número :invoice_number',
        'no_data'           => 'Encara no tens factures.',
    ],

    'invoice_history'       => [
        'title'             => 'Històric de factures',
        'description'       => ':date - Se\'t va facturar la factura :invoice_number.',
        'no_data'           => 'Encara no tens històric de facturació.',
    ],

    'payment_history'       => [
        'title'             => 'Històric de pagaments',
        'description'       => ':date - Vas fer un pagament de :amount.',
        'invoice_description'=> ':date - Vas fer un pagament de :amount per la factura :invoice_number.',

        'no_data'           => 'Encara no tens històric de pagaments.',
    ],

    'payment_detail'        => [
        'description'       => 'Vas fer un pagament de :amount el :date per aquesta factura.'
    ],

];
