<?php

return [

    'next'                  => 'Volgende',
    'refresh'               => 'Vernieuwen',

    'steps' => [
        'requirements'      => 'Vraag uw hostingprovider om de fout(en) op te lossen!',
        'language'          => 'Stap 1/3: Taalkeuze',
        'database'          => 'Stap 2/3: Database Installatie',
        'settings'          => 'Stap 3/3: Gegevens van Bedrijf en Beheerder',
    ],

    'language' => [
        'select'            => 'Taal selecteren',
    ],

    'requirements' => [
        'enabled'           => ':feature moet worden ingeschakeld!',
        'disabled'          => ':feature moet worden uitgeschakeld!',
        'extension'         => ':extension extentie moet worden geïnstalleerd en geladen!',
        'directory'         => ':directory map moet schrijfbaar zijn!',
        'executable'        => 'De PHP CLI uitvoerbare bestand is niet gedefinieerd/werkbaar, of de versie is niet :php_version of hoger! Vraag uw hostingbedrijf om PHP_BINARY of PHP_PATH omgeving correct in te stellen.',
    ],

    'database' => [
        'hostname'          => 'Hostnaam',
        'username'          => 'Gebruikersnaam',
        'password'          => 'Wachtwoord',
        'name'              => 'Database',
    ],

    'settings' => [
        'company_name'      => 'Bedrijfsnaam',
        'company_email'     => 'E-mailadres bedrijf',
        'admin_email'       => 'E-mailadres beheerder',
        'admin_password'    => 'Wachtwoord van beheerder',
    ],

    'error' => [
        'php_version'       => 'Fout: Vraag uw hostingprovider om PHP :php_version of hoger te gebruiken voor zowel HTTP als CLI.',
        'connection'        => 'Fout: Kan geen verbinding met de database! Controleer of alle gegevens juist zijn.',
    ],

];
