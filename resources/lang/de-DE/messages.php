<?php

return [

    'success' => [
        'added'             => ':type hinzugefügt!',
        'updated'           => ':type aktualisiert!',
        'deleted'           => ':type gelöscht!',
        'duplicated'        => ':type dupliziert!',
        'imported'          => ':type importiert!',
    ],
    'error' => [
        'payment_add'       => 'Fehler: Sie können die Zahlung nicht hinzufügen. Überprüfen Sie die Einträge und fügen sie einen Betrag hinzu.',
        'not_user_company'  => 'Fehler: Sie haben nicht die Berechtigung um diese Firma zu verwalten!',
        'customer'          => 'Fehler: Sie können diesen Benutzer nicht erstellen! Die Email wird durch :name bereits genutzt.',
        'no_file'           => 'Fehler: Keine Datei ausgewählt!',
    ],
    'warning' => [
        'deleted'           => 'Warnung: Sie dürfen <b>:name</b> nicht löschen, da :text dazu in Bezug steht.',
        'disabled'          => 'Warnung: Sie dürfen <b>:name</b> nicht deaktivieren, da :text dazu in Bezug steht.',
    ],

];
