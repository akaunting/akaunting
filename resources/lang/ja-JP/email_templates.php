<?php

return [

    'invoice_new_customer' => [
        'subject'       => '{invoice_number} の請求書が作成されました',
        'body'          => '親愛なる {customer_name},<br /><br />次の請求書を用意しました: <strong>{invoice_number}</strong>.<br /><br />請求書の詳細を確認し、次のリンクから支払いを進めることができます: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ご質問はお気軽にお問い合わせください.<br /><br />宜しくお願いします、<br />{company_name}',
    ],

    'invoice_remind_customer' => [
        'subject'       => '{invoice_number} 請求書延滞通知',
        'body'          => '親愛なる{customer_name},<br /><br />これは延滞通知です <strong>{invoice_number}</strong> 請求書。<br /><br />請求書の合計は {invoice_total} そして期限だった <strong>{invoice_due_date}</strong>.<br /><br />請求書の詳細を確認し、次のリンクから支払いを進めることができます: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />宜しくお願いします。<br />{company_name}',
    ],

    'invoice_remind_admin' => [
        'subject'       => '{invoice_number} 請求書延滞通知',
        'body'          => 'こんにちは、<br /><br />{customer_name} の延滞通知を受け取りました <strong>{invoice_number}</strong> invoice.<br /><br />請求書の合計は {invoice_total} そして期限だった <strong>{invoice_due_date}</strong>.<br /><br />次のリンクから請求書の詳細を見ることができます: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />宜しくお願いします、<br />{company_name}',
    ],

    'invoice_recur_customer' => [
        'subject'       => '{invoice_number} 定期的な請求書が作成されました',
        'body'          => '親愛なる {customer_name},<br /><br />定期的なサークルに基づいて、次の請求書を作成しました: <strong>{invoice_number}</strong>.<br /><br />請求書の詳細を確認し、次のリンクから支払いを進めることができます: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ご質問はお気軽にお問い合わせください。<br /><br />宜しくお願いします、<br />{company_name}',
    ],

    'invoice_recur_admin' => [
        'subject'       => '{invoice_number} 定期的な請求書が作成されました',
        'body'          => 'こんにちは、<br /><br /> に基づく {customer_name} 繰り返し円、 <strong>{invoice_number}</strong> 請求書は自動的に作成されました。<br /><br />次のリンクから請求書の詳細を見ることができます: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />宜しくお願いします,<br />{company_name}',
    ],

    'invoice_payment_customer' => [
        'subject'       => '請求書{invoice_number} の支払いを受け取りました',
        'body'          => '様、 {customer_name},<br /><br />お支払いいただきありがとうございます。以下のお支払いの詳細をご覧ください:<br /><br />-------------------------------------------------<br /><br />量: <strong>{transaction_total}<br /></strong>日付: <strong>{transaction_paid_date}</strong><br />請求書番号: <strong>{invoice_number}<br /><br /></strong>-------------------------------------------------<br /><br />次のリンクから請求書の詳細をいつでも確認できます: <a href="{invoice_guest_link}">{invoice_number}</a>.<br /><br />ご質問はお気軽にお問い合わせください。<br /><br />宜しくお願いします、<br />{company_name}',
    ],

    'invoice_payment_admin' => [
        'subject'       => '請求書 {invoice_number} の支払いを受け取りました',
        'body'          => 'こんにちは、<br /><br />{customer_name} の支払いを記録した <strong>{invoice_number}</strong> 請求書.<br /><br />次のリンクから請求書の詳細を見ることができます: <a href="{invoice_admin_link}">{invoice_number}</a>.<br /><br />宜しくお願いします、<br />{company_name}',
    ],

    'bill_remind_admin' => [
        'subject'       => '{bill_number} 法案通知',
        'body'          => 'こんにちは、<br /><br />これは、思い出にさせる通知です。<strong>{bill_number}</strong>請求書送付先{vendor_name}.<br /><br />請求書の合計は {bill_total} そして期日です <strong>{bill_due_date}</strong>.<br /><br />次のリンクから請求書の詳細を見ることができます: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />宜しくお願いします、<br />{company_name}',
    ],

    'bill_recur_admin' => [
        'subject'       => '{bill_number} 定期請求書が作成されました',
        'body'          => 'こんにちは、<br /><br /> に基づく {vendor_name} 繰り返し円、<strong>{bill_number}</strong> 請求書は自動的に作成されました。<br /><br />次のリンクから請求書の詳細を見ることができます: <a href="{bill_admin_link}">{bill_number}</a>.<br /><br />宜しくお願いします、<br />{company_name}',
    ],

];
