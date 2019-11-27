<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'रखरखाव मोड',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'वर्तमान में हम साइट पर काम कर रहे हैं, कृपया बाद में पुनः प्रयास करें!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'यह संदेश अंतिम बार अपडेट किया गया था :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Class :class extends नहीं कर सकता \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Class :class मौजूद नहीं है',
    ]
];