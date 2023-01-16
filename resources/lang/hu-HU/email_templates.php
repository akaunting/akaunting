<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} számla létrehozva',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} számla létrehozva',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} számla létrehozva',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} számla létrehozva',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} számla létrehozva',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '
Fizetés beérkezett a (z) {invoice_number} számlához',
        'body'          => 'Kedves {customer_name}! <br /> <br /> Köszönjük a befizetést. Az alábbiakban megtalálja a fizetési részleteket: <br /> <br /> ------------------------------------ ------------- <br /> Összeg: <strong> {transaction_total} </strong> <br /> Dátum: <strong> {transaction_paid_date} </strong> <br /> Számla Szám: <strong> {invoice_number} </strong> <br /> ---------------------------------- --------------- <br /> <br /> A számla részleteit mindig a következő linkről tekintheti meg: <a href="{invoice_guest_link}"> {invoice_number} </ a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '
Fizetés beérkezett a (z) {invoice_number} számlához',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '
{bill_number} számla emlékeztető értesítés',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{invoice_number} számla létrehozva',
        'body'          => '
Kedves {customer_name}, <br /> <br /> A következő számlát készítettük el Önnek: <strong> {invoice_number} </strong>. <br /> <br /> Megtekintheti a számla adatait és folytathatja a fizetés a következő linkről: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> Bármilyen kérdés esetén forduljon hozzánk bizalommal. <br /> <br /> Üdvözlettel: <br /> {company_name}',
    ],

];
