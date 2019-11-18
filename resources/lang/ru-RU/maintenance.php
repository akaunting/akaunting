<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Режим обслуживания',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'В настоящее время ведутся работы на сайте, пожалуйста, повторите попытку позже!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Последнее обновление сообщения :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Класс :class не расширяет \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Класс :class не существует',
    ]
];