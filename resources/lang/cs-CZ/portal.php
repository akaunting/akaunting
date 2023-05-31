<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Faktury',
    'payments'              => 'Platby',
    'payment_received'      => 'Platba byla přijata, děkujeme!',
    'create_your_invoice'   => 'Nyní vytvořte vlastní fakturu - je to zdarma',
    'get_started'           => 'Začněte zdarma',
    'billing_address'       => 'Fakturační adresa',
    'see_all_details'       => 'Zobrazit všechny údaje o účtu',
    'all_payments'          => 'Přihlaste se pro zobrazení všech plateb',
    'received_date'         => 'Datum přijetí',
    'redirect_description'  => 'Pro provedení platby budete přesměrováni na webovou stránku :name.',

    'last_payment'          => [
        'title'             => 'Poslední platba provedena',
        'description'       => 'Tuto platbu jste provedli dne :date',
        'not_payment'       => 'Zatím jste neprovedli žádnou platbu.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Zůstatek',
        'description'       => 'Váš zůstatek je:',
        'not_payment'       => 'Zatím nemáte zbývající zůstatek.',
    ],

    'latest_invoices'       => [
        'title'             => 'Poslední faktury',
        'description'       => ':date - Bylo vám účtováno číslo faktury :invoice_number.',
        'no_data'           => 'Zatím nemáte fakturu.',
    ],

    'invoice_history'       => [
        'title'             => 'Historie faktur',
        'description'       => ':date - Bylo vám účtováno číslo faktury :invoice_number.',
        'no_data'           => 'Zatím nemáte historii faktur.',
    ],

    'payment_history'       => [
        'title'             => 'Historie plateb',
        'description'       => ':date - Provedli jste platbu ve výši :amount.',
        'invoice_description'=> ':date - Provedli jste platbu ve výši :amount za číslo faktury :invoice_number.',

        'no_data'           => 'Zatím nemáte historii plateb.',
    ],

    'payment_detail'        => [
        'description'       => 'Provedli jste platbu :amount dne :date pro tuto fakturu.'
    ],

];
