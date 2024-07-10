<?php

return [

    'company' => [
        'description'                   => 'Changer le nom de la société, l\'email, l\'adresse, le numéro SIRET, etc',
        'search_keywords'               => 'société, nom, courriel, téléphone, adresse, pays, numéro SIRET, logo, ville, état, province, code postal',
        'name'                          => 'Nom',
        'email'                         => 'Email',
        'phone'                         => 'Téléphone',
        'address'                       => 'Adresse',
        'edit_your_business_address'    => 'Modifier votre adresse professionnelle',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Ces informations sont visibles dans les enregistrements que vous créez.',
            'address'                   => 'L\'adresse sera utilisée dans les factures émises et reçues et dans les autres documents que vous émettez.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Définir l\'année fiscale, le fuseau horaire, le format de la date et autres paramètres locaux',
        'search_keywords'               => 'financier, année, début, désigne, heure, fuseau horaire, date, format, séparateur, réduction, pourcent',
        'financial_start'               => 'Date de début d\'exercice financier',
        'timezone'                      => 'Fuseau horaire',
        'financial_denote' => [
            'title'                     => 'Année financière Indiquée',
            'begins'                    => 'Par l\'année où il commence',
            'ends'                      => 'Par l\'année où il se termine',
        ],
        'preferred_date'                => 'Date préférée',
        'date' => [
            'format'                    => 'Format de date',
            'separator'                 => 'Séparateur de date',
            'dash'                      => 'Tiret (-)',
            'dot'                       => 'Point (.)',
            'comma'                     => 'Virgule (,)',
            'slash'                     => 'Slash (/)',
            'space'                     => 'Espace ( )',
        ],
        'percent' => [
            'title'                     => 'Position du signe "pourcentage" (%)',
            'before'                    => 'Avant le nombre',
            'after'                     => 'Après le nombre',
        ],
        'discount_location' => [
            'name'                      => 'Emplacement de remise',
            'item'                      => 'A la ligne',
            'total'                     => 'Au total',
            'both'                      => 'Ligne et total',
        ],

        'form_description' => [
            'fiscal'                    => 'Définissez la période de l\'année financière utilisée par votre entreprise pour l\'imposition et la déclaration.',
            'date'                      => 'Sélectionnez le format de date que vous souhaitez voir partout dans l\'interface.',
            'other'                     => 'Sélectionnez le point où le signe en pourcentage est affiché pour les taxes. Vous pouvez activer les remises sur les articles en ligne et au total pour les factures reçues et émises.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personnaliser le préfixe de la facture, le numéro, les conditions, le pied de page, etc',
        'search_keywords'               => 'personnaliser, facture, numéro, préfixe, chiffre, suivant, logo, nom, prix, quantité, modèle, titre, sous-en-tête, pied de page, note, masquer, couleurs, paiement, conditions, colonne',
        'prefix'                        => 'Préfixe de numérotation',
        'digit'                         => 'Nombre de chiffres',
        'next'                          => 'Numéro suivant',
        'logo'                          => 'Logo',
        'custom'                        => 'Personnalisé',
        'item_name'                     => 'Nom de l\'article',
        'item'                          => 'Articles',
        'product'                       => 'Produits',
        'service'                       => 'Services',
        'price_name'                    => 'Nom du prix',
        'price'                         => 'Prix',
        'rate'                          => 'Taux',
        'quantity_name'                 => 'Nom de la quantité',
        'quantity'                      => 'Quantité',
        'payment_terms'                 => 'Conditions de paiement',
        'title'                         => 'Titre',
        'subheading'                    => 'Sous-titre',
        'due_receipt'                   => 'Échéance après réception',
        'due_days'                      => 'Échéance dans :days jours',
        'choose_template'               => 'Choisir un modèle de facture',
        'default'                       => 'Défaut',
        'classic'                       => 'Classique',
        'modern'                        => 'Moderne',
        'hide' => [
            'item_name'                 => 'Cacher le nom de l\'article',
            'item_description'          => 'Cacher la description de l\'article',
            'quantity'                  => 'Cacher la quantité',
            'price'                     => 'Cacher le prix',
            'amount'                    => 'Cacher le montant',
        ],
        'column'                        => 'Colonne|Colonnes',

        'form_description' => [
            'general'                   => 'Définissez les paramètres par défaut pour la mise en forme de vos numéros de facture et de vos conditions de paiement.',
            'template'                  => 'Sélectionnez l\'un des modèles ci-dessous pour vos factures.',
            'default'                   => 'La sélection des valeurs par défaut pour les factures qui préremplira les titres, sous-titres, notes et pieds de page. Vous n\'avez donc pas besoin de modifier les factures à chaque fois pour avoir un look plus professionnel.',
            'column'                    => 'Personnalisez la façon dont les colonnes de la facture sont nommées. Si vous souhaitez masquer les descriptions et les montants des articles en ligne, vous pouvez le modifier ici.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Choisir le modèle de transfert',
        'second'                        => 'Second',
        'third'                         => 'Troisième',
    ],

    'default' => [
        'description'                   => 'Compte par défaut, devise, langue de votre entreprise',
        'search_keywords'               => 'compte, devise, langue, taxe, paiement, méthode, pagination',
        'list_limit'                    => 'Résultats par page',
        'use_gravatar'                  => 'Utiliser Gravatar',
        'income_category'               => 'Catégorie de factures',
        'expense_category'              => 'Catégorie de dépenses',

        'form_description' => [
            'general'                   => 'Sélectionnez le compte par défaut, la taxe et la méthode de paiement pour créer des enregistrements rapidement. Le tableau de bord et les rapports sont affichés dans la devise par défaut.',
            'category'                  => 'Sélectionnez les catégories par défaut pour accélérer la création de l\'enregistrement.',
            'other'                     => 'Personnalisez les paramètres par défaut de la langue de l\'entreprise et le fonctionnement de la pagination. ',
        ],
    ],

    'email' => [
        'description'                   => 'Modifier le protocole d\'envoi et les modèles d\'e-mail',
        'search_keywords'               => 'email, envoyer, protocole, smtp, hôte, mot de passe',
        'protocol'                      => 'Protocole',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Hôte SMTP',
            'port'                      => 'Port SMTP',
            'username'                  => 'Utilisateur SMTP',
            'password'                  => 'Mot de passe SMTP',
            'encryption'                => 'Sécurité SMTP',
            'none'                      => 'Aucun',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Chemin d\'accès de sendmail',
        'log'                           => 'Journal des Emails',
        'email_service'                 => 'Service de messagerie',
        'email_templates'               => 'Modèles d\'e-mail',

        'form_description' => [
            'general'                   => 'Envoyez des e-mails réguliers à votre équipe et vos contacts. Vous pouvez définir les paramètres du protocole et du SMTP.',
        ],

        'templates' => [
            'description'               => 'Modifier les modèles d\'e-mail',
            'search_keywords'           => 'e-mail, modèle, sujet, corps, balise',
            'subject'                   => 'Sujet',
            'body'                      => 'Corps',
            'tags'                      => '<strong>Mots-clés disponibles :</strong> :tag_list',
            'invoice_new_customer'      => 'Modèle de nouvelle facture (envoyé au client)',
            'invoice_remind_customer'   => 'Modèle de rappel de facture (envoyé au client)',
            'invoice_remind_admin'      => 'Modèle de rappel de facture (envoyé à l\'administrateur)',
            'invoice_recur_customer'    => 'Modèle de facture récurrente (envoyé au client)',
            'invoice_recur_admin'       => 'Modèle de facture récurrente (envoyé à l\'administrateur)',
            'invoice_view_admin'        => 'Modèle de vue de la facture (envoyé à l\'administrateur)',
            'invoice_payment_customer'  => 'Modèle de paiement reçu (envoyé au client)',
            'invoice_payment_admin'     => 'Modèle de paiement reçu (envoyé à l\'administrateur)',
            'bill_remind_admin'         => 'Modèle de rappel de facture reçue (envoyé à l\'administrateur)',
            'bill_recur_admin'          => 'Modèle de facture reçue récurrente (envoyé à l\'administrateur)',
            'payment_received_customer' => 'Modèle de reçu de paiement (envoyé au client)',
            'payment_made_vendor'       => 'Modèle de paiement effectué (envoyé au fournisseur)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Planification',
        'description'                   => 'Rappels et commandes automatiques pour les récurrents',
        'search_keywords'               => 'automatique, rappel, récurrent, cron, commande',
        'send_invoice'                  => 'Envoyer un rappel de facture',
        'invoice_days'                  => 'Envoyer après les jours d\'échéance',
        'send_bill'                     => 'Envoyer rappel de facture à régler',
        'bill_days'                     => 'Envoyer avant les jours d\'échéance',
        'cron_command'                  => 'Commande Cron',
        'command'                       => 'Commande',
        'schedule_time'                 => 'Heure de fonctionnement',

        'form_description' => [
            'invoice'                   => 'Activez ou désactivez, et définissez des rappels pour vos factures quand elles sont en retard.',
            'bill'                      => 'Activez, désactivez et définissez les rappels de vos factures à régler avant qu\'elles ne soient en retard de paiement.',
            'cron'                      => 'Copiez la commande cron que votre serveur devrait exécuter. Définissez le temps pour déclencher l\'événement.',
        ]
    ],

    'categories' => [
        'description'                   => 'Catégories illimitées pour les revenus, charges et articles',
        'search_keywords'               => 'catégorie, revenu, dépense, article',
    ],

    'currencies' => [
        'description'                   => 'Créez et gérez les devises et définissez leurs taux',
        'search_keywords'               => 'par défaut, devise, devises, code, débit, symbole, précision, position, décimal, milliers, marque, séparateur',
    ],

    'taxes' => [
        'description'                   => 'Taux d\'imposition fixes, normaux, inclusifs et composés',
        'search_keywords'               => 'taxe, taux, type, fixe, inclusif, composé, retenue',
    ],

];
