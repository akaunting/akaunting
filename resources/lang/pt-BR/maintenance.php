<?php

return [
    /**
     * Title of the maintenance page
     *
     * @var string
     */
    'title' => 'Modo de manutenção',

    /**
     * Default application down message, shown on the maintenance page
     *
     * @var string
     */
    'message' => 'Estamos trabalhando no site, por favor, tente novamente mais tarde!',

    /**
     * Last updated string, shown on the maintenance page
     *
     * @var string
     */
    'last-updated' => 'Esta mensagem foi atualizada pela última vez :timestamp',

    /**
     * Exception messages
     *
     * @var array
     */
    'exceptions' => [
        'invalid' => 'Classe :class não estende \MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption',
        'missing' => 'Classe :class não existe',
    ]
];