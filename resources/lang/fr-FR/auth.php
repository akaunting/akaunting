<?php

return [

    'auth'                  => 'Authentification',
    'profile'               => 'Profil',
    'logout'                => 'Déconnexion',
    'login'                 => 'Connexion',
    'forgot'                => 'Oublié',
    'login_to'              => 'Connectez-vous pour démarrer votre session',
    'remember_me'           => 'Se souvenir de moi',
    'forgot_password'       => 'J\'ai oublié mon mot de passe',
    'reset_password'        => 'Réinitialiser le mot de passe',
    'change_password'       => 'Changer le mot de passe',
    'enter_email'           => 'Saisissez votre adresse e-mail',
    'current_email'         => 'E-mail actuel',
    'reset'                 => 'Réinitialiser',
    'never'                 => 'jamais',
    'landing_page'          => 'Page d\'accueil',
    'personal_information'  => 'Informations personnelles',
    'register_user'         => 'Inscription de l\'utilisateur',
    'register'              => 'Inscription',

    'form_description' => [
        'personal'          => 'Le lien d\'invitation sera envoyé au nouvel utilisateur, donc assurez-vous que l\'adresse e-mail est correcte. Ils pourront entrer leur mot de passe.',
        'assign'            => 'L\'utilisateur aura accès aux entreprises sélectionnées. Vous pouvez restreindre les permissions de la page <a href=":url" class="border-b border-black">Rôles</a>.',
        'preferences'       => 'Sélectionnez la langue par défaut de l\'utilisateur. Vous pouvez également définir la page d\'accueil après que l\'utilisateur se connecte.',
    ],

    'password' => [
        'pass'              => 'Mot de passe',
        'pass_confirm'      => 'Confirmation de mot de passe',
        'current'           => 'Mot de passe actuel',
        'current_confirm'   => 'Confirmation du mot de passe actuel',
        'new'               => 'Nouveau mot de passe',
        'new_confirm'       => 'Confirmation du nouveau mot de passe',
    ],

    'error' => [
        'self_delete'       => 'Erreur : Vous ne pouvez pas vous supprimer vous-même !',
        'self_disable'      => 'Erreur : Vous ne pouvez pas vous désactiver vous-même !',
        'unassigned'        => 'Erreur: La société :company ne peut pas être désassignée ! La société :company doit être assignée à au moins un utilisateur.',
        'no_company'        => 'Erreur : Aucune entreprise associée à votre compte. Veuillez contacter votre administrateur système.',
    ],

    'login_redirect'        => 'Vérification effectuée ! Vous êtes en cours de redirection...',
    'failed'                => 'Ces identifiants ne correspondent pas à un utilisateur.',
    'throttle'              => 'Trop de tentatives de connexion. Veuillez réessayer à nouveau dans :seconds secondes.',
    'disabled'              => 'Ce compte est désactivé. Veuillez contacter l’administrateur système.',

    'notification' => [
        'message_1'         => 'Vous recevez cet email car nous avons reçu une demande de réinitialisation de mot de passe pour votre compte.',
        'message_2'         => 'Aucune action n\'est requise si vous n\'êtes pas à l\'origine de la demande de réinitialisation de mot de passe.',
        'button'            => 'Réinitialiser le mot de passe',
    ],

    'invitation' => [
        'message_1'         => 'Vous recevez cet e-mail parce que vous êtes invité à rejoindre Akaunting.',
        'message_2'         => 'Si vous ne voulez pas vous inscrire, aucune action supplémentaire n\'est requise.',
        'button'            => 'C\'est parti',
    ],

    'information' => [
        'invoice'           => 'Créez facilement des factures',
        'reports'           => 'Obtenir des rapports détaillés',
        'expense'           => 'Suivre toutes les dépenses',
        'customize'         => 'Personnaliser votre Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrateur',
            'description'   => 'Ils obtiennent un accès complet à votre Akaunting, y compris vos clients, factures, rapports, paramètres et applications.',
        ],
        'manager' => [
            'name'          => 'Gérant',
            'description'   => 'Ils ont un accès complet à votre Akaunting, mais ne peuvent pas gérer les utilisateurs et les applications.',
        ],
        'customer' => [
            'name'          => 'Client',
            'description'   => 'Ils peuvent accéder au Portail Client et payer leurs factures en ligne par le biais des moyens de paiement que vous avez mis en place.',
        ],
        'accountant' => [
            'name'          => 'Comptable',
            'description'   => 'Ils peuvent accéder à des factures, des transactions, des rapports et créer des entrées de journal.',
        ],
        'employee' => [
            'name'          => 'Employé',
            'description'   => 'Ils peuvent créer des demandes de remboursement et suivre le temps passé pour les projets assignés, mais ne peuvent voir que leurs propres informations.',
        ],
    ],

];
