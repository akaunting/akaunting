<?php

return [

    'auth'                  => 'Todennus',
    'profile'               => 'Profiili',
    'logout'                => 'Kirjaudu ulos',
    'login'                 => 'Kirjaudu sisään',
    'forgot'                => 'Unohtunut',
    'login_to'              => 'Kirjaudu sisään aloittaaksesi istunnon',
    'remember_me'           => 'Muista minut',
    'forgot_password'       => 'Unohdin salasanani',
    'reset_password'        => 'Palauta salasana',
    'change_password'       => 'Vaihda salasana',
    'enter_email'           => 'Anna sähköpostiosoitteesi',
    'current_email'         => 'Nykyinen sähköpostiosoitteesi',
    'reset'                 => 'Palauta',
    'never'                 => 'ei koskaan',
    'landing_page'          => 'Aloitussivu',
    'personal_information'  => 'Henkilötiedot',
    'register_user'         => 'Luo uusi käyttäjä',
    'register'              => 'Rekiteröidy',

    'form_description' => [
        'personal'          => 'Kutsu linkki lähetetään uudelle käyttäjälle, joten varmista, että sähköpostiosoite on oikea. He voivat syöttää heidän salasanansa.',
        'assign'            => 'Käyttäjällä on pääsy valittuihin yrityksiin. Voit rajoittaa oikeuksia <a href=":url" class="border-b border-black">roolit</a>-sivulta.',
        'preferences'       => 'Valitse oletus kieli käyttäjälle. Voit myös asettaa etusivulle kun käyttäjä on kirjautunut sisään.',
    ],

    'password' => [
        'pass'              => 'Salasana',
        'pass_confirm'      => 'Vahvista salasana',
        'current'           => 'Salasana',
        'current_confirm'   => 'Vahvista salasana',
        'new'               => 'Uusi salasana',
        'new_confirm'       => 'Vahvista uusi salasana',
    ],

    'error' => [
        'self_delete'       => 'Virhe: Et voi poistaa itseäsi!',
        'self_disable'      => 'Virhe: Et voi poistaa käytöstä itseäsi!',
        'unassigned'        => 'Virhe: Yritystä ei voi poistaa käytöstä! Yritykselle :company on oltava vähintään yksi käyttäjä.',
        'no_company'        => 'Virhe: Ei käyttäjätiliisi liitettyä yritystä. Ota yhteyttä järjestelmän ylläpitäjään.',
    ],

    'login_redirect'        => 'Vahvistus onnistunut, sinut ohjataan nyt uudelleen...',
    'failed'                => 'Antamaasi sähköpostiosoitetta ja/tai salasanaa ei löydy.',
    'throttle'              => 'Liian monta kirjautumisyritystä. Yritä uudelleen :seconds sekunnin kuluttua.',
    'disabled'              => 'Tämä käyttäjätili on poistettu käytöstä. Ota yhteyttä järjestelmän ylläpitäjään.',

    'notification' => [
        'message_1'         => 'Saat tämän sähköpostiviestin, koska olemme saaneet salasanan palauttamispyynnön tilillesi.',
        'message_2'         => 'Mikäli et pyytänyt salasanan palauttamista, lisätoimia ei tarvita.',
        'button'            => 'Palauta salasana',
    ],

    'invitation' => [
        'message_1'         => 'Sait tämän viestin koska sinut on kutsuttu mukaan käyttämään Akaunting:ia ',
        'message_2'         => 'Jos et halua liittyä, ei sinun tarvitse tehdä mitään.',
        'button'            => 'Aloita',
    ],

    'information' => [
        'invoice'           => 'Tee laskuja helposti',
        'reports'           => 'Saa yksilöityjä raportteja',
        'expense'           => 'Seuraa kulujasi ',
        'customize'         => 'Luo omanlainen Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Ylläpitäjä',
            'description'   => 'He saavat täydet oikeudet kaikkiin Akaunting:in palveluihin kuten asiakastietoihin, laskuihin, raportteihin, asetuksiin ja sovelluksiin
',
        ],
        'manager' => [
            'name'          => 'Valvoja',
            'description'   => 'He saavat täydet oikeudet Akaunting:iin mutta ei voi hallinnoida käyttäjiä tai sovelluksia
',
        ],
        'customer' => [
            'name'          => 'Asiakas',
            'description'   => 'He voivat käyttää Asiakasportaalia ja maksaa laskunsa verkossa niillä maksutavoilla, jotka olet määrittänyt.',
        ],
        'accountant' => [
            'name'          => 'Kirjanpitäjä',
            'description'   => 'He voivat käyttää laskuja, tapahtumia, raportteja ja luoda kirjanpitomerkintöjä.',
        ],
        'employee' => [
            'name'          => 'Työntekijä',
            'description'   => 'He voivat luoda kulukorvauksia ja seurata aikaa määrätyille projekteille, mutta he näkevät vain omat tietonsa.',
        ],
    ],

];
