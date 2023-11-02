<?php

return [

    'auth'                  => '身份验证',
    'profile'               => '个人资料',
    'logout'                => '退出登录',
    'login'                 => '登录',
    'forgot'                => '忘记了',
    'login_to'              => '登录',
    'remember_me'           => '记住我',
    'forgot_password'       => '我忘记了我的密码',
    'reset_password'        => '重置密码',
    'change_password'       => '改变密码',
    'enter_email'           => '请输入电子邮箱',
    'current_email'         => '目前电子邮箱',
    'reset'                 => '重置',
    'never'                 => '永远不要',
    'landing_page'          => '登陆页面',
    'personal_information'  => '个人信息',
    'register_user'         => '注册用户',
    'register'              => '注册',

    'form_description' => [
        'personal'          => '邀请链接将发送给新用户，请确保电子邮件地址正确。 他们可以输入他们的密码。',
        'assign'            => '用户将有权访问选定的公司。您可以限制来自 <a href=":url" class="border-b border-black">角色</a> 页面的权限。',
        'preferences'       => '选择用户的默认语言。您也可以在用户登录后设置登陆页面。',
    ],

    'password' => [
        'pass'              => '密码',
        'pass_confirm'      => '确认密码',
        'current'           => '密码',
        'current_confirm'   => '确认密码',
        'new'               => '新的密码',
        'new_confirm'       => '确认新的密码',
    ],

    'error' => [
        'self_delete'       => '错误：无法删除自己！',
        'self_disable'      => '错误：无法禁用自己',
        'unassigned'        => '错误：无法取消分配的公司！:company 必须至少分配一个用户。',
        'no_company'        => '错误: 你账户下没有公司，请联系管理员.',
    ],

    'login_redirect'        => '验证已完成！您正在重定向...',
    'failed'                => '账号或者密码错误',
    'throttle'              => '尝试登录次数过多，请在 :seconds 秒后再试。',
    'disabled'              => '此账号已被停用，请联系管理员',

    'notification' => [
        'message_1'         => '您收到此电子邮件是因为我们收到了您帐户的密码重置请求.',
        'message_2'         => '如果您未请求重置密码，则无需进一步操作.',
        'button'            => '重置密码',
    ],

    'invitation' => [
        'message_1'         => '您收到此邮件是因为您被邀请加入 Akauning。',
        'message_2'         => '如果你不想加入，就不需要采取进一步行动。',
        'button'            => '开始使用',
    ],

    'information' => [
        'invoice'           => '轻松创建发票',
        'reports'           => '获取详细报表',
        'expense'           => '追踪任何开支',
        'customize'         => '自定义您的 Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => '管理员',
            'description'   => '他们可以完全访问您的 Akaunting ，包括客户、发票、报告、设置和应用程序。',
        ],
        'manager' => [
            'name'          => '经理',
            'description'   => '他们可以完全访问您的 Akaunting, 但无法管理用户和应用程序。',
        ],
        'customer' => [
            'name'          => '客户',
            'description'   => '他们可以访问客户端门户，通过您设置的付款方法在线支付他们的发票。',
        ],
        'accountant' => [
            'name'          => '会计',
            'description'   => '他们可以查阅发票、交易、报告和创建期刊条目。',
        ],
        'employee' => [
            'name'          => '员工',
            'description'   => '他们可以为分配的项目创建费用债权和追踪时间，但只能看到他们自己的信息。',
        ],
    ],

];
