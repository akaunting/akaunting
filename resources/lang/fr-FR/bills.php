<?php

return [

    'bill_number'       => 'Numéro de facture',
    'bill_date'         => 'Date de facture',
    'total_price'       => 'Prix total',
    'due_date'          => 'Date d\'échéance',
    'order_number'      => 'Numéro de commande',
    'bill_from'         => 'Facture de',

    'quantity'          => 'Quantité',
    'price'             => 'Prix',
    'sub_total'         => 'Sous-total',
    'discount'          => 'Remise',
    'tax_total'         => 'Taxe totale',
    'total'             => 'Total',

    'item_name'         => 'Nom de marchandise|Noms des marchandises',

    'show_discount'     => ':discount % de remise',
    'add_discount'      => 'Ajouter une remise',
    'discount_desc'     => 'du sous-total',

    'payment_due'       => 'Paiement dû',
    'amount_due'        => 'Montant dû',
    'paid'              => 'Payé',
    'histories'         => 'Historiques',
    'payments'          => 'Paiements',
    'add_payment'       => 'Ajouter un paiement',
    'mark_received'     => 'Marqué comme reçu',
    'download_pdf'      => 'Télécharger en PDF',
    'send_mail'         => 'Envoyer un Email',

    'status' => [
        'draft'         => 'Brouillon',
        'received'      => 'Reçu',
        'partial'       => 'Partiel',
        'paid'          => 'Payé',
    ],

    'messages' => [
        'received'      => 'Facture marquée comme reçue avec succès !',
        'draft'          => 'Ceci est une facture <b>BROUILLON</b> et sera comptabilisée dans les graphiques une fois reçue.',

        'status' => [
            'created'   => 'Créée le :date',
            'receive'      => [
                'draft'     => 'Pas envoyée',
                'received'  => 'Reçue le :date',
            ],
            'paid'      => [
                'await'     => 'En attente du paiement',
            ],
        ],
    ],

];
