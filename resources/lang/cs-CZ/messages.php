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
        'over_payment'      => 'Error: Payment not added! Amount passes the total.',
        'not_user_company'  => 'Chyba: nemůžeš provádět správu společností!',
        'customer'          => 'Error: User not created! :name already uses this email address.',
        'no_file'           => 'Chyba: Nebyl vybrán žádný soubor!',
        'last_category'     => 'Error: Can not delete the last :type category!',
        'invalid_token'     => 'Error: The token entered is invalid!',
    ],
    'warning' => [
        'deleted'           => 'Upozornění: Nemůžeš odstranit <b>:name</b> protože je spojená s :text.',
        'disabled'          => 'Upozornění: Nemůžeš vypnout <b>:name</b> protože je spojená s :text.',
    ],

];
