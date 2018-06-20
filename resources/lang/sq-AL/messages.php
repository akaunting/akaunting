<?php

return [

    'success' => [
        'added'             => ':type shtuar!',
        'updated'           => ':type përditësuar!',
        'deleted'           => ':type fshirë!',
        'duplicated'        => ':type dubluar!',
        'imported'          => ':type importuar!',
        'enabled'           => ':type aktivizuar!',
        'disabled'          => ':type çaktivizuar!',
    ],
    'error' => [
        'over_payment'      => 'Gabim: Pagesa nuk u shtua! Shuma kalon totalin.',
        'not_user_company'  => 'Gabim: Nuk ju lejohet të menaxhoni këtë kompani!',
        'customer'          => 'Gabim: Përdoruesi nuk u krijua! :name tashmë përdor këtë adresë e-maili.',
        'no_file'           => 'Gabim: Asnjë skedar i përzgjedhur!',
        'last_category'     => 'Gabim: Nuk mund të fshihet :type kategoria e fundit!',
        'invalid_token'     => 'Gabim: Marku i dhënë është i pavlefshëm!',
    ],
    'warning' => [
        'deleted'           => 'Njoftim: <b>:name</b> nuk mund të fshihet sepse ka :text të lidhur.',
        'disabled'          => 'Njoftim: <b>:name</b> nuk mund të disable sepse ka :text të lidhur.',
    ],

];
