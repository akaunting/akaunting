<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Witaj!',
    'salutation'          => 'Pozdrawiamy,<br> :company_name',
    'subcopy'             => 'Jeśli masz problemy z kliknięciem przycisku ":text", skopiuj i wklej poniższy adres URL do swojej przeglądarki: [:url](:url)',
    'mark_read'           => 'Oznacz jako przeczytane',
    'mark_read_all'       => 'Oznacz wszystkie jako przeczytane',
    'empty'               => 'Super, zero powiadomień!',
    'new_apps'            => ':app jest dostępna. <a href=":url">Sprawdź teraz</a>!',

    'update' => [
        'mail' => [
            'title'         => '⚠️ Aktualizacja nie powiodła się na :domain',
            'description'   => 'Aktualizacja :alias z :current_version do :new_version nie powiodła się w kroku <strong>:step</strong> z następującą wiadomością: :error_message',
        ],
        'slack' => [
            'description'   => 'Aktualizacja nie powiodła się na :domain',
        ],
    ],

    'download' => [
        'completed' => [
            'title'         => 'Pobieranie gotowe',
            'description'   => 'Plik jest gotowy do pobrania z następującego linku:',
        ],
        'failed' => [
            'title'         => 'Pobieranie nie powiodło się',
            'description'   => 'Nie można utworzyć pliku z powodu następującego problemu:',
        ],
    ],

    'import' => [
        'completed' => [
            'title'         => 'Import zakończony',
            'description'   => 'Import został zakończony, a rekordy są dostępne w Twoim panelu.',
        ],
        'failed' => [
            'title'         => 'Import nie powiódł się',
            'description'   => 'Nie można zaimportować pliku z powodu następujących problemów:',
        ],
    ],

    'export' => [
        'completed' => [
            'title'         => 'Eksport gotowy',
            'description'   => 'Plik eksportu jest gotowy do pobrania z następującego linku:',
        ],
        'failed' => [
            'title'         => 'Eksport nie powiódł się',
            'description'   => 'Nie można utworzyć pliku eksportu z powodu następującego problemu:',
        ],
    ],

    'email' => [
        'invalid' => [
            'title'         => 'Nieprawidłowy e-mail :type',
            'description'   => 'Adres e-mail :email został zgłoszony jako nieprawidłowy, a osoba została wyłączona. Sprawdź następujący komunikat o błędzie i napraw adres e-mail:',
        ],
    ],

    'menu' => [
        'download_completed' => [
            'title'         => 'Pobieranie gotowe',
            'description'   => 'Twój plik <strong>:type</strong> jest gotowy do <a href=":url" target="_blank"><strong>pobrania</strong></a>.',
        ],
        'download_failed' => [
            'title'         => 'Pobieranie nie powiodło się',
            'description'   => 'Nie można utworzyć pliku z powodu kilku problemów. Sprawdź swój e-mail, aby uzyskać szczegóły.',
        ],
        'export_completed' => [
            'title'         => 'Eksport gotowy',
            'description'   => 'Twój plik eksportu <strong>:type</strong> jest gotowy do <a href=":url" target="_blank"><strong>pobrania</strong></a>.',
        ],
        'export_failed' => [
            'title'         => 'Eksport nie powiódł się',
            'description'   => 'Nie można utworzyć pliku eksportu z powodu kilku problemów. Sprawdź swój e-mail, aby uzyskać szczegóły.',
        ],
        'import_completed' => [
            'title'         => 'Import zakończony',
            'description'   => 'Twoje dane <strong>:type</strong> w liczbie <strong>:count</strong> zostały pomyślnie zaimportowane.',
        ],
        'import_failed' => [
            'title'         => 'Import nie powiódł się',
            'description'   => 'Nie można zaimportować pliku z powodu kilku problemów. Sprawdź swój e-mail, aby uzyskać szczegóły.',
        ],
        'new_apps' => [
            'title'         => 'Nowa aplikacja',
            'description'   => 'Aplikacja <strong>:name</strong> została wydana. Możesz <a href=":url">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'invoice_new_customer' => [
            'title'         => 'Nowa faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> została utworzona. Możesz <a href=":invoice_portal_link">kliknąć tutaj</a>, aby zobaczyć szczegóły i dokonać płatności.',
        ],
        'invoice_remind_customer' => [
            'title'         => 'Przeterminowana faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> była płatna do <strong>:invoice_due_date</strong>. Możesz <a href=":invoice_portal_link">kliknąć tutaj</a>, aby zobaczyć szczegóły i dokonać płatności.',
        ],
        'invoice_remind_admin' => [
            'title'         => 'Przeterminowana faktura',
            'description'   => 'Faktura <strong>:invoice_number</strong> była płatna do <strong>:invoice_due_date</strong>. Możesz <a href=":invoice_admin_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'invoice_recur_customer' => [
            'title'         => 'Nowa faktura cykliczna',
            'description'   => 'Faktura <strong>:invoice_number</strong> została utworzona na podstawie Twojego cyklu powtarzania. Możesz <a href=":invoice_portal_link">kliknąć tutaj</a>, aby zobaczyć szczegóły i dokonać płatności.',
        ],
        'invoice_recur_admin' => [
            'title'         => 'Nowa faktura cykliczna',
            'description'   => 'Faktura <strong>:invoice_number</strong> została utworzona na podstawie cyklu powtarzania <strong>:customer_name</strong>. Możesz <a href=":invoice_admin_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'invoice_view_admin' => [
            'title'         => 'Faktura wyświetlona',
            'description'   => '<strong>:customer_name</strong> wyświetlił fakturę <strong>:invoice_number</strong>. Możesz <a href=":invoice_admin_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'revenue_new_customer' => [
            'title'         => 'Płatność otrzymana',
            'description'   => 'Dziękujemy za płatność za fakturę <strong>:invoice_number</strong>. Możesz <a href=":invoice_portal_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'invoice_payment_customer' => [
            'title'         => 'Płatność otrzymana',
            'description'   => 'Dziękujemy za płatność za fakturę <strong>:invoice_number</strong>. Możesz <a href=":invoice_portal_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'invoice_payment_admin' => [
            'title'         => 'Płatność otrzymana',
            'description'   => ':customer_name zarejestrował płatność za fakturę <strong>:invoice_number</strong>. Możesz <a href=":invoice_admin_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'bill_remind_admin' => [
            'title'         => 'Przeterminowany rachunek',
            'description'   => 'Rachunek <strong>:bill_number</strong> był płatny do <strong>:bill_due_date</strong>. Możesz <a href=":bill_admin_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'bill_recur_admin' => [
            'title'         => 'Nowy rachunek cykliczny',
            'description'   => 'Rachunek <strong>:bill_number</strong> został utworzony na podstawie cyklu powtarzania <strong>:vendor_name</strong>. Możesz <a href=":bill_admin_link">kliknąć tutaj</a>, aby zobaczyć szczegóły.',
        ],
        'invalid_email' => [
            'title'         => 'Nieprawidłowy e-mail :type',
            'description'   => 'Adres e-mail <strong>:email</strong> został zgłoszony jako nieprawidłowy, a osoba została wyłączona. Sprawdź i napraw adres e-mail.',
        ],
    ],

    'messages' => [
        'mark_read'             => ':type przeczytał to powiadomienie!',
        'mark_read_all'         => ':type przeczytał wszystkie powiadomienia!',
    ],

    'browser' => [
        'firefox' => [
            'title' => 'Konfiguracja ikon Firefox',
            'description'  => '<span class="font-medium">Jeśli Twoje ikony się nie wyświetlają:</span> <br /> <span class="font-medium">Zezwól stronom na wybór własnych czcionek, zamiast Twoich powyższych wyborów</span> <br /><br /> <span class="font-bold"> Ustawienia (Preferencje) > Czcionki > Zaawansowane </span>',
        ],
    ],

];
