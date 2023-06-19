<?php

return [

    'auth'                  => 'Ověření',
    'profile'               => 'Profil',
    'logout'                => 'Odhlásit',
    'login'                 => 'Přihlásit',
    'forgot'                => 'Zapomenout',
    'login_to'              => 'Pro pokračování se, prosím, přihlaste',
    'remember_me'           => 'Pamatuj si mě',
    'forgot_password'       => 'Zapoměl jsem heslo',
    'reset_password'        => 'Obnovení hesla',
    'change_password'       => 'Změnit heslo',
    'enter_email'           => 'Zadejte svou e-mailovou adresu',
    'current_email'         => 'Aktuální e-mail',
    'reset'                 => 'Obnovit',
    'never'                 => 'nikdy',
    'landing_page'          => 'Úvodní stránka',
    'personal_information'  => 'Osobní údaje',
    'register_user'         => 'Registrovat uživatele',
    'register'              => 'Registrovat se',

    'form_description' => [
        'personal'          => 'Odkaz s pozvánkou bude odeslán novému uživateli, aby se ujistil, že je e-mailová adresa správná. Budou moci zadat své heslo.',
        'assign'            => 'Uživatel bude mít přístup k vybraným společnostem. Můžete omezit oprávnění ze stránky <a href=":url" class="border-b border-black">rolí</a>.',
        'preferences'       => 'Vyberte výchozí jazyk uživatele. Můžete také nastavit vstupní stránku po přihlášení uživatele.',
    ],

    'password' => [
        'pass'              => 'Heslo',
        'pass_confirm'      => 'Potvrzení hesla',
        'current'           => 'Heslo',
        'current_confirm'   => 'Potvrzení hesla',
        'new'               => 'Nové heslo',
        'new_confirm'       => 'Potvrzení nového hesla',
    ],

    'error' => [
        'self_delete'       => 'Chyba: nemůžete smazat sám sebe!',
        'self_disable'      => 'Chyba: Nemůžete zakázat sami sebe!',
        'unassigned'        => 'Chyba: Nelze zrušit přiřazení společnost! Společnost :company musí být přiřazena alespoň jednomu uživateli.',
        'no_company'        => 'Chyba: Váš účet nemá přidělenou firmu/společnost. Prosím, kontaktujte systémového administrátora.',
    ],

    'login_redirect'        => 'Ověření dokončeno! Jste přesměrováni...',
    'failed'                => 'Tyto přihlašovací údaje neodpovídají žádnému záznamu.',
    'throttle'              => 'Příliš mnoho pokusů o přihlášení. Zkuste to, prosím, znovu za :seconds vteřin.',
    'disabled'              => 'Tento účet je zakázán. Obraťte se na správce systému.',

    'notification' => [
        'message_1'         => 'Posíláme Vám tento e-mail, protože jsme obdrželi žádost o obnovení hesla.',
        'message_2'         => 'Pokud jste o obnovení hesla nežádal(a), neberte jej v potaz.',
        'button'            => 'Obnovit heslo',
    ],

    'invitation' => [
        'message_1'         => 'Obdrželi jste tento e-mail, protože jste pozváni do Akauntingu.',
        'message_2'         => 'Pokud se nechcete připojit, není nutné žádné další akce.',
        'button'            => 'Začněte',
    ],

    'information' => [
        'invoice'           => 'Snadné vytváření faktur',
        'reports'           => 'Získat podrobné reporty',
        'expense'           => 'Sledovat všechny výdaje',
        'customize'         => 'Přizpůsobte si Váš Akauntung',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Admin',
            'description'   => 'Získávají plný přístup k Vašemu účtu včetně zákazníků, faktur, zpráv, nastavení a aplikací.',
        ],
        'manager' => [
            'name'          => 'Správce',
            'description'   => 'Mají plný přístup k Vašemu Akauntingu, ale nemohou spravovat uživatele a aplikace.',
        ],
        'customer' => [
            'name'          => 'Zákazník',
            'description'   => 'Mohou přistupovat k portálu klientů a platit své faktury online prostřednictvím Vašich platebních metod.',
        ],
        'accountant' => [
            'name'          => 'Účetní',
            'description'   => 'Mohou přistupovat k fakturám, transakcím, zprávám a vytvářet časopisové záznamy.',
        ],
        'employee' => [
            'name'          => 'Zaměstnanec',
            'description'   => 'Mohou vytvářet nároky na výdaje a sledovat čas u přiřazených projektů, ale mohou vidět pouze jejich vlastní informace.',
        ],
    ],

];
