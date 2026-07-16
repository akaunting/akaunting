<?php

return [

    'company' => [
        'description'                   => '更改公司名称、邮箱、地址、税号等',
        'search_keywords'               => '公司、名称、邮箱、电话、地址、国家、税号、徽标、城市、城镇、州、省、邮政编码',
        'name'                          => '名称',
        'email'                         => '邮箱',
        'phone'                         => '电话',
        'address'                       => '地址',
        'edit_your_business_address'    => '编辑您的企业地址',
        'logo'                          => '徽标',

        'form_description' => [
            'general'                   => '此信息显示在您创建的记录中。',
            'address'                   => '该地址将用于您开具的发票、账单及其他记录。',
        ],
    ],

    'localisation' => [
        'description'                   => '设置会计年度、时区、日期格式及更多本地化选项',
        'search_keywords'               => '财务、年度、开始、表示、时区、日期、格式、分隔符、折扣、百分比',
        'financial_start'               => '会计年度开始',
        'timezone'                      => '时区',
        'financial_denote' => [
            'title'                     => '会计年度表示',
            'begins'                    => '按开始的年份',
            'ends'                      => '按结束的年份',
        ],
        'preferred_date'                => '首选日期',
        'date' => [
            'format'                    => '日期格式',
            'separator'                 => '日期分隔符',
            'dash'                      => '破折号 (-)',
            'dot'                       => '点 (.)',
            'comma'                     => '逗号 (,)',
            'slash'                     => '斜线 (/)',
            'space'                     => '空格 ( )',
        ],
        'percent' => [
            'title'                     => '百分比 (%) 位置',
            'before'                    => '数字之前',
            'after'                     => '数字之后',
        ],
        'discount_location' => [
            'name'                      => '折扣位置',
            'item'                      => '在行上',
            'total'                     => '在合计上',
            'both'                      => '行与合计均显示',
        ],

        'form_description' => [
            'fiscal'                    => '设置您的公司用于计税和报表的会计年度期间。',
            'date'                      => '选择您希望在整个界面中看到的日期格式。',
            'other'                     => '选择税额百分比符号的显示位置。您可以为发票和账单在行项目和合计上启用折扣。',
        ],
    ],

    'invoice' => [
        'description'                   => '自定义发票前缀、编号、条款、页脚等',
        'search_keywords'               => '自定义、发票、编号、前缀、位数、下一个、徽标、名称、价格、数量、模板、标题、副标题、页脚、备注、隐藏、到期、颜色、付款、条款、列',
        'prefix'                        => '编号前缀',
        'digit'                         => '编号位数',
        'next'                          => '下一个编号',
        'logo'                          => '徽标',
        'custom'                        => '自定义',
        'item_name'                     => '项目名称',
        'item'                          => '项目',
        'product'                       => '产品',
        'service'                       => '服务',
        'price_name'                    => '价格名称',
        'price'                         => '价格',
        'rate'                          => '费率',
        'quantity_name'                 => '数量名称',
        'quantity'                      => '数量',
        'payment_terms'                 => '付款条款',
        'title'                         => '标题',
        'subheading'                    => '副标题',
        'due_receipt'                   => '收到即到期',
        'due_days'                      => '在 :days 天内到期',
        'due_custom'                    => '自定义天数',
        'due_custom_day'                => '之后的天数',
        'choose_template'               => '选择发票模板',
        'default'                       => '默认',
        'classic'                       => '经典',
        'modern'                        => '现代',
        'logo_size_width'               => '徽标宽度',
        'logo_size_height'              => '徽标高度',
        'hide' => [
            'item_name'                 => '隐藏项目名称',
            'item_description'          => '隐藏项目描述',
            'quantity'                  => '隐藏数量',
            'price'                     => '隐藏价格',
            'amount'                    => '隐藏金额',
        ],
        'column'                        => '列|列',

        'form_description' => [
            'general'                   => '设置发票编号和付款条款的默认格式。',
            'template'                  => '为您的发票选择下方的一个模板。',
            'default'                   => '选择发票默认值将预填标题、副标题、备注和页脚。这样您就不必每次都编辑发票以使其更专业。',
            'column'                    => '自定义发票列的命名方式。如果您想在行中隐藏项目描述和金额，可在此处更改。',
        ]
    ],

    'transfer' => [
        'choose_template'               => '选择转账模板',
        'second'                        => '第二种',
        'third'                         => '第三种',
    ],

    'default' => [
        'description'                   => '您公司的默认账户、币种、语言',
        'search_keywords'               => '账户、币种、语言、税、付款、方式、分页',
        'list_limit'                    => '每页记录数',
        'use_gravatar'                  => '使用 Gravatar',
        'income_category'               => '收入类别',
        'expense_category'              => '支出类别',
        'address_format'                => '地址格式',
        'address_tags'                  => '<strong>可用标签：</strong> :tags',

        'form_description' => [
            'general'                   => '选择默认账户、税和付款方式以快速创建记录。仪表板和报表以默认币种显示。',
            'category'                  => '选择默认类别以加快记录创建速度。',
            'other'                     => '自定义公司语言的默认设置以及分页的工作方式。',
        ],
    ],

    'email' => [
        'description'                   => '更改发送协议',
        'search_keywords'               => '邮箱、发送、协议、smtp、主机、密码',
        'protocol'                      => '协议',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP 主机',
            'port'                      => 'SMTP 端口',
            'username'                  => 'SMTP 用户名',
            'password'                  => 'SMTP 密码',
            'encryption'                => 'SMTP 安全',
            'none'                      => '无',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail 路径',
        'log'                           => '记录邮件',
        'email_service'                 => '邮件服务',
        'email_templates'               => '邮件模板',

        'form_description' => [
            'general'                   => '向您的团队和联系人发送常规邮件。您可以设置协议和 SMTP 设置。',
        ],

        'templates' => [
            'description'               => '更改邮件模板',
            'search_keywords'           => '邮件、模板、主题、正文、标签',
            'subject'                   => '主题',
            'body'                      => '正文',
            'tags'                      => '<strong>可用标签：</strong> :tag_list',
            'invoice_new_customer'      => '新发票模板（发送给客户）',
            'invoice_remind_customer'   => '发票提醒模板（发送给客户）',
            'invoice_remind_admin'      => '发票提醒模板（发送给管理员）',
            'invoice_recur_customer'    => '定期发票模板（发送给客户）',
            'invoice_recur_admin'       => '定期发票模板（发送给管理员）',
            'invoice_view_admin'        => '发票查看模板（发送给管理员）',
            'invoice_payment_customer'  => '发票付款收据模板（发送给客户）',
            'invoice_payment_admin'     => '发票付款已收模板（发送给管理员）',
            'bill_remind_admin'         => '账单提醒模板（发送给管理员）',
            'bill_recur_admin'          => '定期账单模板（发送给管理员）',
            'payment_received_customer' => '付款收据模板（发送给客户）',
            'payment_made_vendor'       => '付款已付模板（发送给供应商）',
        ],
    ],

    'scheduling' => [
        'name'                          => '计划任务',
        'description'                   => '自动提醒和定期命令',
        'search_keywords'               => '自动、提醒、定期、cron、命令',
        'send_invoice'                  => '发送发票提醒',
        'invoice_days'                  => '到期后发送天数',
        'send_bill'                     => '发送账单提醒',
        'bill_days'                     => '到期前发送天数',
        'cron_command'                  => 'Cron 命令',
        'command'                       => '命令',
        'schedule_time'                 => '运行时间',

        'form_description' => [
            'invoice'                   => '为逾期发票启用或停用提醒，并设置提醒。',
            'bill'                      => '为账单在逾期前启用或停用提醒，并设置提醒。',
            'cron'                      => '复制您的服务器应运行的 cron 命令。设置触发事件的时间。',
        ]
    ],

    'categories' => [
        'description'                   => '收入、支出和项目的无限类别',
        'search_keywords'               => '类别、收入、支出、项目',
    ],

    'currencies' => [
        'description'                   => '创建和管理币种并设置其汇率',
        'search_keywords'               => '默认、币种、代码、汇率、符号、精度、位置、小数、千位、标记、分隔符',
    ],

    'taxes' => [
        'description'                   => '固定、常规、含税和复合税率',
        'search_keywords'               => '税、税率、类型、固定、含税、复合、预扣',
    ],

];
