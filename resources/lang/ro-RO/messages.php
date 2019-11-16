<?php

return [

    'success' => [
        'added'             => ':type adăugat!',
        'updated'           => ':type actualizat!',
        'deleted'           => ':type şters!',
        'duplicated'        => ':type duplicat!',
        'imported'          => ':type importat!',
        'enabled'           => ':type activat!',
        'disabled'          => ':type dezactivat!',
    ],

    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Eroare: Nu ai permisiunea necesara pentru a gestiona această companie!',
        'customer'          => 'Eroare: Utilizatorul nu a fost creat! :name deja foloseste aceasta adresa de email.',
        'no_file'           => 'Eroare: Nici un fişier selectat!',
        'last_category'     => 'Eroare: Nu pot sterge ultima :type categorie!',
        'invalid_token'     => 'Eroare: Tokenul introdus este invalid!',
        'import_column'     => 'Eroare: message Nume foaie lucru: :sheet. Numar linie: :line.',
        'import_sheet'      => 'Eroare: Numele foii de lucru nu este valid. Te rog verifica fisierul mostra.',
    ],

    'warning' => [
        'deleted'           => 'Avertisment: Nu ti se permite să ştergi <b>:name</b> deoarece are o legatura cu :text.',
        'disabled'          => 'Avertisment: Nu ti se permite să dezactivezi <b>:name</b> deoarece are o legatura cu :text.',
        'disable_code'      => 'Atenție: Nu vi se permite să dezactivezi sau sa schimbi valuta <b>:name</b> deoarece este în legătură cu :text.',
    ],

];
