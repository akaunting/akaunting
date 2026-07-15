<?php

return [

    'bill_number'           => '账单编号',
    'bill_date'             => '账单日期',
    'bill_amount'           => '账单金额',
    'total_price'           => '总价',
    'due_date'              => '到期日期',
    'order_number'          => '订单编号',
    'bill_from'             => '账单来自',

    'quantity'              => '数量',
    'price'                 => '价格',
    'sub_total'             => '小计',
    'discount'              => '折扣',
    'item_discount'         => '行折扣',
    'tax_total'             => '税额合计',
    'total'                 => '总计',

    'item_name'             => '项目名称|项目名称',
    'recurring_bills'       => '定期账单|定期账单',

    'show_discount'         => ':discount% 折扣',
    'add_discount'          => '添加折扣',
    'discount_desc'         => '占小计',

    'payment_made'          => '已付款',
    'payment_due'           => '付款到期',
    'amount_due'            => '应付金额',
    'paid'                  => '已付款',
    'histories'             => '历史记录',
    'payments'              => '付款',
    'add_payment'           => '添加付款',
    'mark_paid'             => '标记为已付款',
    'mark_received'         => '标记为已收到',
    'mark_cancelled'        => '标记为已取消',
    'download_pdf'          => '下载 PDF',
    'send_mail'             => '发送邮件',
    'create_bill'           => '创建账单',
    'receive_bill'          => '接收账单',
    'make_payment'          => '付款',

    'form_description' => [
        'billing'           => '账单详情显示在您的账单中。账单日期用于仪表板和报表。请选择您预期付款的日期作为到期日期。',
    ],

    'messages' => [
        'draft'             => '这是一张 <b>草稿</b> 账单，接收后将反映到图表中。',

        'status' => [
            'created'       => '于 :date 创建',
            'receive' => [
                'draft'     => '未接收',
                'received'  => '于 :date 接收',
            ],
            'paid' => [
                'await'     => '等待付款',
            ],
        ],
    ],

];
