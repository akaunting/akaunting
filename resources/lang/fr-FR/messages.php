<?php

return [

    'success' => [
        'added'             => ':type ajouté !',
        'updated'           => ':type mise à jour !',
        'deleted'           => ':type supprimé !',
        'duplicated'        => ':type dupliqué !',
        'imported'          => ':type importé !',
        'exported'          => ':type exporté !',
        'enabled'           => ':type activé !',
        'disabled'          => ':type désactivé !',
    ],

    'error' => [
        'over_payment'      => 'Erreur: Le paiement n\'a pas été enregistré! Le total entré dépasse le total :amount',
        'not_user_company'  => 'Erreur : Vous n’êtes pas autorisé à gérer cette société !',
        'customer'          => 'Erreur : Utilisateur non créé ! :name utilise déjà cette adresse email.',
        'no_file'           => 'Erreur : Aucun fichier sélectionné !',
        'last_category'     => 'Erreur : impossible de supprimer la dernière catégorie de type :type !',
        'change_type'       => 'Erreur: Impossible de changer le type car il est lié à :text !',
        'invalid_apikey'    => 'Erreur: La clé API saisie n\'est pas valide !',
        'import_column'     => 'Erreur : :message Nom de la feuille : :sheet. Numéro de ligne : :line.',
        'import_sheet'      => 'Erreur : Le nom de la feuille n\'est pas valide. Veuillez télécharger le modèle de fichier.',
    ],

    'warning' => [
        'deleted'           => 'Avertissement : Vous n’êtes pas autorisé à supprimer <b>:name</b> parce qu’il est associé à :texte.',
        'disabled'          => 'Avertissement : Vous n’êtes pas autorisé à désactiver <b>:name</b> parce qu’il est associé à :texte.',
        'reconciled_tran'   => 'AVERTISSEMENT : Vous n\'êtes pas autorisé à modifier/supprimer une transaction car elle est réconciliée !',
        'reconciled_doc'    => 'AVERTISSEMENT : Vous n\'êtes pas autorisé à changer/supprimer :type car il a réconcilié les transactions !',
        'disable_code'      => 'Attention : vous n’êtes pas autorisé à désactiver ou modifier la monnaie de <b>:name</b> car elle a un lien avec :text.',
        'payment_cancel'    => 'AVERTISSEMENT : Vous avez annulé votre paiement récent de :method !',
    ],

];
