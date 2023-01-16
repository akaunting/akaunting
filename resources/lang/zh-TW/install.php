<?php

return [

    'next'                  => '下一步',
    'refresh'               => '重新整理',

    'steps' => [
        'requirements'      => '請詢問您的主機供應商以修復這些錯誤！',
        'language'          => '步驟一：選擇語系',
        'database'          => '步驟二：設定資料庫',
        'settings'          => '步驟三：公司與管理員資訊',
    ],

    'language' => [
        'select'            => '選擇語系',
    ],

    'requirements' => [
        'enabled'           => ':feature 必須啟動！',
        'disabled'          => ':feature 必須關閉！',
        'extension'         => '必須安裝並載入 :extension ！',
        'directory'         => ':directory 必須可寫入！',
        'executable'        => 'PHP CLI 可執行文件未定義/無法運行，或者其版本不是或高於：php_version！ 請向您的託管公司要求正確設置 PHP_BINARY 或 PHP_PATH 的環境參數。',
    ],

    'database' => [
        'hostname'          => '主機名稱',
        'username'          => '使用者名稱',
        'password'          => '密碼',
        'name'              => '資料庫',
    ],

    'settings' => [
        'company_name'      => '公司名稱',
        'company_email'     => '公司電子郵件',
        'admin_email'       => '管理員電子郵件',
        'admin_password'    => '管理員密碼',
    ],

    'error' => [
        'php_version'       => '錯誤：詢問你的主機供應商以使用 PHP :php_version 或更新版本的 HTTP 和 CLI。',
        'connection'        => '錯誤：無法連線到資料庫！請確認所提供的資訊正確無誤。',
    ],

];
