<?php

return [

    'success' => [
        'added'             => ':type adăugat!',
        'updated'           => ':type actualizat!',
        'deleted'           => ':type şters!',
        'duplicated'        => ':type duplicat!',
        'imported'          => ':type importat!',
        'import_queued'     => 'Importul :type a fost programat! Vei primi un e-mail când acesta va fi terminat.',
        'exported'          => ':type exportat!',
        'export_queued'     => 'Exportul :type a fost programat! Vei primi un e-mail atunci când este gata de descărcare.',
        'enabled'           => ':type activat!',
        'disabled'          => ':type dezactivat!',
        'connected'         => ':type conectat!',
        'invited'           => ':type invitat!',
        'ended'             => ':type încheiat!',

        'clear_all'         => 'Minunat! Ai șters toate cele :type.',
    ],

    'error' => [
        'over_payment'      => 'Eroare: Plata nu a fost adăugată! Suma pe care ați introdus-o depășește totalul: :amount',
        'not_user_company'  => 'Eroare: Nu ai permisiunea necesara pentru a gestiona această companie!',
        'customer'          => 'Eroare: Utilizatorul nu a fost creat! :name deja folosește aceasta adresa de email.',
        'no_file'           => 'Eroare: Nici un fişier selectat!',
        'last_category'     => 'Eroare: Nu pot ;șterge ultima :type categorie!',
        'change_type'       => 'Eroare: Nu se poate schimba tipul deoarece are :text legat!',
        'invalid_apikey'    => 'Eroare: Cheia API introdusă nu este invalidă!',
        'import_column'     => 'Eroare :message nume coloană: :column. Număr linie: :line.',
        'import_sheet'      => 'Eroare: Numele foii nu este valid. Te rog verifică fișierul exemplu.',
        'same_amount'       => 'Eroare: Suma totală a împărțirii trebuie să fie exact aceeași cu :transaction total: :amount',
        'over_match'        => 'Eroare: :type nu este conectat! Suma introdusă nu poate depăși totalul plății: :amount',
    ],

    'warning' => [
        'deleted'           => 'Avertisment: Nu ți se permite să ștergi <b>:name</b> deoarece are o legătura cu :text.',
        'disabled'          => 'Avertisment: Nu ți se permite să dezactivezi <b>:name</b> deoarece are o legătura cu :text.',
        'reconciled_tran'   => 'Avertisment: Nu vă este permis să modificați/ștergeți tranzacția deoarece este reconciliată!',
        'reconciled_doc'    => 'Atenție: Nu ai permisiunea de a modifica/șterge :type deoarece conține tranzacții reconciliate!',
        'disable_code'      => 'Atenție: Nu ți se permite să dezactivezi sau sa schimbi valuta <b>:name</b> deoarece este în legătură cu :text.',
        'payment_cancel'    => 'Atenție: Ai anulat ultima ta :method de plată!',
        'missing_transfer'  => 'Atenție: transferul legat de această tranzacție lipsește. Ar trebui să iei în considerare ștergerea acestei tranzacții.',
    ],

];
