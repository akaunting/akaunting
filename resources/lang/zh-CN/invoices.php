<?php

return [

    'invoice_number'        => '发票编号',
    'invoice_date'          => '发票日期',
    'invoice_amount'        => '发票金额',
    'total_price'           => '总价',
    'due_date'              => '到期日期',
    'order_number'          => '订单编号',
    'bill_to'               => '账单收件人',
    'cancel_date'           => '取消日期',

    'quantity'              => '数量',
    'price'                 => '价格',
    'sub_total'             => '小计',
    'discount'              => '折扣',
    'item_discount'         => '行折扣',
    'tax_total'             => '税额合计',
    'total'                 => '总计',

    'item_name'             => '项目名称',
    'recurring_invoices'    => '定期发票',

    'show_discount'         => ':discount% 折扣',
    'add_discount'          => '添加折扣',
    'discount_desc'         => '占小计',

    'payment_due'           => '付款到期',
    'paid'                  => '已付款',
    'histories'             => '历史记录',
    'payments'              => '付款',
    'add_payment'           => '添加付款',
    'mark_paid'             => '标记为已付款',
    'mark_sent'             => '标记为已发送',
    'mark_viewed'           => '标记为已查看',
    'mark_cancelled'        => '标记为已取消',
    'download_pdf'          => '下载 PDF',
    'send_mail'             => '发送邮件',
    'all_invoices'          => '登录以查看所有发票',
    'create_invoice'        => '创建发票',
    'send_invoice'          => '发送发票',
    'get_paid'              => '获取付款',
    'accept_payments'       => '接受在线付款',
    'payments_received'     => '已收到付款',
    'over_payment'          => '您输入的金额超过了总额：:amount',

    'form_description' => [
        'billing'           => '账单详情显示在您的发票中。发票日期用于仪表板和报表。请选择您期望收到付款的日期作为到期日期。',
    ],

    'messages' => [
        'email_required'    => '此客户没有邮箱地址！',
        'totals_required'   => '发票合计为必填项，请编辑 :type 并再次保存。',

        'draft'             => '这是一张 <b>草稿</b> 发票，发送后将反映到图表中。',

        'status' => [
            'created'       => '于 :date 创建',
            'viewed'        => '已查看',
            'send' => [
                'draft'     => '未发送',
                'sent'      => '于 :date 发送',
            ],
            'paid' => [
                'await'     => '等待付款',
            ],
        ],

        'name_or_description_required' => '您的发票必须至少显示 <b>:name</b> 或 <b>:description</b> 之一。',
    ],

    'share' => [
        'show_link'         => '您的客户可在此链接查看发票',
        'copy_link'         => '复制链接并与您的客户分享。',
        'success_message'   => '分享链接已复制到剪贴板！',
    ],

    'sticky' => [
        'description'       => '您正在预览客户将如何看到您发票的网页版本。',
    ],

];
