<?php

return [

    'invoice_number'        => '請求書番号',
    'invoice_date'          => '請求日',
    'total_price'           => '合計価格',
    'due_date'              => '期日',
    'order_number'          => '注文番号',
    'bill_to'               => '請求先',

    'quantity'              => '量',
    'price'                 => '価格',
    'sub_total'             => '小計',
    'discount'              => '割引',
    'tax_total'             => '税合計',
    'total'                 => '合計',

    'item_name'             => 'アイテム名|アイテム名',

    'show_discount'         => '：ディスカウント％ディスカウント',
    'add_discount'          => '割引を追加',
    'discount_desc'         => '小計の',

    'payment_due'           => '支払い期限',
    'paid'                  => '支払済み',
    'histories'             => '記録',
    'payments'              => '支払い',
    'add_payment'           => '支払いを追加',
    'mark_paid'             => 'マークペイド',
    'mark_sent'             => '送信済みマーク',
    'download_pdf'          => 'PDFをダウンロード',
    'send_mail'             => 'メールを送る',
    'all_invoices'          => 'すべての請求書を表示するにはログインしてください',
    'create_invoice'        => '請求書を作成する',
    'send_invoice'          => '請求書を送ります',
    'get_paid'              => '支払いを受ける',
    'accept_payments'       => 'オンラインでの支払いを受け入れる',

    'status' => [
        'draft'             => '下書き',
        'sent'              => '送信',
        'viewed'            => '閲覧',
        'approved'          => '承認',
        'partial'           => '部分的',
        'paid'              => '有料',
    ],

    'messages' => [
        'email_sent'        => '請求書のEメールは正常に送信されました。',
        'marked_sent'       => '請求書は正常に送信されたとマークされました。',
        'email_required'    => 'この顧客のメールアドレスはありません。',
        'draft'             => 'これは <b>ドラフト</b>請求書で、送信後にチャートに反映されます。',

        'status' => [
            'created'       => '作成日：日付',
            'send' => [
                'draft'     => '送信されません',
                'sent'      => '送信: 日付',
            ],
            'paid' => [
                'await'     => '支払いを待っている',
            ],
        ],
    ],

    'notification' => [
        'message'           => '今後、このメールを受信して​​います：請求書の金額:カスタマー:カスタマー',
        'button'            => '今払う',
    ],

];
