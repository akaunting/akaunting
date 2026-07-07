<?php

return [

    'auth'                  => 'Uwierzytelnianie',
    'profile'               => 'Profil',
    'logout'                => 'Wyloguj',
    'login'                 => 'Zaloguj',
    'forgot'                => 'Nie pamiętam',
    'login_to'              => 'Zaloguj się, aby rozpocząć sesję',
    'remember_me'           => 'Zapamiętaj mnie',
    'forgot_password'       => 'Nie pamiętam hasła',
    'reset_password'        => 'Resetuj hasło',
    'change_password'       => 'Zmień hasło',
    'enter_email'           => 'Wpisz swój adres e-mail',
    'current_email'         => 'Bieżący e-mail',
    'reset'                 => 'Resetuj',
    'never'                 => 'nigdy',
    'landing_page'          => 'Strona startowa',
    'personal_information'  => 'Dane osobowe',
    'register_user'         => 'Zarejestruj użytkownika',
    'register'              => 'Zarejestruj',

    'form_description' => [
        'personal'          => 'Link zaproszenia zostanie wysłany do nowego użytkownika, upewnij się, że adres e-mail jest poprawny. Użytkownik będzie mógł wprowadzić swoje hasło.',
        'assign'            => 'Użytkownik będzie miał dostęp do wybranych firm. Możesz ograniczyć uprawnienia na stronie <a href=":url" class="border-b border-black">ról</a>.',
        'preferences'       => 'Wybierz domyślny język użytkownika. Możesz również ustawić stronę startową po zalogowaniu użytkownika.',
    ],

    'password' => [
        'pass'              => 'Hasło',
        'pass_confirm'      => 'Potwierdzenie hasła',
        'current'           => 'Bieżące hasło',
        'current_confirm'   => 'Potwierdzenie bieżącego hasła',
        'new'               => 'Nowe hasło',
        'new_confirm'       => 'Potwierdzenie nowego hasła',
    ],

    'error' => [
        'self_delete'       => 'Błąd: Nie możesz usunąć samego siebie!',
        'self_disable'      => 'Błąd: Nie możesz wyłączyć samego siebie!',
        'unassigned'        => 'Błąd: Nie można cofnąć przypisania firmy! Firma :company musi mieć przypisanego co najmniej jednego użytkownika.',
        'no_company'        => 'Błąd: Brak firmy przypisanej do Twojego konta. Skontaktuj się z administratorem systemu.',
    ],

    'login_redirect'        => 'Weryfikacja zakończona! Następuje przekierowanie...',
    'failed'                => 'Te dane uwierzytelniające nie pasują do naszych rekordów.',
    'throttle'              => 'Zbyt wiele prób logowania. Spróbuj ponownie za :seconds sekund.',
    'disabled'              => 'To konto jest wyłączone. Skontaktuj się z administratorem systemu.',

    'notification' => [
        'message_1'         => 'Otrzymujesz tę wiadomość, ponieważ otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta.',
        'message_2'         => 'Jeśli nie prosiłeś o reset hasła, nie są wymagane żadne dalsze działania.',
        'button'            => 'Resetuj hasło',
    ],

    'invitation' => [
        'message_1'         => 'Otrzymujesz tę wiadomość, ponieważ zostałeś zaproszony do dołączenia do Akaunting.',
        'message_2'         => 'Jeśli nie chcesz dołączyć, nie są wymagane żadne dalsze działania.',
        'button'            => 'Rozpocznij',
    ],

    'information' => [
        'invoice'           => 'Twórz faktury łatwo',
        'reports'           => 'Uzyskaj szczegółowe raporty',
        'expense'           => 'Śledź wszelkie wydatki',
        'customize'         => 'Dostosuj swój Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrator',
            'description'   => 'Ma pełny dostęp do Twojego Akaunting, w tym klientów, faktur, raportów, ustawień i aplikacji.',
        ],
        'manager' => [
            'name'          => 'Menedżer',
            'description'   => 'Ma pełny dostęp do Twojego Akaunting, ale nie może zarządzać użytkownikami i aplikacjami.',
        ],
        'customer' => [
            'name'          => 'Klient',
            'description'   => 'Ma dostęp do Portalu Klienta i może opłacać faktury online za pomocą metod płatności, które ustawiłeś.',
        ],
        'accountant' => [
            'name'          => 'Księgowy',
            'description'   => 'Ma dostęp do faktur, transakcji, raportów i może tworzyć zapisy dziennika.',
        ],
        'employee' => [
            'name'          => 'Pracownik',
            'description'   => 'Może tworzyć wnioski o zwrot wydatków i śledzić czas przypisanych projektów, ale widzi tylko swoje informacje.',
        ],
    ],

];
