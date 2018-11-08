<?php

return [

    'success' => [
        'added'             => ':type pridaný!',
        'updated'           => ':type aktualizovaný!',
        'deleted'           => ':type odstránený!',
        'duplicated'        => ':type duplicitný!',
        'imported'          => ':type importovaný!',
        'enabled'           => ': type povolený!',
        'disabled'          => ':type zakázaný!',
    ],
    'error' => [
        'over_payment'      => 'Chyba: Platba nebola pridaná! Suma, ktor[ ste zadali prekročila celkovú sumu.',
        'not_user_company'  => 'Chyba: Nemôžete spravovať túto spoločnosť!',
        'customer'          => 'Chyba: Používateľ nebol vytvorený! :name už používa táto e-mail adresa.',
        'no_file'           => 'Chyba: Žiadny súbor nebol vybratý!',
        'last_category'     => 'Chyba: Nemožno zmazať poslednú kategóriu :type!',
        'invalid_token'     => 'Chyba: Token je neplatný!',
        'import_column'     => 'Error: :message Názov hárka: :sheet. Číslo riadku: :line.',
        'import_sheet'      => 'Chyba: Názov hárka nie je platný. Prosím, skontrolujte podľa vzorového súboru.',
    ],
    'warning' => [
        'deleted'           => 'Upozornenie: Nemôžete odstrániť <b>:name</b>, pretože má :text súvisiaci.',
        'disabled'          => 'Upozornenie: Nemôžete zakázať <b>: názov</b> , pretože má :text súvisiaci.',
    ],

];
