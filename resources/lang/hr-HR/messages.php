<?php

return [

    'success' => [
        'added'             => ':type dodano!',
        'updated'           => ':type ažuriran!',
        'deleted'           => ':type izbrisan!',
        'duplicated'        => ':type dupliciran!',
        'imported'          => ':type uvezen!',
        'enabled'           => ':type enabled!',
        'disabled'          => ':type disabled!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Pogreška: Nije vam dozvoljeno upravljanje ovom tvrtkom!',
        'customer'          => 'Pogreška: Korisnik nije kreiran! :name već koristi ovu e-mail adresu.',
        'no_file'           => 'Pogreška: Nije odabrana nijedna datoteka!',
        'last_category'     => 'Pogreška: Nije moguće izbrisati zadnju :type kategoriju!',
        'invalid_token'     => 'Pogreška: Upisani token nije valjan!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],
    'warning' => [
        'deleted'           => 'Upozorenje: Nije vam dozvoljeno izbrisati <b>:name</b> jer postoji poveznica s :text.',
        'disabled'          => 'Upozorenje: Nije vam dozvoljeno onemogućiti <b>:name</b> jer postoji poveznica s :text.',
    ],

];
