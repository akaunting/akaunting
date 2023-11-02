<?php

return [

    'edit_columns'              => 'Editer les colonnes',
    'empty_items'               => 'Vous n\'avez ajouté aucun élément.',
    'grand_total'               => 'Total global',
    'accept_payment_online'     => 'Accepter les paiements en ligne',
    'transaction'               => 'Un paiement de :amount a été effectué en utilisant :account.',
    'billing'                   => 'Facturation des achats',
    'advanced'                  => 'Avancé',

    'item_price_hidden'         => 'Cette colonne est cachée sur votre :type.',

    'actions' => [
        'cancel'                => 'Annuler',
    ],

    'invoice_detail' => [
        'marked'                => '<b> Vous </b> avez marqué cette facture comme',
        'services'              => 'Services',
        'another_item'          => 'Un autre élément',
        'another_description'   => 'et une autre description',
        'more_item'             => '+:count plus d\'élément',
    ],

    'statuses' => [
        'draft'                 => 'Brouillon',
        'sent'                  => 'Envoyé',
        'expired'               => 'Expiré',
        'viewed'                => 'Vu',
        'approved'              => 'Approuvé',
        'received'              => 'Reçu',
        'refused'               => 'Refusé',
        'restored'              => 'Restauré',
        'reversed'              => 'Inversé',
        'partial'               => 'Partiel',
        'paid'                  => 'Payé',
        'pending'               => 'En attente',
        'invoiced'              => 'Facturé',
        'overdue'               => 'En retard',
        'unpaid'                => 'Impayé',
        'cancelled'             => 'Annulé',
        'voided'                => 'Annulé',
        'completed'             => 'Terminé',
        'shipped'               => 'Envoyé',
        'refunded'              => 'Remboursé',
        'failed'                => 'Echoué',
        'denied'                => 'Refusé',
        'processed'             => 'Traité',
        'open'                  => 'Ouvert',
        'closed'                => 'Fermé',
        'billed'                => 'Facturé',
        'delivered'             => 'Livré',
        'returned'              => 'Retourné',
        'drawn'                 => 'Dessiné',
        'not_billed'            => 'Non facturé',
        'issued'                => 'Résolu',
        'not_invoiced'          => 'Non facturé',
        'confirmed'             => 'Confirmé',
        'not_confirmed'         => 'Non confirmé',
        'active'                => 'Actif',
        'ended'                 => 'Terminé',
    ],

    'form_description' => [
        'companies'             => 'Changez l\'adresse, le logo et d\'autres informations pour votre entreprise.',
        'billing'               => 'Les détails de facturation apparaissent dans votre document.',
        'advanced'              => 'Sélectionnez la catégorie, ajoutez ou modifiez le pied de page et ajoutez des pièces jointes à votre :type.',
        'attachment'            => 'Télécharger les fichiers attachés à ce :type',
    ],

    'slider' => [
        'create'            => ':user a créé cette :type le :date',
        'create_recurring'  => ':user a créé ce modèle récurrent le :date',
        'send'              => ':user a envoyé cette :type le :date',
        'schedule'          => 'Répéter chaque :interval :frequency depuis :date',
        'children'          => ':count :type ont été créés automatiquement',
        'cancel'            => ':user a annulé cette :type le :date',
    ],

    'messages' => [
        'email_sent'            => ':type L\'email vous a été envoyé.',
        'restored'              => ':type a été restauré !',
        'marked_as'             => ':type marqué comme :status!',
        'marked_sent'           => ':type marqué comme envoyé!',
        'marked_paid'           => ':type marqué comme payé !',
        'marked_viewed'         => ':type marqué comme vu !',
        'marked_cancelled'      => ':type marqué comme annulé !',
        'marked_received'       => ':type marqué comme reçu!',
    ],

    'recurring' => [
        'auto_generated'        => 'Généré automatiquement',

        'tooltip' => [
            'document_date'     => 'La date de :type sera automatiquement assignée en fonction du planning et de la fréquence :type.',
            'document_number'   => 'Le numéro :type sera automatiquement assigné lorsque chaque :type récurrent sera généré.',
        ],
    ],

    'empty_attachments'         => 'Il n\'y a aucun fichier attaché à ce :type.',
];
