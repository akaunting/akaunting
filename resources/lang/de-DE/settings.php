<?php

return [

    'company' => [
        'description'       => 'Firmenname, E-Mail, Adresse, Steuernummer usw. ändern',
        'name'              => 'Name',
        'email'             => 'E-Mail',
        'phone'             => 'Telefon',
        'address'           => 'Adresse',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Steuerjahr, Zeitzone, Datumsformat und mehr lokale Variablen festlegen',
        'financial_start'   => 'Beginn des Finanzjahrs',
        'timezone'          => 'Zeitzone',
        'date' => [
            'format'        => 'Datumsformat',
            'separator'     => 'Datumstrennzeichen',
            'dash'          => 'Bindestrich (-)',
            'dot'           => 'Punkt (.)',
            'comma'         => 'Komma (,)',
            'slash'         => 'Schrägstrich (/)',
            'space'         => 'Leerzeichen ( )',
        ],
        'percent' => [
            'title'         => 'Position des Prozent (%)',
            'before'        => 'Vor der Zahl',
            'after'         => 'Nach der Zahl',
        ],
        'discount_location' => [
            'name'          => 'Rabatt Position',
            'item'          => 'pro Artikel / Position',
            'total'         => 'auf Totalbetrag',
            'both'          => 'pro Artikel / Position und Totalbetrag',
        ],
    ],

    'invoice' => [
        'description'       => 'Standardwerte für die Rechnungsdarstellung anpassen (Präfix, Nummer, Texte, usw.)',
        'prefix'            => 'Rechnungsprefix',
        'digit'             => 'Nachkommastellen',
        'next'              => 'Nächste Nummer',
        'logo'              => 'Logo',
        'custom'            => 'Benutzerdefiniert',
        'item_name'         => 'Artikelbezeichnung',
        'item'              => 'Artikel',
        'product'           => 'Produkte',
        'service'           => 'Dienste',
        'price_name'        => 'Preisbezeichnung',
        'price'             => 'Preis',
        'rate'              => 'Satz',
        'quantity_name'     => 'Mengenbezeichnung',
        'quantity'          => 'Menge',
        'payment_terms'     => 'Zahlungskonditionen',
        'title'             => 'Titel',
        'subheading'        => 'Unterüberschrift',
        'due_receipt'       => 'Fälligkeit: sofort',
        'due_days'          => 'Fällig innerhalb von :days Tagen',
        'choose_template'   => 'Wählen Sie eine Vorlage aus',
        'default'           => 'Standard',
        'classic'           => 'Klassisch',
        'modern'            => 'Modern',
    ],

    'default' => [
        'description'       => 'Standardkonto, Währung, Sprache Ihres Unternehmens',
        'list_limit'        => 'Datensätze pro Seite',
        'use_gravatar'      => 'Gravatar verwenden',
    ],

    'email' => [
        'description'       => 'Sendeprotokoll und E-Mail-Vorlagen ändern',
        'protocol'          => 'Protokoll',
        'php'               => 'PHP-Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP-Server',
            'port'          => 'SMTP-Port',
            'username'      => 'SMTP-Benutzername',
            'password'      => 'SMTP-Passwort',
            'encryption'    => 'SMTP-Sicherheit',
            'none'          => 'Keine',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Pfad',
        'log'               => 'E-Mails protokollieren',

        'templates' => [
            'subject'                   => 'Betreff',
            'body'                      => 'Inhalt',
            'tags'                      => '<strong>Verfügbare Platzhalter:</strong> :tag_list',
            'invoice_new_customer'      => 'Vorlage für neue Rechnungen (an Kunden gesendet)',
            'invoice_remind_customer'   => 'Vorlage für Erinnerungen - Einnahmen (an Kunden gesendet)',
            'invoice_remind_admin'      => 'Vorlage für Erinnerungen - Einnahmen (an Admin gesendet)',
            'invoice_recur_customer'    => 'Vorlage für wiederkehrende Rechnungen (an Kunden gesendet)',
            'invoice_recur_admin'       => 'Vorlage für wiederkehrende Rechnungen (an Admin gesendet)',
            'invoice_payment_customer'  => 'Vorlage für Zahlungseingang (an Kunden gesendet)',
            'invoice_payment_admin'     => 'Vorlage für Zahlungseingang (an Admin gesendet)',
            'bill_remind_admin'         => 'Vorlage für Erinnerungen - Ausgaben (an Admin gesendet)',
            'bill_recur_admin'          => 'Vorlage für wiederkehrende Rechnungen - Ausgaben (an Admin gesendet)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Zeitpläne',
        'description'       => 'Automatische Erinnerungen und Befehl für wiederkehrende Aktionen',
        'send_invoice'      => 'Erinnerung für Kundenrechnung senden',
        'invoice_days'      => 'Senden nach Fälligkeit (Tage)',
        'send_bill'         => 'Erinnerung für Ausgabenrechnung senden',
        'bill_days'         => 'Senden vor Fälligkeit (Tage)',
        'cron_command'      => 'Cron-Befehl',
        'schedule_time'     => 'Ausführungszeit (volle Stunde)',
    ],

    'categories' => [
        'description'       => 'Unbegrenzte Kategorien für Einnahmen, Ausgaben und Artikel',
    ],

    'currencies' => [
        'description'       => 'Währungen erstellen, verwalten und Kurse festlegen',
    ],

    'taxes' => [
        'description'       => 'Steuersätze erstellen und verwalten',
    ],

];
