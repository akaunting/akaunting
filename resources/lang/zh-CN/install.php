<?php

return [

    'next'                  => '下一步',
    'refresh'               => '重新整理',

    'steps' => [
        'requirements'      => '請檢查以下系統需求！',
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
        'extension'         => ':extension 必須載入！',
        'directory'         => ':directory 必須可寫入！',
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
        'connection'        => '錯誤：無法連線到資料庫！請確認所提供的資訊正確無誤。',
    ],

];
