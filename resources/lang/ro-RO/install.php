<?php

return [

    'next'                  => 'Mai departe',
    'refresh'               => 'Reîncarcă',

    'steps' => [
        'requirements'      => 'Va rugam, cere furnizorului serviciilor de hosting sa rezolve erorile!',
        'language'          => 'Pasul 1/3: Selectati limba',
        'database'          => 'Pasul 2/3: Configurarea bazei de date',
        'settings'          => 'Pasul 3/3: Detalii despre Companie şi Administrator',
    ],

    'language' => [
        'select'            => 'Selectează limba',
    ],

    'requirements' => [
        'enabled'           => ':feature trebuie să fie activat/a!',
        'disabled'          => ':feature trebuie să fie dezactivat/a!',
        'extension'         => 'Extensia :extension trebuie sa fie instalata si incarcata!',
        'directory'         => 'directorul :directory trebuie să permita scrierea!',
        'executable'        => 'Fișierul executabil PHP CLI nu este definit/funcțional sau versiunea sa nu este :php_version ori mai mare! Vă rugăm cereți companiei dumneavoastră de găzduire să seteze corect variabila de mediu PHP_BINAR sau PHP_PATH.',
        'npm'               => '<b>Fișierele JavaScript lipsesc !</b> <br><br><span>Ar trebui să rulezi <em class="underline">npm install</em> și <em class="underline">npm run dev</em> commands.</span>', 
    ],

    'database' => [
        'hostname'          => 'Denumire gazdă',
        'username'          => 'Utilizator',
        'password'          => 'Parola',
        'name'              => 'Bază de date',
    ],

    'settings' => [
        'company_name'      => 'Numele firmei',
        'company_email'     => 'Email',
        'admin_email'       => 'E-mail administrator',
        'admin_password'    => 'Parolă administrator',
    ],

    'error' => [
        'php_version'       => 'Eroare: Cereți furnizorului de găzduire să folosească PHP :php_version sau mai mare atât pentru HTTP cat si CLI.',
        'connection'        => 'Eroare: Nu s-a putut conecta la baza de date! Te rugam sa te asiguri ca detaliile sunt corecte.',
    ],

    'update' => [
        'core'              => 'Este disponibilă o nouă versiune! Te rog să actualizezi instalarea.',
        'module'            => ':o nouă versiune a modulului este disponibilă! Te rog să actualizezi instalarea.',
    ],
];
