<?php

return [

    'next'                  => '下一步',
    'refresh'               => '刷新',

    'steps' => [
        'requirements'      => '请让您的托管服务提供商修复错误！',
        'language'          => '步骤 1/3：语言选择',
        'database'          => '步骤 2/3：数据库设置',
        'settings'          => '步骤 3/3：公司和管理员详情',
    ],

    'language' => [
        'select'            => '选择语言',
    ],

    'requirements' => [
        'enabled'           => ':feature 需要启用！',
        'disabled'          => ':feature 需要停用！',
        'extension'         => ':extension 扩展需要安装并加载！',
        'directory'         => ':directory 目录需要可写！',
        'executable'        => 'PHP CLI 可执行文件未定义/无法工作或其版本不是 :php_version 或更高！请让您的主机服务商正确设置 PHP_BINARY 或 PHP_PATH 环境变量。',
        'npm'               => '<b>缺少 JavaScript 文件！</b> <br><br><span>您应运行 <em class="underline">npm install</em> 和 <em class="underline">npm run dev</em> 命令。</span>',
    ],

    'database' => [
        'hostname'          => '主机名',
        'username'          => '用户名',
        'password'          => '密码',
        'name'              => '数据库',
    ],

    'settings' => [
        'company_name'      => '公司名称',
        'company_email'     => '公司邮箱',
        'admin_email'       => '管理员邮箱',
        'admin_password'    => '管理员密码',
    ],

    'error' => [
        'php_version'       => '错误：请让您的主机服务提供商为 HTTP 和 CLI 均使用 PHP :php_version 或更高版本。',
        'connection'        => '错误：无法连接到数据库！请确认详细信息正确无误。',
    ],

    'update' => [
        'core'              => 'Akaunting 有新版本可用！请更新 <a href=":url">您的安装。</a>',
        'module'            => ':module 有新版本可用！请更新 <a href=":url">您的安装。</a>',
    ],
];
