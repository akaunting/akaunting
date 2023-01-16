<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} 發票已創建',
        'body'          => '尊敬的 {customer_name}，<br /><br />我們已經為您准備了以下發票： <strong>{invoice_number}</strong><br /><br />您可以看到發票的詳細信息並從以下鏈接前往付款： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />如您有任何問題可隨時聯繫我們<br /><br />祝好<br />{company_name',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} 發票逾期通知',
        'body'          => '尊敬的 {customer_name},<br /><br />這是此發票 <strong>{invoice_number}</strong> 的逾期通知。<br /><br />發票總額為 {invoice_total} ，到期時間為 <strong>{invoice_due_date}</strong><br /><br />您可以看到發票的詳細信息並從以下鏈接前往付款： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} 發票逾期通知',
        'body'          => '您好，<br /><br />{customer_name} 收到了逾期的 <strong>{invoice_number}</strong> 發票通知。<br /><br />發票總額為 {invoice_total} ，到期時間為 <strong>{invoice_due_date}</strong><br /><br />您可以從以下鏈接查看發票詳情： <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} 定期發票已創建',
        'body'          => '尊敬的 {customer_name},<br /><br />基於您的循環周期， 我們為您准備了以下發票： <strong>{invoice_number}</strong>。<br /><br />您可以看到發票的詳細信息並從以下鏈接前往付款： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />如您有任何問題可隨時聯繫我們<br /><br />祝好<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} 定期發票已創建',
        'body'          => '您好，<br /><br />基於 {customer_name} 的循環周期， <strong>{invoice_number}</strong> 發票已自動創建。<br /><br />您可以從以下鏈接查看發票詳情： <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '已收到發票 {invoice_number} 的付款',
        'body'          => '尊敬的 {customer_name},<br /><br />謝謝謝您的付款。 請在下面查找付款詳情：<br /><br />---------------------------------------------------------------<br />金額： <strong>{transaction_total}</strong><br />日期： <strong>{transaction_paid_date}</strong><br />發票號碼： <strong>{invoice_number}</strong><br />-----------------------------------------------------------------------<br /><br />您隨時可以從以下鏈接查看發票細節： <a href="{invoice_guest_link}">{invoice_number}</a><br /><br />如您有任何問題也可隨時聯繫我們<br /><br />祝好<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '已收到發票 {invoice_number} 的付款',
        'body'          => '您好，<br /><br />{customer_name} 己收到發票 <strong>{invoice_number}</strong> 的付款記錄。<br /><br />您可以從以下鏈接查看發票詳情： <a href="{invoice_admin_link}">{invoice_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} 帳單提醒通知',
        'body'          => '您好，<br /><br />這是帳單 <strong>{bill_number}</strong> 付至 {vendor_name} 的提示。<br /><br />帳單總額為 {bill_total} ，到期時間為 <strong>{bill_due_date}</strong><br /><br />您可以從以下鏈接查看帳單詳情： <a href="{bill_admin_link}">{bill_number}</a><br /><br />祝好，<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} 定期帳賬單已創建',
        'body'          => '您好，<br /><br />基於 {vendor_name} 的循環周期， <strong>{bill_number}</strong> 帳單已自動創建。<br /><br />您可以從以下鏈接查看帳單詳情： <a href="{bill_admin_link}">{bill_number}</a><br /><br />祝好，<br />{company_name}',
    ],

];
