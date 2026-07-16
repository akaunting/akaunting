<?php

return [

    'profile'               => 'Профиль',
    'invoices'              => 'Счета',
    'payments'              => 'Платежи',
    'payment_received'      => 'Платёж получен, спасибо!',
    'create_your_invoice'   => 'Теперь создайте свой собственный счёт — это бесплатно',
    'get_started'           => 'Начните бесплатно',
    'billing_address'       => 'Адрес для выставления счёта',
    'see_all_details'       => 'Показать все данные аккаунта',
    'all_payments'          => 'Войдите, чтобы просмотреть все платежи',
    'received_date'         => 'Дата получения',
    'redirect_description'  => 'Вы будете перенаправлены на сайт :name для осуществления платежа.',

    'last_payment'          => [
        'title'             => 'Последний платёж',
        'description'       => 'Вы осуществили этот платёж :date',
        'not_payment'       => 'Вы ещё не осуществили ни одного платежа.',
    ],

    'outstanding_balance'   => [
        'title'             => 'Непогашенный остаток',
        'description'       => 'Ваш непогашенный остаток:',
        'not_payment'       => 'У вас нет непогашенного остатка.',
    ],

    'latest_invoices'       => [
        'title'             => 'Последние счета',
        'description'       => ':date — вам выставлен счёт номер :invoice_number.',
        'no_data'           => 'У вас пока нет счетов.',
    ],

    'invoice_history'       => [
        'title'             => 'История счетов',
        'description'       => ':date — вам выставлен счёт номер :invoice_number.',
        'no_data'           => 'У вас пока нет истории счетов.',
    ],

    'payment_history'       => [
        'title'             => 'История платежей',
        'description'       => ':date — вы осуществили платёж на сумму :amount.',
        'invoice_description'=> ':date — вы осуществили платёж на сумму :amount по счёту номер :invoice_number.',

        'no_data'           => 'У вас пока нет истории платежей.',
    ],

    'payment_detail'        => [
        'description'       => 'Вы осуществили платёж на сумму :amount :date по этому счёту.'
    ],

];
