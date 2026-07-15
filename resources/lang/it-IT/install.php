<?php

return [

    'next'                  => 'Successivo',
    'refresh'               => 'Aggiorna',

    'steps' => [
        'requirements'      => 'Per favore, chiedi al tuo fornitore di hosting di correggere gli errori!',
        'language'          => 'Passo 1/3: Selezione della lingua',
        'database'          => 'Passo 2/3: Configurazione del database',
        'settings'          => 'Passo 3/3: Azienda e dettagli amministratore',
    ],

    'language' => [
        'select'            => 'Seleziona la lingua',
    ],

    'requirements' => [
        'enabled'           => ':feature deve essere abilitata!',
        'disabled'          => ':feature deve essere disabilitata!',
        'extension'         => 'L\'estensione :extension deve essere installata e caricata!',
        'directory'         => 'La cartella :directory deve essere scrivibile!',
        'executable'        => 'Il file eseguibile PHP CLI non è definito/non funziona o la sua versione non è :php_version o superiore! Si prega di chiedere alla società di hosting di impostare correttamente la variabile d\'ambiente PHP_BINARY o PHP_PATH.',
        'npm'               => '<b>File JavaScript mancanti!</b> <br><br><span>Dovresti eseguire i comandi <em class="underline">npm install</em> e <em class="underline">npm run dev</em>.</span>',
    ],

    'database' => [
        'hostname'          => 'Nome host',
        'username'          => 'Nome utente',
        'password'          => 'Password',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Nome azienda',
        'company_email'     => 'Email azienda',
        'admin_email'       => 'Email amministratore',
        'admin_password'    => 'Password amministratore',
    ],

    'error' => [
        'php_version'       => 'Errore: Chiedi al tuo provider di usare PHP :php_version o superiore sia per HTTP che per CLI.',
        'connection'        => 'Errore: Impossibile connettersi al database! Per favore, assicurati che i dettagli siano corretti.',
    ],

    'update' => [
        'core'              => 'È disponibile una nuova versione di Akaunting! Per favore, aggiorna <a href=":url">la tua installazione.</a>',
        'module'            => 'È disponibile una nuova versione di :module! Per favore, aggiorna <a href=":url">la tua installazione.</a>',
    ],
];
