<?php

return [

    'whoops'              => 'Hoppala!',
    'hello'               => 'Hallo!',
    'salutation'          => 'Mit freundlichen Grüßen,<br> :company_name',
    'subcopy'             => 'Wenn Sie Probleme damit haben den „:text“ Button zu drücken, kopieren Sie bitte die nachfolgende URL in Ihren Webbrowser: [:url](:url)',
    'mark_read'           => 'Als gelesen markieren',
    'mark_read_all'       => 'Alle als gelesen markieren',
    'empty'               => 'Hurra, keine Benachrichtigungen!',
    'new_apps'            => ':app ist verfügbar. <a href=":url">Jetzt ansehen</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Aktualisierung fehlgeschlagen auf :domain',
            'description'   => 'Die Aktualisierung von :alias von :current_version auf :new_version ist im <strong>:step</strong> Schritt mit der folgenden Nachricht fehlgeschlagen: :error_message',

        ],

        'slack' => [

            'description'   => 'Aktualisierung fehlgeschlagen auf :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Download ist bereit',
            'description'   => 'Die Datei ist bereit zum Download über den folgenden Link:',

        ],

        'failed' => [

            'title'         => 'Download fehlgeschlagen',
            'description'   => 'Die Datei konnte aufgrund des folgenden Problems nicht erstellt werden:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Import abgeschlossen',
            'description'   => 'Der Import wurde abgeschlossen und die Datensätze sind in Ihrem Bereich verfügbar.',

        ],

        'failed' => [

            'title'         => 'Import fehlgeschlagen',
            'description'   => 'Die Datei konnte aufgrund der folgenden Probleme nicht importiert werden:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Export ist bereit',
            'description'   => 'Die Exportdatei ist bereit zum Download über den folgenden Link:',

        ],

        'failed' => [

            'title'         => 'Export fehlgeschlagen',
            'description'   => 'Die Exportdatei konnte aufgrund des folgenden Problems nicht erstellt werden:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Ungültige :type-E-Mail',
            'description'   => 'Die E-Mail-Adresse :email wurde als ungültig gemeldet und die Person wurde deaktiviert. Bitte überprüfen Sie die folgende Fehlermeldung und korrigieren Sie die E-Mail-Adresse:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Download ist bereit',
            'description'   => 'Ihre <strong>:type</strong> Datei ist zum <a href=":url" target="_blank"><strong>Download</strong></a> bereit.',

        ],

        'download_failed' => [

            'title'         => 'Download fehlgeschlagen',
            'description'   => 'Die Datei konnte aufgrund mehrerer Probleme nicht erstellt werden. Weitere Informationen finden Sie in Ihrer E-Mail.',

        ],

        'export_completed' => [

            'title'         => 'Export ist bereit',
            'description'   => 'Ihre <strong>:type</strong> Exportdatei ist zum <a href=":url" target="_blank"><strong>Download</strong></a> bereit.',

        ],

        'export_failed' => [

            'title'         => 'Export fehlgeschlagen',
            'description'   => 'Die Exportdatei konnte aufgrund mehrerer Probleme nicht erstellt werden. Weitere Informationen finden Sie in Ihrer E-Mail.',

        ],

        'import_completed' => [

            'title'         => 'Import abgeschlossen',
            'description'   => 'Ihre <strong>:type</strong> Daten mit <strong>:count</strong> Zeilen wurden erfolgreich importiert.',

        ],

        'import_failed' => [

            'title'         => 'Import fehlgeschlagen',
            'description'   => 'Die Datei konnte aufgrund mehrerer Probleme nicht importiert werden. Weitere Informationen finden Sie in Ihrer E-Mail.',

        ],

        'new_apps' => [

            'title'         => 'Neue App',
            'description'   => 'Die App <strong>:name</strong> ist erschienen. Sie können <a href=":url">hier klicken</a> um die Details zu sehen.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Neue Rechnung',
            'description'   => 'Die Rechnung <strong>:invoice_number</strong> wurde erstellt. Sie können <a href=":invoice_portal_link">hier klicken</a> um die Details zu sehen und mit der Zahlung fortzufahren.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Rechnung überfällig',
            'description'   => 'Die Rechnung <strong>:invoice_number</strong> war am <strong>:invoice_due_date</strong> fällig. Sie können <a href=":invoice_portal_link">hier klicken</a> um die Details zu sehen und mit der Zahlung fortzufahren.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Rechnung überfällig',
            'description'   => 'Die Rechnung <strong>:invoice_number</strong> war am <strong>:invoice_due_date</strong> fällig. Sie können <a href=":invoice_admin_link">hier klicken</a> um die Details zu sehen.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Neue wiederkehrende Rechnung',
            'description'   => 'Die Rechnung <strong>:invoice_number</strong> wurde basierend auf Ihrem Wiederholungszyklus erstellt. Sie können <a href=":invoice_portal_link">hier klicken</a> um die Details zu sehen und mit der Zahlung fortzufahren.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Neue wiederkehrende Rechnung',
            'description'   => 'Die Rechnung <strong>:invoice_number</strong> wurde basierend auf dem Wiederholungszyklus von <strong>:customer_name</strong> erstellt. Sie können <a href=":invoice_admin_link">hier klicken</a> um die Details zu sehen.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Rechnung angesehen',
            'description'   => '<strong>:customer_name</strong> hat die Rechnung <strong>:invoice_number</strong> angesehen. Sie können <a href=":invoice_admin_link">hier klicken</a> um die Details zu sehen.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Zahlung erhalten',
            'description'   => 'Vielen Dank für die Zahlung der Rechnung <strong>:invoice_number</strong>. Sie können <a href=":invoice_portal_link">hier klicken</a> um die Details zu sehen.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Zahlung erhalten',
            'description'   => 'Vielen Dank für die Zahlung der Rechnung <strong>:invoice_number</strong>. Sie können <a href=":invoice_portal_link">hier klicken</a> um die Details zu sehen.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Zahlung erhalten',
            'description'   => ':customer_name hat die Zahlung für die Rechnung <strong>:invoice_number</strong> erfasst. Sie können <a href=":invoice_admin_link">hier klicken</a> um die Details zu sehen.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Rechnung überfällig',
            'description'   => 'Die Rechnung <strong>:bill_number</strong> war am <strong>:bill_due_date</strong> fällig. Sie können <a href=":bill_admin_link">hier klicken</a> um die Details zu sehen.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Neue wiederkehrende Rechnung',
            'description'   => 'Die Rechnung <strong>:bill_number</strong> wurde basierend auf dem Wiederholungszyklus von <strong>:vendor_name</strong> erstellt. Sie können <a href=":bill_admin_link">hier klicken</a> um die Details zu sehen.',

        ],

        'invalid_email' => [

            'title'         => 'Ungültige :type-E-Mail',
            'description'   => 'Die E-Mail-Adresse <strong>:email</strong> wurde als ungültig gemeldet und die Person wurde deaktiviert. Bitte überprüfen und korrigieren Sie die E-Mail-Adresse.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type hat diese Benachrichtigung gelesen!',
        'mark_read_all'         => ':type hat alle Benachrichtigungen gelesen!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Firefox-Symbolkonfiguration',
            'description'  => '<span class="font-medium">Wenn Ihre Symbole nicht angezeigt werden;</span> <br /> <span class="font-medium">Bitte erlauben Sie Seiten, ihre eigenen Schriftarten zu wählen, anstelle Ihrer obigen Auswahl</span> <br /><br /> <span class="font-bold"> Einstellungen (Präferenzen) > Schriftarten > Erweitert </span>',

        ],

    ],

];
