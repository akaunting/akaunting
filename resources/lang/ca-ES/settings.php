<?php

return [

    'company' => [
        'description'                   => 'Canvia el nom d\'empresa, correu electrònic, adreça, NIF, etc...',
        'search_keywords'               => 'empresa, nom, correu electrònic, telèfon, adreça, país, número fiscal, logotip, ciutat, poble, estat, província, codi postal',
        'name'                          => 'Nom',
        'email'                         => 'Correu electrònic',
        'phone'                         => 'Telèfon',
        'address'                       => 'Adreça',
        'edit_your_business_address'    => 'Edita l\'adreça de l\'empresa',
        'logo'                          => 'Logotip',

        'form_description' => [
            'general'                   => 'Aquesta informació serà visible als registres que s\'emetin.',
            'address'                   => 'L\'adreça apareixerà a les factures i altres registres que s\'emetin.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Defineix l\'any fiscal, la zona horària, el format de data i altres configuracions locals.',
        'search_keywords'               => 'financer, any, inici, denotar, hora, zona, data, format, separador, descompte, percentatge',
        'financial_start'               => 'Inici de l\'any fiscal',
        'timezone'                      => 'Zona horària',
        'financial_denote' => [
            'title'                     => 'Denominació de l\'exercici',
            'begins'                    => 'Per l\'any d\'inici',
            'ends'                      => 'Per l\'any de finalització',
        ],
        'preferred_date'                => 'Data preferida',
        'date' => [
            'format'                    => 'Format de data',
            'separator'                 => 'Separador de la data',
            'dash'                      => 'Guió (-)',
            'dot'                       => 'Punt (.)',
            'comma'                     => 'Coma (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Espai ( )',
        ],
        'percent' => [
            'title'                     => 'Posició del símbol %',
            'before'                    => 'Abans de la xifra',
            'after'                     => 'Després de la xifra',
        ],
        'discount_location' => [
            'name'                      => 'Posició del descompte',
            'item'                      => 'A la línia',
            'total'                     => 'Al total',
            'both'                      => 'Tots dos, la línia i el total',
        ],

        'form_description' => [
            'fiscal'                    => 'Estableix el període de l\'exercici que l\'empresa utilitza per gravar i generar informes.',
            'date'                      => 'Selecciona el format de data que es vol veure a tot arreu de la interfície.',
            'other'                     => 'Selecciona on es mostra el signe de percentatge per als impostos. Podeu habilitar descomptes en línies i en el total de factures i factures.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalitza el prefix, format i el peu del número de factura així com altres aspectes de les factures.',
        'search_keywords'               => 'personalitzar, factura, número, prefix, dígit, següent, logotip, nom, preu, quantitat, plantilla, títol, subtítol, peu de pàgina, nota, amagar, venciment, color, pagament, termes, columna',
        'prefix'                        => 'Prefix del número de factura',
        'digit'                         => 'Quantitat de dígits',
        'next'                          => 'Número següent',
        'logo'                          => 'Logotip',
        'custom'                        => 'Personalitzat',
        'item_name'                     => 'Nom de l\'article',
        'item'                          => 'Articles',
        'product'                       => 'Productes',
        'service'                       => 'Serveis',
        'price_name'                    => 'Etiqueta del preu',
        'price'                         => 'Preu',
        'rate'                          => 'Percentatge',
        'quantity_name'                 => 'Etiqueta de la quantitat',
        'quantity'                      => 'Quantitat',
        'payment_terms'                 => 'Condicions de pagament',
        'title'                         => 'Títol',
        'subheading'                    => 'Subtítol',
        'due_receipt'                   => 'Al recepcionar',
        'due_days'                      => 'En :days dies',
        'due_custom'                    => 'Dia(es) personalitzats',
        'due_custom_day'                => 'pròxim dia',
        'choose_template'               => 'Tria la plantilla de factura',
        'default'                       => 'Per defecte',
        'classic'                       => 'Clàssica',
        'modern'                        => 'Moderna',
        'hide' => [
            'item_name'                 => 'Amaga el nom de producte',
            'item_description'          => 'Amaga la descripció del producte',
            'quantity'                  => 'Amaga la quantitat',
            'price'                     => 'Amaga el preu',
            'amount'                    => 'Amaga el preu',
        ],
        'column'                        => 'Columna|Columnes',

        'form_description' => [
            'general'                   => 'Estableix els valors predeterminats per al format dels números de factura i condicions de pagament.',
            'template'                  => 'Selecciona una de les plantilles següents per a les factures.',
            'default'                   => 'Si selecciones els valors predeterminats per a les factures, s\'emplenaran prèviament els títols, els subtítols, les notes i els peus de pàgina. Així que no cal que editis les factures cada cop per semblar més professional.',
            'column'                    => 'Personalitza com s\'anomenen les columnes de la factura. Si vols amagar les descripcions i els imports dels articles a les línies, pots canviar-ho aquí.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Tria la plantilla de transferència',
        'second'                        => 'Segona',
        'third'                         => 'Tercera',
    ],

    'default' => [
        'description'                   => 'Compte per defecte, moneda, idioma de la teva empresa',
        'search_keywords'               => 'compte, moneda, idioma, impost, pagament, mètode, paginació',
        'list_limit'                    => 'Registres per pàgina',
        'use_gravatar'                  => 'Fes servir Gravatar',
        'income_category'               => 'Categoria d\'ingrés',
        'expense_category'              => 'Categoria de despesa',
        'address_format'                => 'Format de l\'adreça',
        'address_tags'                  => '<strong>Etiquetes disponibles:</strong>:tags',

        'form_description' => [
            'general'                   => 'Selecciona el compte, l\'impost i el mètode de pagament predeterminats per crear registres ràpidament. El tauler i els informes es mostren amb la moneda predeterminada.',
            'category'                  => 'Selecciona les categories predeterminades per accelerar la creació del registre.',
            'other'                     => 'Personalitza la configuració predeterminada de l\'idioma de l\'empresa i com funciona la paginació.',
        ],
    ],

    'email' => [
        'description'                   => 'Canvia el protocol d\'enviament i les plantilles dels correus',
        'search_keywords'               => 'correu-electrònic, enviament, protocol, smtp, host, contrasenya',
        'protocol'                      => 'Protocol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Allotjament SMTP',
            'port'                      => 'Port SMTP',
            'username'                  => 'Usuari SMTP',
            'password'                  => 'Contrasenya SMTP',
            'encryption'                => 'Seguretat SMTP',
            'none'                      => 'Cap',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Ruta de Sendmail',
        'log'                           => 'Log dels correus',
        'email_service'                 => 'Servei de correu electrònic',
        'email_templates'               => 'Plantilles de correu electrònic',

        'form_description' => [
            'general'                   => 'Envia correus electrònics regulars al vostre equip i contactes. Pots configurar el protocol i la configuració SMTP.',
        ],

        'templates' => [
            'description'               => 'Canvia les plantilles de correu electrònic',
            'search_keywords'           => 'correu-electrònic, plantilla, tema, cos, etiqueta',
            'subject'                   => 'Assumpte',
            'body'                      => 'Cos',
            'tags'                      => '<strong>Etiquetes disponibles:</strong> :tag_list',
            'invoice_new_customer'      => 'Nova plantilla de factura (s\'envia al client)',
            'invoice_remind_customer'   => 'Plantilla de recordatori de factura (s\'envia al client)',
            'invoice_remind_admin'      => 'Plantilla de recordatori de factura (s\'envia a l\'administrador)',
            'invoice_recur_customer'    => 'Plantilla de factura recurrent (s\'envia al client)',
            'invoice_recur_admin'       => 'Plantilla de factura recurrent (s\'envia a l\'administrador)',
            'invoice_view_admin'        => 'Plantilla de visualització de la factura (enviada a l\'administrador)',
            'invoice_payment_customer'  => 'Plantilla de pagament rebut (s\'envia al client)',
            'invoice_payment_admin'     => 'Plantilla de pagament rebut (s\'envia a l\'administrador)',
            'bill_remind_admin'         => 'Plantilla de recordatori de factura a proveïdor (s\'envia a l\'administrador)',
            'bill_recur_admin'          => 'Plantilla de factura a proveïdor recurrent (s\'envia a l\'administrador)',
            'payment_received_customer' => 'Plantilla de rebut de pagament (enviada al client)',
            'payment_made_vendor'       => 'Plantilla de pagament fet (enviada al proveïdor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Planificació',
        'description'                   => 'Programa recordatoris automàtics i instruccions per accions recurrents',
        'search_keywords'               => 'automàtic, recordatori, recurrent, cron, comandament',
        'send_invoice'                  => 'Envia recordatori de factura',
        'invoice_days'                  => 'Envia després del venciment (dies)',
        'send_bill'                     => 'Envia recordatori de factura de proveïdor',
        'bill_days'                     => 'Envia abans de del venciment (dies)',
        'cron_command'                  => 'Programa l\'execució',
        'command'                       => 'Instrucció',
        'schedule_time'                 => 'Hora d\'execució',

        'form_description' => [
            'invoice'                   => 'Activa o desactiva i configura recordatoris per a les factures quan vencen.',
            'bill'                      => 'Activa o desactiva i configura recordatoris per a les factures abans que vencin.',
            'cron'                      => 'Copia la isntrucció cron que hauria d\'executar el vostre servidor. Estableix l\'hora per activar l\'esdeveniment.',
        ]
    ],

    'categories' => [
        'description'                   => 'Crea les categories d\'ingressos, despeses i articles',
        'search_keywords'               => 'categoria, ingressos, despesa, partida',
    ],

    'currencies' => [
        'description'                   => 'Crea i gestiona monedes i el valor d\'intercanvi',
        'search_keywords'               => 'predeterminat, moneda, monedes, codi, taxa, símbol, precisió, posició, decimal, milers, marca, separador',
    ],

    'taxes' => [
        'description'                   => 'Defineix els tipus d\'impost: fixe, normal, retenció, inclusiu o compost.',
        'search_keywords'               => 'impost, tipus, tipus, fix, inclòs, compost, retenció',
    ],

];
