<?php

return [

    'success' => [
        'added'             => ':type hozzáadva!',
        'updated'           => ':type frissítve!',
        'deleted'           => ':type törölve!',
        'duplicated'        => ':type duplikálva!',
        'imported'          => ':type importálva!',
        'exported'          => ':type frissítve!',
        'enabled'           => ': típus engedélyezve!',
        'disabled'          => ': típus tiltva!',
    ],

    'error' => [
        'over_payment'      => '
Hiba: A fizetés nincs hozzáadva! A megadott összeg meghaladja a következő összeget:: összeg',
        'not_user_company'  => 'Hiba: Ön nem kezelheti ezt a céget!',
        'customer'          => 'Hiba: A felhasználó nem jött létre! :name már használja ezt az email címet.',
        'no_file'           => 'Hiba: Nincs fájl kiválasztva!',
        'last_category'     => 'Hiba: Nem lehet törölni az utolsó :type kategóriát!',
        'change_type'       => '
Hiba: Nem lehet megváltoztatni a típust, mert: szöveggel kapcsolatos!',
        'invalid_apikey'    => 'Hiba: A megadott token érvénytelen!',
        'import_column'     => 'Hiba:: üzenet lap neve:: lapot. Sorszám:: vonal.',
        'import_sheet'      => 'Hiba: Oldal neve érvénytelen. Kérjük ellenőrizze a mintafájlt.',
    ],

    'warning' => [
        'deleted'           => 'Figyelem: Nem törölheti <b>:name</b> nevet, mert ez kapcsolt :text szöveghez.',
        'disabled'          => 'Figyelem: Nem tilthatja le <b>:name</b>-t, mert ez kapcsolt :text szöveghez.',
        'reconciled_tran'   => '
Figyelem: Ön nem módosíthatja / törölheti a tranzakciót, mert az összeegyeztethető!',
        'reconciled_doc'    => '
Figyelem: Ön nem módosíthatja / törölheti a tranzakciót, mert az összeegyeztethető!',
        'disable_code'      => 'Figyelem: Nem törölheti <b>:name</b> nevet, mert ez kapcsolt :text szöveghez.',
        'payment_cancel'    => '
Figyelem: Törölte a legutóbbi: method fizetést!',
    ],

];
