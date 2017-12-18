<?php

return [

    'success' => [
        'added'             => ':type shtuar!',
        'updated'           => ':type përditësuar!',
        'deleted'           => ':type fshirë!',
        'duplicated'        => ':type dubluar!',
        'imported'          => ':type importuar!',
    ],
    'error' => [
        'payment_add'       => 'Gabim: Ju nuk mund të shtoni pagesa! Ju duhet të kontrolloni shtimin e shumës.',
        'not_user_company'  => 'Gabim: Nuk ju lejohet të menaxhoni këtë kompani!',
        'customer'          => 'Gabim: Ju nuk mund të krijoni përdorues! :name përdor këtë adresë e-maili.',
        'no_file'           => 'Gabim: Asnjë skedar i përzgjedhur!',
    ],
    'warning' => [
        'deleted'           => 'Njoftim: <b>:name</b> nuk mund të fshihet sepse ka :text të lidhur.',
        'disabled'          => 'Njoftim: <b>:name</b> nuk mund të disable sepse ka :text të lidhur.',
    ],

];
