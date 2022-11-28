<?php

return [

    'payment_received'      => 'Betalning mottagen',
    'payment_made'          => 'Betalning utförd',
    'paid_by'               => 'Betald av',
    'paid_to'               => 'Betalad till',
    'related_invoice'       => 'Relaterad faktura',
    'related_bill'          => 'Relaterad räkning',
    'recurring_income'      => 'Återkommande inkomst',
    'recurring_expense'     => 'Återkommande utgift',

    'form_description' => [
        'general'           => 'Här kan du ange allmän information om transaktionen såsom datum, belopp, konto, beskrivning etc.',
        'assign_income'     => 'Välj en kategori och kund för att göra dina rapporter mer detaljerade.',
        'assign_expense'    => 'Välj en kategori och leverantör för att göra dina rapporter mer detaljerade.',
        'other'             => 'Ange ett nummer och referens för att behålla transaktionen kopplad till dina poster.',
    ],

    'slider' => [
        'create'            => ':user skapade denna transaktion :date',
        'attachments'       => 'Ladda ner filerna bifogade till denna transaktion',
        'create_recurring'  => ':user skapade den här återkommande mallen :date',
        'schedule'          => 'Upprepa varje :interval :frequency sedan :date',
        'children'          => ':count transaktioner skapades automatiskt',
        'transfer_headline' => '<div> <span class="font-bold"> Från: </span> :from_account </div> <div> <span class="font-bold"> till: </span> :to_account </div>',
        'transfer_desc'     => 'Överföring skapad :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Din kund kan se transaktionen på denna länk',
            'copy_link'     => 'Kopiera länken och dela den med din kund.',
        ],

        'expense' => [
            'show_link'     => 'Din leverantör kan se transaktionen på denna länk',
            'copy_link'     => 'Kopiera länken och dela den med din leverantör.',
        ],
    ],

    'sticky' => [
        'description'       => 'Du förhandsgranskar hur din kund kommer att se webbversionen av din betalning.',
    ],

];
