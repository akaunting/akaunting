<?php

return [

    'success' => [
        'added'             => ':type tillagt!',
        'updated'           => ':type uppdaterad!',
        'deleted'           => ':type bortagen!',
        'duplicated'        => ':type dubbelpost!',
        'imported'          => ':type uppdaterad!',
        'import_queued'     => ':type import har schemalagts! Du kommer att få ett e-postmeddelande när den är klar.',
        'exported'          => ':type exporterad!',
        'export_queued'     => ':type import har schemalagts! Du kommer att få ett e-postmeddelande när den är klar.',
        'enabled'           => ': typ aktiverad!',
        'disabled'          => ':type inaktiverat!',
        'connected'         => ':type ansluten!',
        'invited'           => ':type inbjuden!',
        'ended'             => ':type avslutad!',

        'clear_all'         => 'Bra! Du har rensat alla dina :type.',
    ],

    'error' => [
        'over_payment'      => 'Fel: Betalning inte lagt till! Det belopp som du angav överskrider totalen: :amount',
        'not_user_company'  => 'Fel: Du får inte hantera detta företag!',
        'customer'          => 'Fel: Användaren inte skapad! :name använder redan denna e-postadress.',
        'no_file'           => 'Fel: Ingen fil har valts!',
        'last_category'     => 'Fel: Kan inte ta bort sista :type kategorin!',
        'change_type'       => 'Fel: Kan inte ändra typen eftersom den har :text relaterad!',
        'invalid_apikey'    => 'Fel: API-nyckeln som angetts är ogiltig!',
        'import_column'     => 'Fel: :message bladnamn: :sheet. Radnummer: :line.',
        'import_sheet'      => 'Fel: Bladets namn är inte giltigt. Vänligen kontrollera exempelfilen.',
        'same_amount'       => 'Fel: Det totala beloppet av delarna måste vara exakt samma som :transaction total: :amount',
        'over_match'        => 'Fel: :type inte ansluten! Beloppet du angav får inte överstiga betalningens total: :amount',
    ],

    'warning' => [
        'deleted'           => 'Varning: Du får inte ta bort <b>:name</b> eftersom den har :text relaterade.',
        'disabled'          => 'Varning: Du får inte inaktivera <b>:name</b> eftersom den har :text relaterat.',
        'reconciled_tran'   => 'Varning: Du har inte tillåtelse att ändra/ta bort transaktionen eftersom den är förenad!',
        'reconciled_doc'    => 'Varning: Du har inte tillåtelse att ändra/ta bort :type eftersom det har försonat transaktioner!',
        'disable_code'      => 'Varning: Du får inte inaktivera eller ändra valutan i <b>:name</b> eftersom den har :text relaterad.',
        'payment_cancel'    => 'Varning: Du har avbrutit din senaste :method betalning!',
        'missing_transfer'  => 'Varning: Överföringen av denna transaktion saknas. Du bör överväga att ta bort denna transaktion.',
    ],

];
