<?php

return [

    'edit_columns'              => 'Редактиране на колона',
    'empty_items'               => 'Не сте добавили продукт',
    'grand_total'               => 'Обща сума',
    'accept_payment_online'     => 'Приеми плащания онлайн',
    'transaction'               => 'Плащане за :amount е направено от :account.
',
    'billing'                   => 'Фактуриране',
    'advanced'                  => 'Разширени',

    'invoice_detail' => [
        'marked'                => '<b>Вие</b> отбелязохте фактурата като',
        'services'              => 'Услуги',
        'another_item'          => 'Друга услуга',
        'another_description'   => 'и друго описание',
        'more_item'             => '+:count повече продукти',
    ],

    'statuses' => [
        'draft'                 => 'Чернова',
        'sent'                  => 'Изпратен',
        'expired'               => 'Изтекъл',
        'viewed'                => 'Разгледан',
        'approved'              => 'Одобрен',
        'received'              => 'Приет',
        'refused'               => 'Отказан',
        'restored'              => 'Възстановен',
        'reversed'              => 'Върнат',
        'partial'               => 'Частичен',
        'paid'                  => 'Платен',
        'pending'               => 'Предстоящ',
        'invoiced'              => 'Фактуриран',
        'overdue'               => 'Просрочен',
        'unpaid'                => 'Неплатен',
        'cancelled'             => 'Отменен',
        'voided'                => 'Анулиран',
        'completed'             => 'Завършен',
        'shipped'               => 'Изпратен',
        'refunded'              => 'Върнати пари',
        'failed'                => 'Неуспешен',
        'denied'                => 'Отказан',
        'processed'             => 'Обработен',
        'open'                  => 'Отворен',
        'closed'                => 'Затворен',
        'billed'                => 'Фактуриран',
        'delivered'             => 'Доставен',
        'returned'              => 'Върнат',
        'drawn'                 => 'Начертан',
        'not_billed'            => 'Не фактуриран',
        'issued'                => 'Издаден',
        'not_invoiced'          => 'Не фактуриран',
        'confirmed'             => 'Потвърден',
        'not_confirmed'         => 'Не потвърден',
        'active'                => 'Активен',
        'ended'                 => 'Приключил',
    ],

    'form_description' => [
        'companies'             => 'Променете адреса, логото и друга информация за вашата компания.',
        'billing'               => 'Данните за плащане се показват във вашия документ.',
        'advanced'              => 'Изберете категорията, добавете или редактирайте долния колонтитул и добавете прикачени файлове към вашия :type.',
        'attachment'            => 'Изтеглете прикачените файлове към този :type',
    ],

    'messages' => [
        'email_sent'            => ':type имейлът е изпратен!',
        'marked_as'             => ':type е маркиран като :status!',
        'marked_sent'           => ':type е маркиран като изпратен!',
        'marked_paid'           => ':type е маркиран като платен!',
        'marked_viewed'         => ':type е маркиран като прегледан!',
        'marked_cancelled'      => ':type е маркиран като отменен!',
        'marked_received'       => ':type е маркиран като получен!',
    ],

    'recurring' => [
        'auto_generated'        => 'Автоматично генериране',

        'tooltip' => [
            'document_date'     => 'Датата :type ще бъде зададена автоматично въз основа на графика и честотата на :type.',
            'document_number'   => 'Номерът :type ще бъде присвоен автоматично, когато се генерира всеки повтарящ се :type.',
        ],
    ],

];
