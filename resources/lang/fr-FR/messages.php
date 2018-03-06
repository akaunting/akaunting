<?php

return [

    'success' => [
        'added'             => ':type ajouté !',
        'updated'           => ':type mise à jour !',
        'deleted'           => ':type supprimé !',
        'duplicated'        => ':type dupliqué !',
        'imported'          => ':type importé !',
    ],
    'error' => [
        'payment_add'       => 'Erreur : Vous ne pouvez pas ajouter ce paiement ! Vérifiez le montant.',
        'not_user_company'  => 'Erreur : Vous n’êtes pas autorisé à gérer cette société !',
        'customer'          => 'Erreur : Vous ne pouvez pas créer cet utilisateur ! :name utilise cette adresse.',
        'no_file'           => 'Erreur : Aucun fichier sélectionné !',
    ],
    'warning' => [
        'deleted'           => 'Avertissement : Vous n’êtes pas autorisé à supprimer <b>:name</b> parce qu’il est associé à :texte.',
        'disabled'          => 'Avertissement : Vous n’êtes pas autorisé à désactiver <b>:name</b> parce qu’il est associé à :texte.',
    ],

];
