<?php

return [

    'success' => [
        'added'             => ':type tilføjet!',
        'updated'           => ':type opdateret!',
        'deleted'           => ':type slettet!',
        'duplicated'        => ':type duplikeret!',
        'imported'          => ':type importeret!',
    ],
    'error' => [
        'over_payment'      => 'Fejl: Betaling ikke tilføjet! Beløbet overskrider total porisen.',
        'not_user_company'  => 'Fejl: Du har ikke tilladelse til at kontrollere denne virksomhed!',
        'customer'          => 'Fejl: Brugeren ikke oprettet! :name bruger allerede denne e-mail.',
        'no_file'           => 'Fejl: Ingen fil valgt!',
    ],
    'warning' => [
        'deleted'           => 'Advarsel: Du har ikke tilladelse tiil at slette <b>:name</b> fordi den er :text relateret.',
        'disabled'          => 'Advarsel: Du har ikke tilladelse tiil at deaktivere <b>:name</b> fordi den er :text relateret.',
    ],

];
