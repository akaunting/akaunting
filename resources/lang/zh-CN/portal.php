<?php

return [

    'profile'               => '个人资料',
    'invoices'              => '发票',
    'payments'              => '付款',
    'payment_received'      => '已收到付款，谢谢！',
    'create_your_invoice'   => '现在创建您自己的发票 — 免费',
    'get_started'           => '免费开始',
    'billing_address'       => '账单地址',
    'see_all_details'       => '查看所有账户详情',
    'all_payments'          => '登录以查看所有付款',
    'received_date'         => '收到日期',
    'redirect_description'  => '您将被重定向到 :name 网站进行付款。',

    'last_payment'          => [
        'title'             => '最近一次付款',
        'description'       => '您已于 :date 完成此付款',
        'not_payment'       => '您尚未进行任何付款。',
    ],

    'outstanding_balance'   => [
        'title'             => '未结余额',
        'description'       => '您的未结余额为：',
        'not_payment'       => '您目前没有未结余额。',
    ],

    'latest_invoices'       => [
        'title'             => '最新发票',
        'description'       => ':date - 您收到了发票编号为 :invoice_number 的账单。',
        'no_data'           => '您目前没有发票。',
    ],

    'invoice_history'       => [
        'title'             => '发票历史记录',
        'description'       => ':date - 您收到了发票编号为 :invoice_number 的账单。',
        'no_data'           => '您目前没有发票历史记录。',
    ],

    'payment_history'       => [
        'title'             => '付款历史记录',
        'description'       => ':date - 您支付了 :amount。',
        'invoice_description'=> ':date - 您为发票编号 :invoice_number 支付了 :amount。',

        'no_data'           => '您目前没有付款历史记录。',
    ],

    'payment_detail'        => [
        'description'       => '您已于 :date 为此发票支付了 :amount。'
    ],

];
