<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{hisob_raqami} hisob-faktura yaratildi',
        'body'          => 'Hurmatli {customer_name}, <br /> <br /> Biz siz uchun quyidagi hisob-fakturani tayyorladik: <strong> {invoice_number}</strong>. to\'lovni quyidagi havoladan oling: <a href="{invoice_guest_link}"> {invoice_number} </a>. <br /> <br /> har qanday savol uchun biz bilan bog\'laning. <br /><br />Hurmat bilan, <br /> {company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} hisob-fakturasi muddati o\'tgan bildirishnoma',
        'body'          => 'Hurmatli {customer_name},<br /><br /> Bu sizning  <strong>{invoice_number}</strong> hisobingiz muddati tugagan xabarnomadir.<br /> <br />Hisob-fakturaning umumiy summasi {invoice_total} va to\'ldirilgan bo\'lishi kerak<strong>{invoice_due_date}</strong>.<br /><br /> Siz hisob-fakturaning tafsilotlarini ko\'rishingiz va to\'lovni quyidagi havolada ko\'rishingiz mumkin:  <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />Hurmat bilan, <br /> {company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} hisob-fakturasi muddati o\'tgan bildirishnoma',
        'body'          => 'Assalomu alaykum, <br /><br /> {customer_name} <strong> {invoice_number} hisob-fakturasi </strong> {invoice_number} uchun muddati o\'tganligi haqida xabar oldi. <br /> <br /> Hisob-fakturaning umumiy qiymati {invoice_total} kelib tushgan < strong> {invoice_due_date} </strong>. <br /><br /> Hisob-fakturaning tafsilotlarini quyidagi havoladan ko\'rishingiz mumkin: <a href="{invoice_admin_link}"> {invoice_number} </a>. <br / ><br /> Hurmat bilan,<br /> {company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} takroriy hisob yaratildi',
        'body'          => 'Hurmatli {customer_name},<br /><br />Sizning takrorlanuvchi holatingizga asoslanib, biz siz uchun quyidagi hisob-fakturani tayyorladik: <strong>{invoice_number}</strong>.<br /><br />To\'lovni davom ettirish uchun fakturaning tafsilotlarini quyidagi havolada ko\'rishingiz mumkin:  <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Har qanday savol bilan biz bilan bog\'laning. <br />Hurmat bilan,<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} takroriy hisob-faktura yaratildi',
        'body'          => 'Assalomu alaykum, <br /><br /> {customer_name} takroriy doira asosida<strong> {invoice_number}</strong> hisob-fakturasi avtomatik ravishda yaratildi.<br /><br />Hisob-fakturaning tafsilotlarini quyidagi havoladan ko\'rishingiz mumkin: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Best Regards,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '{invoice_number} to\'lov qabul qilindi',
        'body'          => 'Hurmatli {customer_name},<br /><br />To\'lov uchun rahmat. To\'lov tafsilotlarini quyida toping:<br /><br />-------------------------------------------------<br /><br />Miqdori:: <strong>{transaction_total}<br /></strong>Sana: <strong>{transaction_paid_date}</strong><br />Hisob-fakturaning raqami: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Siz har doim hisob-faktura ma\'lumotlarini quyidagi havoladan ko\'rishingiz mumkin: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Har qanday savol uchun biz bilan bog\'laning.<br /><br />Hurmat bilan,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Hisob-fakturaga kelib tushgan {invoice_number} to\'lov',
        'body'          => 'Assalomu alaykum,<br /><br />{customer_name} uchun to\'lov yozilgan <strong>{invoice_number}</strong> invoice.<br /><br />Hisob-fakturaning tafsilotlarini quyidagi havoladan ko\'rishingiz mumkin: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Hurmat bilan,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} eslatma haqida bildirishnoma',
        'body'          => 'Assalomu alaykum,<br /><br />Bu eslatma <strong>{bill_number}</strong> hisob kimning nomiga {vendor_name}.<br /><br />Hisobning umumiy qiymati {bill_total} va chunki <strong>{bill_due_date}</strong>.<br /><br />Hisob-fakturaning tafsilotlarini quyidagi havoladan ko\'rishingiz mumkin: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Hurmat bilan,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} takrorlanadigan hisob-kitob yaratildi',
        'body'          => 'Assalomu alaykum,<br /><br /> Takrorlanuvchi {vendor_name} aylana asosida, <strong>{bill_number}</strong>hisob-faktura avtomatik ravishda yaratildi.<br /><br />Hisob-fakturalar tafsilotlarini quyidagi havoladan ko\'rishingiz mumkin: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Hurmat bilan,<br />{company_name}',
    ],

];
