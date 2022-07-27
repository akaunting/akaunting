<?php

return [

    'invoice_number'        => 'Numéro de facture',
    'invoice_date'          => 'Date de facturation',
    'invoice_amount'        => 'Montant de la facture',
    'total_price'           => 'Prix total',
    'due_date'              => 'Date d\'échéance',
    'order_number'          => 'Numéro de commande',
    'bill_to'               => 'Facture de',
    'cancel_date'           => 'Date d\'annulation',

    'quantity'              => 'Quantité',
    'price'                 => 'Prix',
    'sub_total'             => 'Sous-total',
    'discount'              => 'Remise',
    'item_discount'         => 'Remise sur la ligne',
    'tax_total'             => 'Taxe totale',
    'total'                 => 'Total',

    'item_name'             => 'Nom de marchandise|Noms des marchandises',
    'recurring_invoices'    => 'Facture récurrente|Factures récurrentes',

    'show_discount'         => ':discount % de remise',
    'add_discount'          => 'Ajouter une remise',
    'discount_desc'         => 'du sous-total',

    'payment_due'           => 'Paiement dû',
    'paid'                  => 'Payé',
    'histories'             => 'Historiques',
    'payments'              => 'Paiements',
    'add_payment'           => 'Ajouter un paiement',
    'mark_paid'             => 'Marquer comme payée',
    'mark_sent'             => 'Marquer comme envoyée',
    'mark_viewed'           => 'Marquer comme vu',
    'mark_cancelled'        => 'Marquer comme annulé',
    'download_pdf'          => 'Télécharger en PDF',
    'send_mail'             => 'Envoyer un Email',
    'all_invoices'          => 'Connectez-vous pour voir toutes les factures',
    'create_invoice'        => 'Créer une facture',
    'send_invoice'          => 'Envoyer une facture',
    'get_paid'              => 'Être payé',
    'accept_payments'       => 'Accepter les paiements en ligne',
    'payment_received'      => 'Paiement reçu',

    'form_description' => [
        'billing'           => 'Les détails de facturation apparaissent dans votre facture. La date de facturation est utilisée dans le tableau de bord et les rapports. Sélectionnez la date à laquelle vous comptez payer comme date d\'échéance.',
    ],

    'messages' => [
        'email_required'    => 'Ce client ne possède pas d\'email !',
        'draft'             => 'Ceci est une facture <b>BROUILLON</b> et sera comptabilisé dans les graphiques après reception.',

        'status' => [
            'created'       => 'Créée le :date',
            'viewed'        => 'Vu',
            'send' => [
                'draft'     => 'Pas envoyée',
                'sent'      => 'Envoyée le :date',
            ],
            'paid' => [
                'await'     => 'En attente du paiement',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user a créé cette facture le :date',
        'create_recurring'  => ':user a créé ce modèle récurrent le :date',
        'schedule'          => 'Répéter chaque :interval :frequency depuis :date',
        'children'          => ':count factures ont été créées automatiquement',
    ],

    'share' => [
        'show_link'         => 'Votre client peut voir la facture à ce lien',
        'copy_link'         => 'Copiez le lien et partagez-le avec votre client.',
        'success_message'   => 'Lien de partage copié dans le presse-papiers !',
    ],

    'sticky' => [
        'description'       => 'Vous êtes en train de prévisualiser comment votre client va voir la version web de votre facture.',
    ],

];
