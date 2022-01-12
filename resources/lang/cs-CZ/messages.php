<?php

return [

    'success' => [
        'added'             => ':type přidán!',
        'updated'           => ':type aktualizováno!',
        'deleted'           => ':type odstraněno!',
        'duplicated'        => ':type duplikováno!',
        'imported'          => ':type importováno!',
        'import_queued'     => ':type import byl naplánován! Po dokončení obdržíte e-mail.',
        'exported'          => ':type exportován!',
        'export_queued'     => ':type export aktuální stránky byl naplánován! Až bude připraven ke stažení, obdržíte e-mail.',
        'enabled'           => ':type aktivován!',
        'disabled'          => ':type deaktivován!',

        'clear_all'         => 'Skvělé! Vyčistili jste všechny své :type .',
    ],

    'error' => [
        'over_payment'      => 'Chyba: Platba nebyla přidána! Zadaná částka překračuje celkové množství :amount',
        'not_user_company'  => 'Chyba: pro správu společností nemáte oprávnění!',
        'customer'          => 'Chyba: uživatel nebyl vytvořen! Uživatel :name již používá tuto emailovou adresu.',
        'no_file'           => 'Chyba: Nebyl vybrán žádný soubor!',
        'last_category'     => 'Chyba: Nemohu smazat poslední kategorii :type ! ',
        'change_type'       => 'Chyba: Nelze změnit typ, je k :text relativní!',
        'invalid_apikey'    => 'Chyba: Zadaný API klíč je neplatný!',
        'import_column'     => 'Chyba: :message Tabulka :sheet. Řádek: :line.',
        'import_sheet'      => 'Chyba: Tabulka je neplatná. Prosím, zkontrolujte vzorový soubor.',
    ],

    'warning' => [
        'deleted'           => 'Upozornění: Nemůžete odstranit <b>:name</b> protože je spojená s :text.',
        'disabled'          => 'Upozornění: Nemůžete zakázat <b>:name</b> protože je spojená s :text.',
        'reconciled_tran'   => 'Varování: Nemáte oprávnění měnit/mazat transakci, protože je vyrovnaná!',
        'reconciled_doc'    => 'Varování: Nemáte oprávnění měnit/mazat :type, protože obsahuje vyrovnané transakce!',
        'disable_code'      => 'Upozornění: Není možné zakázat nebo změnit měnu <b>:name</b>, protože je spjata s :text.',
        'payment_cancel'    => 'Upozornění: Zrušili jste aktuální :method placení!',
    ],

];
