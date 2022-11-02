<?php

return [

    'payment_received'      => 'Paiement reçu',
    'payment_made'          => 'Paiement effectué',
    'paid_by'               => 'Payé par',
    'paid_to'               => 'Payé à',
    'related_invoice'       => 'Facture associée',
    'related_bill'          => 'Facture connexe',
    'recurring_income'      => 'Revenus récurrents',
    'recurring_expense'     => 'Dépense récurrente',

    'form_description' => [
        'general'           => 'Ici, vous pouvez entrer les informations générales du journal manuel tels que la date, le numéro, la devise, la description, etc.',
        'assign_income'     => 'Sélectionnez une catégorie et un client pour rendre vos rapports plus détaillés.',
        'assign_expense'    => 'Sélectionnez une catégorie et un fournisseur pour rendre vos rapports plus détaillés.',
        'other'             => 'Entrez un numéro et une référence pour conserver la transaction liée à vos dossiers.',
    ],

    'slider' => [
        'create'            => ':user a créé cette facture le :date',
        'attachments'       => 'Télécharger les fichiers attachés à cette transaction',
        'create_recurring'  => ':user a créé ce modèle récurrent le :date',
        'schedule'          => 'Répéter chaque :interval :frequency depuis :date',
        'children'          => ':count transactions ont été créées automatiquement',
        'transfer_headline' => '<div> <span class="font-bold"> De: </span> :from_account </div> <div> <span class="font-bold"> à: </span> :to_account </div>',
        'transfer_desc'     => 'Transfert créé le :date.',
    ],

    'share' => [
        'income' => [
            'show_link'     => 'Votre client peut voir la transaction à ce lien',
            'copy_link'     => 'Copiez le lien et partagez-le avec votre client.',
        ],

        'expense' => [
            'show_link'     => 'Votre fournisseur peut voir la transaction avec ce lien',
            'copy_link'     => 'Copiez le lien et partagez-le avec votre fournisseur.',
        ],
    ],

    'sticky' => [
        'description'       => 'Vous êtes en train de prévisualiser comment votre client va voir la version web de votre paiement.',
    ],

];
