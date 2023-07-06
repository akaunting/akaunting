<?php

return [

    'whoops'              => 'Oups !',
    'hello'               => 'Bonjour !',
    'salutation'          => 'Cordialement,<br>:company_name',
    'subcopy'             => 'Si vous n\'arrivez pas à cliquer sur le bouton ":text", veuillez copier et coller l\'URL ci-dessous dans votre navigateur web : [:url](:url)',
    'mark_read'           => 'Marquer comme lu',
    'mark_read_all'       => 'Tout marquer comme lu',
    'empty'               => 'Woohoo, aucune notification!',
    'new_apps'            => ':app est disponible. <a href=":url">Vérifiez maintenant</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Échec de la mise à jour sur :domain',
            'description'   => 'La mise à jour de :alias de :current_version à :new_version a échoué à l\'étape <strong>:step</strong> avec le message suivant : :error_message',

        ],

        'slack' => [

            'description'   => 'La mise à jour a échoué sur :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importation terminée',
            'description'   => 'L\'importation est terminée et les enregistrements sont disponibles dans votre panel.',

        ],

        'failed' => [

            'title'         => 'Importation échouée',
            'description'   => 'Impossible d\'importer le fichier en raison des problèmes suivants :',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'L\'export est prêt',
            'description'   => 'Le fichier d\'exportation est prêt à être téléchargé à partir du lien suivant :',

        ],

        'failed' => [

            'title'         => 'L\'exportation a échoué',
            'description'   => 'Impossible de créer le fichier d\'export en raison du problème suivant :',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Courriel :type invalide',
            'description'   => 'L\'adresse email :email a été signalée comme invalide et le compte de la personne a été désactivé. Veuillez vérifier le message d\'erreur suivant et corriger l\'adresse e-mail :',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'L\'export est prêt',
            'description'   => 'Votre fichier d\'exportation <strong>:type</strong> est prêt à être<a href=":url" target="_blank"><strong>téléchargé</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'L\'exportation a échoué',
            'description'   => 'Impossible de créer le fichier d\'export en raison de plusieurs problèmes. Consultez votre e-mail pour plus de détails.',

        ],

        'import_completed' => [

            'title'         => 'Importation terminée',
            'description'   => 'Vos données <strong>:type</strong> lignées <strong>:count</strong> ont été importées avec succès.',

        ],

        'import_failed' => [

            'title'         => 'Échec de l\'importation',
            'description'   => 'Impossible d\'importer le fichier en raison de plusieurs problèmes. Consultez votre e-mail pour plus de détails.',

        ],

        'new_apps' => [

            'title'         => 'Nouvelle application',
            'description'   => 'L\'application <strong>:name</strong> est sortie. Vous pouvez <a href=":url">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nouvelle facture',
            'description'   => ' La facture<strong>:invoice_number</strong> est créée. Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails et procéder au paiement.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Facture en retard',
            'description'   => 'Lla facture <strong>:invoice_number</strong> était due au <strong>:invoice_due_date</strong>. Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails et procéder au paiement.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Facture en retard',
            'description'   => 'La facture<strong>:invoice_number</strong> était due au <strong>:invoice_due_date</strong>. Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nouvelle facture récurrente',
            'description'   => 'La facture <strong>:invoice_number</strong> est créée en fonction de votre cycle récurrent. Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails et procéder au paiement.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nouvelle facture récurrente',
            'description'   => 'La facture <strong>:invoice_number</strong> est créée pour <strong>:customer_name</strong> sur la base du cycle récurrent. Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Facture consultée',
            'description'   => '<strong>:customer_name</strong> a consulté la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Paiement reçu',
            'description'   => 'Merci pour le paiement de la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Paiement reçu',
            'description'   => 'Merci pour le paiement de la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_portal_link">cliquer ici</a> pour voir les détails.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Paiement reçu',
            'description'   => ':customer_name a enregistré le paiement pour la facture <strong>:invoice_number</strong> . Vous pouvez <a href=":invoice_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Facture en retard',
            'description'   => 'La facture <strong>:bill_number</strong> était due au <strong>:bill_due_date</strong>. Vous pouvez <a href=":bill_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nouvelle facture récurrente',
            'description'   => 'La facture <strong>:bill_number</strong> est créée par <strong>:vendor_name</strong> sur la base du plan de facturation planifié. Vous pouvez <a href=":bill_admin_link">cliquer ici</a> pour voir les détails.',

        ],

        'invalid_email' => [

            'title'         => 'Courriel :type invalide',
            'description'   => 'L\'adresse e-mail <strong>:email</strong> a été signalée comme invalide et le compte de la personne a été désactivé. Veuillez vérifier et corriger l\'adresse e-mail.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type est en lecture de cette notification !',
        'mark_read_all'         => ':type est en train de lire toutes les notifications !',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configuration des icônes dans Firefox',
            'description'  => '<span class="font-medium">Si vos icônes n\'apparaissent pas, merci d\'activer;</span> <br /> <span class="font-medium">Autoriser les pages web à utiliser leurs propres polices au lieu de celles choisies ci-dessus</span> <br /><br /> <span class="font-bold">dans Paramètres > Général > Polices > Avancé </span>',

        ],

    ],

];
