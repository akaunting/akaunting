<?php

return [

    'whoops'              => 'Опа!',
    'hello'               => 'Здраво!',
    'salutation'          => 'Поздрав,<br> :company_name',
    'subcopy'             => 'Ако имате проблем со кликнување на копчето ":text", копирајте ја и залепете ја URL адресата подолу во вашиот веб-прелистувач: [:url](:url)',
    'mark_read'           => 'Означи како прочитано',
    'mark_read_all'       => 'Означи ги сите како прочитани',
    'empty'               => 'Одлично, нула известувања!',
    'new_apps'            => ':app е достапна. <a href=":url">Погледнете ја сега</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Ажурирањето не успеа на :domain',
            'description'   => 'Ажурирањето на :alias од :current_version на :new_version не успеа во чекорот <strong>:step</strong> со следната порака: :error_message',

        ],

        'slack' => [

            'description'   => 'Ажурирањето не успеа на :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Преземањето е подготвено',
            'description'   => 'Датотеката е подготвена за преземање од следниот линк:',

        ],

        'failed' => [

            'title'         => 'Преземањето не успеа',
            'description'   => 'Не може да се креира датотеката поради следниот проблем:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Увозот е завршен',
            'description'   => 'Увозот е завршен и записите се достапни во вашиот панел.',

        ],

        'failed' => [

            'title'         => 'Увозот не успеа',
            'description'   => 'Не може да се увезе датотеката поради следните проблеми:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Извозот е подготвен',
            'description'   => 'Датотеката за извоз е подготвена за преземање од следниот линк:',

        ],

        'failed' => [

            'title'         => 'Извозот не успеа',
            'description'   => 'Не може да се креира датотеката за извоз поради следниот проблем:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Невалидна :type е-пошта',
            'description'   => 'Е-пошта адресата :email е пријавена како невалидна и лицето е оневозможено. Проверете ја следната порака за грешка и поправете ја е-пошта адресата:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Преземањето е подготвено',
            'description'   => 'Вашата датотека <strong>:type</strong> е подготвена за <a href=":url" target="_blank"><strong>преземање</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Преземањето не успеа',
            'description'   => 'Не може да се креира датотеката поради повеќе проблеми. Проверете ја вашата е-пошта за детали.',

        ],

        'export_completed' => [

            'title'         => 'Извозот е подготвен',
            'description'   => 'Вашата датотека за извоз <strong>:type</strong> е подготвена за <a href=":url" target="_blank"><strong>преземање</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Извозот не успеа',
            'description'   => 'Не може да се креира датотеката за извоз поради повеќе проблеми. Проверете ја вашата е-пошта за детали.',

        ],

        'import_completed' => [

            'title'         => 'Увозот е завршен',
            'description'   => 'Вашиот <strong>:type</strong> со <strong>:count</strong> податоци е успешно увезен.',

        ],

        'import_failed' => [

            'title'         => 'Увозот не успеа',
            'description'   => 'Не може да се увезе датотеката поради повеќе проблеми. Проверете ја вашата е-пошта за детали.',

        ],

        'new_apps' => [

            'title'         => 'Нова апликација',
            'description'   => 'Апликацијата <strong>:name</strong> е достапна. Можете <a href=":url">да кликнете тука</a> за детали.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Нова фактура',
            'description'   => 'Фактурата <strong>:invoice_number</strong> е креирана. Можете <a href=":invoice_portal_link">да кликнете тука</a> за детали и да продолжите со плаќањето.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Фактура со истечен рок',
            'description'   => 'Фактурата <strong>:invoice_number</strong> доспеа на <strong>:invoice_due_date</strong>. Можете <a href=":invoice_portal_link">да кликнете тука</a> за детали и да продолжите со плаќањето.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Фактура со истечен рок',
            'description'   => 'Фактурата <strong>:invoice_number</strong> доспеа на <strong>:invoice_due_date</strong>. Можете <a href=":invoice_admin_link">да кликнете тука</a> за детали.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Нова повторувачка фактура',
            'description'   => 'Фактурата <strong>:invoice_number</strong> е креирана според вашиот повторувачки циклус. Можете <a href=":invoice_portal_link">да кликнете тука</a> за детали и да продолжите со плаќањето.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Нова повторувачка фактура',
            'description'   => 'Фактурата <strong>:invoice_number</strong> е креирана според повторувачкиот циклус на <strong>:customer_name</strong>. Можете <a href=":invoice_admin_link">да кликнете тука</a> за детали.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Фактура прегледана',
            'description'   => '<strong>:customer_name</strong> ја прегледа фактурата <strong>:invoice_number</strong>. Можете <a href=":invoice_admin_link">да кликнете тука</a> за детали.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Примено плаќање',
            'description'   => 'Ви благодариме за плаќањето за фактурата <strong>:invoice_number</strong>. Можете <a href=":invoice_portal_link">да кликнете тука</a> за детали.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Примено плаќање',
            'description'   => 'Ви благодариме за плаќањето за фактурата <strong>:invoice_number</strong>. Можете <a href=":invoice_portal_link">да кликнете тука</a> за детали.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Примено плаќање',
            'description'   => ':customer_name евидентираше плаќање за фактурата <strong>:invoice_number</strong>. Можете <a href=":invoice_admin_link">да кликнете тука</a> за детали.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Сметка со истечен рок',
            'description'   => 'Сметката <strong>:bill_number</strong> доспеа на <strong>:bill_due_date</strong>. Можете <a href=":bill_admin_link">да кликнете тука</a> за детали.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Нова повторувачка сметка',
            'description'   => 'Сметката <strong>:bill_number</strong> е креирана според повторувачкиот циклус на <strong>:vendor_name</strong>. Можете <a href=":bill_admin_link">да кликнете тука</a> за детали.',

        ],

        'invalid_email' => [

            'title'         => 'Невалидна :type е-пошта',
            'description'   => 'Е-пошта адресата <strong>:email</strong> е пријавена како невалидна и лицето е оневозможено. Ве молиме проверете ја и поправете ја е-пошта адресата.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type го прочита ова известување!',
        'mark_read_all'         => ':type ги прочита сите известувања!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Конфигурација на икони во Firefox',
            'description'  => '<span class="font-medium">Ако иконите не се прикажуваат, направете го следново:</span> <br /> <span class="font-medium">Дозволете страниците да ги избираат сопствените фонтови наместо вашите поставки</span> <br /><br /> <span class="font-bold"> Settings (Preferences) > Fonts > Advanced </span>',

        ],

    ],

];
