<?php

return [

    'success' => [
        'added'             => ':type adăugat!',
        'updated'           => ':type actualizat!',
        'deleted'           => ':type şters!',
        'duplicated'        => ':type duplicat!',
        'imported'          => ':type importat!',
        'import_queued'     => 'Importul :type a fost programat! Veţi primi un e-mail când acesta va fi terminat.',
        'exported'          => ':type exportat!',
        'export_queued'     => 'Exportul :type a fost programat! Veţi primi un e-mail atunci când este gata de descărcare.',
        'enabled'           => ':type activat!',
        'disabled'          => ':type dezactivat!',
    ],

    'error' => [
        'over_payment'      => 'Eroare: Plata nu a fost adăugată! Suma pe care ați introdus-o depășește totalul: :amount',
        'not_user_company'  => 'Eroare: Nu ai permisiunea necesara pentru a gestiona această companie!',
        'customer'          => 'Eroare: Utilizatorul nu a fost creat! :name deja foloseste aceasta adresa de email.',
        'no_file'           => 'Eroare: Nici un fişier selectat!',
        'last_category'     => 'Eroare: Nu pot sterge ultima :type categorie!',
        'change_type'       => 'Eroare: Nu se poate schimba tipul deoarece are :text legat!',
        'invalid_apikey'    => 'Eroare: Tokenul introdus este invalid!',
        'import_column'     => 'Eroare: message Nume foaie lucru: :sheet. Numar linie: :line.',
        'import_sheet'      => 'Eroare: Numele foii de lucru nu este valid. Te rog verifica fisierul mostra.',
    ],

    'warning' => [
        'deleted'           => 'Avertisment: Nu ti se permite să ştergi <b>:name</b> deoarece are o legatura cu :text.',
        'disabled'          => 'Avertisment: Nu ti se permite să dezactivezi <b>:name</b> deoarece are o legatura cu :text.',
        'reconciled_tran'   => 'Avertisment: Nu vă este permis să modificați/ștergeți tranzacția deoarece este reconciliată!',
        'reconciled_doc'    => 'Atenție: Nu ai permisiunea de a modifica/șterge :type deoarece conține tranzacții reconciliate!',
        'disable_code'      => 'Atenție: Nu vi se permite să dezactivezi sau sa schimbi valuta <b>:name</b> deoarece este în legătură cu :text.',
        'payment_cancel'    => 'Atenție: Ai anulat ultima ta :method de plată!',
    ],

];
