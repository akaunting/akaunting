<?php

return [

    'payment_received'      => 'S\'ha rebut el pagament',
    'payment_made'          => 'S\'ha fet el pagament',
    'paid_by'               => 'Pagat per',
    'paid_to'               => 'Pagat a',
    'related_invoice'       => 'Factura relacionada',
    'related_bill'          => 'Factura relacionada',
    'recurring_income'      => 'Ingrés recurrent',
    'recurring_expense'     => 'Despesa recurrent',
    'included_tax'          => 'Impostos inclosos',
    'connected'             => 'Connectat',
    'connect_message'       => 'Els impostos per aquest :type no s\'han calculat durant el procés d\'enllaç. Els impostos no es poden enllaçar.',

    'form_description' => [
        'general'           => 'Aquí pots introduir la informació general de la transacció com ara data, quantitat, compte, descripció, etc.',
        'assign_income'     => 'Selecciona una categoria i un client per acotar més els informes.',
        'assign_expense'    => 'Selecciona una categoria i un proveïdor per acotar més els informes.',
        'other'             => 'Entra una referència per mantenir la transacció enllaçada amb els teus registres.',
    ],

    'slider' => [
        'create'            => ':user va crear aquesta transacció el :date',
        'attachments'       => 'Descarrega els arxius enllaçats a aquesta transacció',
        'create_recurring'  => ':user va crear aquesta plantilla recurrent el dia :date',
        'schedule'          => 'Repeteix cada :interval :frequency des de :date',
        'children'          => 'S\'han creat :count factures automàticament',
        'connect'           => 'Aquesta transacció està connectada a :count transaccions',
        'transfer_headline' => 'Des de :from_account a :to_account',
        'transfer_desc'     => 'Transferència creada el :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'El teu client pot veure la factura des d\'aquest enllaç',
            'copy_link'     => 'Copia l\'enllaç i comparteix-lo amb el teu client.',
        ],

        'expense' => [
            'show_link'     => 'El teu proveïdor pot veure la transacció des d\'aquest enllaç',
            'copy_link'     => 'Copia l\'enllaç i comparteix-lo amb el teu proveïdor.',
        ],
    ],

    'sticky' => [
        'description'       => 'Previsualització de la versió web de la teva factura que veurà el teu client.',
    ],

    'messages' => [
        'update_document_transaction' => 'Pots actualitzar aquesta transacció. Ves al document i fes-ho des d\'allà.',
        'create_document_transaction_error' => 'No es pot afegir aquest enllaç al document. Fes servir {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => 'No es pot actualitzar aquest enllaç del document. Fes servir {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => 'No es pot esborrar aquest enllaç del document. Fes servir {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
