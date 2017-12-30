<?php

return [

    'success' => [
        'added'             => ':type aggiunto!',
        'updated'           => ':type aggiornato!',
        'deleted'           => ':type eliminato!',
        'duplicated'        => ':type duplicato!',
        'imported'          => ':type importato!',
    ],
    'error' => [
        'payment_add'       => 'Errore: Non è possibile aggiungere il pagamento! Si dovrebbe verificare di aggiungere la quantità.',
        'not_user_company'  => 'Errore: Non hai i permessi per gestire questa azienda!',
        'customer'          => 'Errore: Non è stato possibile creare l\'utente! :name usa questo indirizzo e-mail.',
        'no_file'           => 'Errore: Nessun file selezionato!',
    ],
    'warning' => [
        'deleted'           => 'Attenzione: Non è consentito eliminare <b>:name</b> perché ha :text collegato.',
        'disabled'          => 'Attenzione: Non è consentito disabilitare <b>:name</b> perché ha :text collegato.',
    ],

];
