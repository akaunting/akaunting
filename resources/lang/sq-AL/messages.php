<?php

return [

    'success' => [
        'added'             => ':type shtuar!',
        'updated'           => ':type përditësuar!',
        'deleted'           => ':type fshirë!',
        'duplicated'        => ':type dubluar!',
        'imported'          => ':type importuar!',
        'exported'          => ':type eksportuar!',
        'enabled'           => ':type aktivizuar!',
        'disabled'          => ':type çaktivizuar!',
    ],

    'error' => [
        'over_payment'      => 'Gabim: Pagesa nuk u shtua! Shuma që keni futur kalon totalin: amount',
        'not_user_company'  => 'Gabim: Nuk ju lejohet të menaxhoni këtë kompani!',
        'customer'          => 'Gabim: Përdoruesi nuk u krijua! :name tashmë përdor këtë adresë e-maili.',
        'no_file'           => 'Gabim: Asnjë skedar i përzgjedhur!',
        'last_category'     => 'Gabim: Nuk mund të fshihet :type kategoria e fundit!',
        'change_type'       => 'Gabim: Nuk mund të ndryshojë llojin sepse ajo ka :text lidhur!',
        'invalid_apikey'    => 'Gabim: Çelësi API i futur është i pavlefshëm!',
        'import_column'     => 'Gabim: :message Fleta name: :sheet. Rreshti number: :line.',
        'import_sheet'      => 'Gabim: Emri i fletës nuk është i vlefshëm. Ju lutem, kontrolloni skedarin e mostrës.',
    ],

    'warning' => [
        'deleted'           => 'Njoftim: <b>:name</b> nuk mund të fshihet sepse ka :text të lidhur.',
        'disabled'          => 'Njoftim: <b>:name</b> nuk mund të disable sepse ka :text të lidhur.',
        'reconciled_tran'   => 'Njoftim: Nuk ju lejohet të ndryshoni/fshini transaksionin sepse është pajtuar!',
        'reconciled_doc'    => 'Njoftim: Nuk ju lejohet të ndryshoni/fshini :type sepse ka pajtuar transaksionet!',
        'disable_code'      => 'Njoftim: Nuk ju lejohet të çaktivizoni ose të ndryshoni monedhën e <b>:name</b>, sepse ajo ka :text e lidhur.',
        'payment_cancel'    => 'Paralajmërim: Ju keni anuluar :method pagesën tuaj të fundit!',
    ],

];
