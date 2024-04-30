<?php

return [

    'company' => [
        'description'                   => 'Firmenname, E-Mail, Adresse, Steuernummer usw. ändern',
        'search_keywords'               => 'Firma, Name, E-Mail, Telefon, Adresse, Land, Steuernummer, Logo, Stadt, Stadt, Bundesland/Kanton, Provinz, Postleitzahl',
        'name'                          => 'Name',
        'email'                         => 'E-Mail',
        'phone'                         => 'Telefon',
        'address'                       => 'Adresse',
        'edit_your_business_address'    => 'Geschäftsadresse bearbeiten',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Diese Informationen sind in den Datensätzen sichtbar, die Sie erstellen.',
            'address'                   => 'Die Adresse wird in Rechnungen, Quittungen und anderen Datensätzen verwendet, die Sie ausstellen.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Steuerjahr, Zeitzone, Datumsformat und mehr lokale Variablen festlegen',
        'search_keywords'               => 'finanziell, Jahr, Start, Bezeichnung, Zeit, Zone, Datum, Format, Trennzeichen, Rabatt, Prozent',
        'financial_start'               => 'Beginn des Finanzjahrs',
        'timezone'                      => 'Zeitzone',
        'financial_denote' => [
            'title'                     => 'Beginn des Geschäftsjahres',
            'begins'                    => 'Nach dem Jahr, in dem es beginnt',
            'ends'                      => 'Nach dem Jahr, in dem es endet',
        ],
        'preferred_date'                => 'Bevorzugtes Datum',
        'date' => [
            'format'                    => 'Datumsformat',
            'separator'                 => 'Datumstrennzeichen',
            'dash'                      => 'Bindestrich (-)',
            'dot'                       => 'Punkt (.)',
            'comma'                     => 'Komma (,)',
            'slash'                     => 'Schrägstrich (/)',
            'space'                     => 'Leerzeichen ( )',
        ],
        'percent' => [
            'title'                     => 'Position des Prozent (%)',
            'before'                    => 'Vor der Zahl',
            'after'                     => 'Nach der Zahl',
        ],
        'discount_location' => [
            'name'                      => 'Rabattposition',
            'item'                      => 'pro Artikel / Position',
            'total'                     => 'Insgesamt',
            'both'                      => 'pro Artikel / Position und Totalbetrag',
        ],

        'form_description' => [
            'fiscal'                    => 'Legen Sie den Zeitraum des Geschäftsjahres fest, den Ihr Unternehmen für Steuern und Berichtswesen verwendet.',
            'date'                      => 'Wählen Sie das Datumsformat aus, das Sie überall in der Oberfläche sehen möchten.',
            'other'                     => 'Wählen Sie aus, wo das prozentuale Zeichen für Steuern angezeigt wird. Sie können Rabatte auf Linien und insgesamt für Rechnungen aktivieren.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Standardwerte für die Rechnungsdarstellung anpassen (Präfix, Nummer, Texte, usw.)',
        'search_keywords'               => 'anpassen, Rechnung, Nummer, Prefix, Ziffern, nächste, Logo, Name, Preis, Menge, Vorlage, Titel, Unterüberschrift, Fußzeile, Notiz, versteckt, fällig, Farbe, Zahlung, Bedingungen, Spalte',
        'prefix'                        => 'Rechnungsprefix',
        'digit'                         => 'Nachkommastellen',
        'next'                          => 'Nächste Nummer',
        'logo'                          => 'Logo',
        'custom'                        => 'Benutzerdefiniert',
        'item_name'                     => 'Artikelbezeichnung',
        'item'                          => 'Artikel',
        'product'                       => 'Produkte',
        'service'                       => 'Dienste',
        'price_name'                    => 'Preisbezeichnung',
        'price'                         => 'Preis',
        'rate'                          => 'Satz',
        'quantity_name'                 => 'Mengenbezeichnung',
        'quantity'                      => 'Menge',
        'payment_terms'                 => 'Zahlungskonditionen',
        'title'                         => 'Titel',
        'subheading'                    => 'Unterüberschrift',
        'due_receipt'                   => 'Fälligkeit: sofort',
        'due_days'                      => 'Fällig innerhalb von :days Tagen',
        'choose_template'               => 'Wählen Sie eine Vorlage aus',
        'default'                       => 'Standard',
        'classic'                       => 'Klassisch',
        'modern'                        => 'Modern',
        'hide' => [
            'item_name'                 => 'Artikelname ausblenden',
            'item_description'          => 'Artikelbeschreibung ausblenden',
            'quantity'                  => 'Menge ausblenden',
            'price'                     => 'Preis ausblenden',
            'amount'                    => 'Betrag ausblenden',
        ],
        'column'                        => 'Spalte|Spalten',

        'form_description' => [
            'general'                   => 'Legen Sie die Standardwerte für die Formatierung Ihrer Rechnungsnummern und Zahlungsbedingungen fest.',
            'template'                  => 'Wählen Sie eine der unten aufgeführten Vorlagen für Ihre Rechnungen.',
            'default'                   => 'Wenn Sie die Standardwerte für Rechnungen auswählen, werden Titel, Unterüberschriften, Notizen und Fußzeilen vorbelegt. Sie müssen also nicht jedes Mal Rechnungen bearbeiten, um professioneller auszusehen.',
            'column'                    => 'Passen Sie an, wie die Rechnungsspalten benannt sind. Wenn Sie Artikelbeschreibungen und -beträge in Zeilen ausblenden möchten, können Sie dies hier ändern.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Wählen Sie eine Vorlage aus',
        'second'                        => 'Zweite',
        'third'                         => 'Dritte',
    ],

    'default' => [
        'description'                   => 'Standardkonto, Währung, Sprache Ihres Unternehmens',
        'search_keywords'               => 'Konto, Währung, Sprache, Steuer, Zahlung, Methode, Paginierung',
        'list_limit'                    => 'Datensätze pro Seite',
        'use_gravatar'                  => 'Gravatar verwenden',
        'income_category'               => 'Kategorie für Einnahmen',
        'expense_category'              => 'Kategorie für Ausgaben',

        'form_description' => [
            'general'                   => 'Wählen Sie das Standard-Konto, Steuern und Zahlungsmethode aus, um Datensätze schnell zu erstellen. Ihr Dashboard und ihre Berichte werden mit der Standardwährung angezeigt.',
            'category'                  => 'Wählen Sie die Standardkategorien, um die Datensatzerstellung zu beschleunigen.',
            'other'                     => 'Passen Sie die Standardeinstellungen der Firmensprache an und wie die Seitenumstellung funktioniert. ',
        ],
    ],

    'email' => [
        'description'                   => 'Sendeprotokoll und E-Mail-Vorlagen ändern',
        'search_keywords'               => 'E-Mail, senden, Protokoll, SMTP, Host, Passwort',
        'protocol'                      => 'Protokoll',
        'php'                           => 'PHP-Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP-Server',
            'port'                      => 'SMTP-Port',
            'username'                  => 'SMTP-Benutzername',
            'password'                  => 'SMTP-Passwort',
            'encryption'                => 'SMTP-Sicherheit',
            'none'                      => 'Keine',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail Pfad',
        'log'                           => 'E-Mails protokollieren',
        'email_service'                 => 'E-Mail Dienst',
        'email_templates'               => 'E-Mail Vorlagen',

        'form_description' => [
            'general'                   => 'Senden Sie regelmäßig E-Mails an Ihr Team und Ihre Kontakte. Sie können die Protokoll- und SMTP-Einstellungen festlegen.',
        ],

        'templates' => [
            'description'               => 'E-Mail Vorlagen ändern',
            'search_keywords'           => 'E-Mail, Vorlage, Betreff, Schlagwort, Tag',
            'subject'                   => 'Betreff',
            'body'                      => 'Inhalt',
            'tags'                      => '<strong>Verfügbare Platzhalter:</strong> :tag_list',
            'invoice_new_customer'      => 'Vorlage für neue Rechnungen (an Kunden gesendet)',
            'invoice_remind_customer'   => 'Vorlage für Erinnerungen - Einnahmen (an Kunden gesendet)',
            'invoice_remind_admin'      => 'Vorlage für Erinnerungen - Einnahmen (an Admin gesendet)',
            'invoice_recur_customer'    => 'Vorlage für wiederkehrende Rechnungen (an Kunden gesendet)',
            'invoice_recur_admin'       => 'Vorlage für wiederkehrende Rechnungen (an Admin gesendet)',
            'invoice_view_admin'        => 'Vorlage für Rechnungen - Einnahmen (an Admin gesendet)',
            'invoice_payment_customer'  => 'Vorlage für Zahlungseingang (an Kunden gesendet)',
            'invoice_payment_admin'     => 'Vorlage für Zahlungseingang (an Admin gesendet)',
            'bill_remind_admin'         => 'Vorlage für Erinnerungen - Ausgaben (an Admin gesendet)',
            'bill_recur_admin'          => 'Vorlage für wiederkehrende Rechnungen - Ausgaben (an Admin gesendet)',
            'payment_received_customer' => 'Vorlage für Zahlungseingang (an Kunden gesendet)',
            'payment_made_vendor'       => 'Zahlungsbeleg Vorlage (an Verkäufer gesendet)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Zeitpläne',
        'description'                   => 'Automatische Erinnerungen und Befehl für wiederkehrende Aktionen',
        'search_keywords'               => 'automatisch, Erinnerung, Wiederholung, Cron, Befehl',
        'send_invoice'                  => 'Erinnerung für Kundenrechnung senden',
        'invoice_days'                  => 'Senden nach Fälligkeit (Tage)',
        'send_bill'                     => 'Erinnerung für Ausgabenrechnung senden',
        'bill_days'                     => 'Senden vor Fälligkeit (Tage)',
        'cron_command'                  => 'Cron-Befehl',
        'command'                       => 'Befehl',
        'schedule_time'                 => 'Ausführungszeit (volle Stunde)',

        'form_description' => [
            'invoice'                   => 'Aktivieren oder deaktivieren und Erinnerungen für Ihre Rechnungen festlegen, wenn diese überfällig sind.',
            'bill'                      => 'Aktivieren oder deaktivieren Sie und stellen Sie Erinnerungen für Ihre Rechnungen ein, bevor sie überfällig sind.',
            'cron'                      => 'Kopieren Sie den Cron-Befehl, den Ihr Server ausführen soll. Stellen Sie die Zeit ein, um das Ereignis auszulösen.',
        ]
    ],

    'categories' => [
        'description'                   => 'Unbegrenzte Kategorien für Einnahmen, Ausgaben und Artikel',
        'search_keywords'               => 'Kategorie, Einnahmen, Ausgaben, Artikel',
    ],

    'currencies' => [
        'description'                   => 'Währungen erstellen, verwalten und Kurse festlegen',
        'search_keywords'               => 'Standard, Währung, Währungen, Code, Kurs, Symbol, Präzision, Position, Dezimal, Tausend, Markierung, Trennzeichen',
    ],

    'taxes' => [
        'description'                   => 'Steuersätze erstellen und verwalten',
        'search_keywords'               => 'Steuer, Kurs, Typ, fest, inklusiv, Verbindung, Quellenangabe',
    ],

];
