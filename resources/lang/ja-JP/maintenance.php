<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'メンテナンスモード',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => '現在サイトで作業中です。しばらくしてからもう一度お試しください！',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'このメッセージは最後に更新されました：タイムスタンプ',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'クラス：クラスは拡張しません\MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'クラス：クラスが存在しません',
    ]
];