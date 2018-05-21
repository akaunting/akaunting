<?php

return [

    'success' => [
        'added'             => ':type dodan!',
        'updated'           => ':type ažuriran!',
        'deleted'           => ':type izbrisan!',
        'duplicated'        => ':type dupliciran!',
        'imported'          => ':type uvezen!',
    ],
    'error' => [
        'over_payment'      => 'Pogreška: Plaćanje nije dodano! Iznos prelazi ukupan iznos.',
        'not_user_company'  => 'Pogreška: Nije vam dozvoljeno upravljanje ovom tvrtkom!',
        'customer'          => 'Pogreška: Korisnik nije kreiran! :name već koristi ovu e-mail adresu.',
        'no_file'           => 'Pogreška: Nije odabrana nijedna datoteka!',
        'last_category'     => 'Pogreška: Nije moguće izbrisati zadnju :type kategoriju!',
        'invalid_token'     => 'Pogreška: Upisani token nije valjan!',
    ],
    'warning' => [
        'deleted'           => 'Upozorenje: Nije vam dozvoljeno izbrisati <b>:name</b> jer postoji poveznica s :text.',
        'disabled'          => 'Upozorenje: Nije vam dozvoljeno onemogućiti <b>:name</b> jer postoji poveznica s :text.',
    ],

];
