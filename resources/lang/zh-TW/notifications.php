<?php

return [

    'whoops'              => '唉呦！',
    'hello'               => '哈囉！',
    'salutation'          => '你好，<br>:company_name',
    'subcopy'             => '如果你在點選「:text」按鈕時遇到問題，複製並在瀏覽器中貼上以下網址：[:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ 在 :domain 更新失敗',
            'message' => ':alias 更新自 :current_version 至 :new_version 失敗於 <strong>:step</strong> 步驟，錯誤訊息為： :error_message',

        ],

        'slack' => [

            'message' => '在 :domain 更新失敗',

        ],

    ],

];
