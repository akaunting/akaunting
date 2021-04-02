<?php

return [

    'success' => [
        'added'             => '已新增:type ！',
        'updated'           => '已更新:type ！',
        'deleted'           => '已刪除:type ！',
        'duplicated'        => ':type 重复！',
        'imported'          => ':type 已导入！',
        'exported'          => ':type 导出！',
        'enabled'           => ':type 已启用!',
        'disabled'          => ':type 已禁用!',
    ],

    'error' => [
        'over_payment'      => '错误：付款未添加！您输入的金额超过了总金额：:amount',
        'not_user_company'  => '错误：您不允许管理此公司！',
        'customer'          => '错误：未创建用户！:name已经使用此邮箱。',
        'no_file'           => '错误：沒有选择文件！',
        'last_category'     => '错误：无法刪除最后一个 :type 分类！',
        'change_type'       => '错误：无法更改类型，因为它和 :text 相关！',
        'invalid_apikey'    => '错误：输入的 API 密钥无效！',
        'import_column'     => '错误: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => '错误: Sheet name 无效. 请检查示例文件.',
    ],

    'warning' => [
        'deleted'           => '警告：由于和 :text 相关，你不能刪除<b>:name</b>。',
        'disabled'          => '警告：由于和 :text 相关，你不能停用<b>:name</b>。',
        'reconciled_tran'   => 'Warning: You are not allowed to change/delete transaction because it is reconciled!',
        'reconciled_doc'    => 'Warning: You are not allowed to change/delete :type because it has reconciled transactions!',
        'disable_code'      => '警告：您无权禁用或更改货币 <b>:name</b> ，因为它和 :text有 关联。',
        'payment_cancel'    => '警告: 您已取消了您最近的 :methods 付款 ！',
    ],

];
