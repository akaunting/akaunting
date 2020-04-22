<?php

return [

    'success' => [
        'added'             => ':type 追加されました！',
        'updated'           => ':type 更新しました！',
        'deleted'           => ':type 削除されました！',
        'duplicated'        => ':type 複製されました！',
        'imported'          => ':type インポートされました!',
        'exported'          => ':type エクスポートされました！',
        'enabled'           => ':type 有効！',
        'disabled'          => ':type は無効です！',
    ],

    'error' => [
        'over_payment'      => 'エラー: 支払いが追加されません!入力した金額が合計を渡します:: 量',
        'not_user_company'  => 'エラー：この会社を管理することは許可されていません!',
        'customer'          => 'エラー: ユーザーが作成されませんでした。 :name はすでにこのメールアドレスを使用しています。',
        'no_file'           => 'エラー: ファイルが選択されていません!',
        'last_category'     => 'エラー: 削除できません最後 :type category!',
        'change_type'       => 'エラー: タイプを変更できません 持っているから :text related!',
        'invalid_apikey'    => 'エラー: 入力されたAPIキーは無効です!',
        'import_column'     => 'エラー: : メッセージ シート名: :sheet. Line number: :line.',
        'import_sheet'      => 'エラー: シート名が無効です。サンプル ファイルを下さい。',
    ],

    'warning' => [
        'deleted'           => '警告: 削除することはできません <b>:name</b> テキストに関連しているからです。 :text related.',
        'disabled'          => '警告: 無効にすることはできません <b>:name</b>テキストに関連しているため :text related.',
        'reconciled_tran'   => '警告：調整されているため、トランザクションを変更/削除することはできません！',
        'reconciled_doc'    => '警告：トランザクションが調整されているため、:type を変更/削除することはできません！',
        'disable_code'      => '警告: 通貨を無効化または変更することはできません <b>:name</b> のため :text related.',
        'payment_cancel'    => '警告：最近キャンセルされた：お支払い方法！',
    ],

];
