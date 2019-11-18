<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Режим обслуговування',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'Зараз ми працюємо над сайтом, будь ласка, спробуйте ще раз пізніше!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Це повідомлення було востаннє оновлено :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Клас :class не поширюється \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Клас :class не існує',
    ]
];