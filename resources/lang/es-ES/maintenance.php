<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Modo de mantenimiento',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'Actualmente estamos trabajando en el sitio, por favor inténtelo de nuevo más tarde!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Este mensaje fue actualizado por última vez a :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Clase :class no extiende de \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Clase :class no existe',
    ]
];