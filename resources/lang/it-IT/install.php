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
        'connection'        => 'Errore: Impossibile connettersi al database! Per favore, assicurati che i dettagli siano corretti.',
    ],

];
