<?php

return [

    'success' => [
        'added'             => ':type přidán!',
        'updated'           => ':type aktualizováno!',
        'deleted'           => ':type odstraněno!',
        'duplicated'        => ':type duplikováno!',
        'imported'          => ':type importováno!',
    ],
    'error' => [
        'payment_add'       => 'Chyba: Nelze přidat platbu! Měli byste přidat částku.',
        'not_user_company'  => 'Chyba: nemůžeš provádět správu společností!',
        'customer'          => 'Chyba: Nemůžeš vytvořit uživatele! :name používá tento email.',
        'no_file'           => 'Chyba: Nebyl vybrán žádný soubor!',
    ],
    'warning' => [
        'deleted'           => 'Upozornění: Nemůžeš odstranit <b>:name</b> protože je spojená s :text.',
        'disabled'          => 'Upozornění: Nemůžeš vypnout <b>:name</b> protože je spojená s :text.',
    ],

];
