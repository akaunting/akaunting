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
        'executable'        => 'The PHP CLI executable file is not defined/working or its version is not :php_version or higher! Please, ask your hosting company to set PHP_BINARY or PHP_PATH environment variable correctly.',
        'npm'               => '<b>缺少 JavaScript 文件！</b> <br><br><span>您应运行 <em class="underline">npm install</em> 和 <em class="underline">npm run dev</em> 命令。</span>',
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
        'php_version'       => '错误：请让您的主机提供商为 HTTP 和 CLI 均使用 PHP :php_version 或更高版本。',
        'connection'        => '錯誤：無法連線到資料庫！請確認所提供的資訊正確無誤。',
    ],

    'update' => [
        'core'              => 'Akaunting 有新版本可用！请更新 <a href=":url">您的安装。</a>',
        'module'            => ':module 有新版本可用！请更新 <a href=":url">您的安装。</a>',
    ],
];
