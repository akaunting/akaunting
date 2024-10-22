<?php

return [

    'whoops'              => 'Oeps!',
    'hello'               => 'Hallo!',
    'salutation'          => 'Met vriendelijke groet,<br>:company_name',
    'subcopy'             => 'Als u problemen ondervindt met het klikken op ":text" knop, kopieer en plak dan onderstaande URL in uw webbrowser: [:url](:url)',
    'mark_read'           => 'Markeer als gelezen',
    'mark_read_all'       => 'Markeer alles als gelezen',
    'empty'               => 'Woohoo, kennisgeving nul!',
    'new_apps'            => 'Nieuwe App|Nieuwe Apps',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Update mislukt op :domain',
            'description'   => 'De update van :alias van :current_version naar :new_version is mislukt in <strong>:step</strong> stap met de volgende boodschap: :error_message',

        ],

        'slack' => [

            'description'   => 'Update mislukt op :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Downloaden is klaar',
            'description'   => 'Het bestand kan via de volgende link worden gedownload:',

        ],

        'failed' => [

            'title'         => 'Downloaden mislukt',
            'description'   => 'Kan het bestand niet aanmaken vanwege het volgende probleem:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importeren voltooid',
            'description'   => 'De import is voltooid en de records zijn beschikbaar in uw paneel.',

        ],

        'failed' => [

            'title'         => 'Importeren mislukt',
            'description'   => 'Niet in staat om het bestand te importeren vanwege de volgende problemen:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Export is klaar',
            'description'   => 'Het exportbestand is klaar om te downloaden van de volgende link:',

        ],

        'failed' => [

            'title'         => 'Exporteren mislukt',
            'description'   => 'Niet in staat om het exportbestand aan te maken vanwege het volgende probleem:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Ongeldig :type E-mail',
            'description'   => 'Het :email e-mailadres is gerapporteerd als ongeldig, en de persoon is uitgeschakeld. Controleer de volgende foutmelding en corrigeer het e-mailadres:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Downloaden is klaar',
            'description'   => 'Je <strong>:type</strong> bestand is klaar om <a href=":url" target="_blank"><strong>te downloaden</strong></a>.',

        ],

        'download_failed' => [

            'title'         => 'Downloaden mislukt',
            'description'   => 'Kan het bestand niet aanmaken als gevolg van verschillende problemen. Kijk in je e-mail voor de details.',

        ],

        'export_completed' => [

            'title'         => 'Export is klaar',
            'description'   => 'Uw <strong>:type</strong> exportbestand is klaar om <a href=":url" target="_blank"><strong>gedownload te worden</strong></a>.
',

        ],

        'export_failed' => [

            'title'         => 'Exporteren mislukt',
            'description'   => 'Kan het exportbestand niet maken door verschillende problemen. Kijk in je e-mail voor de details.',

        ],

        'import_completed' => [

            'title'         => 'Importeren voltooid',
            'description'   => 'Uw <strong>:type</strong> regel <strong>:count</strong> gegevens zijn succesvol geïmporteerd.',

        ],

        'import_failed' => [

            'title'         => 'Importeren mislukt',
            'description'   => 'Kan het bestand niet importeren door verschillende problemen. Kijk in je e-mail voor de details.',

        ],

        'new_apps' => [

            'title'         => 'Nieuwe App',
            'description'   => '<strong>:name</strong> app is uit. U kunt <a href=":url">hier klikken</a> om de details te zien.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nieuwe factuur',
            'description'   => '<strong>:invoice_number</strong> factuur is aangemaakt. U kunt <a href=":invoice_portal_link">hier klikken</a> om de details te zien en door te gaan met de betaling.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Achterstallig Factuur',
            'description'   => '<strong>:invoice_number</strong> factuur was te voldoen voor <strong>:invoice_due_date</strong>. Je kunt <a href=":invoice_portal_link">hier klikken</a> om de details te zien en door te gaan met de betaling.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Achterstallig Factuur',
            'description'   => '<strong>:invoice_number</strong> factuur was te voldoen voor <strong>:invoice_due_date</strong>. Je kunt <a href=":invoice_admin_link">hier klikken</a> om de details te zien.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nieuwe terugkerende Factuur',
            'description'   => '<strong>:invoice_number</strong> factuur wordt aangemaakt op basis van uw terugkerende cirkel. U kunt <a href=":invoice_portal_link">hier klikken</a> om de details te zien en door te gaan met de betaling.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nieuwe Terugkerende Factuur',
            'description'   => '<strong>:invoice_number</strong> factuur wordt aangemaakt op basis van <strong>:customer_name</strong> terugkerende cirkel. U kunt <a href=":invoice_admin_link">hier klikken</a> om de details te zien.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Factuur Bekeken',
            'description'   => '<strong>:customer_name</strong> heeft de <strong>:invoice_number</strong> factuur bekeken. U kunt <a href=":invoice_admin_link">hier klikken</a> om de details te zien.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Betaling Ontvangen',
            'description'   => 'Bedankt voor de betaling van factuur <strong>:invoice_number</strong>. Je kunt <a href=":invoice_portal_link">hier klikken</a> om de details te zien.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Betaling Ontvangen',
            'description'   => 'Bedankt voor de betaling van factuur <strong>:invoice_number</strong>. Je kunt <a href=":invoice_portal_link">hier klikken</a> om de details te zien.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Betaling Ontvangen',
            'description'   => ':customer_name geregistreerde betaling voor factuur <strong>:invoice_number</strong>. Je kunt <a href=":invoice_admin_link">hier klikken</a> om de details te zien.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Factuur te laat',
            'description'   => '<strong>:bill_number</strong> factuur vervallen op <strong>:bill_due_date</strong>. Je kunt <a href=":bill_admin_link">hier klikken</a> om de details te zien.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nieuwe Terugkerende Factuur',
            'description'   => '<strong>:bill_number</strong> factuur is gemaakt op basis van <strong>:vendor_name</strong> terugkerende cirkel. U kunt <a href=":bill_admin_link">hier klikken</a> om de details te zien.',

        ],

        'invalid_email' => [

            'title'         => 'Ongeldig :type E-mail',
            'description'   => 'Het <strong>:email</strong> e-mailadres is gerapporteerd als ongeldig, en de persoon is uitgeschakeld. Controleer en corrigeer het e-mailadres.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type lees deze notificatie!',
        'mark_read_all'         => ':type lees deze notificaties!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Firefox pictogramconfiguratie',
            'description'  => '<span class="font-medium">Als uw pictogrammen niet verschijnen;</span> <br /> <span class="font-medium">Sta pagina\'s toe hun eigen lettertypen te kiezen, in plaats van uw selecties hierboven.</span> <br /><br /> <span class="font-bold"> Instellingen (Voorkeuren) > Fonts > Geavanceerd </span>',

        ],

    ],

];
