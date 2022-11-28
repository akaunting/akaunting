<?php

return [

    'profile'               => 'Profil',
    'invoices'              => 'Factures',
    'payments'              => 'Paiements',
    'payment_received'      => 'Paiement reçu, merci!',
    'create_your_invoice'   => 'Maintenant, créez votre propre facture - c\'est gratuit',
    'get_started'           => 'Commencez gratuitement',
    'billing_address'       => 'Adresse de facturation',
    'see_all_details'       => 'Voir tous les détails du compte',
    'all_payments'          => 'Connectez-vous pour voir tous les paiements',
    'received_date'         => 'Date de réception',
    'redirect_description'  => 'Vous serez redirigé vers le site web de :name pour effectuer le paiement.',

    'last_payment'          => [
        'title'             => 'Dernier paiement effectué',
        'description'       => 'Vous avez effectué ce paiement le :date',
        'not_payment'       => 'Vous n\'avez pas encore effectué de paiement.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Solde impayé',
        'description'       => 'Votre solde impayé est :',
        'not_payment'       => 'Vous n\'avez pas encore de solde impayé.',
    ],

    'latest_invoices'       => [
        'title'             => 'Dernières factures',
        'description'       => ':date - Vous avez été facturé avec le numéro de facture :invoice_number.',
        'no_data'           => 'Vous n\'avez pas encore de facture.',
    ],

    'invoice_history'       => [
        'title'             => 'Historique des factures',
        'description'       => ':date - Vous avez été facturé avec le numéro de facture :invoice_number.',
        'no_data'           => 'Vous n\'avez pas encore d\'historique de facture.',
    ],

    'payment_history'       => [
        'title'             => 'Historique de paiement',
        'description'       => ':date - Vous avez effectué un paiement de :amount.',
        'invoice_description'=> ':date - Vous avez effectué un paiement de :amount pour la facture numéro :invoice_number.',

        'no_data'           => 'Vous n\'avez pas encore d\'historique de paiement.',
    ],

    'payment_detail'        => [
        'description'       => 'Vous avez effectué un paiement de :amount le :date pour cette facture.'
    ],

];
