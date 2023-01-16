<?php

return [

    'auth'                  => 'Autentisering',
    'profile'               => 'Profil',
    'logout'                => 'Logga ut',
    'login'                 => 'Logga in',
    'forgot'                => 'Glömt',
    'login_to'              => 'Logga in för att starta din session',
    'remember_me'           => 'Kom ihåg mig',
    'forgot_password'       => 'Jag har glömt mitt lösenord',
    'reset_password'        => 'Återställ lösenord',
    'change_password'       => 'Byt Lösenord',
    'enter_email'           => 'Ange din e-postadress',
    'current_email'         => 'Aktuella e-post',
    'reset'                 => 'Återställ',
    'never'                 => 'aldrig',
    'landing_page'          => 'Landningssida',
    'personal_information'  => 'Personuppgifter',
    'register_user'         => 'Registrera Användare',
    'register'              => 'Registrera',

    'form_description' => [
        'personal'          => 'Inbjudningslänken kommer att skickas till den nya användaren, så se till att e-postadressen är korrekt. De kommer att kunna välja sitt lösenord.',
        'assign'            => 'Användaren kommer att ha tillgång till de valda företagen. Du kan begränsa behörigheterna från sidan med <a href=":url" class="border-b border-black">roller</a>.',
        'preferences'       => 'Välj standardspråk för användaren. Du kan också ställa in landningssidan användaren ser efter inloggning.',
    ],

    'password' => [
        'pass'              => 'Lösenord',
        'pass_confirm'      => 'Bekräfta lösenord',
        'current'           => 'Nuvarande lösenord',
        'current_confirm'   => 'Bekräfta nuvarande lösenord',
        'new'               => 'Nytt lösenord',
        'new_confirm'       => 'Ny lösenords bekräftelse',
    ],

    'error' => [
        'self_delete'       => 'Fel: Kan inte ta bort dig själv!',
        'self_disable'      => 'Fel: Kan inte ta bort dig själv!',
        'unassigned'        => 'Fel: Kan inte ta bort tilldelning för företag! Företaget: :company måste vara tilldelat till åtminstone en användare.',
        'no_company'        => 'Fel: Inget företag som tilldelats ditt konto. Kontakta systemadministratören.',
    ],

    'login_redirect'        => 'Verifiering klar! Du blir nu omdirigerad...',
    'failed'                => 'Dessa uppgifter stämmer inte överens med vårt register.',
    'throttle'              => 'För många inloggningsförsök. Var vänlig försök igen om :seconds sekunder.',
    'disabled'              => 'Detta konto är inaktiverat. Kontakta systemadministratören.',

    'notification' => [
        'message_1'         => 'Du får detta mail eftersom vi fått en begäran om återställning av lösenord för ditt konto.',
        'message_2'         => 'Om du inte har begärt en lösenordsåterställning, krävs ingen ytterligare åtgärd.',
        'button'            => 'Återställ lösenordet',
    ],

    'invitation' => [
        'message_1'         => 'Du får detta e-postmeddelande eftersom du är inbjuden att gå med i Akaunting.',
        'message_2'         => 'Om du inte vill gå med krävs inga ytterligare åtgärder.',
        'button'            => 'Kom igång',
    ],

    'information' => [
        'invoice'           => 'Skapa fakturor enkelt',
        'reports'           => 'Få detaljerade redovisningsrapporter',
        'expense'           => 'Spåra alla kostnader och utgifter',
        'customize'         => 'Anpassa Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administratör',
            'description'   => 'De får full tillgång till din Akaunting inklusive kunder, fakturor, rapporter, inställningar och appar.',
        ],
        'manager' => [
            'name'          => 'Föreståndare',
            'description'   => 'De får full tillgång till din Akaunting, men kan inte hantera användare och appar.',
        ],
        'customer' => [
            'name'          => 'Kund',
            'description'   => 'De kan komma åt Kundportalen och betala sina fakturor online via de betalningsmetoder du har konfigurerat.',
        ],
        'accountant' => [
            'name'          => 'Revisor',
            'description'   => 'De kan komma åt fakturor, transaktioner, rapporter och skapa bokföringsposter.',
        ],
        'employee' => [
            'name'          => 'Anställd',
            'description'   => 'De kan skapa utlägg och rapportera tid för tilldelade projekt, men kan bara se sin egen information.',
        ],
    ],

];
