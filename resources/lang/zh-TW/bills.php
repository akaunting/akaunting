<?php

return [

    'bill_number'           => '帳單編號',
    'bill_date'             => '帳單日期',
    'total_price'           => '總價',
    'due_date'              => '到期日',
    'order_number'          => '訂單編號',
    'bill_from'             => '帳單來自',

    'quantity'              => '數量',
    'price'                 => '售價',
    'sub_total'             => '小計',
    'discount'              => '折扣',
    'item_discount'         => '單項折扣',
    'tax_total'             => '稅額',
    'total'                 => '總計',

    'item_name'             => '產品名稱 | 產品名稱',

    'show_discount'         => ':discount% 折扣',
    'add_discount'          => '新增折扣',
    'discount_desc'         => '小計',

    'payment_due'           => '付款到期日',
    'amount_due'            => '到期金額',
    'paid'                  => '已付款',
    'histories'             => '歷史記錄',
    'payments'              => '付款方式',
    'add_payment'           => '新增付款方式',
    'mark_paid'             => '標記為已付款',
    'mark_received'         => '標記已收到',
    'mark_cancelled'        => '標記為已取消',
    'download_pdf'          => '下載 PDF格式',
    'send_mail'             => '傳送電子郵件',
    'create_bill'           => '創建帳單',
    'receive_bill'          => '接收帳單',
    'make_payment'          => '取消付款',

    'messages' => [
        'draft'             => '這是 <b>草稿</b> 帳單, 在簽收後將反映在圖表上。',

        'status' => [
            'created'       => '創建於 :date',
            'receive' => [
                'draft'     => '未發送',
                'received'  => '簽收於 :date',
            ],
            'paid' => [
                'await'     => '等待付款',
            ],
        ],
    ],

];
