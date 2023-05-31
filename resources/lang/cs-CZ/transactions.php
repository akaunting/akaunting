<?php

return [

    'payment_received'      => 'Platba přijata',
    'payment_made'          => 'Platba vytvořena',
    'paid_by'               => 'Zaplaceno od',
    'paid_to'               => 'Zaplaceno',
    'related_invoice'       => 'Související faktura',
    'related_bill'          => 'Související přijatá faktura',
    'recurring_income'      => 'Opakované příjmy',
    'recurring_expense'     => 'Opakované výdaje',

    'form_description' => [
        'general'           => 'Zde můžete zadat obecné informace o transakcích, jako je datum, částka, účet, popis, atd.',
        'assign_income'     => 'Vyberte kategorii a zákazníka, aby vaše reporty byly podrobnější.',
        'assign_expense'    => 'Vyberte kategorii a dodavatele pro podrobnější přehledy.',
        'other'             => 'Zadejte číslo a odkaz pro zachování transakce propojené s vašimi záznamy.',
    ],

    'slider' => [
        'create'            => ':user vytvořil tuto transakci dne :date',
        'attachments'       => 'Stáhnout soubory připojené k této transakci',
        'create_recurring'  => ':user vytvořil tuto opakovanou šablonu dne :date',
        'schedule'          => 'Opakovat každý :interval :frequency od :date',
        'children'          => ':count transakcí bylo vytvořeno automaticky',
        'transfer_headline' => '<div> <span class="font-bold"> Od: </span> :from_account </div> <div> <span class="font-bold"> na: </span> :to_account </div>',
        'transfer_desc'     => 'Převod vytvořen :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Váš zákazník může zobrazit transakci na tomto odkazu',
            'copy_link'     => 'Zkopírujte odkaz a sdílejte jej se svým zákazníkem.',
        ],

        'expense' => [
            'show_link'     => 'Váš dodavatel může zobrazit transakci na tomto odkazu',
            'copy_link'     => 'Zkopírujte odkaz a sdílejte jej s vaším dodavatelem.',
        ],
    ],

    'sticky' => [
        'description'       => 'Prohlížíte, jak bude Váš zákazník vidět webovou verzi vaší platby.',
    ],

];
