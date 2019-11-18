<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Счёт номер {invoice_number} создан',
        'body'          => 'Дорогой {customer_name},<br /><br />Мы подготовили для Вас следующий счет: <strong>{invoice_number}</strong>.<br /><br />Детали счета Вы можете посмотреть и оплатить по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Не стесняйтесь обращаться к нам по любому вопросу.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} уведомление об истечении счета',
        'body'          => 'Дорогой {customer_name},<br /><br />Это уведомление об истечении счета номер <strong>{invoice_number}</strong>.<br /><br />Сумма счета составляет {invoice_total} и истекает <strong>{invoice_due_date}</strong>.<br /><br />Детали счета Вы можете посмотреть и оплатить по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} уведомление об истечении счета',
        'body'          => 'Здравствуйте,<br /><br />{customer_name} получил уведомление об истечении счета номер <strong>{invoice_number}</strong>.<br /><br />Сумма счета составляет {invoice_total} и истекает <strong>{invoice_due_date}</strong>.<br /><br />Детали счета можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} рекуррентный счет создан',
        'body'          => 'Уважаемый {customer_name},<br /><br />На основе вашей периодичности, мы подготовили для вас следующий счет: <strong>{invoice_number}</strong>.<br /><br />Детали счета Вы можете посмотреть и оплатить по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Не стесняйтесь обращаться к нам по любому вопросу.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} рекуррентный счет создан',
        'body'          => 'Здравствуйте,<br /><br /> На основе периодичности клиента {customer_name}, счет номер <strong>{invoice_number}</strong> был создан автоматически.<br /><br />Детали счета можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Получен платеж для счета номер {invoice_number}',
        'body'          => 'Уважаемый {customer_name},<br /><br />Благодарим вас за оплату. Ниже приведена информация об оплате:<br /><br />-------------------------------------------------<br /><br />Сумма: <strong>{transaction_total}<br /></strong>Дата: <strong>{transaction_paid_date}</strong><br />Номер счета: <strong>{invoice_number}<br /><br /><br /></strong>-----------------------------------------<br /><br />Вы всегда можете увидеть детали счета по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Не стесняйтесь обращаться к нам по любому вопросу.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Получен платеж для счета номер {invoice_number}',
        'body'          => 'Здравствуйте,<br /><br />{customer_name} записал оплату по счету <strong>{invoice_number}</strong>.<br /><br />Детали счета можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} напоминание о закупке',
        'body'          => 'Здравствуйте,<br /><br />Это напоминание о закупке номер <strong>{bill_number}</strong> у поставщика {vendor_name}.<br /><br />Сумма закупки составляет {bill_total} и истекает <strong>{bill_due_date}</strong>.<br /><br />Детали закупки можно посмотреть по следующей ссылке: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} рекуррентная закупка создана',
        'body'          => 'Здравствуйте,<br /><br /> На основе периодичности {vendor_name}, <strong>{bill_number}</strong> счет-фактура был создан автоматически.<br /><br />Детали счета можно посмотреть по следующей ссылке: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

];
