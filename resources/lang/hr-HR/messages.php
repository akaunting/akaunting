<?php

return [

    'success' => [
        'added'             => ':type dodano!',
        'updated'           => ':type ažuriran!',
        'deleted'           => ':type izbrisan!',
        'duplicated'        => ':type dupliciran!',
        'imported'          => ':type uvezen!',
        'exported'          => ': type izvezen!',
        'enabled'           => ': type omogućena!',
        'disabled'          => ':type onemogućeno!',
    ],

    'error' => [
        'over_payment'      => 'Greška: Uplata nije dodana! Upisani iznos uplate premašuje ukupni iznos: :amount',
        'not_user_company'  => 'Pogreška: Nije vam dozvoljeno upravljanje ovom tvrtkom!',
        'customer'          => 'Pogreška: Korisnik nije kreiran! :name već koristi ovu e-mail adresu.',
        'no_file'           => 'Pogreška: Nije odabrana nijedna datoteka!',
        'last_category'     => 'Pogreška: Nije moguće izbrisati zadnju :type kategoriju!',
        'change_type'       => 'Pogreška: Ne mogu promijeniti vrstu jer ima: tekst se odnosi!',
        'invalid_apikey'    => 'Pogreška: Uneseni API token nije važeći!',
        'import_column'     => 'Greška:: poruka Naziv lista:: list. Broj retka:: linija.',
        'import_sheet'      => 'Pogreška: naziv liste nije važeći. Provjerite oglednu datoteku.',
    ],

    'warning' => [
        'deleted'           => 'Upozorenje: Nije vam dozvoljeno izbrisati <b>:name</b> jer postoji poveznica s :text.',
        'disabled'          => 'Upozorenje: Nije vam dozvoljeno onemogućiti <b>:name</b> jer postoji poveznica s :text.',
        'disable_code'      => 'Upozorenje: Nije vam dopušteno onesposobiti ili promijeniti valutu <b>: ime </b> jer je: tekst povezan.',
        'payment_cancel'    => 'Upozorenje: Otkazali ste nedavni: način plaćanja!',
    ],

];
