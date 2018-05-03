<?php

return [

    'success' => [
        'added'             => ':type lagt til.',
        'updated'           => ':type oppdatert.',
        'deleted'           => ':type slettet.',
        'duplicated'        => ':type duplisert.',
        'imported'          => ':type importert.',
    ],
    'error' => [
        'over_payment'      => 'Feil: Betaling ble ikke lagt til. Beløpet overstiger total.',
        'not_user_company'  => 'Feil: Du ikke kan administrere dette foretaket.',
        'customer'          => 'Feil: Bruker ble ikke opprettet. :name bruker allerede denne e-postadressen.',
        'no_file'           => 'Feil: Ingen fil er valgt.',
        'last_category'     => 'Feil: Kan ikke slette siste :type kategori.',
        'invalid_token'     => 'Feil: Angitt token er ugyldig.',
    ],
    'warning' => [
        'deleted'           => 'Advarsel: Du har ikke mulighet til å slette <b>:name</b> fordi kontoen har :text relatert.',
        'disabled'          => 'Advarsel: Du kan ikke deaktivere <b>:name</b> fordi kontoen har :text relatert.',
    ],

];
