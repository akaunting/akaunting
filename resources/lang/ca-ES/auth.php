<?php

return [

    'auth'                  => 'Autenticació',
    'profile'               => 'Perfil',
    'logout'                => 'Surt',
    'login'                 => 'Inicia',
    'forgot'                => 'Oblida',
    'login_to'              => 'Inicia la sessió',
    'remember_me'           => 'Recorda\'m',
    'forgot_password'       => 'He oblidat la meva contrasenya',
    'reset_password'        => 'Restableix la contrasenya',
    'change_password'       => 'Canvia la contrasenya',
    'enter_email'           => 'Introdueix la teva adreça de correu electrònic',
    'current_email'         => 'Correu actual',
    'reset'                 => 'Restableix',
    'never'                 => 'mai',
    'landing_page'          => 'Pàgina inicial',
    'personal_information'  => 'Informació personal',
    'register_user'         => 'Registra l\'usuari',
    'register'              => 'Registra',

    'form_description' => [
        'personal'          => 'S\'enviarà al nou usuari l\'enllaç d\'invitació, de manera que assegureu-vos que l\'adreça de correu electrònic sigui correcta. Podrà introduir la seva contrasenya.',
        'assign'            => 'L\'usuari tindrà accés a les empreses seleccionades. Podeu restringir els permisos des de la pàgina de <a href=":url" class="border-b border-black">rols</a>.',
        'preferences'       => 'Selecciona la llengua per defecte de l\'usuari. També pots definir la pàgina de destinació després que l\'usuari iniciï la sessió.',
    ],

    'password' => [
        'pass'              => 'Contrasenya',
        'pass_confirm'      => 'Confirma la contrasenya',
        'current'           => 'Contrasenya',
        'current_confirm'   => 'Confirma la contrasenya',
        'new'               => 'Nova contrasenya',
        'new_confirm'       => 'Confirma la nova contrasenya',
    ],

    'error' => [
        'self_delete'       => 'Error: No et pots eliminar a tu mateix!',
        'self_disable'      => 'Error: No et pots desactivar a tu mateix!',
        'unassigned'        => 'Error: No hi pot haver cap empresa sense usuaris! L\'empresa :company ha de tenir almenys un usuari.',
        'no_company'        => 'Error: No hi ha cap empresa assignada al teu compte. Si us plau, contacta amb l\'administrador del sistema.',
    ],

    'login_redirect'        => 'S\'ha verificat! Estàs sent redireccionat...',
    'failed'                => 'Aquestes credencials no concorden amb els nostres registres.',
    'throttle'              => 'Ha superat el nombre màxim d\'intents d\'accés. Si us plau, torni a intentar-ho en :seconds segons.',
    'disabled'              => 'Aquest compte està desactivat, si us plau contacta amb l\'administrador del sistema.',

    'notification' => [
        'message_1'         => 'Estàs rebent aquest correu perquè s\'ha fet una sol·licitud de restabliment de contrasenya del teu compte. ',
        'message_2'         => 'Si no has sol·licitat el restabliment de contrasenya no cal que facis res més.',
        'button'            => 'Restabliment de contrasenya',
    ],

    'invitation' => [
        'message_1'         => 'Estàs rebent aquest correu electrònic perquè estàs convidat a unir-te a l\'Akaunting.',
        'message_2'         => 'Si no t\'hi vols unir, no cal que facis res més.',
        'button'            => 'Comença',
    ],

    'information' => [
        'invoice'           => 'Crea factures fàcilment',
        'reports'           => 'Obté informes detallats',
        'expense'           => 'Fes el seguiment de qualsevol despesa',
        'customize'         => 'Personalitza el teu Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrador',
            'description'   => 'Tenen accés complet al vostre Akaunting, inclosos clients, factures, informes, configuracions i aplicacions.',
        ],
        'manager' => [
            'name'          => 'Gerent',
            'description'   => 'Tenen accés complet al teu Akaunting, però no poden gestionar usuaris i aplicacions.',
        ],
        'customer' => [
            'name'          => 'Client',
            'description'   => 'Poden accedir al Portal del Client i pagar les seves factures en línia mitjançant els mètodes de pagament que tu autoritzis.',
        ],
        'accountant' => [
            'name'          => 'Comptable',
            'description'   => 'Poden accedir a factures, transaccions, informes i crear entrades de diari.',
        ],
        'employee' => [
            'name'          => 'Empleat',
            'description'   => 'Poden crear reclamacions de despeses i fer un seguiment del temps per als projectes assignats, però només poden veure la seva pròpia informació.',
        ],
    ],

];
