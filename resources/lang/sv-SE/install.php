<?php

return [

    'next'                  => 'Nästa',
    'refresh'               => 'Uppdatera',

    'steps' => [
        'requirements'      => 'Snälla, be webbhotellet åtgärda felen!',
        'language'          => 'Steg 1/3: Språkval',
        'database'          => 'Steg 2/3: Databasinställningar',
        'settings'          => 'Steg 3/3: Företag och Admin uppgifter',
    ],

    'language' => [
        'select'            => 'Välj språk',
    ],

    'requirements' => [
        'enabled'           => ':feature måste vara aktiverad!',
        'disabled'          => ':feature måste inaktiveras!',
        'extension'         => ':extension tillägget måste vara installerad och laddad!',
        'directory'         => ':directory katalogen måste vara skrivbar!',
        'executable'        => 'PHP CLI körbar fil är inte definierad/fungerar eller dess version är inte :php_version eller högre! Be ditt webbhotellföretag att ställa in miljövariabeln PHP_BINARY eller PHP_PATH på rätt sätt.',
        'npm'               => '<b>Saknade JavaScript-filer!</b> <br><br><span>Du bör köra <em class="underline">npm install</em> och <em class="underline">npm run dev</em> kommandona.</span>', 
    ],

    'database' => [
        'hostname'          => 'Hostnamn',
        'username'          => 'Användarnamn',
        'password'          => 'Lösenord',
        'name'              => 'Databas',
    ],

    'settings' => [
        'company_name'      => 'Företagets namn',
        'company_email'     => 'Företagets e-post',
        'admin_email'       => 'Admin e-postadress',
        'admin_password'    => 'Admin lösenord',
    ],

    'error' => [
        'php_version'       => 'Fel: Be ditt webbhotell leverantör att använda PHP :php_version eller högre för både HTTP och CLI.',
        'connection'        => 'Fel: Kunde inte ansluta till databasen! Snälla, se till att uppgifterna stämmer.',
    ],

    'update' => [
        'core'              => 'Ny Akaunting-version tillgänglig! Vänligen uppdatera <a href=":url">din installation.</a>',
        'module'            => 'Ny version av :module tillgänglig! Vänligen uppdatera din installation.',
    ],
];
