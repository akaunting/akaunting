<?php

return [

    'payment_received'      => 'Betaling mottatt',
    'payment_made'          => 'Betaling utført',
    'paid_by'               => 'Betalt av',
    'paid_to'               => 'Betalt til',
    'related_invoice'       => 'Relatert faktura',
    'related_bill'          => 'Relatert regning',
    'recurring_income'      => 'Gjentakende inntekt',
    'recurring_expense'     => 'Gjentakende utgift',

    'form_description' => [
        'general'           => 'Her kan du legge inn den generelle informasjonen om transaksjoner, som dato, beløp, konto, beskrivelse, osv.',
        'assign_income'     => 'Velg en kategori og kunde for å gjøre rapportene mer detaljert.',
        'assign_expense'    => 'Velg en kategori og leverandør for å gjøre rapportene mer detaljert.',
        'other'             => 'Angi et nummer og referanse for å beholde transaksjonen som er knyttet til oppføringene dine.',
    ],

    'slider' => [
        'create'            => ':user opprettet denne transaksjonen den :date',
        'attachments'       => 'Last ned filer som er vedlagt denne transaksjonen',
        'create_recurring'  => ':user opprettet denne gjentagende malen den :date',
        'schedule'          => 'Gjenta hvert :interval :frequency siden :date',
        'children'          => ':count transaksjoner ble automatisk opprettet',
        'transfer_headline' => 'Fra :from_account til :to_account',
        'transfer_desc'     => 'Overføring opprettet den :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Din kunde kan se transaksjonen på denne lenken',
            'copy_link'     => 'Kopier lenken og del den med kunden.',
        ],

        'expense' => [
            'show_link'     => 'Din leverandør kan se transaksjonen på denne lenken',
            'copy_link'     => 'Kopier lenken og del den med leverandøren din.',
        ],
    ],

    'sticky' => [
        'description'       => 'Du ser på hvordan din kunde vil se webversjonen av din betaling.',
    ],

];
