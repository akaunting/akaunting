<?php

return [

    'invoice_number'        => 'Številka računa',
    'invoice_date'          => 'Datum računa',
    'invoice_amount'        => 'Znesek računa',
    'total_price'           => 'Skupna cena',
    'due_date'              => 'Datum zapadlosti',
    'order_number'          => 'Številka naročila',
    'bill_to'               => 'Račun za',
    'cancel_date'           => 'Datum preklica',

    'quantity'              => 'Količina',
    'price'                 => 'Cena',
    'sub_total'             => 'Delna vsota',
    'discount'              => 'Popust',
    'item_discount'         => 'Popust',
    'tax_total'             => 'Davek skupaj',
    'total'                 => 'Skupaj',

    'item_name'             => 'Ime artikla|Imena artiklov',
    'recurring_invoices'    => 'Ponavljajoči se račun|Ponavljajoči se računi',

    'show_discount'         => ':discount% popust',
    'add_discount'          => 'Dodaj popust',
    'discount_desc'         => 'od skupno',

    'payment_due'           => 'Rok plačila',
    'paid'                  => 'Plačano',
    'histories'             => 'Zgodovine',
    'payments'              => 'Plačila',
    'add_payment'           => 'Dodaj plačilo',
    'mark_paid'             => 'Označi kot plačano',
    'mark_sent'             => 'Označi kot poslano',
    'mark_viewed'           => 'Označi kot ogledano',
    'mark_cancelled'        => 'Označi preklicano',
    'download_pdf'          => 'Prenesi PDF',
    'send_mail'             => 'Pošljite e-pošto',
    'all_invoices'          => 'Za pregled vseh računov se vpiši',
    'create_invoice'        => 'Ustvari račun',
    'send_invoice'          => 'Pošlji račun',
    'get_paid'              => 'Prejmi plačilo',
    'accept_payments'       => 'Sprejmi Spletna Plačila',
    'payments_received'     => 'Prejeto plačilo',

    'form_description' => [
        'billing'           => 'Podrobnosti za obračun so prikazane na vašem računu. Datum računa se uporablja na nadzorni plošči in poročilih. Za datum zapadlosti izberite datum, za katerega pričakujete, da boste prejeli plačilo.',
    ],

    'messages' => [
        'email_required'    => 'Za to stranko ne obstaja elektronski naslov!',
        'totals_required'   => 'Zahtevane so skupne vrednosti računov. Uredite :type in znova shranite.',

        'draft'             => 'To je <b>osnutek</b> računa, ki bo v grafikonih viden šele, ko bo poslan.',

        'status' => [
            'created'       => 'Ustvarjeno dne :date',
            'viewed'        => 'Ogledano',
            'send' => [
                'draft'     => 'Ni poslano',
                'sent'      => 'Poslano :date',
            ],
            'paid' => [
                'await'     => 'Čakanje na plačilo',
            ],
        ],

        'name_or_description_required' => 'Vaš račun mora vsebovati vsaj eno od <b>:name</b> ali <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Vaša stranka si lahko ogleda račun na tej povezavi',
        'copy_link'         => 'Kopirajte povezavo in jo delite s stranko.',
        'success_message'   => 'Povezava za skupno rabo je kopirana!',
    ],

    'sticky' => [
        'description'       => 'Predogledujete si, kako bo vaša stranka videla spletno različico vašega računa.',
    ],

];
