<?php

return [

    'whoops'              => 'Whoops!',
    'hello'               => 'Hola!',
    'salutation'          => 'Atentament,<br> :company_name',
    'subcopy'             => 'Si tens problemes quan prems el botó ":text", copia i enganxa l\'enllaç de sota al teu navegador: [:url](:url)',
    'mark_read'           => 'Marca com a llegida',
    'mark_read_all'       => 'Marcades totes com llegides',
    'empty'               => 'Visca, cap notificació!',
    'new_apps'            => ':app està disponible. <a href=":url">Comprova-ho</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Ha fallat l\'actualització a :domain',
            'description'   => '
L\'actualització de :alias des de :current_version a :new_version ha fallat al pas <strong>:step</strong> amb el següent missatge d\'error: :error_message',

        ],

        'slack' => [

            'description'   => 'L\'actualització ha fallat a :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importació completada',
            'description'   => 'S\'ha completat la importació i els registres ja estan disponibles al teu tauler.',

        ],

        'failed' => [

            'title'         => 'Ha fallat la importació.',
            'description'   => 'No s\'ha pogut importar el fitxer a causa de:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'L\'exportació està disponible.',
            'description'   => 'Pots descarregar el fitxer d\'exportació prement el següent enllaç:',

        ],

        'failed' => [

            'title'         => 'Ha fallat l\'exportació',
            'description'   => 'No s\'ha pogut crear el fitxer d\'exportació a causa de:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Correu :type no vàlid',
            'description'   => 'L\'adreça de correu :email ha estat identificada com a no vàlida, i l\'usuari ha estat desactivat. Si us plau comprova el següent missatge d\'error i corregeix l\'adreça de correu.',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'L\'exportació està disponible.',
            'description'   => 'El fitxer d\'exportació de :type està disponible per <a href=":url" target="_blank"><b>descarregar</b></a>.',

        ],

        'export_failed' => [

            'title'         => 'Ha fallat l\'exportació',
            'description'   => 'No s\'ha pogut crear el fitxer d\'exportació a causa de: :issues',

        ],

        'import_completed' => [

            'title'         => 'Importació completada',
            'description'   => 'S\'ha importat correctament <b>:count</b> de :type.',

        ],

        'import_failed' => [

            'title'         => 'Ha fallat la importació',
            'description'   => 'No s\'ha pogut importar l\'arxiu per vàries raons. Pots mirar el teu correu per tenir-ne més detalls.',

        ],

        'new_apps' => [

            'title'         => 'Nova App',
            'description'   => 'Ha sortit l\'app :name. Pots prémer <a href=":url">aquí</a> per veure\'n els detalls.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nova factura',
            'description'   => 'S\'ha creat la factura <strong>:invoice_number</strong>. Pots prémer <a href=":invoice_portal_link">aquí</a> per veure\'n els detalls i procedir amb el pagament.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Factura vençuda',
            'description'   => 'Ha vençut la factura <strong>:invoice_number</strong> amb data <strong>:invoice_due_date</strong>. Pots prémer <a href=":invoice_portal_link">aquí</a> per veure\'n els detalls i procedir amb el pagament.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Factura vençuda',
            'description'   => 'Ha vençut la factura <strong>:invoice_number</strong> amb data <strong>:invoice_due_date</strong>. Pots prémer <a href=":invoice_admin_link">aquí</a> per veure\'n els detalls.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nova factura recurrent',
            'description'   => 'S\'ha creat la factura recurrent <strong>:invoice_number</strong>. Pots prémer <a href=":invoice_portal_link">aquí</a> per veure\'n els detalls i procedir amb el pagament.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nova factura recurrent',
            'description'   => 'S\'ha creat la factura recurrent <strong>:invoice_number</strong> per a <strong>:customer_name</strong>. Pots prémer <a href=":invoice_admin_link">aquí</a> per veure\'n els detalls.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Factura visualitzada',
            'description'   => '<strong>:customer_name</strong> ha visualitzat la factura <strong>:invoice_number</strong>. Pots prémer <a href=":invoice_admin_link">aquí</a> per veure\'n els detalls.',

        ],

        'revenue_new_customer' => [

            'title'         => 'S\'ha rebut el pagament',
            'description'   => 'Gràcies pel pagament de la factura <strong>:invoice_number</strong>. Pots prémer <a href=":invoice_portal_link">aquí</a> per veure\'n els detalls.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'S\'ha rebut el pagament',
            'description'   => 'Gràcies pel pagament de la factura <strong>:invoice_number</strong>. Pots prémer <a href=":invoice_portal_link">aquí</a> per veure\'n els detalls.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'S\'ha rebut el pagament',
            'description'   => ':customer_name ha marcat el pagament de la factura <strong>:invoice_number</strong>. Pots prémer <a href=":invoice_admin_link">aquí</a> per veure\'n els detalls.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Factura vençuda',
            'description'   => 'Ha vençut la factura <strong>:bill_number</strong> amb data <strong>:bill_due_date</strong>. Pots prémer <a href=":bill_admin_link">aquí</a> per veure\'n els detalls.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nova factura recurrent',
            'description'   => 'S\'ha creat la factura recurrent <strong>:bill_number</strong> de <strong>:vendor_name</strong>. Pots prémer <a href=":bill_admin_link">aquí</a> per veure\'n els detalls.',

        ],

        'invalid_email' => [

            'title'         => 'Correu :type no vàlid',
            'description'   => 'L\'adreça de correu <strong>:email</strong> ha estat identificada com a no vàlida, i l\'usuari ha estat desactivat. Si us plau comprova i corregeix l\'adreça de correu.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type ha llegit aquesta notificació!',
        'mark_read_all'         => ':type ha llegit totes les notificacions!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configuració de la icona de Firefox',
            'description'  => '<span class="font-medium">Si les teves icones no apareixen;</span> <br /> <span class="font-medium">Si us plau permet que les pàgines facin servir els seus propis tipus de lletra en comptes de la selecció de dalt</span> <br /><br /> <span class="font-bold"> Configuració (Preferències) > Tipus de lletra > Avançat </span>',

        ],

    ],

];
