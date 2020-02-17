<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} 发票已创建',
        'body'          => '尊敬的 {customer_name}，<br /><br />我们已经为您准备了以下发票： <strong>{invoice_number}</strong><br /><br />您可以看到发票的详细信息并从以下链接继续付款： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />如您有任何问题可随时联系我们<br /><br />祝好<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} 发票逾期通知',
        'body'          => '尊敬的 {customer_name},<br /><br />这是发票 <strong>{invoice_number}</strong> 的逾期通知。<br /><br />发票总额为 {invoice_total} ，到期时间为 <strong>{invoice_due_date}</strong><br /><br />您可以看到发票的详细信息并从以下链接继续付款： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} 发票逾期通知',
        'body'          => '您好，<br /><br />{customer_name} 收到了逾期的 <strong>{invoice_number}</strong> 发票通知。<br /><br />发票总额为 {invoice_total} ，到期时间为 <strong>{invoice_due_date}</strong><br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} 定期发票已创建',
        'body'          => '尊敬的 {customer_name},<br /><br />基于您的循环周期， 我们为您准备了以下发票： <strong>{invoice_number}</strong>。<br /><br />您可以看到发票的详细信息并从以下链接继续付款： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />如您有任何问题可随时联系我们<br /><br />祝好<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} 定期发票已创建',
        'body'          => '您好，<br /><br />基于 {customer_name} 的循环周期， <strong>{invoice_number}</strong> 发票已自动创建。<br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '已收到发票{invoice_number} 的付款',
        'body'          => '尊敬的 {customer_name},<br /><br />谢谢谢您的付款。 请在下面查找付款详情：<br /><br />---------------------------------------------------------------<br />金额： <strong>{transaction_total}</strong><br />日期： <strong>{transaction_paid_date}</strong><br />发票号码： <strong>{invoice_number}</strong><br />-----------------------------------------------------------------------<br /><br />您一直可以从以下链接查看发票细节： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />如您有任何问题可随时联系我们<br /><br />祝好<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '已收到发票{invoice_number} 的付款',
        'body'          => '您好，<br /><br />{customer_name} 创建了发票 <strong>{invoice_number}</strong> 的付款记录。<br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} 账单提醒通知',
        'body'          => '您好，<br /><br />这是账单 <strong>{bill_number}</strong> 付至 {vendor_name} 的提示。<br /><br />账单总额为 {bill_total} ，到期时间为 <strong>{bill_due_date}</strong><br /><br />您可以从以下链接查看账单详情： <a href="{bill_admin_link}">{bill_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} 定期账单已创建',
        'body'          => '您好，<br /><br />基于 {vendor_name} 的循环周期， <strong>{bill_number}</strong> 发票已自动创建。<br /><br />您可以从以下链接查看账单详情： <a href="{bill_admin_link}">{bill_number}</a><br /><br />祝好，<br />{company_name}',
    ],

];
