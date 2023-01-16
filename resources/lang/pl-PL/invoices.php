<?php

return [

    'invoice_number'        => 'Numer faktury',
    'invoice_date'          => 'Data wystawienia faktury',
    'total_price'           => 'Cena Całkowita',
    'due_date'              => 'Termin płatności',
    'order_number'          => 'Numer zamówienia',
    'bill_to'               => 'Dane płatnika',

    'quantity'              => 'Ilość',
    'price'                 => 'Cena',
    'sub_total'             => 'Suma częściowa',
    'discount'              => 'Rabat',
    'item_discount'         => 'Rabat Liniowy',
    'tax_total'             => 'Suma podatku',
    'total'                 => 'Razem',

    'item_name'             => 'Nazwa artykułu|Nazwy artykułów',

    'show_discount'         => ':discount% Rabatu',
    'add_discount'          => 'Dodaj Rabat',
    'discount_desc'         => 'sumy częściowej',

    'payment_due'           => 'Termin płatności',
    'paid'                  => 'Zapłacone',
    'histories'             => 'Historia',
    'payments'              => 'Płatności',
    'add_payment'           => 'Dodaj płatność',
    'mark_paid'             => 'Oznacz jako zapłacone',
    'mark_sent'             => 'Oznacz jako wysłane',
    'mark_viewed'           => 'Oznacz jako obejrzane',
    'mark_cancelled'        => 'Oznacz jako Anulowane',
    'download_pdf'          => 'Pobierz PDF',
    'send_mail'             => 'Wyślij e-mail',
    'all_invoices'          => 'Zaloguj się, aby wyświetlić wszystkie faktury',
    'create_invoice'        => 'Utwórz fakturę',
    'send_invoice'          => 'Wyślij fakturę',
    'get_paid'              => 'Otrzymaj zapłatę',
    'accept_payments'       => 'Akceptuj płatności online',

    'messages' => [
        'email_required'    => 'Brak adresu e-mail dla tego klienta!',
        'draft'             => 'To jest <b>SZKIC</b> faktury i zostanie odzwierciedlone na wykresach po jej wysłaniu.',

        'status' => [
            'created'       => 'Utworzono :date',
            'viewed'        => 'Przeglądane',
            'send' => [
                'draft'     => 'Nie wysłano',
                'sent'      => 'Wysłano :date',
            ],
            'paid' => [
                'await'     => 'Oczekiwanie na płatność',
            ],
        ],
    ],

];
