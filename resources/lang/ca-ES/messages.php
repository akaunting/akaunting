<?php

return [

    'success' => [
        'added'             => 'S\'ha afegit :type!',
        'updated'           => 'S\'ha actualitzat :type!',
        'deleted'           => 'S\'ha esborrat :type!',
        'duplicated'        => 'S\'ha duplicat :type!',
        'imported'          => 'S\'ha importat :type!',
        'import_queued'     => 'S\'ha planificat la importació de :type. Rebràs un correu quan hagi acabat.',
        'exported'          => 'S\'ha exportat :type!',
        'export_queued'     => 'S\'ha planificat l\'exportació de :type. Rebràs un correu quan estigui llesta per la descàrrega.',
        'enabled'           => 'S\'ha activat :type!',
        'disabled'          => 'S\'ha desactivat :type!',
        'connected'         => ':type connectat!',
        'invited'           => ':type convidat!',
        'ended'             => ':type finalitzat!',

        'clear_all'         => 'Fet! Has esborrat tots els :type.',
    ],

    'error' => [
        'over_payment'      => 'Error: No s\'ha anotat el pagament! La quantitat que has entrat és superior al total: :amount',
        'not_user_company'  => 'Error: No tens permisos per gestionar aquesta empresa!',
        'customer'          => 'Error: No s\'ha creat l\'usuari! :name ja utilitza aquesta adreça de correu electrònic.',
        'no_file'           => 'Error: No s\'ha seleccionat cap fitxer!',
        'last_category'     => 'Error: No es pot esborrar l\'últim :type de categoria!',
        'transfer_category' => 'Error: No es pot esborrar l\'última categoria de :type!',
        'change_type'       => 'Error: No es pot canviar el tipus perquè té :text relacionat!',
        'invalid_apikey'    => 'Error: La clau API proporcionada no és vàlida!',
        'import_column'     => 'Error: :message Nom de la pàgina: :sheet. Número de línia: :line.',
        'import_sheet'      => 'Error: Nom de pàgina no vàlid. Si us plau, comprova el fitxer de mostra.',
        'same_amount'       => 'Error: l\'import total de la divisió ha de ser exactament el mateix que el total de :transaction: :amount',
        'over_match'        => 'Error: :type no connectat! L\'import que heu introduït no pot superar el total del pagament: :amount',
    ],

    'warning' => [
        'deleted'           => 'Avís: No pots esborrar <b>:name</b> perquè té :text relacionat.',
        'disabled'          => 'Avís: No pots desactivar <b>:name</b> perquè té :text relacionat.',
        'reconciled_tran'   => 'Avís: No post canviar/esborrar la transacció perquè ja està conciliada.',
        'reconciled_doc'    => 'Avís: No pots canviar/esborrar :type perquè conté transaccions conciliades!',
        'disable_code'      => 'Avís: No pots desactivar o canviar la moneda de <b>:name</b> perquè té :text relacionat.',
        'payment_cancel'    => 'Avís: Has cancel·lat el teu pagament recent :method!',
        'missing_transfer'  => 'Avís: No es troba la transferència relativa a aquesta transacció. S\'hauria d\'esborrar la transacció.',
    ],

];
