<?php

return [

    'bill_number'           => '請求書番号',
    'bill_date'             => '請求日',
    'total_price'           => '合計金額',
    'due_date'              => '期日',
    'order_number'          => '注文番号',
    'bill_from'             => '請求元',

    'quantity'              => '数量',
    'price'                 => '価格',
    'sub_total'             => '小計',
    'discount'              => '割引',
    'item_discount'         => 'ライン割引',
    'tax_total'             => '税合計',
    'total'                 => '合計',

    'item_name'             => 'アイテム名|アイテム名',

    'show_discount'         => ':discount% ディスカウント',
    'add_discount'          => '割引を追加',
    'discount_desc'         => '小計の',

    'payment_due'           => '支払い期限',
    'amount_due'            => '料金',
    'paid'                  => '支払う',
    'histories'             => '記録',
    'payments'              => '支払い',
    'add_payment'           => '支払いを追加',
    'mark_paid'             => '有給マーク',
    'mark_received'         => '受信マーク',
    'mark_cancelled'        => 'キャンセル済みとマーク',
    'download_pdf'          => 'PDFをダウンロード',
    'send_mail'             => 'メールを送る',
    'create_bill'           => '請求書を作成',
    'receive_bill'          => '請求書を受け取る',
    'make_payment'          => '支払う',

    'messages' => [
        'draft'             => 'これは<b>ドラフト</b>請求書で、受け取り後にチャートに反映されます。',

        'status' => [
            'created'       => '作成日 :date',
            'receive' => [
                'draft'     => '送信されません',
                'received'  => '受信日 :date',
            ],
            'paid' => [
                'await'     => '支払いを待っている',
            ],
        ],
    ],

];
