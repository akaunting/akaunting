<?php

return [

    'success' => [
        'added'             => ':type toegevoegd!',
        'updated'           => ':type bijgewerkt!',
        'deleted'           => ':type verwijderd!',
        'duplicated'        => ':type gedupliceerd!',
        'imported'          => ':type geïmporteerd!',
        'exported'          => ':type exported!',
        'enabled'           => ':type ingeschakeld!',
        'disabled'          => ':type uitgeschakeld!',
    ],

    'error' => [
        'over_payment'      => 'Fout: Betaling niet toegevoegd! Het door u ingevoerde bedrag passeert het totaal: :amount',
        'not_user_company'  => 'Fout: U heeft niet de juiste bevoegdheid om dit bedrijf te beheren!',
        'customer'          => 'Fout: Gebruiker niet aangemaakt! :name heeft dit e-mailadres al in gebruik.',
        'no_file'           => 'Fout: geen bestand geselecteerd!',
        'last_category'     => 'Fout: Kan de laatste categorie niet verwijderen: :type',
        'change_type'       => 'Error: Can not change the type because it has :text related!',
        'invalid_apikey'    => 'Error: The API Key entered is invalid!',
        'import_column'     => 'Fout: :message Blad naam: :sheet. Lijnnummer: :line.',
        'import_sheet'      => 'Fout: Bladnaam is niet geldig. Vergelijk het met het voorbeeldbestand.',
    ],

    'warning' => [
        'deleted'           => 'Waarschuwing: Het is voor u niet toegestaan om <b>:name</b> te verwijderen omdat het gerelateerd is aan :text.',
        'disabled'          => 'Waarschuwing: U mag <b>:name</b> niet uitschakelen omdat het gerelateerd is aan :text.',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => 'Waarschuwing: U mag <b>:name</b> niet uitschakelen omdat het gerelateerd is aan :text.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
