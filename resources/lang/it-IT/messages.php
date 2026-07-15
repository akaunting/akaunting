<?php

return [

    'success' => [
        'added'             => ':type aggiunto!',
        'created'            => ':type creato!',
        'updated'           => ':type aggiornato!',
        'deleted'           => ':type eliminato!',
        'duplicated'        => ':type duplicato!',
        'imported'          => ':type importato!',
        'import_queued'     => ':type importazione pianificata! Riceverai un\'email al termine.',
        'exported'          => ':type esportato!',
        'export_queued'     => 'L\'esportazione di :type della pagina corrente è stata pianificata! Riceverai un\'email quando sarà pronta per il download.',
        'download_queued'   => 'Il download di :type della pagina corrente è stato pianificato! Riceverai un\'email quando sarà pronto per il download.',
        'enabled'           => ':type abilitato!',
        'disabled'          => ':type disabilitato!',
        'connected'         => ':type connesso!',
        'invited'           => ':type invitato!',
        'ended'             => ':type terminato!',

        'clear_all'         => 'Ottimo! Hai cancellato tutti i tuoi :type.',
    ],

    'error' => [
        'over_payment'      => 'Errore: Pagamento non aggiunto! L\'importo inserito supera il totale: :amount',
        'not_user_company'  => 'Errore: Non sei autorizzato a gestire questa azienda!',
        'customer'          => 'Errore: Utente non creato! :name usa già questo indirizzo email.',
        'no_file'           => 'Errore: Nessun file selezionato!',
        'last_category'     => 'Errore: Impossibile eliminare l\'ultima categoria <b>:type</b>!',
        'transfer_category' => 'Errore: Impossibile eliminare la categoria di trasferimento <b>:type</b>!',
        'change_type'       => 'Errore: Impossibile cambiare il tipo perché ha :text collegato!',
        'invalid_apikey'    => 'Errore: La chiave API inserita non è valida!',
        'empty_apikey'      => 'Errore: Non hai inserito la chiave API! <a href=":url" class="font-bold underline underline-offset-4">Fai clic qui</a> per inserire la chiave API.',
        'import_column'     => 'Errore: :message Nome colonna: :column. Numero di riga: :line.',
        'import_sheet'      => 'Errore: Il nome del foglio non è valido. Si prega di controllare il file di esempio.',
        'same_amount'       => 'Errore: L\'importo totale dello scorporo deve essere esattamente uguale al totale della transazione :transaction: :amount',
        'over_match'        => 'Errore: :type non connesso! L\'importo inserito non può superare il totale del pagamento: :amount',
    ],

    'warning' => [
        'deleted'           => 'Avviso: Non sei autorizzato a eliminare <b>:name</b> perché ha :text collegato.',
        'disabled'          => 'Avviso: Non sei autorizzato a disabilitare <b>:name</b> perché ha :text collegato.',
        'reconciled_tran'   => 'Avviso: Non sei autorizzato a modificare/eliminare la transazione perché è riconciliata!',
        'reconciled_doc'    => 'Avviso: Non sei autorizzato a modificare/eliminare :type perché ha transazioni riconciliate!',
        'disable_code'      => 'Avviso: Non sei autorizzato a disabilitare o cambiare la valuta di <b>:name</b> perché ha :text collegato.',
        'payment_cancel'    => 'Avviso: Hai annullato il tuo ultimo pagamento con :method!',
        'missing_transfer'  => 'Avviso: Manca il trasferimento relativo a questa transazione. Dovresti considerare di eliminare questa transazione.',
        'connect_tax'       => 'Avviso: Questo :type ha un importo di imposta. Le imposte aggiunte a :type non possono essere collegate, quindi l\'imposta verrà aggiunta al totale e calcolata di conseguenza.',
        'contact_change'    => 'Avviso: Non sei autorizzato a modificare il contatto su un :type che è già stato inviato, ricevuto o pagato!',
    ],

];
