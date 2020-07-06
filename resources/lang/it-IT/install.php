<?php

return [

    'next'                  => 'Successivo',
    'refresh'               => 'Aggiorna',

    'steps' => [
        'requirements'      => 'Per favore, chiedi al tuo fornitore di hosting di correggere gli errori!',
        'language'          => 'Passo 1/3: Selezione della lingua',
        'database'          => 'Passo 2/3: Configurazione del Database',
        'settings'          => 'Passo 3/3: Azienda e dettagli Amministrazione',
    ],

    'language' => [
        'select'            => 'Seleziona la lingua',
    ],

    'requirements' => [
        'enabled'           => ':feature deve essere abilitata!',
        'disabled'          => ':feature deve essere disabilitata!',
        'extension'         => ':extension estensione deve essere installata e caricata!',
        'directory'         => 'la cartella :directory deve essere scrivibile!',
        'executable'        => 'Il file eseguibile PHP CLI non è definito/non funziona o la versione non è :php_version o superiore! Si prega di chiedere alla società di hosting di impostare correttamente la variabile d\'ambiente PHP_BINARY o PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Hostname',
        'username'          => 'Username',
        'password'          => 'Password',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Nome Azienda',
        'company_email'     => 'Email azienda',
        'admin_email'       => 'E-mail dell\'amministratore',
        'admin_password'    => 'Password amministratore:',
    ],

    'error' => [
        'php_version'       => 'Errore: chiedi al tuo provider di usare PHP :php_version o superiore sia per HTTP che per CLI.',
        'connection'        => 'Errore: Impossibile connettersi al database! Per favore, assicurati che i dettagli siano corretti.',
    ],

];
