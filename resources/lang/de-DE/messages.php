<?php

return [

    'success' => [
        'added'             => ':type hinzugefügt!',
        'updated'           => ':type aktualisiert!',
        'deleted'           => ':type gelöscht!',
        'duplicated'        => ':type dupliziert!',
        'imported'          => ':type imported!',
    ],
    'error' => [
        'not_user_company'  => 'Fehler: Sie haben nicht die Berechtigung um diese Firma zu verwalten!',
        'customer'          => 'Fehler: Sie können diesen Benutzer nicht erstellen! :name benutzt diese Email bereits.',
        'no_file'           => 'Error: No file selected!',
    ],
    'warning' => [
        'deleted'           => 'Warnung: Sie dürfen <b>:name</b> nicht löschen, da :text dazu in Bezug steht.',
        'disabled'          => 'Warnung: Sie dürfen <b>:name</b> nicht deaktivieren, da :text dazu in Bezug steht.',
    ],

];
