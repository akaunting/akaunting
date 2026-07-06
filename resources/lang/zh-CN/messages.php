<?php

return [

    'success' => [
        'added'             => ':type 已添加！',
        'created'           => ':type 已创建！',
        'updated'           => ':type 已更新！',
        'deleted'           => ':type 已删除！',
        'duplicated'        => ':type 已复制！',
        'imported'          => ':type 已导入！',
        'import_queued'     => ':type 导入已计划！完成后您将收到一封邮件。',
        'exported'          => ':type 已导出！',
        'export_queued'     => '当前页面的 :type 导出已计划！可下载时您将收到一封邮件。',
        'download_queued'   => '当前页面的 :type 下载已计划！可下载时您将收到一封邮件。',
        'enabled'           => ':type 已启用！',
        'disabled'          => ':type 已停用！',
        'connected'         => ':type 已连接！',
        'invited'           => ':type 已邀请！',
        'ended'             => ':type 已结束！',

        'clear_all'         => '太好了！您已清除所有 :type。',
    ],

    'error' => [
        'over_payment'      => '错误：未添加付款！您输入的金额超过了总额：:amount',
        'not_user_company'  => '错误：您无权管理此公司！',
        'customer'          => '错误：用户未创建！:name 已使用此邮箱地址。',
        'no_file'           => '错误：未选择文件！',
        'last_category'     => '错误：无法删除最后一个 <b>:type</b> 分类！',
        'transfer_category' => '错误：无法删除转账 <b>:type</b> 分类！',
        'change_type'       => '错误：无法更改类型，因为它有 :text 相关！',
        'invalid_apikey'    => '错误：输入的 API 密钥无效！',
        'empty_apikey'      => '错误：您尚未输入 API 密钥！<a href=":url" class="font-bold underline underline-offset-4">点击此处</a>输入您的 API 密钥。',
        'import_column'     => '错误：:message 列名：:column。行号：:line。',
        'import_sheet'      => '错误：工作表名称无效。请检查示例文件。',
        'same_amount'       => '错误：拆分总额必须与 :transaction 总额完全相同：:amount',
        'over_match'        => '错误：:type 未连接！您输入的金额不能超过付款总额：:amount',
    ],

    'warning' => [
        'deleted'           => '警告：您无权删除 <b>:name</b>，因为它有 :text 相关。',
        'disabled'          => '警告：您无权停用 <b>:name</b>，因为它有 :text 相关。',
        'reconciled_tran'   => '警告：您无权更改/删除交易，因为它已对账！',
        'reconciled_doc'    => '警告：您无权更改/删除 :type，因为它有已对账的交易！',
        'disable_code'      => '警告：您无权停用或更改 <b>:name</b> 的币种，因为它有 :text 相关。',
        'payment_cancel'    => '警告：您已取消最近的 :method 付款！',
        'missing_transfer'  => '警告：与此交易相关的转账缺失。您应考虑删除此交易。',
        'connect_tax'       => '警告：此 :type 含有税额。添加到 :type 的税无法连接，因此税将被加到总额中并据此计算。',
        'contact_change'    => '警告：您无权在已发送、已接收或已付款的 :type 上更改联系人！',
    ],

];
