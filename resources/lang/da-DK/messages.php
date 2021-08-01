<?php

return [

    'success' => [
        'added'             => ':type tilføjet!',
        'updated'           => ':type opdateret!',
        'deleted'           => ':type slettet!',
        'duplicated'        => ':type duplikeret!',
        'imported'          => ':type importeret!',
        'import_queued'     => ':type import er planlagt! Du vil modtage en e-mail, når den er færdig.',
        'exported'          => ':type exporteret!',
        'export_queued'     => ':type eksport er planlagt! Du vil modtage en e-mail, når den er klar til download.',
        'enabled'           => ':type aktiveret!',
        'disabled'          => ':type deaktiveret!',

        'clear_all'         => 'Fantastisk! Du har ryddet alle dine :type.',
    ],

    'error' => [
        'over_payment'      => 'Error: Betaling blev ikke tilføjet! Beløbet du har angivet overstiger: :amount',
        'not_user_company'  => 'Fejl: Du har ikke tilladelse til at administrere denne enhed!',
        'customer'          => 'Fejl: Brugeren ikke oprettet! :name bruger allerede denne E-mail.',
        'no_file'           => 'Fejl: Ingen fil valgt!',
        'last_category'     => 'Fejl: Kan ikke slette sidste :type kategori!',
        'change_type'       => 'Fejl: Kan ikke ændre type fordi den har :text relateret!',
        'invalid_apikey'    => 'Fejl: API nøglen er ikke gyldig!',
        'import_column'     => 'Error: :message arkets navn: :sheet. Linje nummer: :line.',
        'import_sheet'      => 'Error: Ark navn er ikke valid. Kontroller venligst eksempel filen.',
    ],

    'warning' => [
        'deleted'           => 'Advarsel: Du har ikke tilladelse tiil at slette <b>:name</b> fordi den er :text relateret.',
        'disabled'          => 'Advarsel: Du har ikke tilladelse tiil at deaktivere <b>:name</b> fordi den er :text relateret.',
        'reconciled_tran'   => 'Advarsel: Du kan ikke ændre eller slette denne transaktion, da den er afstemt.',
        'reconciled_doc'    => 'Advarsel: Du kan ikke ændre eller slette :type, da den er afstemt.',
        'disable_code'      => 'Advarsel: Du må ikke deaktivere eller ændre valutaen i <b>:name</b> , fordi den er :text relateret.',
        'payment_cancel'    => 'Advarsel: Du har annulleret den seneste :method betaling!',
    ],

];
