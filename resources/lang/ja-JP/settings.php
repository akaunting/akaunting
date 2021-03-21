<?php

return [

    'company' => [
        'description'       => '会社名、メール、住所、税番号などを変更する',
        'name'              => '名前',
        'email'             => 'メールアドレス',
        'phone'             => '電話番号',
        'address'           => '住所',
        'logo'              => 'ロゴ',
    ],

    'localisation' => [
        'description'       => '会計年度、タイムゾーン、日付形式などのローカルを設定します',
        'financial_start'   => '会計年度開始',
        'timezone'          => 'タイムゾーン',
        'date' => [
            'format'        => '日付フォーマット',
            'separator'     => '日付区切り記号',
            'dash'          => 'ダッシュ (-)',
            'dot'           => 'ドット (.)',
            'comma'         => 'コンマ (,)',
            'slash'         => 'スラッシュ (/)',
            'space'         => 'スペース ( )',
        ],
        'percent' => [
            'title'         => 'パーセント (%) 位置',
            'before'        => '番号の前に',
            'after'         => '番号の後',
        ],
        'discount_location' => [
            'name'          => '割引の場所',
            'item'          => 'ラインで',
            'total'         => '合計で',
            'both'          => 'ラインと合計の両方',
        ],
    ],

    'invoice' => [
        'description'       => '請求書のプレフィックス、番号、条件、フッターなどをカスタマイズする',
        'prefix'            => '番号プレフィックス',
        'digit'             => '数の桁',
        'next'              => '次の番号',
        'logo'              => 'ロゴ',
        'custom'            => 'カスタム',
        'item_name'         => '項目名',
        'item'              => 'アイテム',
        'product'           => '製品',
        'service'           => 'サービス',
        'price_name'        => '価格名',
        'price'             => '価格',
        'rate'              => 'レート',
        'quantity_name'     => '数量名',
        'quantity'          => '量',
        'payment_terms'     => '支払い条件',
        'title'             => 'タイトル',
        'subheading'        => '小見出し',
        'due_receipt'       => '領収書による',
        'due_days'          => '期限内：日日',
        'choose_template'   => '請求書テンプレートを選択',
        'default'           => 'デフォルト',
        'classic'           => 'クラシック',
        'modern'            => 'モダン',
    ],

    'default' => [
        'description'       => '会社のデフォルトのアカウント、通貨、言語',
        'list_limit'        => 'ページごとの記録',
        'use_gravatar'      => 'Gravatarを使用する',
    ],

    'email' => [
        'description'       => '送信プロトコルとメールテンプレートを変更する',
        'protocol'          => 'プロトコル',
        'php'               => 'PHP メール',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP ホスト',
            'port'          => 'SMTP ポート',
            'username'      => 'SMTP ユーザー名',
            'password'      => 'SMTP パスワード',
            'encryption'    => 'SMTPセキュリティ',
            'none'          => 'なし',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail のパス',
        'log'               => 'ログの電子メール',

        'templates' => [
            'subject'                   => '件名',
            'body'                      => '体',
            'tags'                      => '<strong>利用可能なタグ:</strong> :tag_list',
            'invoice_new_customer'      => '新しい請求書テンプレート顧客に送信）',
            'invoice_remind_customer'   => '請求書通知テンプレート（顧客に送信）',
            'invoice_remind_admin'      => '請求書通知テンプレート（管理者に送信）',
            'invoice_recur_customer'    => '請求書の繰り返しテンプレート（顧客に送信）',
            'invoice_recur_admin'       => '請求書の繰り返しテンプレート（管理者に送信）',
            'invoice_payment_customer'  => '支払い受領済みテンプレート（顧客に送信）',
            'invoice_payment_admin'     => '支払い受領済みテンプレート（管理者に送信）',
            'bill_remind_admin'         => 'ビルリマインダーテンプレート（管理者に送信）',
            'bill_recur_admin'          => '請求書定期テンプレート（管理者に送信）',
        ],
    ],

    'scheduling' => [
        'name'              => 'スケジューリング',
        'description'       => '自動リマインダーと定期的なコマンド',
        'send_invoice'      => '請求書アラームを送信する',
        'invoice_days'      => '期日後に送信',
        'send_bill'         => '請求書のリマインダーを送信',
        'bill_days'         => '期日前に送信日',
        'cron_command'      => 'Cron コマンド',
        'schedule_time'     => '実行する時間',
    ],

    'categories' => [
        'description'       => '収入、費用、アイテムの無制限のカテゴリ',
    ],

    'currencies' => [
        'description'       => '通貨を作成および管理し、金利を設定します',
    ],

    'taxes' => [
        'description'       => '固定税率、通常税率、包括的税率、複合税率',
    ],

];
