<?php

return [

    'whoops'              => 'Опа!',
    'hello'               => 'Здравейте!',
    'salutation'          => 'Поздрави,<br> :company_name',
    'subcopy'             => 'Ако срещате проблеми при щракване върху бутона ":text", копирайте и поставете URL-долу във вашия уеб браузър: [: url](:url)',
    'mark_read'           => 'Маркирай като прочетено',
    'mark_read_all'       => 'Маркирай всички като прочетени',
    'empty'               => 'Уау, няма известия!',
    'new_apps'            => ':app е налично. <a href=":url">Вижте сега</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Неуспешна актуализация на :domain',
            'description'   => 'Актуализацията на :alias от :current_version на :new_version се провали в стъпка <strong>:step</strong> със следното съобщение: :error_message',

        ],

        'slack' => [

            'description'   => 'Неуспешна актуализация на :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Импортирането завърши',
            'description'   => 'Импортирането е завършено и записите са налични във вашия панел.',

        ],

        'failed' => [

            'title'         => 'Грешка при импорт',
            'description'   => 'Файлът не може да се импортира поради следните проблеми:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Експортът е готов',
            'description'   => 'Експортираният файл е готов за изтегляне от следната връзка:',

        ],

        'failed' => [

            'title'         => 'Грешка при експорт',
            'description'   => 'Файлът не може да се експортира поради следните проблеми:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Експортът е готов',
            'description'   => 'Вашият <strong>:type</strong> експортиран файл е готов за <a href=":url" target="_blank"><strong>изтегляне</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Грешка при експорт',
            'description'   => 'Не може да се създаде експортиран файл поради няколко проблема. Проверете имейла си за подробности.',

        ],

        'import_completed' => [

            'title'         => 'Импортирането завърши',
            'description'   => 'Вашите <strong>:type</strong> данни на <strong>:count</strong>  реда са импортирани успешно.',

        ],

        'import_failed' => [

            'subject'       => 'Грешка при импорт',
            'description'   => 'Не може да се импортира файл поради няколко проблема. Проверете имейла си за подробности.',

        ],

        'new_apps' => [

            'title'         => 'Ново приложение',
            'description'   => 'Илязло ново е приложение <strong>:name</strong>. Можете да <a href=":url">щракнете тук</a>, за да видите подробностите.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Нова фактура',
            'description'   => '<strong>:invoice_number</strong> фактурата е създадена. Можете да <a href=":invoice_portal_link">щракнете тук</a>, за да видите подробностите и да продължите с плащането.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Просрочена фактура',
            'description'   => '<strong>:invoice_number</strong> фактурата беше дължима <strong>:invoice_due_date</strong>. Можете да <a href=":invoice_portal_link">щракнете тук</a>, за да видите подробностите и да продължите с плащането.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Просрочена фактура',
            'description'   => '<strong>:invoice_number</strong> фактурата беше дължима <strong>:invoice_due_date</strong>. Можете да <a href=":invoice_admin_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Нова повтаряща се фактура',
            'description'   => 'Фактура <strong>:invoice_number</strong> е създадена въз основа на вашия повтарящ се цикъл. Можете да <a href=":invoice_portal_link">щракнете тук</a>, за да видите подробностите и да продължите с плащането.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Нова повтаряща се фактура',
            'description'   => 'Фактура <strong>:invoice_number</strong> е създадена въз основа на повтарящ се цикъл <strong>:customer_name</strong>. Можете да <a href=":invoice_admin_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Прегледана фактура',
            'description'   => '<strong>:customer_name</strong> е прегледал фактурата <strong>:invoice_number</strong>. Можете да <a href=":invoice_admin_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Получено плащане',
            'description'   => 'Благодарим ви за плащането за фактура <strong>:invoice_number</strong>. Можете да <a href=":invoice_portal_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Получено плащане',
            'description'   => 'Благодарим ви за плащането за фактура <strong>:invoice_number</strong>. Можете да <a href=":invoice_portal_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Получено плащане',
            'description'   => ':customer_name записа плащане за <strong>:invoice_number</strong> фактура. Можете да <a href=":invoice_admin_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Просрочена фактура',
            'description'   => 'Фактура <strong>:bill_number</strong> е била дължима до <strong>:bill_due_date</strong>. Можете да <a href=":bill_admin_link">щракнете тук</a>, за да видите подробностите.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Нова повтаряща се фактура',
            'description'   => 'Фактура <strong>:bill_number</strong> е създадена въз основа на <strong>:vendor_name</strong> повтарящ се цикъл. Можете да <a href=":bill_admin_link">щракнете тук</a>, за да видите подробностите.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type чете това известие!',
        'mark_read_all'         => ':type чете това известие!',

    ],
];
