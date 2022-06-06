<?php

return [

    'payment_received'      => 'Uplata primljena',
    'payment_made'          => 'Dospijeća plaćanja',
    'paid_by'               => 'Uplaceno od',
    'paid_to'               => 'Plaćeno',
    'related_invoice'       => 'Vezane fakture',
    'related_bill'          => 'Vezani računi',
    'recurring_income'      => 'Ponavljajuće uplate',
    'recurring_expense'     => 'Ponavljajući troškovi',

    'form_description' => [
        'general'           => 'Ovdje možete unijeti opće informacije o transakciji kao što su datum, iznos, račun, opis itd.',
        'assign_income'     => 'Odaberite kategoriju i kupca kako biste svoje izvještaje učinili detaljnijim.',
        'assign_expense'    => 'Odaberite kategoriju i dobavljača kako biste svoje izvještaje učinili detaljnijim.',
        'other'             => 'Unesite referencu da transakcija ostane povezana sa vašim zapisima.',
    ],

    'slider' => [
        'create'            => ':user je kreirao ovu transakciju :date',
        'attachments'       => 'Preuzmite datoteke priložene ovoj transakciji',
        'create_recurring'  => ':user je kreirao ovaj ponavljajući šablon na :date',
        'schedule'          => 'Ponovite svaki :interval :frequency from :date',
        'children'          => ':count transakcije su kreirane automatski',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Vaš kupac može vidjeti transakciju na ovom linku',
            'copy_link'     => 'Kopirajte vezu i podijelite je sa svojim klijentom.',
        ],

        'expense' => [
            'show_link'     => 'Vaš prodavac može vidjeti transakciju na ovom linku',
            'copy_link'     => 'Kopirajte vezu i podijelite je sa svojim dobavljačem.',
        ],
    ],

    'sticky' => [
        'description'       => 'Pregledate kako će vaš klijent vidjeti web verziju vašeg plaćanja.',
    ],

];
