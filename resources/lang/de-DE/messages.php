<?php

return [

    'success' => [
        'added'             => ':type hinzugefügt!',
        'updated'           => ':type aktualisiert!',
        'deleted'           => ':type gelöscht!',
        'duplicated'        => ':type dupliziert!',
        'imported'          => ':type importiert!',
        'import_queued'     => ':type Import ist geplant! Sie erhalten eine E-Mail, sobald dieser fertiggestellt ist.',
        'exported'          => ':type exportiert!',
        'export_queued'     => ':type Export ist geplant! Sie erhalten eine E-Mail, sobald dieser fertiggestellt ist.',
        'enabled'           => ':type aktiviert!',
        'disabled'          => ':type deaktiviert!',

        'clear_all'         => 'Großartig! Du hast all deinen :type gelöscht.',
    ],

    'error' => [
        'over_payment'      => 'Fehler: Zahlung wurde nicht gebucht! Der eingegebenen Betrag überschreitet die Gesamtsumme: :amount',
        'not_user_company'  => 'Fehler: Sie haben nicht die Berechtigung um diese Firma zu verwalten!',
        'customer'          => 'Fehler: User wurde nicht angelegt! :name benutzt schon diese Email-Adresse.',
        'no_file'           => 'Fehler: Keine Datei ausgewählt!',
        'last_category'     => 'Fehler: Kann die letzte Kategorie :type nicht löschen!',
        'change_type'       => 'Fehler: Der Typ kann nicht geändert werden, da :text verwandt ist!',
        'invalid_apikey'    => 'Fehler: Der eingegebene API-Schlüssel ist ungültig!',
        'import_column'     => 'Fehler: :message. Name des Blattes: :sheet. Zeilennummer: :line.',
        'import_sheet'      => 'Fehler: Name des Blattes ist nicht gültig. Bitte die Beispieldatei überprüfen.',
    ],

    'warning' => [
        'deleted'           => 'Warnung: Sie dürfen <b>:name</b> nicht löschen, da :text dazu in Bezug steht.',
        'disabled'          => 'Warnung: Sie dürfen <b>:name</b> nicht deaktivieren, da :text dazu in Bezug steht.',
        'reconciled_tran'   => 'Warnung: Sie dürfen die Transaktion nicht ändern/löschen, da sie mit einem anderen Datensatz (Einnahme oder Ausgabe) verknüpft ist!',
        'reconciled_doc'    => 'Warnung: Sie dürfen :type nicht ändern/löschen, da Transaktionen abgeglichen wurden!',
        'disable_code'      => 'Warnung: Sie dürfen die Währung von <b>:name</b> nicht deaktivieren oder verändern, da :text dazu in Bezug steht.',
        'payment_cancel'    => 'Warnung: Sie haben Ihre letzte Zahlung :method abgebrochen!',
    ],

];
