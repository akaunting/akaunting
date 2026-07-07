<?php

return [

    'success' => [
        'added'             => ':type dodano!',
        'created'           => ':type utworzono!',
        'updated'           => ':type zaktualizowano!',
        'deleted'           => ':type usunięto!',
        'duplicated'        => ':type zduplikowano!',
        'imported'          => ':type zaimportowano!',
        'import_queued'     => 'Import :type został zaplanowany! Otrzymasz e-mail po zakończeniu.',
        'exported'          => ':type wyeksportowano!',
        'export_queued'     => 'Eksport :type bieżącej strony został zaplanowany! Otrzymasz e-mail, gdy będzie gotowy do pobrania.',
        'download_queued'   => 'Pobieranie :type bieżącej strony zostało zaplanowane! Otrzymasz e-mail, gdy będzie gotowe do pobrania.',
        'enabled'           => ':type włączone!',
        'disabled'          => ':type wyłączone!',
        'connected'         => ':type połączone!',
        'invited'           => ':type zaproszono!',
        'ended'             => ':type zakończone!',

        'clear_all'         => 'Świetnie! Wyczyściłeś wszystkie swoje :type.',
    ],

    'error' => [
        'over_payment'      => 'Błąd: Płatność nie została dodana! Wprowadzona kwota przekracza sumę: :amount',
        'not_user_company'  => 'Błąd: Nie masz uprawnień do zarządzania tą firmą!',
        'customer'          => 'Błąd: Użytkownik nie utworzony! :name już używa tego adresu e-mail.',
        'no_file'           => 'Błąd: Nie wybrano pliku!',
        'last_category'     => 'Błąd: Nie można usunąć ostatniej kategorii <b>:type</b>!',
        'transfer_category' => 'Błąd: Nie można usunąć kategorii transferu <b>:type</b>!',
        'change_type'       => 'Błąd: Nie można zmienić typu, ponieważ ma powiązane :text!',
        'invalid_apikey'    => 'Błąd: Wprowadzony klucz API jest nieprawidłowy!',
        'empty_apikey'      => 'Błąd: Nie wprowadziłeś swojego klucza API! <a href=":url" class="font-bold underline underline-offset-4">Kliknij tutaj</a>, aby wprowadzić swój klucz API.',
        'import_column'     => 'Błąd: :message Nazwa kolumny: :column. Numer wiersza: :line.',
        'import_sheet'      => 'Błąd: Nazwa arkusza jest nieprawidłowa. Sprawdź plik przykładowy.',
        'same_amount'       => 'Błąd: Łączna kwota podziału musi być dokładnie taka sama jak łączna kwota :transaction: :amount',
        'over_match'        => 'Błąd: :type nie połączone! Wprowadzona kwota nie może przekraczać łącznej płatności: :amount',
    ],

    'warning' => [
        'deleted'           => 'Ostrzeżenie: Nie możesz usunąć <b>:name</b>, ponieważ ma powiązane :text.',
        'disabled'          => 'Ostrzeżenie: Nie możesz wyłączyć <b>:name</b>, ponieważ ma powiązane :text.',
        'reconciled_tran'   => 'Ostrzeżenie: Nie możesz zmienić/usunąć transakcji, ponieważ jest uzgodniona!',
        'reconciled_doc'    => 'Ostrzeżenie: Nie możesz zmienić/usunąć :type, ponieważ ma uzgodnione transakcje!',
        'disable_code'      => 'Ostrzeżenie: Nie możesz wyłączyć lub zmienić waluty <b>:name</b>, ponieważ ma powiązane :text.',
        'payment_cancel'    => 'Ostrzeżenie: Anulowałeś swoją ostatnią płatność :method!',
        'missing_transfer'  => 'Ostrzeżenie: Brak transferu powiązanego z tą transakcją. Rozważ usunięcie tej transakcji.',
        'connect_tax'       => 'Ostrzeżenie: Ten :type ma kwotę podatku. Podatki dodane do :type nie mogą być połączone, więc podatek zostanie dodany do sumy i obliczony odpowiednio.',
        'contact_change'    => 'Ostrzeżenie: Nie możesz zmienić kontaktu w :type, który został już wysłany, otrzymany lub opłacony!',
    ],

];
