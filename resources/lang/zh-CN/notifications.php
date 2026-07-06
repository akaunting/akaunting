<?php

return [

    'whoops'              => '哎呀！',
    'hello'               => '您好！',
    'salutation'          => '顺祝商祺，<br> :company_name',
    'subcopy'             => '如果您点击“:text”按钮时遇到问题，请将下面的网址复制并粘贴到您的浏览器中： [:url](:url)',
    'mark_read'           => '标记为已读',
    'mark_read_all'       => '全部标记为已读',
    'empty'               => '太棒了，没有通知！',
    'new_apps'            => ':app 已上线。<a href=":url">立即查看</a>！',

    'update' => [

        'mail' => [

            'title'         => '⚠️ :domain 上的更新失败',
            'description'   => ':alias 从 :current_version 更新至 :new_version 时在 <strong>:step</strong> 步骤失败，错误信息为：:error_message',

        ],

        'slack' => [

            'description'   => ':domain 上的更新失败',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => '下载已就绪',
            'description'   => '文件可从以下链接下载：',

        ],

        'failed' => [

            'title'         => '下载失败',
            'description'   => '由于以下问题无法创建文件：',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => '导入完成',
            'description'   => '导入已完成，记录可在您的面板中查看。',

        ],

        'failed' => [

            'title'         => '导入失败',
            'description'   => '由于以下问题无法导入文件：',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => '导出已就绪',
            'description'   => '导出文件可从以下链接下载：',

        ],

        'failed' => [

            'title'         => '导出失败',
            'description'   => '由于以下问题无法创建导出文件：',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => '无效的 :type 邮箱',
            'description'   => '邮箱 :email 已被报告为无效，该人员已被停用。请检查以下错误信息并修复邮箱地址：',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => '下载已就绪',
            'description'   => '您的 <strong>:type</strong> 文件已准备好 <a href=":url" target="_blank"><strong>下载</strong></a>。',

        ],

        'download_failed' => [

            'title'         => '下载失败',
            'description'   => '由于若干问题无法创建文件。请查看您的电子邮件了解详情。',

        ],

        'export_completed' => [

            'title'         => '导出已就绪',
            'description'   => '您的 <strong>:type</strong> 导出文件已准备好 <a href=":url" target="_blank"><strong>下载</strong></a>。',

        ],

        'export_failed' => [

            'title'         => '导出失败',
            'description'   => '由于若干问题无法创建导出文件。请查看您的电子邮件了解详情。',

        ],

        'import_completed' => [

            'title'         => '导入完成',
            'description'   => '您的 <strong>:type</strong> 共 <strong>:count</strong> 条数据已成功导入。',

        ],

        'import_failed' => [

            'title'         => '导入失败',
            'description'   => '由于若干问题无法导入文件。请查看您的电子邮件了解详情。',

        ],

        'new_apps' => [

            'title'         => '新应用',
            'description'   => '<strong>:name</strong> 应用已发布。您可以<a href=":url">点击此处</a>查看详情。',

        ],

        'invoice_new_customer' => [

            'title'         => '新发票',
            'description'   => '<strong>:invoice_number</strong> 发票已创建。您可以<a href=":invoice_portal_link">点击此处</a>查看详情并进行付款。',

        ],

        'invoice_remind_customer' => [

            'title'         => '发票逾期',
            'description'   => '<strong>:invoice_number</strong> 发票已于 <strong>:invoice_due_date</strong> 到期。您可以<a href=":invoice_portal_link">点击此处</a>查看详情并进行付款。',

        ],

        'invoice_remind_admin' => [

            'title'         => '发票逾期',
            'description'   => '<strong>:invoice_number</strong> 发票已于 <strong>:invoice_due_date</strong> 到期。您可以<a href=":invoice_admin_link">点击此处</a>查看详情。',

        ],

        'invoice_recur_customer' => [

            'title'         => '新定期发票',
            'description'   => '<strong>:invoice_number</strong> 发票已根据您的定期周期创建。您可以<a href=":invoice_portal_link">点击此处</a>查看详情并进行付款。',

        ],

        'invoice_recur_admin' => [

            'title'         => '新定期发票',
            'description'   => '<strong>:invoice_number</strong> 发票已根据 <strong>:customer_name</strong> 的定期周期创建。您可以<a href=":invoice_admin_link">点击此处</a>查看详情。',

        ],

        'invoice_view_admin' => [

            'title'         => '发票已查看',
            'description'   => '<strong>:customer_name</strong> 已查看 <strong>:invoice_number</strong> 发票。您可以<a href=":invoice_admin_link">点击此处</a>查看详情。',

        ],

        'revenue_new_customer' => [

            'title'         => '已收到付款',
            'description'   => '感谢您为 <strong>:invoice_number</strong> 发票付款。您可以<a href=":invoice_portal_link">点击此处</a>查看详情。',

        ],

        'invoice_payment_customer' => [

            'title'         => '已收到付款',
            'description'   => '感谢您为 <strong>:invoice_number</strong> 发票付款。您可以<a href=":invoice_portal_link">点击此处</a>查看详情。',

        ],

        'invoice_payment_admin' => [

            'title'         => '已收到付款',
            'description'   => ':customer_name 已为 <strong>:invoice_number</strong> 发票记录付款。您可以<a href=":invoice_admin_link">点击此处</a>查看详情。',

        ],

        'bill_remind_admin' => [

            'title'         => '账单逾期',
            'description'   => '<strong>:bill_number</strong> 账单已于 <strong>:bill_due_date</strong> 到期。您可以<a href=":bill_admin_link">点击此处</a>查看详情。',

        ],

        'bill_recur_admin' => [

            'title'         => '新定期账单',
            'description'   => '<strong>:bill_number</strong> 账单已根据 <strong>:vendor_name</strong> 的定期周期创建。您可以<a href=":bill_admin_link">点击此处</a>查看详情。',

        ],

        'invalid_email' => [

            'title'         => '无效的 :type 邮箱',
            'description'   => '邮箱 <strong>:email</strong> 已被报告为无效，该人员已被停用。请检查并修复邮箱地址。',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type 已读取此通知！',
        'mark_read_all'         => ':type 已读取所有通知！',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Firefox 图标配置',
            'description'  => '<span class="font-medium">如果您的图标未显示，请：</span> <br /> <span class="font-medium">请允许网页选择自己的字体，而不是使用您上面的选择</span> <br /><br /> <span class="font-bold"> 设置（首选项）> 字体 > 高级 </span>',

        ],

    ],

];
