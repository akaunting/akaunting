<?php

return [

    'success' => [
        'added'             => ':type pridėta!',
        'updated'           => ':type atnaujintas!',
        'deleted'           => ':type ištrintas!',
        'duplicated'        => ':type duplikuotas!',
        'imported'          => ':type importuotas!',
        'exported'          => ':type išeksportuotas!',
        'enabled'           => ':type įjungtas!',
        'disabled'          => ':type išjungtas!',
    ],

    'error' => [
        'over_payment'      => 'Klaida: Apmokėjimo būdas nepridėtas! Jūsų įvesta suma viršija :amount',
        'not_user_company'  => 'Klaida: Jūs neturite teisės valdyti šios kompanijos!',
        'customer'          => 'Klaida: Vartotojas nebuvo sukurtas! :name jau naudoja šį el. pašto adresą.',
        'no_file'           => 'Klaida: Nepasirinktas failas!',
        'last_category'     => 'Klaida: Negalite ištrinti paskutinės :type kategorijos!',
        'change_type'       => 'Klaida: Negalima pakeisti tipo, nes jis yra susijęs su :text!',
        'invalid_apikey'    => 'Klaida: Neteisingas raktas!',
        'import_column'     => 'Klaida: :message :sheet lape. Eilutė: :line.',
        'import_sheet'      => 'Klaida: Lapo pavadinimas neteisingas Peržiūrėkite pavyzdį.',
    ],

    'warning' => [
        'deleted'           => 'Negalima ištrinti <b>:name</b>, nes jis yra susijęs su :text.',
        'disabled'          => 'Negalima išjungti <b>:name</b>, nes jis yra susijęs su :text.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'Įspėjimas: Negalima išjungti arba pakeisti valiutos <b>:name</b>, nes ji susijusi su :text.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
