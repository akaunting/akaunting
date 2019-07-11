<?php

return [

    'invoice_number'    => '订单号码',
    'invoice_date'      => '订单日期',
    'total_price'       => '总价',
    'due_date'          => '到期日',
    'order_number'      => '订单编号',
    'bill_to'           => '账单收件人',

    'quantity'          => '数量',
    'price'             => '价格',
    'sub_total'         => '小计',
    'discount'          => '折扣',
    'tax_total'         => '税额',
    'total'             => '总计',

    'item_name'         => '产品名称 | 产品名称',

    'show_discount'     => ':discount% 折扣',
    'add_discount'      => '新增折扣',
    'discount_desc'     => '小计',

    'payment_due'       => '付款到期日',
    'paid'              => '已付款',
    'histories'         => '历史记录',
    'payments'          => '付款方式',
    'add_payment'       => '新增付款方式',
    'mark_paid'         => '标记为已付款',
    'mark_sent'         => '标记为已发送',
    'download_pdf'      => '下载 PDF格式',
    'send_mail'         => '发送邮件',
    'all_invoices'      => 'Login to view all invoices',

    'status' => [
        'draft'         => '草稿',
        'sent'          => '已发送',
        'viewed'        => '已浏览',
        'approved'      => '已批准',
        'partial'       => '部分',
        'paid'          => '已付款',
    ],

    'messages' => [
        'email_sent'     => '成功发送账单邮件！',
        'marked_sent'    => '成功标记账单为已发送！',
        'email_required' => '此客户沒有邮箱！',
        'draft'          => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'   => 'Created on :date',
            'send'      => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'       => '由于您有一个即将到來的 :amount 账单给客户 :customer，因此收到此邮件。',
        'button'        => '立即付款',
    ],

];
