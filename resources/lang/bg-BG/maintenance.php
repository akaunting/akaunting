<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Режим на поддръжка',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'В момента работим по сайта, моля, опитайте отново по-късно!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Това съобщение беше обновено на :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Class :class does not extend \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Клас :class не съществува',
    ]
];