<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Maintenance Mode',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'We\'re currently working on the site, please try again later!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'This message was last updated :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Class :class does not extend \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Class :class does not exist',
    ]
];