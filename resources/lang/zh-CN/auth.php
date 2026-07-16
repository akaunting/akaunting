<?php

return [

    'auth'                  => '身份验证',
    'profile'               => '个人资料',
    'logout'                => '退出登录',
    'login'                 => '登录',
    'forgot'                => '忘记',
    'login_to'              => '登录以开始您的会话',
    'remember_me'           => '记住我',
    'forgot_password'       => '我忘记了我的密码',
    'reset_password'        => '重置密码',
    'change_password'       => '更改密码',
    'enter_email'           => '请输入您的邮箱地址',
    'current_email'         => '当前邮箱',
    'reset'                 => '重置',
    'never'                 => '从不',
    'landing_page'          => '登录页面',
    'personal_information'  => '个人信息',
    'register_user'         => '注册用户',
    'register'              => '注册',

    'form_description' => [
        'personal'          => '邀请链接将发送给新用户，请确保邮箱地址正确。他们将能够输入自己的密码。',
        'assign'            => '用户将有权访问选定的公司。您可以从 <a href=":url" class="border-b border-black">角色</a> 页面限制权限。',
        'preferences'       => '选择用户的默认语言。您还可以设置用户登录后的登录页面。',
    ],

    'password' => [
        'pass'              => '密码',
        'pass_confirm'      => '确认密码',
        'current'           => '当前密码',
        'current_confirm'   => '确认当前密码',
        'new'               => '新密码',
        'new_confirm'       => '确认新密码',
    ],

    'error' => [
        'self_delete'       => '错误：无法删除自己！',
        'self_disable'      => '错误：无法停用自己！',
        'unassigned'        => '错误：无法取消分配公司！:company 公司必须至少分配一个用户。',
        'no_company'        => '错误：您的账户下没有分配公司。请联系系统管理员。',
    ],

    'login_redirect'        => '验证完成！正在为您重定向...',
    'failed'                => '这些凭据与我们的记录不匹配。',
    'throttle'              => '尝试登录次数过多。请在 :seconds 秒后重试。',
    'disabled'              => '此账号已被停用。请联系系统管理员。',

    'notification' => [
        'message_1'         => '您收到此邮件是因为我们收到了您账户的密码重置请求。',
        'message_2'         => '如果您未请求重置密码，则无需进一步操作。',
        'button'            => '重置密码',
    ],

    'invitation' => [
        'message_1'         => '您收到此邮件是因为您被邀请加入 Akaunting。',
        'message_2'         => '如果您不想加入，则无需进一步操作。',
        'button'            => '开始使用',
    ],

    'information' => [
        'invoice'           => '轻松创建发票',
        'reports'           => '获取详细报表',
        'expense'           => '跟踪任何支出',
        'customize'         => '自定义您的 Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => '管理员',
            'description'   => '他们可以完全访问您的 Akaunting，包括客户、发票、报表、设置和应用。',
        ],
        'manager' => [
            'name'          => '经理',
            'description'   => '他们可以完全访问您的 Akaunting，但无法管理用户和应用。',
        ],
        'customer' => [
            'name'          => '客户',
            'description'   => '他们可以访问客户门户，通过您设置的付款方式在线支付其发票。',
        ],
        'accountant' => [
            'name'          => '会计',
            'description'   => '他们可以访问发票、交易、报表并创建日记账分录。',
        ],
        'employee' => [
            'name'          => '员工',
            'description'   => '他们可以为分配的项目创建费用申请并跟踪时间，但只能看到自己的信息。',
        ],
    ],

];
