<?php

return [

    'bill_number'           => 'Numer rachunku',
    'bill_date'             => 'Data rachunku',
    'bill_amount'           => 'Kwota rachunku',
    'total_price'           => 'Cena całkowita',
    'due_date'              => 'Termin płatności',
    'order_number'          => 'Numer zamówienia',
    'bill_from'             => 'Rachunek od',

    'quantity'              => 'Ilość',
    'price'                 => 'Cena',
    'sub_total'             => 'Suma częściowa',
    'discount'              => 'Rabat',
    'item_discount'         => 'Rabat w linii',
    'tax_total'             => 'Suma podatku',
    'total'                 => 'Razem',

    'item_name'             => 'Nazwa pozycji|Nazwy pozycji',
    'recurring_bills'       => 'Rachunek cykliczny|Rachunki cykliczne',

    'show_discount'         => ':discount% rabatu',
    'add_discount'          => 'Dodaj rabat',
    'discount_desc'         => 'z sumy częściowej',

    'payment_made'          => 'Płatność dokonana',
    'payment_due'           => 'Termin płatności',
    'amount_due'            => 'Kwota do zapłaty',
    'paid'                  => 'Opłacone',
    'histories'             => 'Historia',
    'payments'              => 'Płatności',
    'add_payment'           => 'Dodaj płatność',
    'mark_paid'             => 'Oznacz jako zapłacone',
    'mark_received'         => 'Oznacz jako otrzymane',
    'mark_cancelled'        => 'Oznacz jako anulowane',
    'download_pdf'          => 'Pobierz PDF',
    'send_mail'             => 'Wyślij e-mail',
    'create_bill'           => 'Utwórz rachunek',
    'receive_bill'          => 'Odbierz rachunek',
    'make_payment'          => 'Dokonaj płatności',

    'form_description' => [
        'billing'           => 'Szczegóły płatności pojawiają się w Twoim rachunku. Data rachunku jest używana w panelu i raportach. Wybierz datę, kiedy spodziewasz się zapłacić jako termin płatności.',
    ],

    'messages' => [
        'draft'             => 'To jest <b>SZKIC</b> rachunku i zostanie odzwierciedlony na wykresach po jego wysłaniu.',

        'status' => [
            'created'       => 'Utworzono :date',
            'receive' => [
                'draft'     => 'Nie otrzymano',
                'received'  => 'Otrzymano :date',
            ],
            'paid' => [
                'await'     => 'Oczekuje na płatność',
            ],
        ],
    ],

];
