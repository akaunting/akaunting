<?php

return [

    'company' => [
        'description'       => '更改公司名称、电子邮件、地址、税号等',
        'name'              => '名称',
        'email'             => '电子邮箱',
        'phone'             => '电话',
        'address'           => '地址',
        'logo'              => '标志',
    ],

    'localisation' => [
        'description'       => '设置财年、时区、日期格式和更多局部信息',
        'financial_start'   => '财年开始',
        'timezone'          => '时区',
        'date' => [
            'format'        => '日期格式',
            'separator'     => '日期分隔',
            'dash'          => '破折号 (-)',
            'dot'           => '点 (.)',
            'comma'         => '逗号 (,)',
            'slash'         => '斜线 (/)',
            'space'         => '空格 ( )',
        ],
        'percent' => [
            'title'         => '百分比 (%) 位置',
            'before'        => '编号之前',
            'after'         => '编号之后',
        ],
    ],

    'invoice' => [
        'description'       => '自定义发票前缀、数字、条款、页脚等。',
        'prefix'            => '订单前缀',
        'digit'             => '数字位数',
        'next'              => '下一个号码',
        'logo'              => '标志',
        'custom'            => '自定义',
        'item_name'         => '项目名称',
        'item'              => '项目',
        'product'           => '产品',
        'service'           => '服务',
        'price_name'        => '价格名称',
        'price'             => '价格',
        'rate'              => '税率',
        'quantity_name'     => '数量名称',
        'quantity'          => '数量',
        'payment_terms'     => '付款条款',
        'title'             => '标题',
        'subheading'        => '副标题',
        'due_receipt'       => '由于收到',
        'due_days'          => '在 :days 内到期',
        'choose_template'   => '选择发票模板',
        'default'           => '默认',
        'classic'           => '经典',
        'modern'            => '现代的',
    ],

    'default' => [
        'description'       => '默认账户、货币、公司语言',
        'list_limit'        => '每页记录',
        'use_gravatar'      => '使用 Gravatar',
    ],

    'email' => [
        'description'       => '更改发送协议和电子邮件模板',
        'protocol'          => '协议',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP IP',
            'port'          => 'SMTP 端口',
            'username'      => 'SMTP 账户',
            'password'      => 'SMTP 绵绵',
            'encryption'    => 'SMTP 安全性',
            'none'          => '无',
        ],
        'sendmail'          => '发送邮件',
        'sendmail_path'     => 'Sendmail 路径',
        'log'               => '邮件日志',

        'templates' => [
            'subject'                   => '主题',
            'body'                      => '正文',
            'tags'                      => '<strong>可用标签：</strong> :tag_list',
            'invoice_new_customer'      => '新发票模板 (发送给客户)',
            'invoice_remind_customer'   => '发票提醒模板 (发送给客户)',
            'invoice_remind_admin'      => '发票提醒模板 (发送给管理员)',
            'invoice_recur_customer'    => '定期发票模板 (发送给客户)',
            'invoice_recur_admin'       => '定期发票模板 (发送给管理员)',
            'invoice_payment_customer'  => '付款接收模板 (发送给客户)',
            'invoice_payment_admin'     => '付款接收模板 (发送给管理员)',
            'bill_remind_admin'         => '账单提醒模板 (发送给管理员)',
            'bill_recur_admin'          => '定期账单模板 (发送给管理员)',
        ],
    ],

    'scheduling' => [
        'name'              => '计划任务',
        'description'       => '定期的自动提醒和指令',
        'send_invoice'      => '发送订单提醒',
        'invoice_days'      => '与到期日后发送',
        'send_bill'         => '发送账单提醒',
        'bill_days'         => '与到期日前发送',
        'cron_command'      => 'Cron命令',
        'schedule_time'     => '执行时间',
    ],

    'categories' => [
        'description'       => '收入、支出和项目的非限定类别',
    ],

    'currencies' => [
        'description'       => '创建、管理货币并设定他们的汇率',
    ],

    'taxes' => [
        'description'       => '固定税率，常规税率，其他费率以及复合税率',
    ],

];
