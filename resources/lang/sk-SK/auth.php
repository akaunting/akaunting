<?php

return [

    'auth'                  => 'Autentifikácia',
    'profile'               => 'Profil',
    'logout'                => 'Odhlásiť',
    'login'                 => 'Login',
    'forgot'                => 'Zabudnuté',
    'login_to'              => 'Prihlásiť sa a začať reláciu',
    'remember_me'           => 'Zapamätať prihlásenie',
    'forgot_password'       => 'Zabudol som moje heslo',
    'reset_password'        => 'Obnovenie hesla',
    'change_password'       => 'Zmeniť heslo',
    'enter_email'           => 'Zadajte vašu e-mailovú adresu',
    'current_email'         => 'Aktuálny E-mail',
    'reset'                 => 'Reset',
    'never'                 => 'nikdy',
    'landing_page'          => 'Úvodná stránka',
    'personal_information'  => 'Osobné údaje',
    'register_user'         => 'Registrácia',
    'register'              => 'Registrovať',

    'form_description' => [
        'personal'          => 'Odkaz na pozvánku bude odoslaný novému používateľovi, preto skontrolujte, či je e-mailová adresa správna. Budú môcť zadať svoje heslo.',
        'assign'            => 'Používateľ bude mať prístup k vybraným spoločnostiam. Povolenia môžete obmedziť na stránke <a href=":url" class="border-b border-black">roly</a>.',
        'preferences'       => 'Vyberte predvolený jazyk používateľa. Môžte tiež nastaviť  predvolenú vstupnú stránku po prihlásení používateľa.',
    ],

    'password' => [
        'pass'              => 'Heslo',
        'pass_confirm'      => 'Potvrdenie hesla',
        'current'           => 'Heslo',
        'current_confirm'   => 'Potvrdenie hesla',
        'new'               => 'Nové heslo',
        'new_confirm'       => 'Potvrdenie hesla',
    ],

    'error' => [
        'self_delete'       => 'Chyba: Nemožete zmazať tento účet pokiaľ ste prihlásený!',
        'self_disable'      => 'Chyba: Nemôžete zakázať samého seba!',
        'unassigned'        => 'Chyba: Nedá sa zrušiť priradená spoločnosť! Spoločnosť :company musí mať prideleného aspoň jedného používateľa.',
        'no_company'        => 'Chyba: Žiadna spoločnosť priradené k vášmu kontu. Prosím, kontaktujte správcu systému.',
    ],

    'login_redirect'        => 'Overenie dokončené! Budete presmerovaný...',
    'failed'                => 'Prihlasovacie údaje nie sú správne.',
    'throttle'              => 'Prekročený limit pokusov. Skúste znovu o :seconds sekúnd.',
    'disabled'              => 'Tento účet je zakázaný. Prosím, kontaktujte správcu systému.',

    'notification' => [
        'message_1'         => 'Dostávate tento e-mail, pretože sme dostali žiadosť o obnovenie hesla pre tento účet.',
        'message_2'         => 'Ak ste nežiadali o vytvorenie nového hesla, potom sa nevyžaduje žiadne ďalšia akcia.',
        'button'            => 'Obnovenie hesla',
    ],

    'invitation' => [
        'message_1'         => 'Tento e-mail ste dostali, pretože ste pozvaní do Akauntingu.',
        'message_2'         => 'Ak sa nechcete pripojiť, nie sú potrebné žiadne ďalšie kroky.',
        'button'            => 'Začíname',
    ],

    'information' => [
        'invoice'           => 'Vytvárajte faktúry jednoducho',
        'reports'           => 'Získajte podrobné prehľady',
        'expense'           => 'Sledujte akékoľvek výdavky',
        'customize'         => 'Prispôsobte si svoj Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrátor',
            'description'   => 'Získajú úplný prístup k vášmu Akauntingu vrátane zákazníkov, faktúr, prehľadov, nastavení a aplikácií.',
        ],
        'manager' => [
            'name'          => 'Manažér',
            'description'   => 'Získajú úplný prístup k vášmu Akauntingu, ale nemôžu spravovať používateľov a aplikácie.',
        ],
        'customer' => [
            'name'          => 'Zákazník',
            'description'   => 'Môžu pristupovať na Klientsky portál a platiť svoje faktúry online prostredníctvom vami nastavených spôsobov platby.',
        ],
        'accountant' => [
            'name'          => 'Účtovník',
            'description'   => 'Môžu pristupovať k faktúram, transakciám, výkazom a vytvárať účtovné zápisy.',
        ],
        'employee' => [
            'name'          => 'Zamestnanec',
            'description'   => 'Môžu vytvárať výdavky a sledovať čas pre priradené projekty, ale môžu vidieť iba svoje vlastné informácie.',
        ],
    ],

];
