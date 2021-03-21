<?php

return [

    'invoice_new_customer' => [
        'subject'       => 'فاکتور شماره {invoice_number} ساخته شد',
        'body'          => '{customer_name} عزیز،<br /><br />ما فاکتور زیر را برای شما آماده کرده‌ایم:
<strong>{invoice_number}</strong>.<br /><br />شما از طریق لینک زیر می‌توانید جزییات فاکتور را مشاهده و مبلغ را پرداخت کنید: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />در صورتی که سوالی داشتید با ما در ارتباط باشید.<br /><br />با احترام،<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => 'هشدار عبور از تاریخ سررسید فاکتور شماره {invoice_number}',
        'body'          => '{customer_name} عزیز،<br /><br />این یک هشدار برای رد شدن از تاریخ سررسید فاکتور <strong>{invoice_number}</strong> است.<br /><br />مبلغ کل فاکتور {invoice_total} و تاریخ سررسید <strong>{invoice_due_date}</strong> می باشد.<br /><br />شما از طریق لینک زیر می‌توانید جزییات فاکتور را مشاهده و مبلغ را پرداخت کنید: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />با احترام،<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => 'هشدار عبور از تاریخ سررسید فاکتور شماره {invoice_number}',
        'body'          => 'سلام،<br /><br />{customer_name} یک هشدار برای رد شدن از تاریخ سررسید فاکتور <strong>{invoice_number}</strong> دریافت کرده است.<br /><br />مبلغ کل فاکتور {invoice_total} و تاریخ سررسید <strong>{invoice_due_date}</strong> می باشد.<br /><br />شما از طریق لینک زیر می‌توانید جزییات فاکتور را مشاهده و مبلغ را پرداخت کنید: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />با احترام،<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => 'فاکتور دوره‌ای شماره {invoice_number} ساخته شد',
        'body'          => '{customer_name} عزیز،<br /><br />بر اساس دوره زمانی شما، ما فاکتور زیر را برای شما آماده کرده‌ایم:
<strong>{invoice_number}</strong>.<br /><br />شما از طریق لینک زیر می‌توانید جزییات فاکتور را مشاهده و مبلغ را پرداخت کنید: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />در صورتی که سوالی داشتید با ما در ارتباط باشید.<br /><br />با احترام،<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => 'فاکتور دوره‌ای شماره {invoice_number} ساخته شد',
        'body'          => 'سلام،<br /><br />بر اساس دوره زمانی {customer_name}، فاکتور شماره <strong>{invoice_number}</strong> به صورت خودکار ساخته شده است.<br /><br />شما از طریق لینک زیر می‌توانید جزییات فاکتور را مشاهده و مبلغ را پرداخت کنید: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />با احترام،<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => 'پرداخت شما برای فاکتور {invoice_number} دریافت شد',
        'body'          => '{customer_name} عزیز،<br /><br />با تشکر از پرداخت شما. شما می‌توانید جزییات پرداخت را در زیر مشاهده کنید:<br /><br />-------------------------------------------------<br />مبلغ: <strong>{transaction_total}</strong><br />تاریخ: <strong>{transaction_paid_date}</strong><br />فاکتور شماره‌ی: <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />شما در هر موقع می‌توانید جزییات فاکتور را از طریق لینک زیر مشاهده کنید: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />در صورتی که سوالی داشتید با ما در ارتباط باشید.<br /><br />با احترام،<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => 'فاکتور {invoice_number} پرداخت شد',
        'body'          => 'سلام،<br /><br />{customer_name} پرداختی برای فاکتور شماره <strong>{invoice_number}</strong> داشته است.<br /><br />شما از طریق لینک زیر می‌توانید جزییات فاکتور را مشاهده و مبلغ را پرداخت کنید: <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />با احترام،<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => 'هشدار باقیمانده صورتحساب شماره‌ {bill_number}',
        'body'          => 'سلام،<br /><br />این یک یادآوری برای صورتحساب شماره‌ی <strong>{bill_number}</strong> از سرویس دهنده {vendor_name} است.<br /><br />مبلغ صورتحساب {bill_total} و سررسید آن <strong>{bill_due_date}</strong> است.<br /><br />شما می‌توانید جزییات صورتحساب را در لینک زیر مشاهده کنید: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />با احترام،<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => 'صورتحساب دوره‌ای شماره {bill_number} ساخته شد',
        'body'          => 'سلام،<br /><br />بر اساس دوره زمانی {vendor_name}، صورتحساب شماره <strong>{bill_number}</strong> به صورت خودکار ساخته شده است.<br /><br />شما از طریق لینک زیر می‌توانید جزییات صورتحساب را مشاهده کنید: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />با احترام،<br />{company_name}',
    ],

];
