<?php

return [

    'success' => [
        'added'             => ':type dodano!',
        'updated'           => ':type ažurirano!',
        'deleted'           => ':type izbrisano!',
        'duplicated'        => ':type duplicirano!',
        'imported'          => ':type uvezeno!',
        'import_queued'     => ':type uvoz he zakaan! Dobicete email kad se završi.',
        'exported'          => ': type izvezeno!',
        'export_queued'     => ':type izvoz je zakazan! Dobićete email kad se završi.',
        'enabled'           => ': type omogućeno!',
        'disabled'          => ':type onemogućeno!',

        'clear_all'         => 'Super! Očistili ste sve svoje :type.',
    ],

    'error' => [
        'over_payment'      => 'Greška: Uplata nije dodana! Upisani iznos uplate premašuje ukupni iznos: :amount',
        'not_user_company'  => 'Pogreška: Nije vam dozvoljeno upravljanje ovom kompanijom!',
        'customer'          => 'Pogreška: Korisnik nije kreiran! :name već koristi ovu e-mail adresu.',
        'no_file'           => 'Pogreška: Nije odabrana nijedna datoteka!',
        'last_category'     => 'Pogreška: Nije moguće izbrisati zadnju :type kategoriju!',
        'change_type'       => 'Pogreška: Ne mogu promijeniti vrstu jer se već unaprijed odnosi na :text!',
        'invalid_apikey'    => 'Pogreška: Uneseni API token nije važeći!',
        'import_column'     => 'Greška:: poruka Naziv lista:: list. Broj retka:: linija.',
        'import_sheet'      => 'Pogreška: naziv liste nije važeći. Provjerite oglednu datoteku.',
    ],

    'warning' => [
        'deleted'           => 'Upozorenje: Nije vam dozvoljeno izbrisati <b>:name</b> jer postoji poveznica s :text.',
        'disabled'          => 'Upozorenje: Nije vam dozvoljeno onemogućiti <b>:name</b> jer postoji poveznica s :text.',
        'reconciled_tran'   => 'Upozorenje: Nije vam dozvoljeno mijenjati/brisati transakciju, jer je već podmirena!',
        'reconciled_doc'    => 'Upozorenje: Nije vam dozvoljeno mijenjati/brisati :type, jer sadrži već podmirene transakcije!',
        'disable_code'      => 'Upozorenje: Nije vam dopušteno onesposobiti ili promijeniti valutu <b>: ime </b> jer je: tekst povezan.',
        'payment_cancel'    => 'Upozorenje: Otkazali ste nedavni: način plaćanja!',
    ],

];
