<?php

return [

    'bill_number'           => 'Numéro de facture',
    'bill_date'             => 'Date de facture',
    'bill_amount'           => 'Montant de la facture',
    'total_price'           => 'Prix total',
    'due_date'              => 'Date d\'échéance',
    'order_number'          => 'Numéro de commande',
    'bill_from'             => 'Facture de',

    'quantity'              => 'Quantité',
    'price'                 => 'Prix',
    'sub_total'             => 'Sous-total',
    'discount'              => 'Remise',
    'item_discount'         => 'Remise sur la ligne',
    'tax_total'             => 'Taxe totale',
    'total'                 => 'Total',

    'item_name'             => 'Nom de marchandise|Noms des marchandises',
    'recurring_bills'       => 'Facture récurrente|Factures récurrentes',

    'show_discount'         => ':discount % de remise',
    'add_discount'          => 'Ajouter une remise',
    'discount_desc'         => 'du sous-total',

    'payment_made'          => 'Paiements effectués',
    'payment_due'           => 'Paiement dû',
    'amount_due'            => 'Montant dû',
    'paid'                  => 'Payé',
    'histories'             => 'Historiques',
    'payments'              => 'Paiements',
    'add_payment'           => 'Ajouter un paiement',
    'mark_paid'             => 'Marquer comme payée',
    'mark_received'         => 'Marqué comme reçu',
    'mark_cancelled'        => 'Marquer comme annulé',
    'download_pdf'          => 'Télécharger en PDF',
    'send_mail'             => 'Envoyer un Email',
    'create_bill'           => 'Créer une facture',
    'receive_bill'          => 'Recevoir une facture',
    'make_payment'          => 'Faire un paiement',

    'form_description' => [
        'billing'           => 'Les détails de facturation apparaissent dans votre facture. La date de facturation est utilisée dans le tableau de bord et les rapports. Sélectionnez la date à laquelle vous comptez payer comme date d\'échéance.',
    ],

    'messages' => [
        'draft'             => 'Ceci est une facture <b>BROUILLON</b> et sera comptabilisée dans les graphiques une fois reçue.',

        'status' => [
            'created'       => 'Créée le :date',
            'receive' => [
                'draft'     => 'Non reçue',
                'received'  => 'Reçue le :date',
            ],
            'paid' => [
                'await'     => 'Paiement en attente',
            ],
        ],
    ],

];
