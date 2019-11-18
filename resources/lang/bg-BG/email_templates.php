<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'Създаден е нов документ {invoice_number}',
        'body'          => 'Уважаеми {customer_name},<br /><br />Създадохме следният документ: <strong>{invoice_number}</strong>.<br /><br />Може да разгледате детайлите на документа и да продължите с плащането му на следния адрес: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Свържете се с нас при възникнали въпроси.<br /><br />С уважение,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'Забавено плащане по фактура {invoice_number}',
        'body'          => 'Уважаеми {customer_name},<br /><br />Имате забавено плащане по следната фактура: <strong>{invoice_number}</strong>.<br /><br />Обща сума за заплащане {invoice_total}, дължима до <strong>{invoice_due_date}</strong>.<br /><br />Може да разгледате детайлите на документа и да продължите с плащането му на следния адрес: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />С уважение,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'Забавено плащане по фактура {invoice_number}',
        'body'          => 'Здравейте,<br /><br />{customer_name} получи напомняне за забавено плащане по фактура <strong>{invoice_number}</strong>.<br /><br />Обща сума на фактурата {invoice_total} дължима до <strong>{invoice_due_date}</strong>.<br /><br />Може да разгледате детайли по фактурата на следния линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважение,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'Създадена е фактура за повтаряща се услуга с номер {invoice_number}',
        'body'          => 'Уважаеми {customer_name},<br /><br />Имате повтарящи се услуги, на базата на което е създадена нова фактура с номер: <strong>{invoice_number}</strong>.<br /><br />Може да разгледате детайли по фактурата и да преминете към нейното заплащане на следния линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />За въпроси, свържете се с нас.<br /><br />С уважение,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'Създадена е фактура за повтаряща се услуга с номер {invoice_number}',
        'body'          => 'Здравейте,<br /><br /> За клиент {customer_name} има зададено циклично издаване на фактури, като автоматично беше генериран документ с номер <strong>{invoice_number}</strong>.<br /><br />Може да разгледате детайлите на фактурата на следния линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважение,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Получено плащане по фактура {invoice_number}',
        'body'          => 'Уважаеми {customer_name},<br /><br />Благодарим Ви за направеното плащане. Детайли за плащането:<br /><br />-------------------------------------------------<br /><br />Сума: <strong>{transaction_total}<br /></strong>Дата: <strong>{transaction_paid_date}</strong><br />Фактура: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Винаги можете да разгледате детайлите по фактурата на следния линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Свържете се с нас при възникнали въпроси.<br /><br />С уважение,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Получено плащане по фактура {invoice_number}',
        'body'          => 'Здравейте,<br /><br />{customer_name} добави плащане по фактура <strong>{invoice_number}</strong>.<br /><br />Може да разгледате детайли по фактурата тук: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />С уважение,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'Напомняне за плащане по документ {bill_number}',
        'body'          => 'Здравейте,<br /><br />Това писмо е автоматично и е напомняне за предстоящо плащане по фактура <strong>{bill_number}</strong> издадена на {vendor_name}.<br /><br />Обща сума {bill_total}, дължима до <strong>{bill_due_date}</strong>.<br /><br />Може да разгледате детайли по документа тук: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />С уважение,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'Създадена е фактура за повтаряща се услуга с номер {bill_number}',
        'body'          => 'Здравейте,<br /><br />За клиент {vendor_name} има зададено циклично издаване на фактури, като автомотично беше генериран документ с номер <strong>{bill_number}</strong>.<br /><br />Може да разгледате детайли по докумнта тук: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />С уважение,<br />{company_name}',
    ],

];
