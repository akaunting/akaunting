<?php

return [

    'auth'                  => 'Authenticatie',
    'profile'               => 'Profiel',
    'logout'                => 'Afmelden',
    'login'                 => 'Inloggen',
    'forgot'                => 'Vergeten',
    'login_to'              => 'Login om uw sessie te starten',
    'remember_me'           => 'Onthoud mijn gegevens',
    'forgot_password'       => 'Ik ben mijn wachtwoord vergeten',
    'reset_password'        => 'Wachtwoord resetten',
    'change_password'       => 'Wachtwoord wijzigen',
    'enter_email'           => 'Voer uw e-mailadres in',
    'current_email'         => 'Huidig E-mailadres',
    'reset'                 => 'Resetten',
    'never'                 => 'nooit',
    'landing_page'          => 'Landingspagina',
    'personal_information'  => 'Persoonlijke Informatie',
    'register_user'         => 'Registreer een gebruiker',
    'register'              => 'Registreren',

    'form_description' => [
        'personal'          => 'De uitnodigingslink wordt naar de nieuwe gebruiker gestuurd, dus zorg ervoor dat het e-mailadres correct is. 
Zij zullen dan hun wachtwoord kunnen invoeren.',
        'assign'            => 'De gebruiker krijgt toegang tot de geselecteerde bedrijven. Je kunt de rechten beperken vanaf de <a href=":url" class="border-b border-black" >rollen</a> pagina.',
        'preferences'       => 'Selecteer de standaardtaal van de gebruiker. U kunt ook de landingspagina instellen nadat de gebruiker inlogt.',
    ],

    'password' => [
        'pass'              => 'Wachtwoord',
        'pass_confirm'      => 'Wachtwoordbevestiging',
        'current'           => 'Wachtwoord',
        'current_confirm'   => 'Wachtwoordbevestiging',
        'new'               => 'Nieuw Wachtwoord',
        'new_confirm'       => 'Nieuw Wachtwoordbevestiging',
    ],

    'error' => [
        'self_delete'       => 'Fout: u kunt uzelf niet verwijderen!',
        'self_disable'      => 'Fout: u kunt uw eigen account niet blokkeren!',
        'unassigned'        => 'Fout: Kan bedrijf niet uitschrijven! Het :company moet minstens een gebruiker toegewezen hebben.',
        'no_company'        => 'Fout: U heeft geen bedrijf gekoppeld aan uw account. Neem alstublieft contact op met de applicatiebeheerder.',
    ],

    'login_redirect'        => 'Verificatie voltooid! U wordt omgeleid...',
    'failed'                => 'Deze gegevens komen niet overeen met onze administratie.',
    'throttle'              => 'Te veel inlogpogingen. Probeer het met :seconds seconden opnieuw.',
    'disabled'              => 'Dit account is uitgeschakeld. Neem alstublieft contact op met de applicatiebeheerder.',

    'notification' => [
        'message_1'         => 'U ontvangt deze e-mail omdat wij een wachtwoord reset verzoek hebben ontvangen voor uw account.',
        'message_2'         => 'Indien u geen wachtwoord reset heeft aangevraagd, hoeft u geen verdere acties te ondernemen.',
        'button'            => 'Wachtwoord resetten',
    ],

    'invitation' => [
        'message_1'         => 'U ontvangt deze e-mail omdat u bent uitgenodigd om deel te nemen aan Akaunting.',
        'message_2'         => 'Als u niet mee wilt doen, hoeft u verder niets te doen.',
        'button'            => 'Aan de slag',
    ],

    'information' => [
        'invoice'           => 'Eenvoudig facturen aanmaken',
        'reports'           => 'Ontvang gedetailleerde rapporten',
        'expense'           => 'Volg elke uitgave',
        'customize'         => 'Uw Akaunting Aanpassen',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Admin',
            'description'   => 'Zij krijgen volledige toegang tot uw Akaunting inclusief klanten, facturen, rapporten, instellingen en apps.',
        ],
        'manager' => [
            'name'          => 'Beheerder',
            'description'   => 'Zij krijgen volledige toegang tot je Akaunting, maar kunnen geen gebruikers en apps beheren.',
        ],
        'customer' => [
            'name'          => 'Klant',
            'description'   => 'Ze hebben toegang tot het Klantenportaal en kunnen hun facturen online betalen via de betaalmethoden die je hebt ingesteld.',
        ],
        'accountant' => [
            'name'          => 'Boekhouder',
            'description'   => 'Zij hebben toegang tot facturen, transacties, rapporten en kunnen journaalposten aanmaken.',
        ],
        'employee' => [
            'name'          => 'Werknemer',
            'description'   => 'Zij kunnen de onkosten indienen en de tijd bijhouden voor toegewezen projecten, maar kunnen alleen hun eigen informatie zien.',
        ],
    ],

];
