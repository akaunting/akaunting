<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Wartungsmodus',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'Wir arbeiten gerade an dem Programm, bitte versuche es später noch einmal!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Diese Nachricht wurde zuletzt aktualisiert :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Class :class erweitert nicht \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Class :class existiert nicht',
    ]
];