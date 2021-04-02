<?php

return [

    'company' => [
        'description'                => '\'更改公司名稱、電子郵件、地址、稅號等等',
        'name'                       => '名稱',
        'email'                      => '電子郵件',
        'phone'                      => '電話',
        'address'                    => '地址',
        'edit_your_business_address' => '編輯你的營業地址',
        'logo'                       => '商標',
    ],

    'localisation' => [
        'description'       => '設置財年、時區、日期格式和更多局部信息',
        'financial_start'   => '財年開始',
        'timezone'          => '時區',
        'financial_denote' => [
            'title'         => '會計開始年度',
            'begins'        => '在開始的那一年',
            'ends'          => '在結束的那一年',
        ],
        'date' => [
            'format'        => '日期格式',
            'separator'     => '日期分隔',
            'dash'          => '破折號 (-)',
            'dot'           => '點 (.)',
            'comma'         => '逗號 (,)',
            'slash'         => '斜線 (/)',
            'space'         => '空格 ( )',
        ],
        'percent' => [
            'title'         => '百分比 (%) 位置',
            'before'        => '編號之前',
            'after'         => '編號之後',
        ],
        'discount_location' => [
            'name'          => '折扣位置',
            'item'          => '單項',
            'total'         => '總計',
            'both'          => '單項及總計',
        ],
    ],

    'invoice' => [
        'description'       => '自定義發票前綴、數字、條款、註腳等。',
        'prefix'            => '數字字軌',
        'digit'             => '數字位數',
        'next'              => '下一個號碼',
        'logo'              => '商標',
        'custom'            => '自訂',
        'item_name'         => '產品名稱',
        'item'              => '產品',
        'product'           => '商品',
        'service'           => '服務',
        'price_name'        => '價格名稱',
        'price'             => '價格',
        'rate'              => '率',
        'quantity_name'     => '數量名稱',
        'quantity'          => '數量',
        'payment_terms'     => '付款條件',
        'title'             => '標題',
        'subheading'        => '副標題',
        'due_receipt'       => '收到日即行到期',
        'due_days'          => ':days 內到期',
        'choose_template'   => '選擇發票範本',
        'default'           => '默認',
        'classic'           => '傳統',
        'modern'            => '新式',
        'hide'              => [
            'item_name'         => '隱藏項目名稱',
            'item_description'  => '隱藏項目描述',
            'quantity'          => '隱藏數量',
            'price'             => '隱藏定價',
            'amount'            => '隱藏合計',
        ],
    ],

    'default' => [
        'description'       => '默認賬戶、貨幣、公司語言',
        'list_limit'        => '每頁行數',
        'use_gravatar'      => '使用 Gravatar',
        'income_category'   => '收入分類',
        'expense_category'  => '支出分類',
    ],

    'email' => [
        'description'       => '更改發送協議和電子郵件範本',
        'protocol'          => '協定',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP 主機',
            'port'          => 'SMTP 通訊埠',
            'username'      => 'SMTP 帳號',
            'password'      => 'SMTP 密碼',
            'encryption'    => 'SMTP 安全性',
            'none'          => '無',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail 路徑',
        'log'               => '郵件日誌',

        'templates' => [
            'subject'                   => '主題',
            'body'                      => '正文',
            'tags'                      => '<strong>可用標簽：</strong> :tag_list',
            'invoice_new_customer'      => '新發票範本 (發送給客戶)',
            'invoice_remind_customer'   => '發票提醒範本 (發送給客戶)',
            'invoice_remind_admin'      => '發票提醒範本 (發送給管理員)\'',
            'invoice_recur_customer'    => '定期發票範本 (發送給客戶)',
            'invoice_recur_admin'       => '定期發票範本 (發送給管理員)',
            'invoice_payment_customer'  => '接收付款範本 (發送給客戶)',
            'invoice_payment_admin'     => '收到付款範本 (發送給管理員)\'',
            'bill_remind_admin'         => '帳單提醒範本 (發送給管理員)\'',
            'bill_recur_admin'          => '定期帳單範本 (發送給管理員)',
        ],
    ],

    'scheduling' => [
        'name'              => '計劃任務',
        'description'       => '定期的自動提醒和指令',
        'send_invoice'      => '傳送訂單提醒',
        'invoice_days'      => '於到期日後傳送',
        'send_bill'         => '傳送帳單提醒',
        'bill_days'         => '於到期日前傳送',
        'cron_command'      => 'Cron指令',
        'schedule_time'     => '執行時間',
    ],

    'categories' => [
        'description'       => '沒有限制 收入、支出和項目的類別 數量',
    ],

    'currencies' => [
        'description'       => '創建、管理貨幣並設定它們的兌換率',
    ],

    'taxes' => [
        'description'       => '固定稅率，常規稅率，其他費率以及復合稅率',
    ],

];
