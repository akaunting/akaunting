<?php

return [

    'success' => [
        'added'             => ':type hinzugefügt!',
        'created'            => ':type wurde erstellt!',
        'updated'           => ':type aktualisiert!',
        'deleted'           => ':type gelöscht!',
        'duplicated'        => ':type dupliziert!',
        'imported'          => ':type importiert!',
        'import_queued'     => ':type-Import ist geplant! Sie erhalten eine E-Mail, sobald dieser fertiggestellt ist.',
        'exported'          => ':type exportiert!',
        'export_queued'     => ':type-Export ist geplant! Sie erhalten eine E-Mail, sobald dieser fertiggestellt ist.',
        'download_queued'   => ':type-Download der aktuellen Seite wurde geplant! Sie erhalten eine E-Mail, sobald dieser zum Download bereit ist.',
        'enabled'           => ':type aktiviert!',
        'disabled'          => ':type deaktiviert!',
        'connected'         => ':type verbunden!',
        'invited'           => ':type eingeladen!',
        'ended'             => ':type beendet!',

        'clear_all'         => 'Hervorragend! Sie haben alle Ihre :type gelöscht.',
    ],

    'error' => [
        'over_payment'      => 'Fehler: Zahlung wurde nicht gebucht! Der eingegebene Betrag überschreitet die Gesamtsumme: :amount',
        'not_user_company'  => 'Fehler: Sie haben nicht die Berechtigung, dieses Unternehmen zu verwalten!',
        'customer'          => 'Fehler: Benutzer wurde nicht angelegt! :name verwendet bereits diese E-Mail-Adresse.',
        'no_file'           => 'Fehler: Keine Datei ausgewählt!',
        'last_category'     => 'Fehler: Die letzte <b>:type</b>-Kategorie kann nicht gelöscht werden!',
        'transfer_category' => 'Fehler: Die <b>:type</b>-Umbuchungskategorie kann nicht gelöscht werden!',
        'change_type'       => 'Fehler: Der Typ kann nicht geändert werden, da :text zugeordnet ist!',
        'invalid_apikey'    => 'Fehler: Der eingegebene API-Schlüssel ist ungültig!',
        'empty_apikey'      => 'Fehler: Sie haben Ihren API-Schlüssel nicht hinterlegt! <a href=":url" class="font-bold underline underline-offset-4">Klicken Sie hier</a>, um den API-Schlüssel einzugeben.',
        'import_column'     => 'Fehler: :message. Spaltenname: :column. Zeilennummer: :line.',
        'import_sheet'      => 'Fehler: Der Name des Blattes ist nicht gültig. Bitte überprüfen Sie die Beispieldatei.',
        'same_amount'       => 'Fehler: Der Gesamtbetrag der Aufteilung muss genau der :transaction-Gesamtsumme entsprechen: :amount',
        'over_match'        => 'Fehler: :type nicht verbunden! Der eingegebene Betrag darf die Zahlungssumme nicht überschreiten: :amount',
    ],

    'warning' => [
        'deleted'           => 'Warnung: Sie dürfen <b>:name</b> nicht löschen, da :text zugeordnet ist.',
        'disabled'          => 'Warnung: Sie dürfen <b>:name</b> nicht deaktivieren, da :text zugeordnet ist.',
        'reconciled_tran'   => 'Warnung: Sie dürfen die Transaktion nicht ändern/löschen, da sie abgeglichen ist!',
        'reconciled_doc'    => 'Warnung: Sie dürfen :type nicht ändern/löschen, da sie abgeglichene Transaktionen enthält!',
        'disable_code'      => 'Warnung: Sie dürfen die Währung von <b>:name</b> nicht deaktivieren oder ändern, da :text zugeordnet ist.',
        'payment_cancel'    => 'Warnung: Sie haben Ihre letzte :method-Zahlung abgebrochen!',
        'missing_transfer'  => 'Warnung: Die mit dieser Transaktion verknüpfte Umbuchung fehlt. Sie sollten erwägen, diese Transaktion zu löschen.',
        'connect_tax'       => 'Warnung: Diese :type hat einen Steuerbetrag. Steuern, die zur :type hinzugefügt wurden, können nicht verbunden werden, daher wird die Steuer zur Gesamtsumme hinzugefügt und entsprechend berechnet.',
        'contact_change'    => 'Warnung: Sie dürfen den Kontakt nicht ändern, wenn die :type bereits gesendet, empfangen oder bezahlt wurde!',
    ],

];
