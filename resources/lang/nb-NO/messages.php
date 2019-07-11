<?php

return [

    'success' => [
        'added'             => ':type lagt til.',
        'updated'           => ':type oppdatert.',
        'deleted'           => ':type slettet.',
        'duplicated'        => ':type duplisert.',
        'imported'          => ':type importert.',
        'enabled'           => ':type aktivert!',
        'disabled'          => ':type deaktivert!',
    ],

    'error' => [
        'over_payment'      => 'Feil: Betaling er ikke lagt til! Beløpet overskrider totalbeløp: :amount',
        'not_user_company'  => 'Feil: Du ikke kan administrere dette foretaket.',
        'customer'          => 'Feil: Bruker ble ikke opprettet. :name bruker allerede denne e-postadressen.',
        'no_file'           => 'Feil: Ingen fil er valgt.',
        'last_category'     => 'Feil: Kan ikke slette siste :type kategori.',
        'invalid_token'     => 'Feil: Angitt token er ugyldig.',
        'import_column'     => 'Feil: :message Arknavn: :sheet. Linjenummer: :line.',
        'import_sheet'      => 'Feil: Arknavn er ikke gyldig. Vennligst sjekk malfilen.',
    ],

    'warning' => [
        'deleted'           => 'Advarsel: Du har ikke mulighet til å slette <b>:name</b> fordi kontoen har :text relatert.',
        'disabled'          => 'Advarsel: Du kan ikke deaktivere <b>:name</b> fordi kontoen har :text relatert.',
        'disable_code'      => 'Advarsel: Du har ikke tillatelse til å deaktivere eller endre valutaen for <b>:name</b> fordi den har relatert :text.',
    ],

];
