<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number}Рахунок створено',
        'body'          => 'Шановний {customer_name},<br /><br />Ми підготували для вас наступний рахунок-фактуру: <strong>{invoice_number}</strong>.<br /><br />Ви можете побачити реквізити рахунку і продовжити оплату за наступним посиланням: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Ви можете зв\'язатися з нами для будь-яких питань.<br /><br />З найкращими побажаннями,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} повідомлення про прострочення рахунку',
        'body'          => 'Шановний {customer_name},<br /><br />Це прострочена інформація для <strong>{invoice_number}</strong> рахунку-фактури.<br /><br />Загальний рахунок складає {invoice_total} та має бути приблизно <strong>{invoice_due_date}</strong>.<br /><br />Ви можете побачити реквізити рахунку та продовжити оплату за наступним посиланням: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />найкращих поваг,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} повідомлення про прострочення рахунку',
        'body'          => 'Привіт,<br /><br />{customer_name} отримано повідомлення про прострочення <strong>{invoice_number}</strong> рахунку-фактури .<br /><br />Загальна сума  складає {invoice_total} та має бути приблизно <strong>{invoice_due_date}</strong>.<br /><br />Ви можете побачити реквізити за наступним посиланням: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />З повагою,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} повторний рахунок створено',
        'body'          => 'Шановний {customer_name},<br /><br />Ми підготували для вас наступний рахунок-фактуру: <strong>{invoice_number}</strong>.<br /><br />Ви можете побачити реквізити рахунку і продовжити оплату за наступним посиланням: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Ви можете зв\'язатися з нами з будь-яких питань.<br /><br />З найкращими побажаннями,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} повторний рахунок створено',
        'body'          => 'Привіт,<br /><br />{customer_name} отримано повідомлення про прострочення <strong>{invoice_number}</strong> рахунку-фактури .<br /><br />Загальна сума  складає {invoice_total} та має бути приблизно <strong>{invoice_due_date}</strong>.<br /><br />Ви можете побачити реквізити за наступним посиланням: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />З повагою,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Платіж отримано за рахунок {invoice_number}',
        'body'          => 'Шановний {customer_name},<br /><br />Спасибі за оплату. Деталі про оплату:<br /><br />-------------------------------------------------<br /><br />Сума: <strong>{transaction_total}<br /></strong>Дата: <strong>{transaction_paid_date}</strong><br />Номер рахунку: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Деталі рахунку можна побачити за наступним посиланням: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Звертайтеся до нас з будь-якого питання.<br /><br />З повагою,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Платіж отримано за рахунок {invoice_number}',
        'body'          => 'Привіт,<br /><br />{customer_name} отримано повідомлення про прострочення <strong>{invoice_number}</strong> рахунку-фактури .<br /><br />Загальна сума складає {invoice_total} та має бути приблизно <strong>{invoice_due_date}</strong>.<br /><br />Ви можете побачити реквізити за наступним посиланням: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />З повагою,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} нагадування про рахунок',
        'body'          => 'Привіт,<br /><br />{customer_name} отримано повідомлення про прострочення <strong>{invoice_number}</strong> рахунку-фактури .<br /><br />Загальна сума  складає {invoice_total} та має бути приблизно <strong>{invoice_due_date}</strong>.<br /><br />Ви можете побачити реквізити за наступним посиланням: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />З повагою,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{invoice_number} повторний рахунок створено',
        'body'          => 'Привіт,<br /><br />{customer_name} отримано повідомлення про прострочення <strong>{invoice_number}</strong> рахунку-фактури .<br /><br />Загальна сума  складає {invoice_total} та має бути приблизно <strong>{invoice_due_date}</strong>.<br /><br />Ви можете побачити реквізити за наступним посиланням: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />З повагою,<br />{company_name}',
    ],

];
