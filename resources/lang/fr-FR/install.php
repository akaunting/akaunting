<?php

return [

    'next'                  => 'Suivant',
    'refresh'               => 'Actualiser',

    'steps' => [
        'requirements'      => 'Veuillez contacter votre hébergeur pour régler les problèmes !',
        'language'          => 'Etape 1/3 : Choix de la langue',
        'database'          => 'Etape 2/3 : Configuration de la base de données',
        'settings'          => 'Etape 3/3 : Société et détails de l\'administrateur',
    ],

    'language' => [
        'select'            => 'Choix de la langue',
    ],

    'requirements' => [
        'enabled'           => ':feature doit être activée !',
        'disabled'          => ':feature doit être désactivée !',
        'extension'         => 'L\'extension :extension doit être installée et chargée !',
        'directory'         => ':directory doit être accessible en écriture !',
        'executable'        => 'Le fichier exécutable PHP CLI n\'est pas défini, ou sa version n\'est pas :php_version ou supérieure! Veuillez demander à votre hébergeur de définir correctement la variable d\'environnement PHP_BINARY ou PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Nom d\'hôte',
        'username'          => 'Nom d\'utilisateur',
        'password'          => 'Mot de passe',
        'name'              => 'Base de données',
    ],

    'settings' => [
        'company_name'      => 'Nom de l\'entreprise',
        'company_email'     => 'Email de l’entreprise',
        'admin_email'       => 'E-mail de l\'administrateur',
        'admin_password'    => 'Mot de passe de l\'administrateur',
    ],

    'error' => [
        'php_version'       => 'Erreur: Demandez à votre hébergeur d\'utiliser PHP :php_version ou supérieure pour HTTP et CLI.',
        'connection'        => 'Erreur : Impossible de se connecter à la base de données ! S’il vous plaît, assurez-vous que les informations sont correctes.',
    ],

];
