<?php

return [

    'success' => [
        'added'             => ':type ajouté !',
        'created'            => ':type créé !',
        'updated'           => ':type mis à jour !',
        'deleted'           => ':type supprimé !',
        'duplicated'        => ':type dupliqué !',
        'imported'          => ':type importé !',
        'import_queued'     => 'L\'import de :type a été planifié ! Vous recevrez un e-mail quand il sera terminé.',
        'exported'          => ':type exporté !',
        'export_queued'     => 'L\'export de :type de la page courante a été programmé ! Vous recevrez un e-mail quand il sera prêt à être téléchargé.',
        'download_queued'   => 'Le téléchargement de :type de la page courante a été programmé ! Vous recevrez un e-mail quand il sera prêt à être téléchargé.',
        'enabled'           => ':type activé !',
        'disabled'          => ':type désactivé !',
        'connected'         => ':type connecté !',
        'invited'           => ':type invité !',
        'ended'             => ':type terminé !',

        'clear_all'         => 'Super ! Vous avez effacé tous les éléments de :type.',
    ],

    'error' => [
        'over_payment'      => 'Erreur : Ce paiement n\'a pas été ajouté ! Le montant entré dépasse le montant total :amount',
        'not_user_company'  => 'Erreur : Vous n\'êtes pas autorisé à gérer cette société !',
        'customer'          => 'Erreur : Utilisateur non créé ! :name utilise déjà cette adresse e-mail.',
        'no_file'           => 'Erreur : Aucun fichier sélectionné !',
        'last_category'     => 'Erreur : Impossible de supprimer la dernière catégorie de <b>:type</b> !',
        'transfer_category' => 'Erreur : Impossible de supprimer la catégorie de transfert <b>:type</b> !',
        'change_type'       => 'Erreur : Impossible de changer le type car il est lié à :text !',
        'invalid_apikey'    => 'Erreur : La clé API saisie n\'est pas valide !',
        'empty_apikey'      => 'Erreur : Votre clé d\'API n\'a pas été entrée ! <a href=":url" class="font-bold underline underline-offset-4">Cliquez ici</a> pour enregistrer votre clé d\'API.',
        'import_column'     => 'Erreur : :message Nom de la colonne : :column. Numéro de ligne : :line.',
        'import_sheet'      => 'Erreur : Le nom de la feuille n\'est pas valide. Veuillez vérifier le fichier d\'exemple.',
        'same_amount'       => 'Erreur : Le montant total de la ventilation doit être exactement le même que le montant total de :transaction : :amount',
        'over_match'        => 'Erreur : :type non connecté ! Le montant que vous avez entré ne peut pas dépasser le montant total du paiement : :amount',
    ],

    'warning' => [
        'deleted'           => 'Avertissement : Vous n\'êtes pas autorisé à supprimer <b>:name</b> parce qu\'il est associé à :text.',
        'disabled'          => 'Avertissement : Vous n\'êtes pas autorisé à désactiver <b>:name</b> parce qu\'il est associé à :text.',
        'reconciled_tran'   => 'Avertissement : Vous n\'êtes pas autorisé à modifier/supprimer cette transaction car elle a été rapprochée !',
        'reconciled_doc'    => 'Avertissement : Vous n\'êtes pas autorisé à changer/supprimer :type car il contient des transactions rapprochées !',
        'disable_code'      => 'Avertissement : Vous n\'êtes pas autorisé à désactiver ou modifier la devise de <b>:name</b> car elle est liée à :text.',
        'payment_cancel'    => 'Avertissement : Vous avez annulé votre paiement récent par :method !',
        'missing_transfer'  => 'Avertissement : Le transfert lié à cette transaction est manquant. Vous devriez envisager de supprimer cette transaction.',
        'connect_tax'       => 'Avertissement : Ce :type comporte un montant de taxe. Les taxes ajoutées au :type ne peuvent pas être connectées, la taxe sera donc ajoutée au total et calculée en conséquence.',
        'contact_change'    => 'Avertissement : Vous n\'êtes pas autorisé à modifier le contact sur un :type qui a déjà été envoyé, reçu ou payé !',
    ],

];
