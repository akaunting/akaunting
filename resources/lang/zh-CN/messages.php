<?php

return [

    'success' => [
        'added'             => '已新增:type ！',
        'updated'           => '已更新:type ！',
        'deleted'           => '已刪除:type ！',
        'duplicated'        => ':type 重复！',
        'imported'          => ':type 已导入！',
        'enabled'           => ':type 已启用!',
        'disabled'          => ':type 已禁用!',
    ],
    'error' => [
        'over_payment'      => 'Error: Payment not added! The amount you entered passes the total: :amount',
        'not_user_company'  => '错误：您不允许管理此公司！',
        'customer'          => '错误：未创建用户！:name已经使用此邮箱。',
        'no_file'           => '错误：沒有选择文件！',
        'last_category'     => '错误：无法刪除最后一个 :type 分类！',
        'invalid_token'     => '错误：token输入错误！',
        'import_column'     => '错误: :message Sheet name: :sheet. Line number: :line.',
        'import_sheet'      => '错误: Sheet name 无效. 请检查示例文件.',
    ],
    'warning' => [
        'deleted'           => '警告：由于和 :text 相关，你不能刪除<b>:name</b>。',
        'disabled'          => '警告：由于和 :text 相关，你不能停用<b>:name</b>。',
    ],

];
