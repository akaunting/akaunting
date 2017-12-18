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
        'payment_add'       => 'Pogreška: Nije moguće dodati plaćanje! Trebali bi provjeriti dodani iznos.',
        'not_user_company'  => 'Pogreška: Nije vam dozvoljeno upravljanje ovom tvrtkom!',
        'customer'          => 'Pogreška: Nije moguće kreirati korisnika! :name koristite ovu e-mail adresu.',
        'no_file'           => 'Pogreška: Nije odabrana nijedna datoteka!',
    ],
    'warning' => [
        'deleted'           => 'Upozorenje: Nije vam dozvoljeno izbrisati <b>:name</b> jer postoji poveznica s :text.',
        'disabled'          => 'Upozorenje: Nije vam dozvoljeno onemogućiti <b>:name</b> jer postoji poveznica s :text.',
    ],

];
