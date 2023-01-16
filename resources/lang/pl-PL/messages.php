<?php

return [

    'success' => [
        'added'             => ':type dodane!',
        'updated'           => ':type zaktualizowany!',
        'deleted'           => ':type usunięty!',
        'duplicated'        => ':type zduplikowany!',
        'imported'          => ':type zaimportowany!',
        'import_queued'     => ':type import został zaplanowany! Otrzymasz wiadomość e-mail po zakończonym imporcie.',
        'exported'          => ':type wyeksportowane!',
        'export_queued'     => 'Eksport :type został zaplanowany! Otrzymasz e-mail, gdy będzie gotowy do pobrania.',
        'enabled'           => ':type włączony!',
        'disabled'          => ':type wyłączony!',

        'clear_all'         => 'Świetnie! Wyczyszczono wszystkie Twoje :typ.',
    ],

    'error' => [
        'over_payment'      => 'Błąd: Płatność nie dodana! Wprowadzona kwota przekracza sumę :amount',
        'not_user_company'  => 'Błąd: Nie masz uprawnień do zarządzania tą firmą!',
        'customer'          => 'Błąd: Użytkownik nie został utworzony! :name już używa tego adresu e-mail.',
        'no_file'           => 'Błąd: Nie wybrano pliku!',
        'last_category'     => 'Błąd: Nie można usunąć ostatniej kategorii :type!',
        'change_type'       => 'Błąd: Nie można zmienić typu, ponieważ jest związany :text',
        'invalid_apikey'    => 'Błąd: Wprowadzony klucz API jest nieprawidłowy!',
        'import_column'     => 'Błąd: :message Nazwa kolumny: :column. Numer linii: :line.',
        'import_sheet'      => 'Błąd: Nazwa arkusza jest nieprawidłowa. Proszę sprawdzić plik próbny.',
    ],

    'warning' => [
        'deleted'           => 'Ostrzeżenie: Nie masz uprawnień do usunięcia <b>:name</b> , ponieważ jest powiązany z :text',
        'disabled'          => 'Ostrzeżenie: Nie masz uprawnień do wyłączenia <b>:name</b> , ponieważ jest powiązany z :tex.',
        'reconciled_tran'   => 'Ostrzeżenie: Nie masz uprawnień do zmiany/usunięcia transakcji, ponieważ została ona uzgodniona!',
        'reconciled_doc'    => 'Ostrzeżenie: Nie masz uprawnień do zmiany/usunięcia :type ponieważ posiada uzgodnione transakcje!',
        'disable_code'      => 'Ostrzeżenie: Nie masz uprawnień do wyłączenia lub zmiany waluty <b>:name</b> , ponieważ jest ona związana :text',
        'payment_cancel'    => 'Ostrzeżenie: Anulowałeś ostatnią płatność :method!',
    ],

];
