<?php

return [

    'auth'                  => 'Godkendelse',
    'profile'               => 'Profil',
    'logout'                => 'Log ud',
    'login'                 => 'Log ind',
    'forgot'                => 'Glemt',
    'login_to'              => 'Log ind for at starte din session',
    'remember_me'           => 'Husk mig',
    'forgot_password'       => 'Jeg har glemt min adgangskode',
    'reset_password'        => 'Nulstil adgangskode',
    'change_password'       => 'Skift adgangskode',
    'enter_email'           => 'Indtast din mailadresse',
    'current_email'         => 'Nuværende e-mail',
    'reset'                 => 'Nulstil',
    'never'                 => 'Aldrig',
    'landing_page'          => 'Startside',
    'personal_information'  => 'Personlig information',
    'register_user'         => 'Registrerer Bruger',
    'register'              => 'Registrer',

    'form_description' => [
        'personal'          => 'Invitationslinket vil blive sendt til den nye bruger, så sørg for at e-mail-adressen er korrekt. De vil være i stand til at indtaste deres adgangskode.',
        'assign'            => 'Brugeren vil have adgang til de valgte virksomheder. Du kan begrænse tilladelserne fra siden <a href=":url" class="border-b border-black">roller</a>.',
        'preferences'       => 'Vælg brugerens standardsprog. Du kan også indstille startsiden efter at brugeren logger ind.',
    ],

    'password' => [
        'pass'              => 'Adgangskode',
        'pass_confirm'      => 'Bekræft adgangskode',
        'current'           => 'Adgangskode',
        'current_confirm'   => 'Bekræft adgangskode',
        'new'               => 'Ny adgangskode',
        'new_confirm'       => 'Bekræft ny adgangskode',
    ],

    'error' => [
        'self_delete'       => 'Fejl: Du kan ikke slette dig selv!',
        'self_disable'      => 'Fejl: Du kan ikke deaktivere dig selv!',
        'unassigned'        => 'Fejl: Kan ikke fjerne tildelingen af virksomheden! Firmaet skal tildeles mindst én bruger.',
        'no_company'        => 'Fejl: Ingen enhed er tilknyttet din konto. Kontakt systemadministratoren.',
    ],

    'login_redirect'        => 'Godkendt! Du bliver omdirigeret...',
    'failed'                => 'Disse legitimationsoplysninger passer ikke med de gemte.',
    'throttle'              => 'For mange loginforsøg. Prøv igen om :seconds sekunder.',
    'disabled'              => 'Denne konto er deaktiveret. Kontakt systemadministratoren.',

    'notification' => [
        'message_1'         => 'Du modtager denne e-mail, fordi vi har modtaget en anmodning om nulstilling af adgangskoden til din konto.',
        'message_2'         => 'Hvis du ikke har bedt om en nulstilling af adgangskoden, skal du ikke gøre yderligere.',
        'button'            => 'Nulstil adgangskode',
    ],

    'invitation' => [
        'message_1'         => 'Du modtager denne e-mail, fordi du er inviteret til at deltage i Akaunting.',
        'message_2'         => 'Hvis du ikke ønsker at deltage, er der ikke behov for yderligere handling.',
        'button'            => 'Kom i gang',
    ],

    'information' => [
        'invoice'           => 'Opret nemt fakturaer',
        'reports'           => 'Hent detaljerede rapporter',
        'expense'           => 'Spor enhver udgift',
        'customize'         => 'Tilpas din Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Admin',
            'description'   => 'De får fuld adgang til din Akaunting herunder kunder, fakturaer, rapporter, indstillinger og apps.',
        ],
        'manager' => [
            'name'          => 'Leder',
            'description'   => 'De får fuld adgang til din Akaunting, men kan ikke administrere brugere og apps.',
        ],
        'customer' => [
            'name'          => 'Kunde',
            'description'   => 'De får adgang til kundeportalen og kan betale deres fakturaer online via de betalingsmetoder, du har opsat.',
        ],
        'accountant' => [
            'name'          => 'Revisor',
            'description'   => 'De kan få adgang til fakturaer, transaktioner, rapporter og oprette journalposter.',
        ],
        'employee' => [
            'name'          => 'Medarbejder',
            'description'   => 'De kan lave udgiftsfordringer og spore tid for tildelte projekter, men kan kun se deres egne oplysninger.',
        ],
    ],

];
