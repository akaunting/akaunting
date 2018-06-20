<?php

return [

    'success' => [
        'added'             => ':type adaugat!',
        'updated'           => ':type actualizat!',
        'deleted'           => ':type şters!',
        'duplicated'        => ':type duplicat!',
        'imported'          => ':type importat!',
        'enabled'           => ':type activat!',
        'disabled'          => ':type dezactivat!',
    ],
    'error' => [
        'over_payment'      => 'Eroare: Plata nu a fost adaugata. Suma depaseste totalul.',
        'not_user_company'  => 'Eroare: Nu ai permisiunea necesara pentru a gestiona această companie!',
        'customer'          => 'Eroare: Utilizatorul nu a fost creat! :name deja foloseste aceasta adresa de email.',
        'no_file'           => 'Eroare: Nici un fişier selectat!',
        'last_category'     => 'Eroare: Nu pot sterge ultima :type categorie!',
        'invalid_token'     => 'Eroare: Tokenul introdus este invalid!',
    ],
    'warning' => [
        'deleted'           => 'Avertisment: Nu ti se permite să ştergi <b>:name</b> deoarece are o legatura cu :text.',
        'disabled'          => 'Avertisment: Nu ti se permite să dezactivezi <b>:name</b> deoarece are o legatura cu :text.',
    ],

];
