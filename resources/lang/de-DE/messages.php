<?php

return [

    'success' => [
        'added'             => ':type hinzugefügt!',
        'updated'           => ':type aktualisiert!',
        'deleted'           => ':type gelöscht!',
        'duplicated'        => ':type dupliziert!',
        'imported'          => ':type importiert!',
        'enabled'           => ':type aktiviert!',
        'disabled'          => ':type deaktiviert!',
    ],

    'error' => [
        'over_payment'      => 'Fehler: Zahlung wurde nicht gebucht! Der eingegebenen Betrag überschreitet die Gesamtsumme: :amount',
        'not_user_company'  => 'Fehler: Sie haben nicht die Berechtigung um diese Firma zu verwalten!',
        'customer'          => 'Fehler: User wurde nicht angelegt! :name benutzt schon diese Email-Adresse.',
        'no_file'           => 'Fehler: Keine Datei ausgewählt!',
        'last_category'     => 'Fehler: Kann die letzte Kategorie :type nicht löschen!',
        'invalid_token'     => 'Fehler: Der eingegebene Token ist ungültig!',
        'import_column'     => 'Fehler: :message. Name des Blattes: :sheet. Zeilennummer: :line.',
        'import_sheet'      => 'Fehler: Name des Blattes ist nicht gültig. Bitte die Beispieldatei überprüfen.',
    ],

    'warning' => [
        'deleted'           => 'Warnung: Sie dürfen <b>:name</b> nicht löschen, da :text dazu in Bezug steht.',
        'disabled'          => 'Warnung: Sie dürfen <b>:name</b> nicht deaktivieren, da :text dazu in Bezug steht.',
        'disable_code'      => 'Warnung: Sie dürfen die Währung von <b>:name</b> nicht deaktivieren oder verändern, da :text dazu in Bezug steht.',
    ],

];
