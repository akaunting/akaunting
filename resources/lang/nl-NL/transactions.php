<?php

return [

    'payment_received'      => 'Betaling Ontvangen',
    'payment_made'          => 'Betaling Gebeurd',
    'paid_by'               => 'Betaald door',
    'paid_to'               => 'Betaald aan',
    'related_invoice'       => 'Gerelateerde factuur',
    'related_bill'          => 'Gerelateerde rekening',
    'recurring_income'      => 'Terugkerende Inkomsten',
    'recurring_expense'     => 'Terugkerende Uitgaven',
    'included_tax'          => 'Inclusief belastingbedrag',
    'connected'             => 'Verbonden',
    'connect_message'       => 'Belastingen voor deze :type werden niet berekend tijdens het verbindingsproces. Belastingen kunnen niet worden verbonden.',

    'form_description' => [
        'general'           => 'Hier kunt u de algemene informatie van de transactie invoeren, zoals datum, bedrag, rekening, beschrijving, enz.',
        'assign_income'     => 'Selecteer een categorie en een klant om uw rapporten gedetailleerder te maken.',
        'assign_expense'    => 'Selecteer een categorie en een verkoper om uw rapporten gedetailleerder te maken.',
        'other'             => 'Voer een nummer en een referentie in om de transactie aan uw gegevens te koppelen.',
    ],

    'slider' => [
        'create'            => ':user maakte deze transactie op :date',
        'attachments'       => 'Download de bestanden die aan deze transactie zijn gekoppeld',
        'create_recurring'  => ':user maakte deze terugkerende sjabloon op :date',
        'schedule'          => 'Herhaal elke :interval :frequency sinds :date',
        'children'          => ':count transacties werden automatisch aangemaakt',
        'connect'           => 'Deze transactie is gekoppeld aan :count transacties',
        'transfer_headline' => '<div> <span class="font-bold"> Van: </span> :from_account </div> <div> <span class="font-bold"> to: </span> :to_account </div>',
        'transfer_desc'     => 'Transactie gemaakt op :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Uw klant kan de transactie bekijken via deze link',
            'copy_link'     => 'Kopieer de link en deel hem met uw klant.',
        ],

        'expense' => [
            'show_link'     => 'Uw verkoper kan de transactie bekijken via deze link',
            'copy_link'     => 'Kopieer de link en deel hem met uw verkoper.',
        ],
    ],

    'sticky' => [
        'description'       => 'Je bekijkt hoe je klant de webversie van je betaling zal zien.',
    ],

];
