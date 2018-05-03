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
        'over_payment'      => 'Fel: Betalning inte tillagd! Belopp överskrider totalen.',
        'not_user_company'  => 'Fel: Du får inte hantera detta företag!',
        'customer'          => 'Fel: Användaren inte skapad! :name använder redan denna e-postadress.',
        'no_file'           => 'Fel: Ingen fil har valts!',
        'last_category'     => 'Fel: Kan inte ta bort sista :type kategorin!',
        'invalid_token'     => 'Fel: Den symbolen som angetts är ogiltigt!',
    ],
    'warning' => [
        'deleted'           => 'Varning: Du får inte ta bort <b>:name</b> eftersom den har :text relaterade.',
        'disabled'          => 'Varning: Du får inte inaktivera <b>:name</b> eftersom den har :text relaterat.',
    ],

];
