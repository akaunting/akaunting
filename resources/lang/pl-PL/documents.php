<?php

return [

    'edit_columns'              => 'Edytuj kolumny',
    'empty_items'               => 'Nie dodałeś żadnych elementów.',
    'grand_total'               => 'Brutto',
    'accept_payment_online'     => 'Akceptuj płatności online',
    'transaction'               => 'Płatność na :amount została dokonana przy użyciu :account.',
    'billing'                   => 'Płatności',
    'advanced'                  => 'Zaawansowane',

    'item_price_hidden'         => 'Ta kolumna jest ukryta w Twoim :type.',

    'actions' => [
        'cancel'                => 'Anuluj',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Oznaczyłeś</b> tę fakturę jako',
        'services'              => 'Usługi',
        'another_item'          => 'Kolejny Przedmiot',
        'another_description'   => 'kolejny opis',
        'more_item'             => '+:count więcej przedmiotów',
    ],

    'statuses' => [
        'draft'                 => 'Szkic',
        'sent'                  => 'Wysłano',
        'expired'               => 'Okres upłynął',
        'viewed'                => 'Wyświetlono',
        'approved'              => 'Zatwierdzono',
        'received'              => 'Otrzymano',
        'refused'               => 'Odrzucono',
        'restored'              => 'Przywrócono',
        'reversed'              => 'Odwrócono',
        'partial'               => 'Częściowo',
        'paid'                  => 'Opłacone',
        'pending'               => 'Oczekujące',
        'invoiced'              => 'Zafakturowano',
        'overdue'               => 'Zaległe',
        'unpaid'                => 'Nieopłacone',
        'cancelled'             => 'Anulowane',
        'voided'                => 'Unieważnione',
        'completed'             => 'Zakończone',
        'shipped'               => 'Wysłane',
        'refunded'              => 'Zwrócono',
        'failed'                => 'Zakończone niepowodzeniem',
        'denied'                => 'Odmówione',
        'processed'             => 'Przetworzone',
        'open'                  => 'Otwarte',
        'closed'                => 'Zamknięte',
        'billed'                => 'Zafakturowane',
        'delivered'             => 'Dostarczone',
        'returned'              => 'Zwrócone',
        'drawn'                 => 'Oznaczone',
        'not_billed'            => 'Nierozliczone',
        'issued'                => 'Wydano',
        'not_invoiced'          => 'Nie zafakturowano',
        'confirmed'             => 'Potwierdzone',
        'not_confirmed'         => 'Nie potwierdzone',
        'active'                => 'Aktywny',
        'ended'                 => 'Zakończony',
    ],

    'form_description' => [
        'companies'             => 'Zmień adres, logo i inne informacje twojej firmy',
        'billing'               => 'Szczegóły płatności pojawiają się w Twoim dokumencie.',
        'advanced'              => 'Wybierz kategorię, dodaj lub edytuj stopkę i dodaj załączniki do swojego :type.',
        'attachment'            => 'Pobierz pliki dołączone do tego :type',
    ],

    'slider' => [
        'create'            => ':user utworzył ten :type w dniu :date',
        'create_recurring'  => ':user utworzył ten szablon cykliczny w dniu :date',
        'send'              => ':user wysłał ten :type w dniu :date',
        'schedule'          => 'Powtarzaj co :interval :frequency od :date',
        'children'          => ':count :type zostały utworzone automatycznie',
        'cancel'            => ':user anulował ten :type w dniu :date',
    ],

    'messages' => [
        'email_sent'            => ':type wiadomość e-mail została wysłana!',
        'restored'              => ':type został przywrócony!',
        'marked_as'             => ':type oznaczono jako :status!',
        'marked_sent'           => ':type oznaczono jako wysłane!',
        'marked_paid'           => ':type oznaczono jako zapłacone!',
        'marked_viewed'         => ':type oznaczono jako obejrzane!',
        'marked_cancelled'      => ':type oznaczono jako anulowane!',
        'marked_received'       => ':type oznaczono jako otrzymane!',
    ],

    'recurring' => [
        'auto_generated'        => 'Generowany automatycznie',

        'tooltip' => [
            'document_date'     => 'Data typu :type zostanie automatycznie przypisana na podstawie harmonogramu i częstotliwości :Type.',
            'document_number'   => 'Numer :type zostanie przypisany automatycznie za każdym razem gdy cykliczny :type zostanie wygenerowany.',
        ],
    ],

    'empty_attachments'         => 'Brak plików dołączonych do tego :type.',
];
