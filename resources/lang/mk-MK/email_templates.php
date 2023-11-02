<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} креирана фактура',
        'body'          => 'Почитувани {customer_name},<br /><br />Ја подготвивме следнава фактура за вас: <strong>{invoice_number}</strong>.<br /><br />Можете да ги видите поединостите на фактурата и да ја платите истата на следниов линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />За било какви прашања, слободно контактирајте не.<br /><br />Поздрав,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} известување за доцнење со плаќање по фактура',
        'body'          => 'Почитувани {customer_name},<br /><br />Ова е потсетување за заостанат долг по  <strong>{invoice_number}</strong> фактура.<br /><br />Вкупната сума на фактурата е {invoice_total} и доспеа за плаќање на <strong>{invoice_due_date}</strong>.<br /><br />Можете да ги видите поединостите на фактурата и да ја платите истата на следниов линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} известување за доцнење со плаќање по фактура',
        'body'          => 'Почитувани,<br /><br />{customer_name} прими известување за доцнење со плаќање по <strong>{invoice_number}</strong> фактура.<br /><br />Вкупната сума по фактурата е {invoice_total} и доспеа за плаќање на <strong>{invoice_due_date}</strong>.<br /><br />Можете да ги видите поединостите на фактурата и да ја платите истата на следниов линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} креирана повторувачка фактура',
        'body'          => 'Почитувани {customer_name},<br /><br />Врз основа на повторувачки услуги, ја подготвивме следнава фактура за вас: <strong>{invoice_number}</strong>.<br /><br />Можете да ги видите поединостите на фактурата и да ја платите истата на следниов линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />За било какви прашања, слободно контактирајте не.<br /><br />Поздрав,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} креирана повторувачка фактура',
        'body'          => 'Почитувани,<br /><br />{customer_name} прими известување за доцнење со плаќање по <strong>{invoice_number}</strong> фактура.<br /><br />Вкупната сума по фактурата е {invoice_total} и доспеа за плаќање на <strong>{invoice_due_date}</strong>.<br /><br />Можете да ги видите поединостите на фактурата и да ја платите истата на следниов линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => '{invoice_number} фактура прегледана ',
        'body'          => 'Здраво,<br /><br />{customer_name} ја прегледа <strong>{invoice_number}</strong> фактурата<br /><br />Можете да ги видите деталит за фактурата на следиот линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Поздрав,,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Вашата потврда за {invoice_number} фактура',
        'body'          => 'Почитувани {customer_name},<br /><br />Ви благодариме за уплтата. Можете да ги најдете  деталите за плаќањето подолу::<br /><br />-------------------------------------------------<br />Износ: <strong>{transaction_total}</strong><br />Датум: <strong>{transaction_paid_date}</strong><br />Број на  Фактура: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Деталите за фактурата секогаш можете да ги видите на следниот линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Слободно контактирајте не за било какви прашања.<br /><br />Поздрав,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Примена е уплата по {invoice_number} фактура',
        'body'          => 'Здраво,<br /><br />{customer_name} евидентираше плаќање по  <strong>{invoice_number}</strong> фактура.<br /><br />Можете да ги видите поединостите на фактурата на следниов линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} Потсетување за плаќање',
        'body'          => 'Здраво,<br /><br />Ова е потсетување за  <strong>{bill_number}</strong> сетка до {vendor_name}.<br /><br />Сумата на сметката е {bill_total} и доспеа на <strong>{bill_due_date}</strong>.<br /><br />Можете да ги видите деталите на линкот: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{invoice_number} креирана повторувачка сметка',
        'body'          => 'Здраво,<br /><br />Врз основа на {vendor_name} повторувачки циклус, <strong>{bill_number}</strong> сметака беше автоматски креирана.<br /><br />Можете да ги видите деталите за сметката на следиот линк: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => 'Вашата потврда од {company_name}',
        'body'          => 'Почитувани {contact_name},<br /><br />Ви благодариме за уплатата. <br /><br />Можете да ги видите деталите за плаќањето на следниот линк: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Слободно контактирајте не за било какви прашања.<br /><br />Поздрав,<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => 'Направено е плаќање од {company_name}',
        'body'          => 'Почитувани {contact_name},<br /><br />Го направивме следното плаќање. <br /><br />Можете да ги видите деталите за плаќањето на следниот линк: <a href="{payment_guest_link}">{payment_date}</a>.<br /><br />Слободно контактирајте не за било какви прашања.<br /><br />Поздрав,<br />{company_name}',
    ],
];
