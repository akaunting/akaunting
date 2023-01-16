<?php

return [

    'success' => [
        'added'             => ':type додадено!',
        'updated'           => ':type надградено!',
        'deleted'           => ':type избришано!',
        'duplicated'        => ':type дупликат!',
        'imported'          => ':type превземено!',
        'enabled'           => ':type овозможено!',
        'disabled'          => ':type оневозможено!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => 'Error: Немате дозвола да ја менаџирате оваа компанија!',
        'customer'          => 'Error: Корисникот не е додаден! :name веќе ја користи оваа е-маил адреса',
        'no_file'           => 'Грешка: Не е селектирај ниеден фајл!',
        'last_category'     => 'Error: Неможе да се избрише последната :type категорија!',
        'invalid_apikey'     => 'Error: Внесениот токен е невалиден!',
        'import_column'     => 'Error: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => 'Error: Sheet name is not valid. Please, check the sample file.',
    ],
    'warning' => [
        'deleted'           => 'Предупредување: Немате дозвола да бришете <b>:name</b> затоа што има :text поврзаност.',
        'disabled'          => 'Предупредување: Немате дозвола да оневозможите  <b>:name</b>затоа што има :text поврзаност.
 
',
    ],

];
