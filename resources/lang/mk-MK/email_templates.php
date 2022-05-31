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

    'invoice_payment_customer' => [
        'subject'       => 'Примено е плаќање по {invoice_number} фактура',
        'body'          => 'Почистувани {customer_name},<br /><br />Ви благодариме за уплатата. Видете ги деталите за плаќањето подолу:<br /><br />-------------------------------------------------<br />Вредност: <strong>{transaction_total}</strong><br />Датум: <strong>{transaction_paid_date}</strong><br />Број на фактура: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />Во секое време можете да ги видите деталите за фактурата на следниот линк: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Доколку имате некое прашање, слободно контактирајте не.<br /><br />Со почит,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Примено е плаќање по {invoice_number} фактура',
        'body'          => 'Почитувани,<br /><br />{customer_name} прими известување за доцнење со плаќање по <strong>{invoice_number}</strong> фактура.<br /><br />Вкупната сума по фактурата е {invoice_total} и доспеа за плаќање на <strong>{invoice_due_date}</strong>.<br /><br />Можете да ги видите поединостите на фактурата и да ја платите истата на следниов линк: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} Потсетување за плаќање',
        'body'          => 'Здраво,<br /><br />Ова е белешка за потсетување за фактура број <strong>{bill_number}</strong> фактурирана до {vendor_name}.<br /><br />Сумата на сметката е {bill_total} и доспева на <strong>{bill_due_date}</strong>.<br /><br />Можете да ги видите деталите на линкот: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{invoice_number} креирана повторувачка сметка',
        'body'          => 'Здраво,<br /><br />Ова е белешка за потсетување за фактура број <strong>{bill_number}</strong> фактурирана до {vendor_name}.<br /><br />Сумата на сметката е {bill_total} и доспева на <strong>{bill_due_date}</strong>.<br /><br />Можете да ги видите деталите на линкот: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Поздрав,<br />{company_name}',
    ],

    'revenue_new_customer' => [
        'subject'       => '{revenue_date} направено е плаќање',
        'body'          => 'Почитувани {vendor_name},<br /><br />Го подготвивме следново плаќање. <br /><br />Можете да ги видите деталите за плаќаето на следниов линк: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />слободно обратете ни се со било какви прашања..<br /><br />Со почит,<br />{company_name}',
    ],

    'payment_new_vendor' => [
        'subject'       => 'направено плаќање',
        'body'          => 'Почитувани {vendor_name},<br /><br />Го подготвивме следново плаќање. <br /><br />Можете да ги видите деталите за плаќаето на следниов линк: <a href="{payment_admin_link}">{payment_date}</a>.<br /><br />слободно обратете ни се со било какви прашања..<br /><br />Со почит,<br />{company_name}',
    ],
];
