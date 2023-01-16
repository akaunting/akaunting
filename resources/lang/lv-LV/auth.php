<?php

return [

    'auth'                  => 'Autentifikācija',
    'profile'               => 'Profils',
    'logout'                => 'Iziet',
    'login'                 => 'Pieslēgties',
    'forgot'                => 'Aizmirsi',
    'login_to'              => 'Pieslēgties, lai sāktu darbu',
    'remember_me'           => 'Atcerēties mani',
    'forgot_password'       => 'Es aizmirsu savu paroli',
    'reset_password'        => 'Atjaunot paroli',
    'change_password'       => 'Nomainīt paroli',
    'enter_email'           => 'Ievadiet e-pasta adresi',
    'current_email'         => 'E-pasta adrese',
    'reset'                 => 'Atiestatīt',
    'never'                 => 'nekad',
    'landing_page'          => 'Galvenā lapa',
    'personal_information'  => 'Personīgā informācija',
    'register_user'         => 'Reģistrēt lietotāju',
    'register'              => 'Reģistrēties',

    'form_description' => [
        'personal'          => 'Uzaicinājuma saite tiks nosūtīta jaunajam lietotājam, tāpēc pārliecinieties, vai e-pasta adrese ir pareiza. Viņi varēs ievadīt savu paroli.',
        'assign'            => 'Lietotājam būs piekļuve atlasītajiem uzņēmumiem. Varat ierobežot atļaujas <a href=":url" class="border-b border-black">lomu</a> lapā.',
        'preferences'       => 'Izvēlieties lietotāja noklusējuma valodu. Varat arī iestatīt galveno lapu pēc lietotāja pieteikšanās.',
    ],

    'password' => [
        'pass'              => 'Parole',
        'pass_confirm'      => 'Paroles apstiprinājums',
        'current'           => 'Parole',
        'current_confirm'   => 'Paroles apstiprinājums',
        'new'               => 'Jauna parole',
        'new_confirm'       => 'Jaunās paroles apstiprinājums',
    ],

    'error' => [
        'self_delete'       => 'Kļūda: Nevar izdzēst sevi!',
        'self_disable'      => 'Kļūda: nevar sevi atspējot!',
        'unassigned'        => 'Kļūda: nevar atcelt uzņēmuma piešķiršanu! Uzņēmumam :company ir jāpiešķir vismaz viens lietotājs.',
        'no_company'        => 'Kļūda: Jūsu kontam nav piesaistīts neviens uzņēmums. Sazinieties, lūdzu, ar sistēmas administratoru.',
    ],

    'login_redirect'        => 'Pārbaude veikta! Jūs tiekat novirzīts...',
    'failed'                => 'Lietotāja vārds un/vai parole ir nepareiza.',
    'throttle'              => 'Pārāk daudz pieteikšanās mēģinājumu. Lūdzu, mēģiniet vēlreiz pēc :seconds sekundēm.',
    'disabled'              => 'Šis konts ir atiespējots. Lūdzu, sazinieties ar sistēmas administratoru.',

    'notification' => [
        'message_1'         => 'Jūs saņēmāt šo epastu, jo esam saņēmuši paroles atjaunošanas pieprasījumu Jūsu kontam.',
        'message_2'         => 'Ja Jūs neesat veikši šo pieprasījumi, lūdzu ignorējiet to.',
        'button'            => 'Atjaunot paroli',
    ],

    'invitation' => [
        'message_1'         => 'Jūs saņēmāt šo e-pasta ziņojumu, jo esat uzaicināts pievienoties Akaunting.',
        'message_2'         => 'Ja nevēlaties pievienoties, turpmāka darbība nav nepieciešama.',
        'button'            => 'Sākt tagad',
    ],

    'information' => [
        'invoice'           => 'Viegli izveidojiet rēķinus',
        'reports'           => 'Saņemiet detalizētus pārskatus',
        'expense'           => 'Izsekojiet visiem izdevumiem',
        'customize'         => 'Pielāgojiet savu Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrators',
            'description'   => 'Viņi iegūst pilnu piekļuvi jūsu Akaunting, tostarp klientiem, rēķiniem, pārskatiem, iestatījumiem un lietotnēm.',
        ],
        'manager' => [
            'name'          => 'Vadītājs',
            'description'   => 'Viņi iegūst pilnu piekļuvi jūsu Akaunting, taču nevar pārvaldīt lietotājus un lietotnes.',
        ],
        'customer' => [
            'name'          => 'Klients',
            'description'   => 'Viņi var piekļūt klientu portālam un apmaksāt rēķinus tiešsaistē, izmantojot jūsu iestatītās maksājuma metodes.',
        ],
        'accountant' => [
            'name'          => 'Grāmatvedis',
            'description'   => 'Viņi var piekļūt rēķiniem, darījumiem, pārskatiem un izveidot žurnāla ierakstus.',
        ],
        'employee' => [
            'name'          => 'Darbinieks',
            'description'   => 'Viņi var izveidot izdevumu deklarācijas un izsekot piešķirto projektu laikam, taču var redzēt tikai savu informāciju.',
        ],
    ],

];
