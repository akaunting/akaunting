<?php

return [

    'success' => [
        'added'             => ':type tillagt!',
        'updated'           => ':type uppdaterad!',
        'deleted'           => ':type bortagen!',
        'duplicated'        => ':type dubbelpost!',
        'imported'          => ':type uppdaterad!',
    ],
    'error' => [
        'payment_add'       => 'Fel: Du kan inte lägga till betalning! Du bör kontrollera lägg till beloppet.',
        'not_user_company'  => 'Fel: Du får inte hantera detta företag!',
        'customer'          => 'Fel: Du kan inte skapat användare! :name använder denna e-postadress.',
        'no_file'           => 'Fel: Ingen fil har valts!',
    ],
    'warning' => [
        'deleted'           => 'Varning: Du får inte ta bort <b>:name</b> eftersom den har :text relaterade.',
        'disabled'          => 'Varning: Du får inte inaktivera <b>:name</b> eftersom den har :text relaterat.',
    ],

];
