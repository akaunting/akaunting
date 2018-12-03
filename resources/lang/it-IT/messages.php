<?php

return [

    'success' => [
        'added'             => ':type aggiunto!',
        'updated'           => ':type aggiornato!',
        'deleted'           => ':type eliminato!',
        'duplicated'        => ':type duplicato!',
        'imported'          => ':type importato!',
        'enabled'           => ':type abilitato!',
        'disabled'          => ':type disabilitato!',
    ],

    'error' => [
        'over_payment'      => 'Errore: pagamento non aggiunto! L\'importo inserito supera il totale: :amount',
        'not_user_company'  => 'Errore: Non hai i permessi per gestire questa azienda!',
        'customer'          => 'Errore: Utente non creato! :name usa già questo indirizzo email.',
        'no_file'           => 'Errore: Nessun file selezionato!',
        'last_category'     => 'Errore: Non è possibile eliminare l\'ultimo categoria di :type!',
        'invalid_token'     => 'Errore: Il token inserito non è valido!',
        'import_column'     => 'Errore: :message Foglio nome: :sheet. Riga numero: :line.',
        'import_sheet'      => 'Errore: Il nome del foglio non è valido. Vi preghiamo di controllare il file di esempio.',
    ],

    'warning' => [
        'deleted'           => 'Attenzione: Non è consentito eliminare <b>:name</b> perché ha :text collegato.',
        'disabled'          => 'Attenzione: Non è consentito disabilitare <b>:name</b> perché ha :text collegato.',
        'disable_code'      => 'Avviso: Non è consentito disabilitare o modificare la valuta di <b>:nome</b> perché ha: testo correlato.',
    ],

];
