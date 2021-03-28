<?php

return [

    'whoops'              => 'Упс!',
    'hello'               => 'Привет!',
    'salutation'          => 'С уважением,<br>:company_name',
    'subcopy'             => 'Если у вас возникли проблемы c нажатием на кнопку ":text", скопируйте и вставьте следующий URL-адрес в ваш браузер: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Ошибка обновления :domain',
            'message' => 'Обновление :alias с :current_version до :new_version было прекращено из-за ошибки на шаге <strong>:step</strong> со следующим сообщением: :error_message',

        ],

        'slack' => [

            'message' => '⚠️ Ошибка обновления :domain',

        ],

    ],

];
