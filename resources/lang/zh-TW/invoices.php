<?php

return [

    'invoice_number'    => '訂單號碼',
    'invoice_date'      => '訂單日期',
    'total_price'       => '總價',
    'due_date'          => '到期日',
    'order_number'      => '訂單編號',
    'bill_to'           => '帳單收件人',

    'quantity'          => '數量',
    'price'             => '售價',
    'sub_total'         => '小計',
    'discount'          => '折扣',
    'tax_total'         => '稅額',
    'total'             => '總計',

    'item_name'         => '產品名稱 | 產品名稱',

    'show_discount'     => ':discount% 折扣',
    'add_discount'      => '新增折扣',
    'discount_desc'     => '小計',

    'payment_due'       => '付款到期日',
    'paid'              => '已付款',
    'histories'         => '歷史記錄',
    'payments'          => '付款方式',
    'add_payment'       => '新增付款方式',
    'mark_paid'         => '標記為已付款',
    'mark_sent'         => '標記為已傳送',
    'download_pdf'      => '下載 PDF格式',
    'send_mail'         => '傳送電子郵件',
    'all_invoices'      => '登錄以查看所有發票',

    'status' => [
        'draft'         => '草稿',
        'sent'          => '已傳送',
        'viewed'        => '已瀏覽',
        'approved'      => '已批准',
        'partial'       => '部分',
        'paid'          => '已付款',
    ],

    'messages' => [
        'email_sent'     => '成功傳送帳單郵件！',
        'marked_sent'    => '成功標記帳單為已傳送！',
        'email_required' => '此客戶沒有電子郵件地址！',
        'draft'          => '這是 <b>草稿</b> 發票, 在簽收後將反映在圖表上。',

        'status' => [
            'created'   => '創建於 :date',
            'send'      => [
                'draft'     => '未發送',
                'sent'      => '發送於 :date',
            ],
            'paid'      => [
                'await'     => '等待付款',
            ],
        ],
    ],

    'notification' => [
        'message'       => '由於您有一個即將到來的 :amount 帳單給客戶 :customer，因此收到此封郵件。',
        'button'        => '立即付款',
    ],

];
