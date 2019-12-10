<?php

return [

    'success' => [
        'added'             => ':type přidán!',
        'updated'           => ':type aktualizováno!',
        'deleted'           => ':type odstraněno!',
        'duplicated'        => ':type duplikováno!',
        'imported'          => ':type importováno!',
        'enabled'           => ':type aktivován!',
        'disabled'          => ':type deaktivován!',
    ],

    'error' => [
        'over_payment'      => 'Chyba: Platba nebyla přidána! Zadaná částka překračuje celkové množství :amount',
        'not_user_company'  => 'Chyba: pro správu společností nemáte oprávnění!',
        'customer'          => 'Chyba: uživatel nebyl vytvořen! Uživatel :name již používá tuto emailovou adresu.',
        'no_file'           => 'Chyba: Nebyl vybrán žádný soubor!',
        'last_category'     => 'Chyba: Nemohu smazat poslední kategorii :type ! ',
        'invalid_token'     => 'Chyba: Zadaný token je neplatný!',
        'import_column'     => 'Chyba: :message Tabulka :sheet. Řádek: :line.',
        'import_sheet'      => 'Chyba: Tabulka je neplatná. Prosím, zkontrolujte vzorový soubor.',
    ],

    'warning' => [
        'deleted'           => 'Upozornění: Nemůžete odstranit <b>:name</b> protože je spojená s :text.',
        'disabled'          => 'Upozornění: Nemůžete zakázat <b>:name</b> protože je spojená s :text.',
        'disable_code'      => 'Upozornění: Není možné zakázat nebo změnit měnu <b>:name</b>, protože je spjata s :text.',
    ],

];
