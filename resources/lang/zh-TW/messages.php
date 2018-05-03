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
        'over_payment'      => '錯誤：未加入付款方式！數量超過總計。',
        'not_user_company'  => '錯誤：您不允許管理此公司！',
        'customer'          => '錯誤：未建立使用者！:name已經使用此電子郵件。',
        'no_file'           => '錯誤：沒有選擇檔案！',
        'last_category'     => '錯誤：無法刪除最後一個 :type 分類！',
        'invalid_token'     => '錯誤：token輸入錯誤！',
    ],
    'warning' => [
        'deleted'           => '警告：由於和 :text 相關，你不能刪除<b>:name</b>。',
        'disabled'          => '警告：由於和 :text 相關，你不能停用<b>:name</b>。',
    ],

];
