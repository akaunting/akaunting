<?php

return [

    'success' => [
        'added'             => '已新增:type ！',
        'updated'           => '已更新:type ！',
        'deleted'           => '已刪除:type ！',
        'duplicated'        => ':type 重複！',
        'imported'          => ':type 已匯入！',
        'enabled'           => '已啟用 :type ！',
        'disabled'          => '已停用 :type ！',
    ],
    'error' => [
        'over_payment'      => '錯誤：付款未添加! 你輸入的金額超過總金額: :amount',
        'not_user_company'  => '錯誤：您不允許管理此公司！',
        'customer'          => '錯誤：未建立使用者！:name已經使用此電子郵件。',
        'no_file'           => '錯誤：沒有選擇檔案！',
        'last_category'     => '錯誤：無法刪除最後一個 :type 分類！',
        'invalid_token'     => '錯誤：token輸入錯誤！',
        'import_column'     => '錯誤： :message工作表 name: :sheet，第 number: :line 行',
        'import_sheet'      => '錯誤：工作表名稱不正確，請檢查範例檔案。',
    ],
    'warning' => [
        'deleted'           => '警告：由於和 :text 相關，你不能刪除<b>:name</b>。',
        'disabled'          => '警告：由於和 :text 相關，你不能停用<b>:name</b>。',
    ],

];
