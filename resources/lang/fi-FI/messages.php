<?php

return [

    'success' => [
        'added'             => ':type lisätty!',
        'updated'           => ':type päivitetty!',
        'deleted'           => ':type päivitetty!',
        'duplicated'        => ':type kopioitu!',
        'imported'          => ':type tuotu!',
        'exported'          => ':type viety!',
        'enabled'           => ':type käytössä!',
        'disabled'          => ':type pois käytöstä!',
    ],

    'error' => [
        'over_payment'      => 'Virhe: Maksua ei lisätty! Antamasi summa ylittää summan: :amount',
        'not_user_company'  => 'Virhe: Sinulla ei ole oikeutta ohjata tätä yritystä!',
        'customer'          => 'Virhe: Käyttäjää ei luotu! :name käyttää jo tätä sähköpostiosoitetta.',
        'no_file'           => 'Virhe: Tiedostoa ei ole valittu!',
        'last_category'     => 'Virhe: Ei voi poistaa viimeistä :type kategoriaa!',
        'change_type'       => 'Virhe: Ei voi muuttaa tyyppiä, koska se liittyy :text!',
        'invalid_apikey'    => 'Virhe: Syötetty API-avain on virheellinen!',
        'import_column'     => 'Virhe: :message Taulukon nimi: :arkki. Rivinumero: :line.',
        'import_sheet'      => 'Virhe: Taulukon nimi ei kelpaa. Tarkista tiedosto.',
    ],

    'warning' => [
        'deleted'           => 'Varoitus: Sinulla ei ole oikeutta poistaa <b>:name</b> koska se liittyy :text.',
        'disabled'          => 'Varoitus: Sinulla ei ole oikeutta poistaa käytöstä <b>:name</b> koska se liittyy :textiin.',
        'reconciled_tran'   => 'Varoitus: Sinulla ei ole oikeutta vaihtaa tai poistaa tapahtumaa, koska se on täsmäytetty!',
        'reconciled_doc'    => 'Varoitus: Sinulla ei ole oikeutta vaihtaa tai poistaa tapahtumaa, koska se on täsmäytetty!',
        'disable_code'      => 'Varoitus: Sinulla ei ole oikeutta poistaa käytöstä tai vaihtaa <b>:name</b> valuuttaa koska se liittyy :textiin.',
        'payment_cancel'    => 'Varoitus: Olet peruuttanut viime :method maksun!',
    ],

];
