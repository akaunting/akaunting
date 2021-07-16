<?php

return [

    'success' => [
        'added'             => ':type lagt til.',
        'updated'           => ':type oppdatert.',
        'deleted'           => ':type slettet.',
        'duplicated'        => ':type duplisert.',
        'imported'          => ':type importert.',
        'import_queued'     => ':type import er planlagt! Du vil motta en e-post når det er ferdig.',
        'exported'          => ':type eksportert!',
        'export_queued'     => ':type eksport av gjeldende side er planlagt! Du vil motta en e-post når den er klar til å laste ned.',
        'enabled'           => ':type aktivert!',
        'disabled'          => ':type deaktivert!',

        'clear_all'         => 'Utmerket! Du har slettet all din :type.',
    ],

    'error' => [
        'over_payment'      => 'Feil: Betaling er ikke lagt til! Beløpet overskrider totalbeløp: :amount',
        'not_user_company'  => 'Feil: Du ikke kan administrere dette foretaket.',
        'customer'          => 'Feil: Bruker ble ikke opprettet. :name bruker allerede denne e-postadressen.',
        'no_file'           => 'Feil: Ingen fil er valgt.',
        'last_category'     => 'Feil: Kan ikke slette siste :type kategori.',
        'change_type'       => 'Feil: Du kan ikke endre denne typen fordi den har relatert :text!',
        'invalid_apikey'    => 'Feil: API nøkkelen du har skrevet er feil!',
        'import_column'     => 'Feil: :message kolonnenavn: :column. Linjenummer: :line.',
        'import_sheet'      => 'Feil: Arknavn er ikke gyldig. Vennligst sjekk malfilen.',
    ],

    'warning' => [
        'deleted'           => 'Advarsel: Du har ikke mulighet til å slette <b>:name</b> fordi kontoen har :text relatert.',
        'disabled'          => 'Advarsel: Du kan ikke deaktivere <b>:name</b> fordi kontoen har :text relatert.',
        'reconciled_tran'   => 'Advarsel: Du har ikke lov til å endre / slette transaksjonen fordi den er avstemt!',
        'reconciled_doc'    => 'Advarsel: Du har ikke lov til å endre / slette :type fordi den har avstemte transaksjoner!',
        'disable_code'      => 'Advarsel: Du har ikke tillatelse til å deaktivere eller endre valutaen for <b>:name</b> fordi den har relatert :text.',
        'payment_cancel'    => 'Advarsel: Du har avbrutt din siste :method betaling!',
    ],

];
