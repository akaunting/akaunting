<?php

return [

    'success' => [
        'added'             => ':type додат!',
        'updated'           => ':type ажуриран!',
        'deleted'           => ':type избрисан!',
        'duplicated'        => ':type умножен!',
        'imported'          => ':type увезен!',
        'enabled'           => ':type омогућен!',
        'disabled'          => ':type онемогућен!',
    ],

    'error' => [
        'over_payment'      => 'Грешка: Уплата није додата! Износ који сте унели прелази укупан износ: :amount',
        'not_user_company'  => 'Грешка: Није вам дозвољено управљање овом фирмом!',
        'customer'          => 'Грешка: Корисник није креиран! :name већ корисити ову адресу е-поште.',
        'no_file'           => 'Грешка: Није одабрана ниједна датотека!',
        'last_category'     => 'Грешка: Није могуће избрисати задњу :type категорију!',
        'invalid_token'     => 'Грешка: Upisani token nije valjan!',
        'import_column'     => 'Грешка: :message Назив табле: :sheet. Број линије: :line.',
        'import_sheet'      => 'Грешка: Назив табле није валидан. Молимо Вас, проверите фајл узорак.',
    ],

    'warning' => [
        'deleted'           => 'Упозорење: Није вам дозвољено да избришете <b>:name</b> јер постоји веза са :text.',
        'disabled'          => 'Упозорење: Није вам дозвољено да онемогућите <b>:name</b> јер постоји веза са :text.',
        'disable_code'      => 'Warning: You are not allowed to disable or change the currency of <b>:name</b> because it has :text related.',
    ],

];
