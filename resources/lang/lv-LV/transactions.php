<?php

return [

    'payment_received'      => 'Saņemts maksājums',
    'payment_made'          => 'Maksājums veikts',
    'paid_by'               => 'Apmaksāja',
    'paid_to'               => 'Samaksāts uz',
    'related_invoice'       => 'Saistīts rēķins',
    'related_bill'          => 'Saistīts rēķins',
    'recurring_income'      => 'Periodiskie ieņēmumi',
    'recurring_expense'     => 'Peridiskās izmaksas',

    'form_description' => [
        'general'           => 'Šeit varat ievadīt vispārīgu informāciju par darījumu, piemēram, datumu, summu, kontu, aprakstu utt.',
        'assign_income'     => 'Atlasiet kategoriju un klientu, lai padarītu jūsu pārskatus detalizētākus.',
        'assign_expense'    => 'Atlasiet kategoriju un piegādātāju, lai padarītu jūsu pārskatus detalizētākus.',
        'other'             => 'Ievadiet atsauci, lai darījums būtu saistīts ar jūsu ierakstiem.',
    ],

    'slider' => [
        'create'            => ':user izveidoja šo pārskaitījumu :date',
        'attachments'       => 'Lejupielādējiet šim darījumam pievienotos failus',
        'create_recurring'  => ':user izveidoja šo periodisko veidni :date',
        'schedule'          => 'Atkārtojiet katru :interval :frequency kopš :date',
        'children'          => ':count transakcijas tika izveidotas automātiski',
        'transfer_headline' => '<div> <span class="font-bold"> No: </span> :from_account </div> <div> <span class="font-bold"> uz: </span> :to_account </div>',
        'transfer_desc'     => 'Pārskaitījums izveidots :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Jūsu klients darījumu var apskatīt šajā saitē',
            'copy_link'     => 'Kopējiet saiti un kopīgojiet to ar savu klientu.',
        ],

        'expense' => [
            'show_link'     => 'Jūsu piegādātājs var apskatīt darījumu, izmantojot šo saiti',
            'copy_link'     => 'Kopējiet saiti un kopīgojiet to ar savu piegādātāju.',
        ],
    ],

    'sticky' => [
        'description'       => 'Jūsu priekšskatījums, kā klients redzēs jūsu maksājuma tīmekļa versiju.',
    ],

];
