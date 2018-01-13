<?php

return [

    'success' => [
        'added'             => '已新增:type ！',
        'updated'           => '已更新:type ！',
        'deleted'           => '已刪除:type ！',
        'duplicated'        => ':type 重複！',
        'imported'          => ':type 已匯入！',
    ],
    'error' => [
        'payment_add'       => '錯誤：您不能新增付款！您需要檢查新增金額。',
        'not_user_company'  => '錯誤：您不允許管理此公司！',
        'customer'          => '錯誤：您不能使用此電子郵件建立使用者 :name ！',
        'no_file'           => '錯誤：沒有選擇檔案！',
    ],
    'warning' => [
        'deleted'           => '警告：由於和 :text 相關，你不能刪除<b>:name</b>。',
        'disabled'          => '警告：由於和 :text 相關，你不能停用<b>:name</b>。',
    ],

];
