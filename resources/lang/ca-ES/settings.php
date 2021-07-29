<?php

return [

    'company' => [
        'description'                => 'Canvia el nom d\'empresa, correu electrònic, adreça, NIF, etc...',
        'name'                       => 'Nom',
        'email'                      => 'Correu electrònic',
        'phone'                      => 'Telèfon',
        'address'                    => 'Adreça',
        'edit_your_business_address' => 'Edita l\'adreça de l\'empresa',
        'logo'                       => 'Logotip',
    ],

    'localisation' => [
        'description'       => 'Defineix l\'any fiscal, la zona horària, el format de data i altres configuracions locals.',
        'financial_start'   => 'Inici de l\'any fiscal',
        'timezone'          => 'Zona horària',
        'financial_denote' => [
            'title'         => 'Denominació de l\'exercici',
            'begins'        => 'Per l\'any d\'inici',
            'ends'          => 'Per l\'any de finalització',
        ],
        'date' => [
            'format'        => 'Format de data',
            'separator'     => 'Separador de la data',
            'dash'          => 'Guió (-)',
            'dot'           => 'Punt (.)',
            'comma'         => 'Coma (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espai ( )',
        ],
        'percent' => [
            'title'         => 'Posició del símbol %',
            'before'        => 'Abans de la xifra',
            'after'         => 'Després de la xifra',
        ],
        'discount_location' => [
            'name'          => 'Posició del descompte',
            'item'          => 'A la línia',
            'total'         => 'Al total',
            'both'          => 'Tots dos, la línia i el total',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalitza el prefix, format i el peu del número de factura així com altres aspectes de les factures.',
        'prefix'            => 'Prefix del número de factura',
        'digit'             => 'Quantitat de dígits',
        'next'              => 'Número següent',
        'logo'              => 'Logotip',
        'custom'            => 'Personalitzat',
        'item_name'         => 'Nom de l\'article',
        'item'              => 'Articles',
        'product'           => 'Productes',
        'service'           => 'Serveis',
        'price_name'        => 'Etiqueta del preu',
        'price'             => 'Preu',
        'rate'              => 'Percentatge',
        'quantity_name'     => 'Etiqueta de la quantitat',
        'quantity'          => 'Quantitat',
        'payment_terms'     => 'Condicions de pagament',
        'title'             => 'Títol',
        'subheading'        => 'Subtítol',
        'due_receipt'       => 'Al recepcionar',
        'due_days'          => 'En :days dies',
        'choose_template'   => 'Tria la plantilla de factura',
        'default'           => 'Per defecte',
        'classic'           => 'Clàssica',
        'modern'            => 'Moderna',
        'hide'              => [
            'item_name'         => 'Amaga el nom de producte',
            'item_description'  => 'Amaga la descripció del producte',
            'quantity'          => 'Amaga la quantitat',
            'price'             => 'Amaga el preu',
            'amount'            => 'Amaga el preu',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Tria la plantilla de transferència',
        'second'            => 'Segona',
        'third'             => 'Tercera',
    ],

    'default' => [
        'description'       => 'Compte per defecte, moneda, idioma de la teva empresa',
        'list_limit'        => 'Registres per pàgina',
        'use_gravatar'      => 'Fes servir Gravatar',
        'income_category'   => 'Categoria d\'ingrés',
        'expense_category'  => 'Categoria de despesa',
    ],

    'email' => [
        'description'       => 'Canvia el protocol d\'enviament i les plantilles dels correus',
        'protocol'          => 'Protocol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Allotjament SMTP',
            'port'          => 'Port SMTP',
            'username'      => 'Usuari SMTP',
            'password'      => 'Contrasenya SMTP',
            'encryption'    => 'Seguretat SMTP',
            'none'          => 'Cap',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Ruta de Sendmail',
        'log'               => 'Log dels correus',

        'templates' => [
            'subject'                   => 'Assumpte',
            'body'                      => 'Cos',
            'tags'                      => '<strong>Etiquetes disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Nova plantilla de factura (s\'envia al client)',
            'invoice_remind_customer'   => 'Plantilla de recordatori de factura (s\'envia al client)',
            'invoice_remind_admin'      => 'Plantilla de recordatori de factura (s\'envia a l\'administrador)',
            'invoice_recur_customer'    => 'Plantilla de factura recurrent (s\'envia al client)',
            'invoice_recur_admin'       => 'Plantilla de factura recurrent (s\'envia a l\'administrador)',
            'invoice_payment_customer'  => 'Plantilla de pagament rebut (s\'envia al client)',
            'invoice_payment_admin'     => 'Plantilla de pagament rebut (s\'envia a l\'administrador)',
            'bill_remind_admin'         => 'Plantilla de recordatori de factura a proveïdor (s\'envia a l\'administrador)',
            'bill_recur_admin'          => 'Plantilla de factura a proveïdor recurrent (s\'envia a l\'administrador)',
            'revenue_new_customer'      => 'Plantilla d\'ingrés rebut (enviat al client)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Planificació',
        'description'       => 'Programa recordatoris automàtics i instruccions per accions recurrents',
        'send_invoice'      => 'Envia recordatori de factura',
        'invoice_days'      => 'Envia després del venciment (dies)',
        'send_bill'         => 'Envia recordatori de factura de proveïdor',
        'bill_days'         => 'Envia abans de del venciment (dies)',
        'cron_command'      => 'Programa l\'execució',
        'schedule_time'     => 'Hora d\'execució',
    ],

    'categories' => [
        'description'       => 'Crea les categories d\'ingressos, despeses i articles',
    ],

    'currencies' => [
        'description'       => 'Crea i gestiona monedes i el valor d\'intercanvi',
    ],

    'taxes' => [
        'description'       => 'Defineix els tipus d\'impost: fixe, normal, retenció, inclusiu o compost.',
    ],

];
