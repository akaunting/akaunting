<?php

return [

    'edit_columns'              => 'Редактировать столбцы',
    'empty_items'               => 'Вы не добавили ни одной позиции.',
    'grand_total'               => 'Итого',
    'accept_payment_online'     => 'Принимать онлайн-платежи',
    'transaction'               => 'Платёж на сумму :amount был осуществлён через :account.',
    'portal_transaction'        => 'Платёж на сумму :amount был осуществлён через :payment_method.',
    'billing'                   => 'Выставление счёта',
    'advanced'                  => 'Дополнительно',

    'item_price_hidden'         => 'Этот столбец скрыт в вашем :type.',

    'actions' => [
        'cancel'                => 'Отмена',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Вы</b> отметили этот счёт как',
        'services'              => 'Услуги',
        'another_item'          => 'Ещё одна позиция',
        'another_description'   => 'и другое описание',
        'more_item'             => '+:count ещё позиций',
    ],

    'statuses' => [
        'draft'                 => 'Черновик',
        'sent'                  => 'Отправлено',
        'expired'               => 'Срок истёк',
        'viewed'                => 'Просмотрено',
        'approved'              => 'Утверждено',
        'received'              => 'Получено',
        'refused'               => 'Отклонено',
        'restored'              => 'Восстановлено',
        'reversed'              => 'Отменено',
        'partial'               => 'Частично',
        'paid'                  => 'Оплачено',
        'pending'               => 'В ожидании',
        'invoiced'              => 'Счёт выставлен',
        'overdue'               => 'Просрочено',
        'unpaid'                => 'Не оплачено',
        'cancelled'             => 'Отменено',
        'voided'                => 'Аннулировано',
        'completed'             => 'Завершено',
        'shipped'               => 'Отгружено',
        'refunded'              => 'Возврат',
        'failed'                => 'Ошибка',
        'denied'                => 'Отказано',
        'processed'             => 'Обработано',
        'open'                  => 'Открыто',
        'closed'                => 'Закрыто',
        'billed'                => 'Счёт выставлен',
        'delivered'             => 'Доставлено',
        'returned'              => 'Возвращено',
        'drawn'                 => 'Выписано',
        'not_billed'            => 'Счёт не выставлен',
        'issued'                => 'Выпущено',
        'not_invoiced'          => 'Счёт не выставлен',
        'confirmed'             => 'Подтверждено',
        'not_confirmed'         => 'Не подтверждено',
        'active'                => 'Активно',
        'ended'                 => 'Завершено',
    ],

    'form_description' => [
        'companies'             => 'Измените адрес, логотип и другую информацию о вашей компании.',
        'billing'               => 'Данные для выставления счёта отображаются в вашем документе.',
        'advanced'              => 'Выберите категорию, добавьте или отредактируйте нижний колонтитул и добавьте вложения к вашему :type.',
        'attachment'            => 'Загрузить файлы, прикреплённые к этому :type',
    ],

    'slider' => [
        'create'            => ':user создал этот :type :date',
        'create_recurring'  => ':user создал этот шаблон повторения :date',
        'send'              => ':user отправил этот :type :date',
        'schedule'          => 'Повторять каждые :interval :frequency начиная с :date',
        'children'          => ':count :type были созданы автоматически',
        'cancel'            => ':user отменил этот :type :date',
    ],

    'messages' => [
        'email_sent'            => 'Письмо :type отправлено!',
        'restored'              => ':type восстановлено!',
        'marked_as'             => ':type отмечено как :status!',
        'marked_sent'           => ':type отмечено как отправлено!',
        'marked_paid'           => ':type отмечено как оплачено!',
        'marked_viewed'         => ':type отмечено как просмотрено!',
        'marked_cancelled'      => ':type отмечено как отменено!',
        'marked_received'       => ':type отмечено как получено!',
    ],

    'recurring' => [
        'auto_generated'        => 'Сгенерировано автоматически',

        'tooltip' => [
            'document_date'     => 'Дата :type будет автоматически установлена на основе расписания и частоты :type.',
            'document_number'   => 'Номер :type будет автоматически присвоен при генерации каждого повторяющегося :type.',
        ],
    ],

    'empty_attachments'         => 'К этому :type не прикреплено ни одного файла.',
];
