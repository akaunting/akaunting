<?php

return [

    'auth'                  => 'Autentificare',
    'profile'               => 'Profil',
    'logout'                => 'Deconectare',
    'login'                 => 'Conectare',
    'forgot'                => 'Uitat',
    'login_to'              => 'Conectează-te pentru a începe sesiunea',
    'remember_me'           => 'Ţine-mă minte',
    'forgot_password'       => 'Am uitat parola',
    'reset_password'        => 'Resetează parola',
    'change_password'       => 'Schimbă parola',
    'enter_email'           => 'Introduceți adresa dvs. de Email',
    'current_email'         => 'Adresa de Email actuală',
    'reset'                 => 'Resetare',
    'never'                 => 'niciodată',
    'landing_page'          => 'Pagina de destinație',
    'personal_information'  => 'Informații personale',
    'register_user'         => 'Înregistrare utilizator',
    'register'              => 'Înregistrare',

    'form_description' => [
        'personal'          => 'Linkul de invitație va fi trimis noului utilizator, așadar asigură-te că adresa de e-mail este corectă. Ei vor putea să-și introducă parola.',
        'assign'            => 'Utilizatorul va avea acces la companiile selectate. Poți restricționa permisiunile din pagina <a href=":url" class="border-b border-black">roluri</a>.',
        'preferences'       => 'Selectează limba implicită a utilizatorului. De asemenea, poți seta pagina de destinație după autentificarea utilizatorului.',
    ],

    'password' => [
        'pass'              => 'Parolă',
        'pass_confirm'      => 'Confirmarea parolei',
        'current'           => 'Parola',
        'current_confirm'   => 'Confirmă parola',
        'new'               => 'Parola nouă',
        'new_confirm'       => 'Confirmă parola nouă',
    ],

    'error' => [
        'self_delete'       => 'Eroare: Nu îți poți șterge singur contul!',
        'self_disable'      => 'Eroare: Nu îți poți șterge singur contul!',
        'unassigned'        => 'Eroare: Nu se poate anula atribuirea companiei! :companiei trebuie să i se aloce cel puțin un utilizator.',
        'no_company'        => 'Eroare: Nici o companie nu este atribuită contului. Contactează administratorul de sistem.',
    ],

    'login_redirect'        => 'Verificare finalizată! Ești redirecționat...',
    'failed'                => 'Datele de identificare nu pot fi confirmate.',
    'throttle'              => 'Prea multe încercări de intrare în cont. Poți încerca din nou peste :seconds secunde.',
    'disabled'              => 'Acest cont este dezactivat. Vă rugăm contactați administratorul de sistem.',

    'notification' => [
        'message_1'         => 'Primești acest Email pentru că a fost făcută o cerere de resetare a parolei pentru contul tău.',
        'message_2'         => 'Dacă nu ai cerut resetarea parolei, nu este necesară o acțiune suplimentară.',
        'button'            => 'Resetare Parolă',
    ],

    'invitation' => [
        'message_1'         => 'Primești acest email deoarece ești invitat să te alături în Akaunting.',
        'message_2'         => 'Dacă nu dorești să te alături, nu este necesară nicio acțiune suplimentară.',
        'button'            => 'Să începem',
    ],

    'information' => [
        'invoice'           => 'Creează facturi ușor',
        'reports'           => 'Obține rapoarte detaliate',
        'expense'           => 'Urmărește orice cheltuială',
        'customize'         => 'Personalizează-ți Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrator',
            'description'   => 'Aceștia au acces deplin la Akaunting-ul tău, inclusiv la clienți, facturi, rapoarte, setări și aplicații.',
        ],
        'manager' => [
            'name'          => 'Manager',
            'description'   => 'Obțin acces deplin la Akauntingul tău, dar nu pot gestiona utilizatorii și aplicațiile.',
        ],
        'customer' => [
            'name'          => 'Client',
            'description'   => 'Ei pot accesa Portalul Clientului și își pot plăti facturile online prin metodele de plată pe care le-ai configurat.',
        ],
        'accountant' => [
            'name'          => 'Contabil',
            'description'   => 'Aceștia pot accesa facturi, tranzacții, rapoarte și pot crea înregistrări în jurnal.',
        ],
        'employee' => [
            'name'          => 'Angajat',
            'description'   => 'Aceștia pot crea declarații de cheltuieli și pot urmări timpul pentru proiectele alocate, dar își pot vedea doar propriile informații.',
        ],
    ],

];
