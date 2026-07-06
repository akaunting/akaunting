<?php

return [

    'edit_columns'              => '编辑列',
    'empty_items'               => '您尚未添加任何项目。',
    'grand_total'               => '总计',
    'accept_payment_online'     => '在线接受付款',
    'transaction'               => '已使用 :account 支付了 :amount。',
    'portal_transaction'        => '已使用 :payment_method 支付了 :amount。',
    'billing'                   => '账单',
    'advanced'                  => '高级',

    'item_price_hidden'         => '此列在您的 :type 上已隐藏。',

    'actions' => [
        'cancel'                => '取消',
    ],

    'invoice_detail' => [
        'marked'                => '<b>您</b>已将此发票标记为',
        'services'              => '服务',
        'another_item'          => '另一个项目',
        'another_description'   => '和另一个描述',
        'more_item'             => '还有 :count 个项目',
    ],

    'statuses' => [
        'draft'                 => '草稿',
        'sent'                  => '已发送',
        'expired'               => '已过期',
        'viewed'                => '已查看',
        'approved'              => '已批准',
        'received'              => '已收到',
        'refused'               => '已拒绝',
        'restored'              => '已恢复',
        'reversed'              => '已撤销',
        'partial'               => '部分',
        'paid'                  => '已付款',
        'pending'               => '待处理',
        'invoiced'              => '已开票',
        'overdue'               => '逾期',
        'unpaid'                => '未付款',
        'cancelled'             => '已取消',
        'voided'                => '已作废',
        'completed'             => '已完成',
        'shipped'               => '已发货',
        'refunded'              => '已退款',
        'failed'                => '失败',
        'denied'                => '已拒绝',
        'processed'             => '已处理',
        'open'                  => '待处理',
        'closed'                => '已关闭',
        'billed'                => '已开单',
        'delivered'             => '已交付',
        'returned'              => '已退回',
        'drawn'                 => '已提取',
        'not_billed'            => '未开单',
        'issued'                => '已签发',
        'not_invoiced'          => '未开票',
        'confirmed'             => '已确认',
        'not_confirmed'         => '未确认',
        'active'                => '活跃',
        'ended'                 => '已结束',
    ],

    'form_description' => [
        'companies'             => '更改您公司的地址、徽标和其他信息。',
        'billing'               => '账单详情显示在您的文档中。',
        'advanced'              => '选择类别，添加或编辑页脚，并向您的 :type 添加附件。',
        'attachment'            => '下载附加到此 :type 的文件',
    ],

    'slider' => [
        'create'            => ':user 于 :date 创建了此 :type',
        'create_recurring'  => ':user 于 :date 创建了此定期模板',
        'send'              => ':user 于 :date 发送了此 :type',
        'schedule'          => '自 :date 起每 :interval :frequency 重复一次',
        'children'          => '已自动创建 :count 个 :type',
        'cancel'            => ':user 于 :date 取消了此 :type',
    ],

    'messages' => [
        'email_sent'            => ':type 邮件已发送！',
        'restored'              => ':type 已恢复！',
        'marked_as'             => ':type 已标记为 :status！',
        'marked_sent'           => ':type 已标记为已发送！',
        'marked_paid'           => ':type 已标记为已付款！',
        'marked_viewed'         => ':type 已标记为已查看！',
        'marked_cancelled'      => ':type 已标记为已取消！',
        'marked_received'       => ':type 已标记为已收到！',
    ],

    'recurring' => [
        'auto_generated'        => '自动生成',

        'tooltip' => [
            'document_date'     => ':type 日期将根据 :type 的计划和频率自动分配。',
            'document_number'   => '生成每个定期 :type 时将自动分配 :type 编号。',
        ],
    ],

    'empty_attachments'         => '没有附加到此 :type 的文件。',
];
