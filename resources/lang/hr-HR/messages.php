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
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Pogreška: Nije vam dozvoljeno upravljanje ovom tvrtkom!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Pogreška: Nije odabrana nijedna datoteka!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Upozorenje: Nije vam dozvoljeno izbrisati <b>:name</b> jer postoji poveznica s :text.',
        'disabled'          => 'Upozorenje: Nije vam dozvoljeno onemogućiti <b>:name</b> jer postoji poveznica s :text.',
    ],

];
