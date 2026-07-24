<?php

return [

    'edit_columns'              => 'Уреди колони',
    'empty_items'               => 'Немате додадено ниту едена ставка.',
    'grand_total'               => 'Вкупно',
    'accept_payment_online'     => 'Прифатете плаќања преку Интернет',
    'transaction'               => 'Уплатата за :amount беше извршена преку :account.',
    'portal_transaction'        => 'Уплатата за :amount беше извршена преку :payment_method.',
    'billing'                   => 'Наплата',
    'advanced'                  => 'Напредно',
    'item_price_hidden'         => 'Оваа колона е скриена на вашиот :type.',

    'actions' => [
        'cancel'                => 'Откажи',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Вие</b> ја означивте оваа фактура како',
        'services'              => 'Услуги',
        'another_item'          => 'Друга ставка',
        'another_description'   => 'и друг опис',
        'more_item'             => '+:count повеќе ставка',
    ],

    'statuses' => [
        'draft'                 => 'Нацрт',
        'sent'                  => 'Испратено',
        'expired'               => 'Изминат рок',
        'viewed'                => 'Прегледано',
        'approved'              => 'Одобрено',
        'received'              => 'Примено',
        'refused'               => 'Одбиено',
        'restored'              => 'Обновено',
        'reversed'              => 'Вратено назад',
        'partial'               => 'Делумно',
        'paid'                  => 'Платено',
        'pending'               => 'Во очекување',
        'invoiced'              => 'Фактурирано',
        'overdue'               => 'Доспеано',
        'unpaid'                => 'Неплатено',
        'cancelled'             => 'Откажано',
        'voided'                => 'Сторнирано',
        'completed'             => 'Завршено',
        'shipped'               => 'Отпремено',
        'refunded'              => 'Рефундирано',
        'failed'                => 'Неуспешно',
        'denied'                => 'Одбиено',
        'processed'             => 'Процесирано',
        'open'                  => 'Отворено',
        'closed'                => 'Затворено',
        'billed'                => 'Фактурирано',
        'delivered'             => 'Доставено',
        'returned'              => 'Вратено',
        'drawn'                 => 'Извлечено',
        'not_billed'            => 'Не е наплатено',
        'issued'                => 'Издадено',
        'not_invoiced'          => 'Не е фактурирано',
        'confirmed'             => 'Потврдена',
        'not_confirmed'         => 'Не е потврдена',
        'active'                => 'Активен',
        'ended'                 => 'Завршено',
    ],

    'form_description' => [
        'companies'             => 'Променете ја адресата, логото и другите информации за вашата компанија.',
        'billing'               => 'Деталите за наплата се појавуваат во вашиот документ.',
        'advanced'              => 'Изберете ја категоријата, додајте или уредете го подножјето и додајте прилози на вашиот :type.',
        'attachment'            => 'Преземете ги документите прикачени на оваа :type',
    ],

    'slider' => [
        'create'            => ':user го создаде овој :type на :date',
        'create_recurring'  => ':user го создаде овој повторувачки шаблон на :date',
        'send'              => ':user го испрати овој :type на :date',
        'schedule'          => 'Повторувај секој :interval :frequency од :date',
        'children'          => ':count :type беа создадени автоматски',
        'cancel'            => ':user го откажа овој :type на :date',
    ],

    'messages' => [
        'email_sent'            => ':type Е-маил пораката е успешно пратена!',
        'restored'              => ':type е обновено!',
        'marked_as'             => ':type означен како :status!',
        'marked_sent'           => ':type означен како пратено!',
        'marked_paid'           => ':type означено како платено!',
        'marked_viewed'         => ':type означен како видено!',
        'marked_cancelled'      => ':type означено како откажано!',
        'marked_received'       => ':type означено како примено!',
    ],

    'recurring' => [
        'auto_generated'        => 'Автоматски генериран',

        'tooltip' => [
            'document_date'     => 'Датумот :type автоматски ќе се додели врз основа на распоредот и зачестеноста на :type.',
            'document_number'   => 'Бројот :type автоматски ќе се додели кога ќе се генерира секој повторлив :type.',
        ],
    ],

    'empty_attachments'         => 'Нема прикачени датотеки за овој :type.',

];
