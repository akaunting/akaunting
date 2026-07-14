<?php

return [

    'success' => [
        'added'             => ':type aggiunto!',
        'created'            => ':type creato!',
        'updated'           => ':type aggiornato!',
        'deleted'           => ':type eliminato!',
        'duplicated'        => ':type duplicato!',
        'imported'          => ':type importato!',
        'import_queued'     => ':type è stata pianificata! Riceverai un\'e-mail al termine.',
        'exported'          => ':type esportato!',
        'export_queued'     => ':type è stata programmata l\'esportazione della pagina corrente! Riceverai un\'e-mail quando sarà pronto per il download.',
        'download_queued'   => ':type Il download della pagina corrente è stato programmato! Riceverai un\'e-mail quando sarà pronto per il download.',
        'enabled'           => ':type abilitato!',
        'disabled'          => ':type disabilitato!',
        'connected'         => ':type connesso!',
        'invited'           => ':type invitato!',
        'ended'             => ':type terminato!',

        'clear_all'         => 'Ottimo! Hai cancellato tutti i tuoi :type.',
    ],

    'error' => [
        'over_payment'      => 'Errore: pagamento non aggiunto! L\'importo inserito supera il totale: :amount',
        'not_user_company'  => 'Errore: Non hai i permessi per gestire questa azienda!',
        'customer'          => 'Errore: Utente non creato! :name usa già questo indirizzo email.',
        'no_file'           => 'Errore: Nessun file selezionato!',
        'last_category'     => ': impossibile eliminare l\'ultima categoria <b>:type</b>!
Errore',
        'transfer_category' => ': impossibile eliminare la categoria di trasferimento <b>:type</b>!
Errore',
        'change_type'       => 'Errore: non è possibile cambiare il tipo perchè il tipo è :text collegato!',
        'invalid_apikey'    => 'Errore: La chiave API inserita non è valida!',
        'empty_apikey'      => 'Errore: non hai inserito la chiave API! <a href=":url" class="font-bold underline underline-offset-4">Fai clic qui</a> per inserire la chiave API.',
        'import_column'     => 'Errore: :message Nome colonna: :column. Numero di riga: :line.',
        'import_sheet'      => 'Errore: Il nome del foglio non è valido. Vi preghiamo di controllare il file di esempio.',
        'same_amount'       => ': l\'importo totale della suddivisione deve essere esattamente uguale al totale :transaction: :amount',
        'over_match'        => 'Errore: :type non connesso! L\'importo inserito non può superare il totale del pagamento: :amount',
    ],

    'warning' => [
        'deleted'           => 'Attenzione: Non è consentito eliminare <b>:name</b> perché ha :text collegato.',
        'disabled'          => 'Attenzione: Non è consentito disabilitare <b>:name</b> perché ha :text collegato.',
        'reconciled_tran'   => 'Attenzione: Non è consentito modificare/eliminare la transazione perché è riconciliata!',
        'reconciled_doc'    => 'Attenzione: Non sei autorizzato a cambiare/eliminare :type perché ha riconciliato le transazioni!',
        'disable_code'      => 'Attenzione: non è consentito disabilitare o modificare la valuta di <b>:name</b> perché è correlata a :text.',
        'payment_cancel'    => 'Attenzione: hai annullato il tuo ultimo pagamento con :method !',
        'missing_transfer'  => 'Attenzione: manca il bonifico relativo a questa transazione. Dovresti considerare l\'eliminazione di questa transazione.',
        'connect_tax'       => 'Attenzione: questo :type ha un importo fiscale. Le tasse aggiunte a :type non possono essere collegate, quindi la tassa verrà aggiunta al totale e calcolata di conseguenza.',
        'contact_change'    => 'Attenzione: non è consentito modificare il contatto su un :type che è già stato inviato, ricevuto o pagato!
Chiave API',
    ],

];
