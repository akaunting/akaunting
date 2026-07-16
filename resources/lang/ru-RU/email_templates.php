<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Счёт {invoice_number} создан',
        'body'          => 'Уважаемый(ая) {customer_name},<br /><br />Мы подготовили для вас следующий счёт: <strong>{invoice_number}</strong>.<br /><br />Вы можете посмотреть детали счёта и произвести оплату по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Если у вас есть вопросы, пожалуйста, обращайтесь к нам.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Уведомление о просрочке счёта {invoice_number}',
        'body'          => 'Уважаемый(ая) {customer_name},<br /><br />Это уведомление о просрочке счёта <strong>{invoice_number}</strong>.<br /><br />Сумма счёта составляет {invoice_total}, срок оплаты истёк <strong>{invoice_due_date}</strong>.<br /><br />Вы можете посмотреть детали счёта и произвести оплату по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Уведомление о просрочке счёта {invoice_number}',
        'body'          => 'Здравствуйте,<br /><br />{customer_name} получил уведомление о просрочке счёта <strong>{invoice_number}</strong>.<br /><br />Сумма счёта составляет {invoice_total}, срок оплаты истёк <strong>{invoice_due_date}</strong>.<br /><br />Детали счёта можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Повторяющийся счёт {invoice_number} создан',
        'body'          => 'Уважаемый(ая) {customer_name},<br /><br />На основе вашего шаблона повторения мы подготовили для вас следующий счёт: <strong>{invoice_number}</strong>.<br /><br />Вы можете посмотреть детали счёта и произвести оплату по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Если у вас есть вопросы, пожалуйста, обращайтесь к нам.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Повторяющийся счёт {invoice_number} создан',
        'body'          => 'Здравствуйте,<br /><br />На основе шаблона повторения {customer_name} счёт <strong>{invoice_number}</strong> был создан автоматически.<br /><br />Детали счёта можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => 'Счёт {invoice_number} просмотрен',
        'body'          => 'Здравствуйте,<br /><br />{customer_name} просмотрел счёт <strong>{invoice_number}</strong>.<br /><br />Детали счёта можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Квитанция об оплате счёта {invoice_number}',
        'body'          => 'Уважаемый(ая) {customer_name},<br /><br />Благодарим вас за платёж. Ниже приведена информация об оплате:<br /><br />-------------------------------------------------<br />Сумма: <strong>{transaction_total}</strong><br />Дата: <strong>{transaction_paid_date}</strong><br />Номер счёта: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Вы всегда можете посмотреть детали счёта по следующей ссылке: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Если у вас есть вопросы, пожалуйста, обращайтесь к нам.<br /><br />С уважением,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Получен платёж по счёту {invoice_number}',
        'body'          => 'Здравствуйте,<br /><br />{customer_name} произвёл оплату по счёту <strong>{invoice_number}</strong>.<br /><br />Детали счёта можно посмотреть по следующей ссылке: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Напоминание о счёте на оплату {bill_number}',
        'body'          => 'Здравствуйте,<br /><br />Это напоминание о счёте на оплату <strong>{bill_number}</strong> от поставщика {vendor_name}.<br /><br />Сумма счёта на оплату составляет {bill_total}, срок оплаты <strong>{bill_due_date}</strong>.<br /><br />Детали счёта на оплату можно посмотреть по следующей ссылке: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Повторяющийся счёт на оплату {bill_number} создан',
        'body'          => 'Здравствуйте,<br /><br />На основе шаблона повторения {vendor_name} счёт на оплату <strong>{bill_number}</strong> был создан автоматически.<br /><br />Детали счёта на оплату можно посмотреть по следующей ссылке: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />С уважением,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Ваша квитанция от {company_name}',
        'body'          => 'Уважаемый(ая) {contact_name},<br /><br />Благодарим вас за платёж.<br /><br />Вы можете посмотреть детали платежа по следующей ссылке: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Если у вас есть вопросы, пожалуйста, обращайтесь к нам.<br /><br />С уважением,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Платёж произведён {company_name}',
        'body'          => 'Уважаемый(ая) {contact_name},<br /><br />Мы произвели следующий платёж.<br /><br />Вы можете посмотреть детали платежа по следующей ссылке: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Если у вас есть вопросы, пожалуйста, обращайтесь к нам.<br /><br />С уважением,<br />{company_name}',
    ],

];
