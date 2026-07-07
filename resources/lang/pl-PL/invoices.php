<?php

return [

    'invoice_number'        => 'Numer faktury',
    'invoice_date'          => 'Data faktury',
    'invoice_amount'        => 'Kwota faktury',
    'total_price'           => 'Cena całkowita',
    'due_date'              => 'Termin płatności',
    'order_number'          => 'Numer zamówienia',
    'bill_to'               => 'Rachunek dla',
    'cancel_date'           => 'Data anulowania',

    'quantity'              => 'Ilość',
    'price'                 => 'Cena',
    'sub_total'             => 'Suma częściowa',
    'discount'              => 'Rabat',
    'item_discount'         => 'Rabat w linii',
    'tax_total'             => 'Suma podatku',
    'total'                 => 'Razem',

    'item_name'             => 'Nazwa pozycji|Nazwy pozycji',
    'recurring_invoices'    => 'Faktura cykliczna|Faktury cykliczne',

    'show_discount'         => ':discount% rabatu',
    'add_discount'          => 'Dodaj rabat',
    'discount_desc'         => 'z sumy częściowej',

    'payment_due'           => 'Płatność należna',
    'paid'                  => 'Zapłacone',
    'histories'             => 'Historie',
    'payments'              => 'Płatności',
    'add_payment'           => 'Dodaj płatność',
    'mark_paid'             => 'Oznacz jako zapłacone',
    'mark_sent'             => 'Oznacz jako wysłane',
    'mark_viewed'           => 'Oznacz jako wyświetlone',
    'mark_cancelled'        => 'Oznacz jako anulowane',
    'download_pdf'          => 'Pobierz PDF',
    'send_mail'             => 'Wyślij e-mail',
    'all_invoices'          => 'Zaloguj się, aby zobaczyć wszystkie faktury',
    'create_invoice'        => 'Utwórz fakturę',
    'send_invoice'          => 'Wyślij fakturę',
    'get_paid'              => 'Otrzymaj zapłatę',
    'accept_payments'       => 'Akceptuj płatności online',
    'payments_received'     => 'Otrzymane płatności',
    'over_payment'          => 'Wprowadzona kwota przekracza sumę: :amount',

    'form_description' => [
        'billing'           => 'Szczegóły rozliczeń pojawiają się na Twojej fakturze. Data faktury jest używana w panelu i raportach. Wybierz datę, do której spodziewasz się otrzymać zapłatę, jako termin płatności.',
    ],

    'messages' => [
        'email_required'    => 'Brak adresu e-mail dla tego klienta!',
        'totals_required'   => 'Sumy faktury są wymagane. Edytuj :type i zapisz ponownie.',

        'draft'             => 'To jest faktura <b>WERSJA ROBOCZA</b> i zostanie odzwierciedlona na wykresach po jej wysłaniu.',

        'status' => [
            'created'       => 'Utworzono :date',
            'viewed'        => 'Wyświetlone',
            'send' => [
                'draft'     => 'Nie wysłane',
                'sent'      => 'Wysłane :date',
            ],
            'paid' => [
                'await'     => 'Oczekiwanie na płatność',
            ],
        ],

        'name_or_description_required' => 'Twoja faktura musi zawierać co najmniej jedno z: <b>:name</b> lub <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Twój klient może zobaczyć fakturę pod tym linkiem',
        'copy_link'         => 'Skopiuj link i udostępnij go swojemu klientowi.',
        'success_message'   => 'Skopiowano link udostępniania do schowka!',
    ],

    'sticky' => [
        'description'       => 'Podglądasz, jak Twój klient zobaczy wersję internetową Twojej faktury.',
    ],

];
