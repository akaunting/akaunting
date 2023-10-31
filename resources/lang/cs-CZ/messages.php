<?php

return [

    'success' => [
        'added'             => ':type přidán!',
        'created'			=> ':type vytvořen!',
        'updated'           => ':type aktualizováno!',
        'deleted'           => ':type odstraněno!',
        'duplicated'        => ':type duplikováno!',
        'imported'          => ':type importováno!',
        'import_queued'     => ':type import byl naplánován! Po dokončení obdržíte e-mail.',
        'exported'          => ':type exportován!',
        'export_queued'     => ':type export aktuální stránky byl naplánován! Až bude připraven ke stažení, obdržíte e-mail.',
        'enabled'           => ':type aktivován!',
        'disabled'          => ':type deaktivován!',
        'connected'         => ':type připojen!',
        'invited'           => ':type pozván!',
        'ended'             => ':type ukončen!',

        'clear_all'         => 'Skvělé! Vyčistili jste všechny své :type .',
    ],

    'error' => [
        'over_payment'      => 'Chyba: Platba nebyla přidána! Zadaná částka překračuje celkové množství :amount',
        'not_user_company'  => 'Chyba: pro správu společností nemáte oprávnění!',
        'customer'          => 'Chyba: uživatel nebyl vytvořen! Uživatel :name již používá tuto emailovou adresu.',
        'no_file'           => 'Chyba: Nebyl vybrán žádný soubor!',
        'last_category'     => 'Chyba: Nemohu smazat poslední kategorii :type ! ',
        'transfer_category' => 'Chyba: Nemohu odstranit převod <b>:type</b> kategorie!',
        'change_type'       => 'Chyba: Nelze změnit typ, je k :text relativní!',
        'invalid_apikey'    => 'Chyba: Zadaný API klíč je neplatný!',
        'empty_apikey'      => 'Chyba: Není vložen váš API klíč!  <a href=":url" class="font-bold underline underline-offset-4">Klikněte zde</a> pro vložení vašeho API klíče.',
        'import_column'     => 'Chyba: :message Tabulka :sheet. Řádek: :line.',
        'import_sheet'      => 'Chyba: Tabulka je neplatná. Prosím, zkontrolujte vzorový soubor.',
        'same_amount'       => 'Chyba: Celková částka rozdělení musí být přesně stejná jako :transaction celkem: :amount',
        'over_match'        => 'Chyba: :type není připojen! Zadaná částka nesmí překročit celkovou platbu: :amount',
    ],

    'warning' => [
        'deleted'           => 'Upozornění: Nemůžete odstranit <b>:name</b> protože je spojená s :text.',
        'disabled'          => 'Upozornění: Nemůžete zakázat <b>:name</b> protože je spojená s :text.',
        'reconciled_tran'   => 'Varování: Nemáte oprávnění měnit/mazat transakci, protože je vyrovnaná!',
        'reconciled_doc'    => 'Varování: Nemáte oprávnění měnit/mazat :type, protože obsahuje vyrovnané transakce!',
        'disable_code'      => 'Upozornění: Není možné zakázat nebo změnit měnu <b>:name</b>, protože je spjata s :text.',
        'payment_cancel'    => 'Upozornění: Zrušili jste aktuální :method placení!',
        'missing_transfer'  => 'Varování: Převod související s touto transakcí chybí. Měli byste zvážit odstranění této transakce.',
    ],

];
