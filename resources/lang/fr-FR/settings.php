<?php

return [

    'company' => [
        'description'       => 'Changer le nom de la société, l\'email, l\'adresse, le numéro de taxe, etc',
        'name'              => 'Nom',
        'email'             => 'Email',
        'phone'             => 'Téléphone',
        'address'           => 'Adresse',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Définir l\'année fiscale, le fuseau horaire, le format de la date et plus de locaux',
        'financial_start'   => 'Date de démarrage de la comptabilité',
        'timezone'          => 'Fuseau horaire',
        'date' => [
            'format'        => 'Format de date',
            'separator'     => 'Séparateur de date',
            'dash'          => 'Tiret (-)',
            'dot'           => 'Point (.)',
            'comma'         => 'Virgule (,)',
            'slash'         => 'Slash (/)',
            'space'         => 'Espace ( )',
        ],
        'percent' => [
            'title'         => 'Position du signe "pourcentage" (%)',
            'before'        => 'Avant le nombre',
            'after'         => 'Après le nombre',
        ],
        'discount_location' => [
            'name'          => 'Emplacement de remise',
            'item'          => 'A la ligne',
            'total'         => 'Au total',
            'both'          => 'Ligne et total',
        ],
    ],

    'invoice' => [
        'description'       => 'Personnaliser le préfixe de la facture, le numéro , les termes, le pied de page, etc',
        'prefix'            => 'Préfixe de numérotation',
        'digit'             => 'Nombre de chiffres',
        'next'              => 'Numéro suivant',
        'logo'              => 'Logo',
        'custom'            => 'Personnalisé',
        'item_name'         => 'Nom de l\'élément',
        'item'              => 'Éléments',
        'product'           => 'Produits',
        'service'           => 'Services',
        'price_name'        => 'Nom du prix',
        'price'             => 'Prix',
        'rate'              => 'Tarif',
        'quantity_name'     => 'Nom de la quantité',
        'quantity'          => 'Quantité',
        'payment_terms'     => 'Conditions de paiement',
        'title'             => 'Titre',
        'subheading'        => 'Sous-titre',
        'due_receipt'       => 'Échéance après réception',
        'due_days'          => 'Échéance dans :jours',
        'choose_template'   => 'Choisi un modèle de facture',
        'default'           => 'Défaut',
        'classic'           => 'Classique',
        'modern'            => 'Moderne',
    ],

    'default' => [
        'description'       => 'Compte par défaut, devise, langue de votre entreprise',
        'list_limit'        => 'Résultats par page',
        'use_gravatar'      => 'Utiliser Gravatar',
    ],

    'email' => [
        'description'       => 'Modifier les modèles de protocole d\'envoi et les modèles d\'e-mail',
        'protocol'          => 'Protocole',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Hôte SMTP',
            'port'          => 'Port SMTP',
            'username'      => 'Utilisateur SMTP',
            'password'      => 'Mot de passe SMTP',
            'encryption'    => 'Sécurité SMTP',
            'none'          => 'Aucun',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Chemin d’accès de sendmail',
        'log'               => 'Journal des Emails',

        'templates' => [
            'subject'                   => 'Sujet',
            'body'                      => 'Corps',
            'tags'                      => '<strong>Mots-clés disponibles :</strong> :tag_list',
            'invoice_new_customer'      => 'Nouveau modèle de facture (envoyé au client)',
            'invoice_remind_customer'   => 'Modèle de rappel de facture (envoyé au client)',
            'invoice_remind_admin'      => 'Modèle de rappel de facture (envoyé à l\'administrateur)',
            'invoice_recur_customer'    => 'Modèle de facture récurrente (envoyé au client)',
            'invoice_recur_admin'       => 'Modèle de facture récurrente (envoyé à l\'administrateur)',
            'invoice_payment_customer'  => 'Modèle de paiement reçu (envoyé au client)',
            'invoice_payment_admin'     => 'Modèle de paiement reçu (envoyé à l\'administrateur)',
            'bill_remind_admin'         => 'Modèle de rappel de facture (envoyé à l\'administrateur)',
            'bill_recur_admin'          => 'Modèle de facture récurrente (envoyé à l\'administrateur)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planification',
        'description'       => 'Rappels et commandes automatiques pour les récurrents',
        'send_invoice'      => 'Envoyer un rappel de facture',
        'invoice_days'      => 'Envoyer après les jours d\'échéance',
        'send_bill'         => 'Envoyer rappel de facture',
        'bill_days'         => 'Envoyer avant les jours d\'échéance',
        'cron_command'      => 'Commande Cron',
        'schedule_time'     => 'Heure de fonctionnement',
    ],

    'categories' => [
        'description'       => 'Catégories illimitées pour les revenus, charges et articles',
    ],

    'currencies' => [
        'description'       => 'Créez et gérez des devises et définissez leurs taux',
    ],

    'taxes' => [
        'description'       => 'Taux d\'imposition fixes, normaux, inclusifs et composés',
    ],

];
