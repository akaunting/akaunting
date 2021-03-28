<?php

return [

    'success' => [
        'added'             => ':type bætt við!',
        'updated'           => ':type uppfært!',
        'deleted'           => ':type eytt!',
        'duplicated'        => ':type afritað!',
        'imported'          => ':type innflutt!',
        'exported'          => ':type exported!',
        'enabled'           => ':type virkjað!',
        'disabled'          => ':type slökkt!',
    ],

    'error' => [
        'over_payment'      => 'Error: Greiðslu ekki bætt við! Upphæðin sem þú settir inn er hærri en: :amount',
        'not_user_company'  => 'Error: Þú hefur ekki heimild til að stjórna þessu fyrirtæki!',
        'customer'          => 'Error: Notandi ekki skapaður! :name hefur þegar skráð þetta netfang.',
        'no_file'           => 'Error: Engin skrá valin!',
        'last_category'     => 'Error: Get ekki eytt :type flokki!',
        'change_type'       => 'Error: Can not change the type because it has :text related!',
        'invalid_apikey'    => 'Villa: Innritaður API lykill er ógildur!',
        'import_column'     => 'Villa:: skilaboð Heiti skjals:: blað. Lína númer:: lína.',
        'import_sheet'      => 'Villa: Nafn skjals er ógilt. Vinsamlegast skoðaðu sýnishorn skjal.',
    ],

    'warning' => [
        'deleted'           => 'Viðvörun: Þú mátt ekki eyða <b>:name</b> vegna þess að það hefur: textatengsl.',
        'disabled'          => 'Viðvörun: Þú mátt ekki slökkva á <b>:name</b> vegna þess að það hefur: textatengsl.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'Viðvörun: Ekki er heimilt að gera óvirkt eða breyta gjaldmiðli <b>:name</b> vegna þess að hann hefur: textatengsl.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
