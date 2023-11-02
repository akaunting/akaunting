<?php

return [

    'edit_columns'              => 'Uredi stolpce',
    'empty_items'               => 'Niste dodali nobenega elementa.',
    'grand_total'               => 'Skupna vsota',
    'accept_payment_online'     => 'Sprejemajte plačila prek spleta',
    'transaction'               => 'Plačilo v vrednosti :amount je bila narejena na :account.',
    'billing'                   => 'Obračun',
    'advanced'                  => 'Napredno',

    'item_price_hidden'         => 'Ta stolpec je skrit v vašem :type.',

    'actions' => [
        'cancel'                => 'Prekliči',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Vi</b> ste ta račun označili kot',
        'services'              => 'Storitve',
        'another_item'          => 'Še en predmet',
        'another_description'   => 'in še en opis',
        'more_item'             => '+:count elementov',
    ],

    'statuses' => [
        'draft'                 => 'Osnutek',
        'sent'                  => 'Poslano',
        'expired'               => 'Preteklo',
        'viewed'                => 'Ogledano',
        'approved'              => 'Odobreno',
        'received'              => 'Prejeto',
        'refused'               => 'Zavrnjeno',
        'restored'              => 'Obnovljeno',
        'reversed'              => 'Obrnjeno',
        'partial'               => 'Delno',
        'paid'                  => 'Plačano',
        'pending'               => 'V čakanju',
        'invoiced'              => 'Fakturirano',
        'overdue'               => 'Zapadlo',
        'unpaid'                => 'Neplačano',
        'cancelled'             => 'Preklicano',
        'voided'                => 'Razveljavljeno',
        'completed'             => 'Zaključeno',
        'shipped'               => 'Poslano',
        'refunded'              => 'Povrnjeno',
        'failed'                => 'Neuspešno',
        'denied'                => 'Zavrnjeno',
        'processed'             => 'Procesirano',
        'open'                  => 'Odprto',
        'closed'                => 'Zaprto ',
        'billed'                => 'Zaračunano',
        'delivered'             => 'Dostavljeno',
        'returned'              => 'Vrnjeno',
        'drawn'                 => 'Narisano',
        'not_billed'            => 'Ni obračunano',
        'issued'                => 'Izdano',
        'not_invoiced'          => 'Ni fakturirano',
        'confirmed'             => 'Potrjeno',
        'not_confirmed'         => 'Ni potrjeno',
        'active'                => 'Aktivno',
        'ended'                 => 'Končano',
    ],

    'form_description' => [
        'companies'             => 'Spremenite naslov, logotip in druge podatke vašega podjetja.',
        'billing'               => 'Podrobnosti za obračun so prikazane v vašem dokumentu.',
        'advanced'              => 'Izberite kategorijo, dodajte ali uredite nogo in dodajte priloge svojemu :type.',
        'attachment'            => 'Prenesite datoteke, priložene tej transakciji tipa :type',
    ],

    'slider' => [
        'create'            => ':user je ustvaril :type dne :date',
        'create_recurring'  => ':user je ustvaril ponavljajočo se predlogo dne :date',
        'send'              => ':user je poslal :type dne :date',
        'schedule'          => 'Ponovi vsak :interval :frequency od :date',
        'children'          => ':count :type so bili ustvarjeni avtomatsko',
        'cancel'            => ':user je preklical :type dne :date',
    ],

    'messages' => [
        'email_sent'            => ':type elektronska pošta je bila poslana!',
        'restored'              => ':type je bilo obnovljeno!',
        'marked_as'             => ':type označen kot :status!',
        'marked_sent'           => ':type označen kot poslan!',
        'marked_paid'           => ':type označen kot plačan!',
        'marked_viewed'         => ':type označek kot ogledan!',
        'marked_cancelled'      => ':type označen kot preklican!',
        'marked_received'       => ':type označen kot prejet!',
    ],

    'recurring' => [
        'auto_generated'        => 'Samodejno ustvarjeno',

        'tooltip' => [
            'document_date'     => 'Datum :type bo samodejno dodeljen glede na razpored :type in pogostost.',
            'document_number'   => 'Številka :type bo samodejno dodeljena, ko se ustvari vsak ponavljajoči se :type.',
        ],
    ],

    'empty_attachments'         => 'Temu :type ni priložena nobena datoteka.',
];
