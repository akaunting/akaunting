<?php

return [

    'bill_number'           => 'Numéro de facture',
    'bill_date'             => 'Date de facture',
    'bill_amount'           => 'Montant de la facture',
    'total_price'           => 'Prix total',
    'due_date'              => 'Date d\'échéance',
    'order_number'          => 'Numéro de commande',
    'bill_from'             => 'Fournisseur',

    'quantity'              => 'Quantité',
    'price'                 => 'Prix',
    'sub_total'             => 'Sous-total',
    'discount'              => 'Remise',
    'item_discount'         => 'Remise sur la ligne',
    'tax_total'             => 'Taxe totale',
    'total'                 => 'Total',

    'item_name'             => 'Nom de l\'article|Noms des articles',
    'recurring_bills'       => 'Facture récurrente|Factures récurrentes',

    'show_discount'         => ':discount % de remise',
    'add_discount'          => 'Ajouter une remise',
    'discount_desc'         => 'du sous-total',

    'payment_made'          => 'Paiement effectué',
    'payment_due'           => 'Paiement dû',
    'amount_due'            => 'Montant dû',
    'paid'                  => 'Payé',
    'histories'             => 'Historiques',
    'payments'              => 'Paiements',
    'add_payment'           => 'Ajouter un paiement',
    'mark_paid'             => 'Marquer comme payée',
    'mark_received'         => 'Marquer comme reçu',
    'mark_cancelled'        => 'Marquer comme annulée',
    'download_pdf'          => 'Télécharger en PDF',
    'send_mail'             => 'Envoyer un e-mail',
    'create_bill'           => 'Créer une facture d\'achat',
    'receive_bill'          => 'Recevoir une facture d\'achat',
    'make_payment'          => 'Faire un paiement',

    'form_description' => [
        'billing'           => 'Les détails de facturation apparaissent dans votre facture d\'achat. La date de facturation est utilisée dans le tableau de bord et les rapports. Sélectionnez la date à laquelle vous comptez payer comme date d\'échéance.',
    ],

    'messages' => [
        'draft'             => 'Ceci est une facture d\'achat <b>BROUILLON</b> et sera comptabilisée dans les graphiques une fois reçue.',

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
