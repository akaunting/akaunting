<?php

return [

    'success' => [
        'added'             => ':type pievients!',
        'updated'           => ':type atjaunināts!',
        'deleted'           => ':type dzēsts!',
        'duplicated'        => ':type kopēts!',
        'imported'          => ':type importēts!',
        'exported'          => ':type exported!',
        'enabled'           => ':type iespējots!',
        'disabled'          => ':type atspējots!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Kļūda: Jums nav tiesības strādāt ar šo uzņēmumu!',
        'customer'          => 'Kļūda: Lietotājs nav izveidots! :name jau lieto šādu e-pasta adresi.',
        'no_file'           => 'Kļūda: Fails nav izvēlēts!',
        'last_category'     => 'Kļūda: Nevar izdzēst pēdējo :type kategoriju!',
        'change_type'       => 'Error: Can not change the type because it has :text related!',
        'invalid_apikey'    => 'Error: The API Key entered is invalid!',
        'import_column'     => 'Kļūda: :message Lapas nosaukums: :sheet. Rindas numurs: :line.',
        'import_sheet'      => 'Kļūda: Lapas nosaukums nav pareizs. Lūdzu pārbaudiet parauga failu.',
    ],

    'warning' => [
        'deleted'           => 'Brīdinājums: Jums nav tiesību dzēst <b>:name</b> jo tas ir saistīts ar :text.',
        'disabled'          => 'Brīdinājums: Jums nav tiesību atspējot <b>:name</b> jo tas ir saistīts ar :text.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
