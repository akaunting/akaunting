<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} 发票已创建',
        'body'          => '尊敬的 {customer_name}，<br /><br />我们已经为您准备了以下发票： <strong>{invoice_number}</strong>。<br /><br />您可以从以下链接查看发票详情并继续付款： <a href="{invoice_guest_link}">{invoice_number}</a>。<br /><br />如有任何问题，请随时与我们联系。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} 发票逾期通知',
        'body'          => '尊敬的 {customer_name}，<br /><br />这是 <strong>{invoice_number}</strong> 发票的逾期通知。<br /><br />发票总额为 {invoice_total}，到期日为 <strong>{invoice_due_date}</strong>。<br /><br />您可以从以下链接查看发票详情并继续付款： <a href="{invoice_guest_link}">{invoice_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} 发票逾期通知',
        'body'          => '您好，<br /><br />{customer_name} 收到了 <strong>{invoice_number}</strong> 发票的逾期通知。<br /><br />发票总额为 {invoice_total}，到期日为 <strong>{invoice_due_date}</strong>。<br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} 定期发票已创建',
        'body'          => '尊敬的 {customer_name}，<br /><br />根据您的定期周期，我们为您准备了以下发票： <strong>{invoice_number}</strong>。<br /><br />您可以从以下链接查看发票详情并继续付款： <a href="{invoice_guest_link}">{invoice_number}</a>。<br /><br />如有任何问题，请随时与我们联系。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} 定期发票已创建',
        'body'          => '您好，<br /><br />根据 {customer_name} 的定期周期，<strong>{invoice_number}</strong> 发票已自动创建。<br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_view_admin' => [
        'subject'       => '{invoice_number} 发票已被查看',
        'body'          => '您好，<br /><br />{customer_name} 已查看 <strong>{invoice_number}</strong> 发票。<br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '您 {invoice_number} 发票的收据',
        'body'          => '尊敬的 {customer_name}，<br /><br />感谢您的付款。请在下方查看付款详情：<br /><br />-------------------------------------------------<br />金额： <strong>{transaction_total}</strong><br />日期： <strong>{transaction_paid_date}</strong><br />发票编号： <strong>{invoice_number}</strong><br />-------------------------------------------------<br /><br />您随时可以从以下链接查看发票详情： <a href="{invoice_guest_link}">{invoice_number}</a>。<br /><br />如有任何问题，请随时与我们联系。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '已收到 {invoice_number} 发票的付款',
        'body'          => '您好，<br /><br />{customer_name} 已为 <strong>{invoice_number}</strong> 发票记录了一笔付款。<br /><br />您可以从以下链接查看发票详情： <a href="{invoice_admin_link}">{invoice_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} 账单提醒通知',
        'body'          => '您好，<br /><br />这是付给 {vendor_name} 的 <strong>{bill_number}</strong> 账单的提醒通知。<br /><br />账单总额为 {bill_total}，到期日为 <strong>{bill_due_date}</strong>。<br /><br />您可以从以下链接查看账单详情： <a href="{bill_admin_link}">{bill_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} 定期账单已创建',
        'body'          => '您好，<br /><br />根据 {vendor_name} 的定期周期，<strong>{bill_number}</strong> 账单已自动创建。<br /><br />您可以从以下链接查看账单详情： <a href="{bill_admin_link}">{bill_number}</a>。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'payment_received_customer' => [
        'subject'       => '您来自 {company_name} 的收据',
        'body'          => '尊敬的 {contact_name}，<br /><br />感谢您的付款。<br /><br />您可以从以下链接查看付款详情： <a href="{payment_guest_link}">{payment_date}</a>。<br /><br />如有任何问题，请随时与我们联系。<br /><br />顺祝商祺，<br />{company_name}',
    ],

    'payment_made_vendor' => [
        'subject'       => '{company_name} 已付款',
        'body'          => '尊敬的 {contact_name}，<br /><br />我们已进行以下付款。<br /><br />您可以从以下链接查看付款详情： <a href="{payment_guest_link}">{payment_date}</a>。<br /><br />如有任何问题，请随时与我们联系。<br /><br />顺祝商祺，<br />{company_name}',
    ],
];
