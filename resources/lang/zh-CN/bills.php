<?php

return [

    'bill_number'       => '账单编号',
    'bill_date'         => '账单日期',
    'total_price'       => '总价',
    'due_date'          => '到期日',
    'order_number'      => '订单编号',
    'bill_from'         => '账单来自',

    'quantity'          => '数量',
    'price'             => '价格',
    'sub_total'         => '小计',
    'discount'          => '折扣',
    'tax_total'         => '税率',
    'total'             => '总计',

    'item_name'         => '产品名称 | 产品名称',

    'show_discount'     => ':discount% 折扣',
    'add_discount'      => '新增折扣',
    'discount_desc'     => '小计',

    'payment_due'       => '付款到期日',
    'amount_due'        => '到期金额',
    'paid'              => '已付款',
    'histories'         => '历史记录',
    'payments'          => '付款方式',
    'add_payment'       => '新增付款方式',
    'mark_received'     => '标记已收到',
    'download_pdf'      => '下载 PDF格式',
    'send_mail'         => '发送邮件',

    'status' => [
        'draft'         => '草稿',
        'received'      => '已收到',
        'partial'       => '部分',
        'paid'          => '已付款',
    ],

    'messages' => [
        'received'      => '成功标记账单为已收到！',
        'draft'          => '这是 <b>草稿</b> 账单, 在收到后将反映在图表上。',

        'status' => [
            'created'   => '创建日期: date',
            'receive'      => [
                'draft'     => 'Not sent',
                'received'  => 'Received on :date',
            ],
            'paid'      => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

];
