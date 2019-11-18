<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Vedligeholdelsestilstand',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'Vi arbejder på sitet, prøv venligst senere!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Denne besked var sidst opdateret :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Klassen :class udvider ikke \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Klassen :class eksisterer ikke',
    ]
];