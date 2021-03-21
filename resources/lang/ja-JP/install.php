<?php

return [

    'next'                  => '次へ',
    'refresh'               => '更新する',

    'steps' => [
        'requirements'      => 'ホスティングプロバイダにエラーの修正を依頼してください。',
        'language'          => 'ステップ 1/3 : 言語の選択',
        'database'          => 'ステップ 2/3 : データベースのセットアップ',
        'settings'          => '手順 3/3 : 会社および管理の詳細',
    ],

    'language' => [
        'select'            => '言語の選択',
    ],

    'requirements' => [
        'enabled'           => ':feature 有効にする必要があります！',
        'disabled'          => ':feature 無効にする必要があります！',
        'extension'         => ':extension エクステンション 拡張機能をインストールしてロードする必要があります!',
        'directory'         => ':directory ディレクトリは書き込み可能である必要があります!',
        'executable'        => 'PHP CLI 実行可能ファイルが定義されていないか、機能していないか、バージョンが :php_version  以降ではありません！ PHP_BINARY または PHP_PATH 環境変数を正しく設定するようにホスティング会社に依頼してください。',
    ],

    'database' => [
        'hostname'          => 'ホスト名',
        'username'          => 'ユーザー名',
        'password'          => 'パスワード',
        'name'              => 'データベース',
    ],

    'settings' => [
        'company_name'      => '会社名',
        'company_email'     => '会社の電子メール',
        'admin_email'       => '管理者メール',
        'admin_password'    => '管理者パスワード',
    ],

    'error' => [
        'connection'        => 'Error: データベースに接続できませんでした! 詳細が正しいことを確認してください。',
    ],

];
