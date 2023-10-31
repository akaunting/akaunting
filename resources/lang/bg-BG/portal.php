<?php

return [

    'profile'               => 'Профил',
    'invoices'              => 'Фактури',
    'payments'              => 'Плащания',
    'payment_received'      => 'Плащането е получено, благодаря!',
    'create_your_invoice'   => 'Сега създайте своя собствена фактура — безплатно е',
    'get_started'           => 'Започенете безплатно',
    'billing_address'       => 'Данъчен адрес',
    'see_all_details'       => 'Вижте всички подробности за акаунта',
    'all_payments'          => 'Вход за да видите всички плащания',
    'received_date'         => 'Дата на приемане',
    'redirect_description'  => 'Ще бъдете пренасочени към уебсайта :name, за да извършите плащането.',

    'last_payment'          => [
        'title'             => 'Последна дата на плащане',
        'description'       => 'Вие сте направили това плащане на :date',
        'not_payment'       => 'Все още не сте извършили плащане.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Неизплатен баланс',
        'description'       => 'Вашето неплатено салдо е:',
        'not_payment'       => 'Все още нямате неплатено салдо.',
    ],

    'latest_invoices'       => [
        'title'             => 'Последни фактури',
        'description'       => ':date - Таксувани сте с номер на фактура :invoice_number.',
        'no_data'           => 'Все още нямате фактура.',
    ],

    'invoice_history'       => [
        'title'             => 'История на фактури',
        'description'       => ':date - Таксувани сте с номер на фактура :invoice_number.',
        'no_data'           => 'Все още нямате история на фактурите.',
    ],

    'payment_history'       => [
        'title'             => 'История на плащанията',
        'description'       => ':date - Направихте плащане на :amount.',
        'invoice_description'=> ':date - Направихте плащане на :amount по номер на фактура :invoice_number.',

        'no_data'           => 'Все още нямате история на плащанията.',
    ],

    'payment_detail'        => [
        'description'       => 'Вие извършихте :amount плащане на :date по тази фактура.'
    ],

];
