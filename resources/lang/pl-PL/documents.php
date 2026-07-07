<?php

return [

    'edit_columns'              => 'Edytuj kolumny',
    'empty_items'               => 'Nie dodałeś żadnych pozycji.',
    'grand_total'               => 'Suma końcowa',
    'accept_payment_online'     => 'Akceptuj płatności online',
    'transaction'               => 'Płatność w wysokości :amount została dokonana z użyciem :account.',
    'portal_transaction'        => 'Płatność w wysokości :amount została dokonana z użyciem :payment_method.',
    'billing'                   => 'Rozliczenia',
    'advanced'                  => 'Zaawansowane',

    'item_price_hidden'         => 'Ta kolumna jest ukryta w Twoim :type.',

    'actions' => [
        'cancel'                => 'Anuluj',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Ty</b> oznaczyłeś tę fakturę jako',
        'services'              => 'Usługi',
        'another_item'          => 'Inna pozycja',
        'another_description'   => 'i inny opis',
        'more_item'             => '+:count dodatkowa pozycja',
    ],

    'statuses' => [
        'draft'                 => 'Wersja robocza',
        'sent'                  => 'Wysłane',
        'expired'               => 'Wygasłe',
        'viewed'                => 'Wyświetlone',
        'approved'              => 'Zatwierdzone',
        'received'              => 'Otrzymane',
        'refused'               => 'Odrzucone',
        'restored'              => 'Przywrócone',
        'reversed'              => 'Odwrócone',
        'partial'               => 'Częściowe',
        'paid'                  => 'Zapłacone',
        'pending'               => 'Oczekujące',
        'invoiced'              => 'Zafakturowane',
        'overdue'               => 'Przeterminowane',
        'unpaid'                => 'Niezapłacone',
        'cancelled'             => 'Anulowane',
        'voided'                => 'Unieważnione',
        'completed'             => 'Zakończone',
        'shipped'               => 'Wysłane',
        'refunded'              => 'Zwrócone',
        'failed'                => 'Nieudane',
        'denied'                => 'Odmówione',
        'processed'             => 'Przetworzone',
        'open'                  => 'Otwarte',
        'closed'                => 'Zamknięte',
        'billed'                => 'Zafakturowane',
        'delivered'             => 'Dostarczone',
        'returned'              => 'Zwrócone',
        'drawn'                 => 'Wyciągnięte',
        'not_billed'            => 'Nie zafakturowane',
        'issued'                => 'Wystawione',
        'not_invoiced'          => 'Nie zafakturowane',
        'confirmed'             => 'Potwierdzone',
        'not_confirmed'         => 'Niepotwierdzone',
        'active'                => 'Aktywne',
        'ended'                 => 'Zakończone',
    ],

    'form_description' => [
        'companies'             => 'Zmień adres, logo i inne informacje swojej firmy.',
        'billing'               => 'Szczegóły rozliczeń pojawiają się w Twoim dokumencie.',
        'advanced'              => 'Wybierz kategorię, dodaj lub edytuj stopkę i dodaj załączniki do swojego :type.',
        'attachment'            => 'Pobierz pliki załączone do tego :type',
    ],

    'slider' => [
        'create'            => ':user utworzył ten :type w dniu :date',
        'create_recurring'  => ':user utworzył ten szablon cykliczny w dniu :date',
        'send'              => ':user wysłał ten :type w dniu :date',
        'schedule'          => 'Powtarzaj co :interval :frequency od :date',
        'children'          => ':count :type zostało utworzonych automatycznie',
        'cancel'            => ':user anulował ten :type w dniu :date',
    ],

    'messages' => [
        'email_sent'            => 'E-mail :type został wysłany!',
        'restored'              => ':type został przywrócony!',
        'marked_as'             => ':type oznaczony jako :status!',
        'marked_sent'           => ':type oznaczony jako wysłany!',
        'marked_paid'           => ':type oznaczony jako zapłacony!',
        'marked_viewed'         => ':type oznaczony jako wyświetlony!',
        'marked_cancelled'      => ':type oznaczony jako anulowany!',
        'marked_received'       => ':type oznaczony jako otrzymany!',
    ],

    'recurring' => [
        'auto_generated'        => 'Generowane automatycznie',

        'tooltip' => [
            'document_date'     => 'Data :type zostanie automatycznie przypisana na podstawie harmonogramu i częstotliwości :type.',
            'document_number'   => 'Numer :type zostanie automatycznie przypisany przy generowaniu każdego cyklicznego :type.',
        ],
    ],

    'empty_attachments'         => 'Brak plików załączonych do tego :type.',
];
