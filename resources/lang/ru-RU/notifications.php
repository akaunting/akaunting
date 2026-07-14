<?php

return [

    'whoops'              => 'Упс!',
    'hello'               => 'Привет!',
    'salutation'          => 'С уважением,<br> :company_name',
    'subcopy'             => 'Если у вас возникли проблемы с нажатием на кнопку ":text", скопируйте и вставьте следующий URL-адрес в ваш браузер: [:url](:url)',
    'mark_read'           => 'Отметить как прочитанное',
    'mark_read_all'       => 'Отметить все как прочитанные',
    'empty'               => 'Ура, уведомлений нет!',
    'new_apps'            => 'Приложение :app доступно. <a href=":url">Ознакомьтесь</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Ошибка обновления на :domain',
            'description'   => 'Обновление :alias с :current_version до :new_version завершилось ошибкой на шаге <strong>:step</strong> со следующим сообщением: :error_message',

        ],

        'slack' => [

            'description'   => 'Ошибка обновления на :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Загрузка готова',
            'description'   => 'Файл готов к загрузке по следующей ссылке:',

        ],

        'failed' => [

            'title'         => 'Ошибка загрузки',
            'description'   => 'Не удалось создать файл из-за следующей проблемы:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Импорт завершён',
            'description'   => 'Импорт завершён, записи доступны в вашей панели.',

        ],

        'failed' => [

            'title'         => 'Ошибка импорта',
            'description'   => 'Не удалось импортировать файл из-за следующих проблем:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Экспорт готов',
            'description'   => 'Файл экспорта готов к загрузке по следующей ссылке:',

        ],

        'failed' => [

            'title'         => 'Ошибка экспорта',
            'description'   => 'Не удалось создать файл экспорта из-за следующей проблемы:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Недействительный адрес эл. почты :type',
            'description'   => 'Адрес эл. почты :email был отмечен как недействительный, и пользователь был отключён. Пожалуйста, проверьте следующее сообщение об ошибке и исправьте адрес эл. почты:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Загрузка готова',
            'description'   => 'Ваш файл <strong>:type</strong> готов к <a href=":url" target="_blank"><strong>загрузке</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Ошибка загрузки',
            'description'   => 'Не удалось создать файл из-за нескольких проблем. Подробности в письме на вашей эл. почте.',

        ],

        'export_completed' => [

            'title'         => 'Экспорт готов',
            'description'   => 'Ваш файл экспорта <strong>:type</strong> готов к <a href=":url" target="_blank"><strong>загрузке</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Ошибка экспорта',
            'description'   => 'Не удалось создать файл экспорта из-за нескольких проблем. Подробности в письме на вашей эл. почте.',

        ],

        'import_completed' => [

            'title'         => 'Импорт завершён',
            'description'   => 'Ваши данные <strong>:type</strong> в количестве <strong>:count</strong> записей успешно импортированы.',

        ],

        'import_failed' => [

            'title'         => 'Ошибка импорта',
            'description'   => 'Не удалось импортировать файл из-за нескольких проблем. Подробности в письме на вашей эл. почте.',

        ],

        'new_apps' => [

            'title'         => 'Новое приложение',
            'description'   => 'Вышло приложение <strong>:name</strong>. Вы можете <a href=":url">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Новый счёт',
            'description'   => 'Создан счёт <strong>:invoice_number</strong>. Вы можете <a href=":invoice_portal_link">нажать здесь</a>, чтобы посмотреть подробности и приступить к оплате.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Просроченный счёт',
            'description'   => 'Срок оплаты счёта <strong>:invoice_number</strong> истёк <strong>:invoice_due_date</strong>. Вы можете <a href=":invoice_portal_link">нажать здесь</a>, чтобы посмотреть подробности и приступить к оплате.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Просроченный счёт',
            'description'   => 'Срок оплаты счёта <strong>:invoice_number</strong> истёк <strong>:invoice_due_date</strong>. Вы можете <a href=":invoice_admin_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Новый повторяющийся счёт',
            'description'   => 'Счёт <strong>:invoice_number</strong> создан по вашему шаблону повторения. Вы можете <a href=":invoice_portal_link">нажать здесь</a>, чтобы посмотреть подробности и приступить к оплате.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Новый повторяющийся счёт',
            'description'   => 'Счёт <strong>:invoice_number</strong> создан по шаблону повторения <strong>:customer_name</strong>. Вы можете <a href=":invoice_admin_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Счёт просмотрен',
            'description'   => '<strong>:customer_name</strong> просмотрел счёт <strong>:invoice_number</strong>. Вы можете <a href=":invoice_admin_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Платёж получен',
            'description'   => 'Спасибо за оплату счёта <strong>:invoice_number</strong>. Вы можете <a href=":invoice_portal_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Платёж получен',
            'description'   => 'Спасибо за оплату счёта <strong>:invoice_number</strong>. Вы можете <a href=":invoice_portal_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Платёж получен',
            'description'   => ':customer_name произвёл оплату по счёту <strong>:invoice_number</strong>. Вы можете <a href=":invoice_admin_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Просроченный счёт на оплату',
            'description'   => 'Срок оплаты счёта на оплату <strong>:bill_number</strong> истёк <strong>:bill_due_date</strong>. Вы можете <a href=":bill_admin_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Новый повторяющийся счёт на оплату',
            'description'   => 'Счёт на оплату <strong>:bill_number</strong> создан по шаблону повторения <strong>:vendor_name</strong>. Вы можете <a href=":bill_admin_link">нажать здесь</a>, чтобы посмотреть подробности.',

        ],

        'invalid_email' => [

            'title'         => 'Недействительный адрес эл. почты :type',
            'description'   => 'Адрес эл. почты <strong>:email</strong> был отмечен как недействительный, и пользователь был отключён. Пожалуйста, проверьте и исправьте адрес эл. почты.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type прочитал это уведомление!',
        'mark_read_all'         => ':type прочитал все уведомления!',

    ],

    'browser' => [

        'firefox' => [

            'title'         => 'Настройка значков в Firefox',
            'description'   => '<span class="font-medium">Если значки не отображаются;</span> <br /> <span class="font-medium">Разрешите страницам использовать свои шрифты вместо выбранных вами выше</span> <br /><br /> <span class="font-bold"> Настройки (Предпочтения) > Шрифты > Дополнительно </span>',

        ],

    ],

];
