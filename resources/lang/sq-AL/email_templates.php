<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} fatura e krijuar',
        'body'          => 'I dashur {customer_name},<br /><br />Ne kemi përgatitur faturën e mëposhtme për ju:<strong>{invoice_number}</strong>.<br /><br />Ju mund të shihni detajet e faturës dhe të vazhdoni me pagesën nga linku i mëposhtëm: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Mos ngurroni të na kontaktoni për çdo pyetje.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'njoftim i faturës {invoice_number} së vonuar',
        'body'          => 'I dashur {customer_name},<br /><br />Ky është një njoftim i vonuar për faturën <strong>{invoice_number}</strong>.<br /><br />Totali i faturës është {invoice_total} dhe ishte deri më <strong>{invoice_due_date}</strong>.<br /><br />Ju mund të shihni detajet e faturës dhe të vazhdoni me pagesën nga lidhja e mëposhtme: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'njoftim i faturës {invoice_number} së vonuar',
        'body'          => 'Përshëndetje,<br /><br />{customer_name} ka marrë një njoftim të vonuar për faturën <strong>{invoice_number}</strong>.<br /><br />Totali i faturës është {invoice_total} dhe ishte deri më <strong>{invoice_due_date}</strong>.<br /><br />Ju mund t\'i shihni detajet e faturës në linkun e mëposhtëm: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} fatura periodike u krijua',
        'body'          => 'I dashur {customer_name},<br /><br />Bazuar në rrethin tuaj periodik, ne kemi përgatitur faturën e mëposhtme për ju: <strong>{invoice_number}</strong>.<br /><br />Ju mund të shihni detajet e faturës dhe të vazhdoni me pagesën nga linku i mëposhtëm: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Mos ngurroni të na kontaktoni për çdo pyetje.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} fatura periodike u krijua',
        'body'          => 'Përshëndetje,<br /><br /> Bazuar në rrethin periodik të {customer_name}, <strong>{invoice_number}</strong> fatura është krijuar automatikisht. <br /><br />Ju mund t\'i shihni detajet e faturës në linkun e mëposhtëm: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'Pagesa e pranuar për faturën {invoice_number}',
        'body'          => 'I dashur {customer_name},<br /><br />Faleminderit për pagesën. Gjeni detajet e pagesës më poshtë:<br /><br />-------------------------------------------------<br /><br />Shuma: <strong>{transaction_total}<br /></strong>Data: <strong>{transaction_paid_date}</strong><br />Numri i Faturës:<strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />Gjithmonë mund t\'i shihni detajet e faturës nga linku i mëposhtëm: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />Mos ngurroni të na kontaktoni për çdo pyetje.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'Pagesa e pranuar për faturën {invoice_number}',
        'body'          => 'Përshëndetje,<br /><br />{customer_name} regjistroi një pagesë për faturën <strong>{invoice_number}</strong>.<br /><br />Ju mund t\'i shihni detajet e faturës në linkun e mëposhtëm: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'njoftim për kujtesë të faturës {bill_number}',
        'body'          => 'Përshëndetje,<br /><br />Ky është një njoftim kujtues për <strong>{bill_number}</strong> faturën tek {vendor_name}.<br /><br /> Totali i faturës është {bill_total} dhe ishte deri më <strong>{bill_due_date}</strong>.<br /><br />Ju mund t\'i shihni detajet e faturës nga linku i mëposhtëm: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Me Respekt,<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} fatura periodike u krijua',
        'body'          => 'Përshëndetje,<br /><br /> Bazuar në rrethin periodik të {vendor_name}, <strong>{bill_number}</strong> fatura është krijuar automatikisht. <br /><br />Ju mund t\'i shihni detajet e faturës në linkun e mëposhtëm: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />Me Respekt,<br />{company_name}',
    ],

];
