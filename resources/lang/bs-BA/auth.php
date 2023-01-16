<?php

return [

    'auth'                  => 'Autentikacija',
    'profile'               => 'Profil',
    'logout'                => 'Odjava',
    'login'                 => 'Prijava',
    'forgot'                => 'Zaboravljeno',
    'login_to'              => 'Prijavite se da biste otpočeli sesiju',
    'remember_me'           => 'Zapamti me',
    'forgot_password'       => 'Zaboravio/la sam lozinku',
    'reset_password'        => 'Resetiraj lozinku',
    'change_password'       => 'Promijenite lozinku',
    'enter_email'           => 'Unesite svoju adresu e-pošte',
    'current_email'         => 'Vaš email',
    'reset'                 => 'Poništi',
    'never'                 => 'nikada',
    'landing_page'          => 'Odredišna stranica',
    'personal_information'  => 'Lične informacije',
    'register_user'         => 'Registracija korisnika',
    'register'              => 'Registracija',

    'form_description' => [
        'personal'          => 'Link pozivnice će biti poslan novom korisniku, pa se uvjerite da je adresa e-pošte ispravna. Oni će moći da unesu svoju lozinku.',
        'assign'            => 'Korisnik će imati pristup odabranim firmama. Možete ograničiti dozvole sa stranice <a href=":url" class="border-b border-black">uloga</a>.',
        'preferences'       => 'Odaberite zadani jezik korisnika. Također možete postaviti odredišnu stranicu nakon što se korisnik prijavi.',
    ],

    'password' => [
        'pass'              => 'Lozinka',
        'pass_confirm'      => 'Potvrda lozinke',
        'current'           => 'Lozinka',
        'current_confirm'   => 'Potvrda lozinke',
        'new'               => 'Nova lozinka',
        'new_confirm'       => 'Potvrda lozinke',
    ],

    'error' => [
        'self_delete'       => 'Greška: Ne možete izbrisati sami sebe!',
        'self_disable'      => 'Greška: Ne možete sami sebe onemogućiti!',
        'no_company'        => 'Greška: Nijedna firma nije dodjeljena Vašem računu. Molimo Vas da kontaktirate sistem administratora.',
    ],

    'login_redirect'        => 'Završena je provjera! Sada cećete biti prdlijeđeni ...',
    'failed'                => 'Uneseni podaci ne odgovaraju onima u našoj bazi.',
    'throttle'              => 'Previše pogrešnih unosa. Molimo pokušajte ponovo nakon :seconds sekundi.',
    'disabled'              => 'Račun je onemogućen. Molimo Vas kontaktirajte sistemskog administratora.',

    'notification' => [
        'message_1'         => 'Zaprimili ste ovaj email zato što ste tražili poništavanje lozinke za Vaš račun.',
        'message_2'         => 'Ukoliko lično Vi niste zatražili poništavanje lozinke, nije potreban niti jedan dodatni korak.',
        'button'            => 'Poništi lozinku',
    ],

    'invitation' => [
        'message_1'         => 'Primili ste ovaj email jer ste pozvani da se pridružite Akauntingu.',
        'message_2'         => 'Ako ne želite da se pridružite, nisu potrebne dodatne radnje.',
        'button'            => 'Započni',
    ],

    'information' => [
        'invoice'           => 'Lako kreirajte fakture',
        'reports'           => 'Dobijte detaljne izvještaje',
        'expense'           => 'Pratite sve troškove',
        'customize'         => 'Prilagodite svoj Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Admin',
            'description'   => 'Oni dobijaju potpuni pristup vašem Akaunting-u uključujući klijente, fakture, izvještaje, postavke i aplikacije.',
        ],
        'manager' => [
            'name'          => 'Upravljaj',
            'description'   => 'Oni imaju potpuni pristup vašem Akauntingu, ali ne mogu upravljati korisnicima i aplikacijama.',
        ],
        'customer' => [
            'name'          => 'Kupci',
            'description'   => 'Oni mogu pristupiti portalu za klijente i plaćati svoje fakture na mreži putem načina plaćanja koje ste postavili.',
        ],
        'accountant' => [
            'name'          => 'Računovođa',
            'description'   => 'Oni mogu pristupiti fakturama, transakcijama, izvještajima i kreirati unose u dnevnik.',
        ],
        'employee' => [
            'name'          => 'Zaposlenik',
            'description'   => 'Oni mogu kreirati zahtjeve za troškovima i pratiti vrijeme za dodijeljene projekte, ali mogu vidjeti samo svoje vlastite informacije.',
        ],
    ],

];
