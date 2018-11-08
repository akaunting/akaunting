<?php

return [

    'success' => [
        'added'             => ':type ajouté !',
        'updated'           => ':type mise à jour !',
        'deleted'           => ':type supprimé !',
        'duplicated'        => ':type dupliqué !',
        'imported'          => ':type importé !',
        'enabled'           => ':type activé !',
        'disabled'          => ':type désactivé !',
    ],
    'error' => [
        'over_payment'      => 'Erreur: Le paiement n\'a pas été enregistré! Le total entré dépasse le total :amount',
        'not_user_company'  => 'Erreur : Vous n’êtes pas autorisé à gérer cette société !',
        'customer'          => 'Erreur : Utilisateur non créé ! :name utilise déjà cette adresse email.',
        'no_file'           => 'Erreur : Aucun fichier sélectionné !',
        'last_category'     => 'Erreur : impossible de supprimer la dernière catégorie de type :type !',
        'invalid_token'     => 'Erreur : le token est invalide !',
        'import_column'     => 'Erreur : :message Nom de la feuille : :sheet. Numéro de ligne : :line.',
        'import_sheet'      => 'Erreur : Le nom de la feuille n\'est pas valide. Veuillez télécharger le modèle de fichier.',
    ],
    'warning' => [
        'deleted'           => 'Avertissement : Vous n’êtes pas autorisé à supprimer <b>:name</b> parce qu’il est associé à :texte.',
        'disabled'          => 'Avertissement : Vous n’êtes pas autorisé à désactiver <b>:name</b> parce qu’il est associé à :texte.',
    ],

];
