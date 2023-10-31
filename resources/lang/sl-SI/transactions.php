<?php

return [

    'payment_received'      => 'Plačilo je bilo prejeto',
    'payment_made'          => 'Plačilo je bilo izvedeno',
    'paid_by'               => 'Plačnik',
    'paid_to'               => 'Plačano',
    'related_invoice'       => 'Povezan račun',
    'related_bill'          => 'Povezani račun',
    'recurring_income'      => 'Ponavljajoči se prihodki',
    'recurring_expense'     => 'Ponavljajoči se odhodki',
    'included_tax'          => 'Znesek davka je vključen',
    'connected'             => 'Povezano',

    'form_description' => [
        'general'           => '
Tukaj lahko vnesete splošne informacije o transakciji, kot so datum, znesek, račun, opis itd.',
        'assign_income'     => 'Izberite kategorijo in stranko, da bodo vaša poročila podrobnejša.',
        'assign_expense'    => 'Izberite kategorijo in dobavitelja da bodo vaša poročila podrobnejša.',
        'other'             => 'Vnesite številko in sklic, da bo transakcija povezana z vašimi evidencami.',
    ],

    'slider' => [
        'create'            => ':user je ustvaril ta račun dne :date',
        'attachments'       => 'Prenesite datoteke, priložene tej transakciji',
        'create_recurring'  => ':user je ustvaril to ponavljajočo se predlogo dne :date',
        'schedule'          => 'Ponovi vsak :interval :pogostost od :date',
        'children'          => ':count računov je bilo ustvarjeno samodejno',
        'connect'           => 'Ta transakcija je povezana s :count transakcijami',
        'transfer_headline' => '<div> <span class="font-bold"> Od: </span> :from_account </div> <div> <span class="font-bold"> do: </span> :to_account </div>',
        'transfer_desc'     => 'Prenos ustvarjen dne :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Vaša stranka si lahko ogleda račun na tej povezavi',
            'copy_link'     => 'Kopirajte povezavo in jo delite s stranko.',
        ],

        'expense' => [
            'show_link'     => '
Vaš dobavitelj si lahko transakcijo ogleda na tej povezavi',
            'copy_link'     => '
Kopirajte povezavo in jo delite s svojim dobaviteljem.',
        ],
    ],

    'sticky' => [
        'description'       => 'Predogledujete si, kako bo vaša stranka videla spletno različico vašega računa.',
    ],

];
