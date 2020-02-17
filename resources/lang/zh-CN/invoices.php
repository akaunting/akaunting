<?php

return [

    'invoice_number'        => '订单号码',
    'invoice_date'          => '订单日期',
    'total_price'           => '总价',
    'due_date'              => '到期日',
    'order_number'          => '订单编号',
    'bill_to'               => '账单收件人',

    'quantity'              => '数量',
    'price'                 => '价格',
    'sub_total'             => '小计',
    'discount'              => '折扣',
    'tax_total'             => '税额',
    'total'                 => '总计',

    'item_name'             => '产品名称 | 产品名称',

    'show_discount'         => ':discount% 折扣',
    'add_discount'          => '新增折扣',
    'discount_desc'         => '小计',

    'payment_due'           => '付款到期日',
    'paid'                  => '已付款',
    'histories'             => '历史记录',
    'payments'              => '付款方式',
    'add_payment'           => '新增付款方式',
    'mark_paid'             => '标记为已付款',
    'mark_sent'             => '标记为已发送',
    'mark_viewed'           => '标记已查看',
    'download_pdf'          => '下载 PDF格式',
    'send_mail'             => '发送邮件',
    'all_invoices'          => '登录以查看所有发票',
    'create_invoice'        => '创建发票',
    'send_invoice'          => '发送发票',
    'get_paid'              => '获得报酬',
    'accept_payments'       => '接受在线付款',

    'statuses' => [
        'draft'             => '草稿',
        'sent'              => '已发送',
        'viewed'            => '已浏览',
        'approved'          => '已批准',
        'partial'           => '部分',
        'paid'              => '已付款',
        'overdue'           => '已逾期',
        'unpaid'            => '未付款',
    ],

    'messages' => [
        'email_sent'        => '发票邮件已发送',
        'marked_sent'       => '发票标记为已发送！',
        'marked_paid'       => '发票标记为已发送！',
        'email_required'    => '此客户沒有邮箱！',
        'draft'             => '这是 <b>DRAFT</b> 发票，发送后将会反映在图表中。',

        'status' => [
            'created'       => '创建日期: date',
            'viewed'        => '已浏览',
            'send' => [
                'draft'     => '未发送',
                'sent'      => '送达日期：date',
            ],
            'paid' => [
                'await'     => '等待付款',
            ],
        ],
    ],

];
