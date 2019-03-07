<?php

return [

    'success' => [
        'added'             => ':type pievients!',
        'updated'           => ':type atjaunināts!',
        'deleted'           => ':type dzēsts!',
        'duplicated'        => ':type kopēts!',
        'imported'          => ':type importēts!',
        'enabled'           => ':type iespējots!',
        'disabled'          => ':type atspējots!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Kļūda: Jums nav tiesības strādāt ar šo uzņēmumu!',
        'customer'          => 'Kļūda: Lietotājs nav izveidots! :name jau lieto šādu e-pasta adresi.',
        'no_file'           => 'Kļūda: Fails nav izvēlēts!',
        'last_category'     => 'Kļūda: Nevar izdzēst pēdējo :type kategoriju!',
        'invalid_token'     => 'Kļūda: Ievadītā atslēga nav pareiza!',
        'import_column'     => 'Kļūda: :message Lapas nosaukums: :sheet. Rindas numurs: :line.',
        'import_sheet'      => 'Kļūda: Lapas nosaukums nav pareizs. Lūdzu pārbaudiet parauga failu.',
    ],

    'warning' => [
        'deleted'           => 'Brīdinājums: Jums nav tiesību dzēst <b>:name</b> jo tas ir saistīts ar :text.',
        'disabled'          => 'Brīdinājums: Jums nav tiesību atspējot <b>:name</b> jo tas ir saistīts ar :text.',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
    ],

];
