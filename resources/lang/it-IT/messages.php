<?php

return [

    'success' => [
        'added'             => ':type aggiunto!',
        'updated'           => ':type aggiornato!',
        'deleted'           => ':type eliminato!',
        'duplicated'        => ':type duplicato!',
        'imported'          => ':type importato!',
        'exported'          => ':tipo esportato!',
        'enabled'           => ':type abilitato!',
        'disabled'          => ':type disabilitato!',
    ],

    'error' => [
        'over_payment'      => 'Errore: pagamento non aggiunto! L\'importo inserito supera il totale: :amount',
        'not_user_company'  => 'Errore: Non hai i permessi per gestire questa azienda!',
        'customer'          => 'Errore: Utente non creato! :name usa già questo indirizzo email.',
        'no_file'           => 'Errore: Nessun file selezionato!',
        'last_category'     => 'Errore: Non è possibile eliminare l\'ultimo categoria di :type!',
        'change_type'       => 'Errore: non è possibile cambiare il tipo perchè il tipo è :text collegato!',
        'invalid_apikey'    => 'Errore: La chiave API inserita non è valida!',
        'import_column'     => 'Errore: :message Foglio nome: :sheet. Riga numero: :line.',
        'import_sheet'      => 'Errore: Il nome del foglio non è valido. Vi preghiamo di controllare il file di esempio.',
    ],

    'warning' => [
        'deleted'           => 'Attenzione: Non è consentito eliminare <b>:name</b> perché ha :text collegato.',
        'disabled'          => 'Attenzione: Non è consentito disabilitare <b>:name</b> perché ha :text collegato.',
        'reconciled_tran'   => 'Attenzione: Non è consentito modificare/eliminare la transazione perché è riconciliata!',
        'reconciled_doc'    => 'Attenzione: Non sei autorizzato a cambiare/eliminare :type perché ha riconciliato le transazioni!',
        'disable_code'      => 'Avviso: Non è consentito disabilitare o modificare la valuta di <b>:nome</b> perché ha: testo correlato.',
        'payment_cancel'    => 'Attenzione: hai annullato il tuo ultimo pagamento con :method !',
    ],

];
