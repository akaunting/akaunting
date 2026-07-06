<?php

return [

    'payment_received'      => '已收到付款',
    'payment_made'          => '已付款',
    'paid_by'               => '付款人',
    'paid_to'               => '收款方',
    'related_invoice'       => '关联发票',
    'related_bill'          => '关联账单',
    'recurring_income'      => '定期收入',
    'recurring_expense'     => '定期支出',
    'included_tax'          => '含税金额',
    'connected'             => '已连接',
    'connect_message'       => '在连接过程中未计算此 :type 的税款。税款无法连接。',

    'form_description' => [
        'general'           => '您可以在此处输入交易的一般信息，例如日期、金额、账户、描述等。',
        'assign_income'     => '选择类别和客户，使您的报表更详细。',
        'assign_expense'    => '选择类别和供应商，使您的报表更详细。',
        'other'             => '输入编号和参考，以保持交易与您的记录关联。',
    ],

    'slider' => [
        'create'            => ':user 于 :date 创建了此交易',
        'attachments'       => '下载附加到此交易的文件',
        'create_recurring'  => ':user 于 :date 创建了此定期模板',
        'schedule'          => '自 :date 起每 :interval :frequency 重复一次',
        'children'          => '已自动创建 :count 笔交易',
        'connect'           => '此交易已连接到 :count 笔交易',
        'transfer_headline' => '<div> <span class="font-bold"> 来自： </span> :from_account </div> <div> <span class="font-bold"> 至： </span> :to_account </div>',
        'transfer_desc'     => '转账于 :date 创建。',
    ],

    'share' => [
        'income' => [
            'show_link'     => '您的客户可在此链接查看交易',
            'copy_link'     => '复制链接并与您的客户分享。',
        ],

        'expense' => [
            'show_link'     => '您的供应商可在此链接查看交易',
            'copy_link'     => '复制链接并与您的供应商分享。',
        ],
    ],

    'sticky' => [
        'description'       => '您正在预览客户将如何看到您付款的网页版本。',
    ],

    'messages' => [
        'update_document_transaction' => '您可以更新此交易。您应该转到文档并在那里进行编辑。',
        'create_document_transaction_error' => '此端点无法添加到文档。请使用 {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions',
        'update_document_transaction_error' => '此端点无法更新到文档。请使用 {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
        'delete_document_transaction_error' => '此端点无法从文档删除。请使用 {{akaunting_url}}/documents/{{akaunting_document_id}}/transactions/{akaunting_transaction_id}',
    ]

];
