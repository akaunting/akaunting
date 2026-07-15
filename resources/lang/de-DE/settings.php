<?php

return [

    'company' => [
        'description'                   => 'Firmenname, E-Mail, Adresse, Steuernummer usw. ändern',
        'search_keywords'               => 'Firma, Name, E-Mail, Telefon, Adresse, Land, Steuernummer, Logo, Stadt, Bundesland/Kanton, Provinz, Postleitzahl',
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
        'description'                   => 'Geschäftsjahr, Zeitzone, Datumsformat und weitere Lokalisierungen festlegen',
        'search_keywords'               => 'Geschäftsjahr, Jahr, Start, Bezeichnung, Zeit, Zone, Datum, Format, Trennzeichen, Rabatt, Prozent',
        'financial_start'               => 'Beginn des Geschäftsjahres',
        'timezone'                      => 'Zeitzone',
        'financial_denote' => [
            'title'                     => 'Geschäftsjahr-Bezeichnung',
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
            'title'                     => 'Position des Prozentzeichens (%)',
            'before'                    => 'Vor der Zahl',
            'after'                     => 'Nach der Zahl',
        ],
        'discount_location' => [
            'name'                      => 'Rabattposition',
            'item'                      => 'Pro Position',
            'total'                     => 'Auf Gesamtbetrag',
            'both'                      => 'Pro Position und Gesamtbetrag',
        ],

        'form_description' => [
            'fiscal'                    => 'Legen Sie den Zeitraum des Geschäftsjahres fest, den Ihr Unternehmen für Steuern und Berichtswesen verwendet.',
            'date'                      => 'Wählen Sie das Datumsformat aus, das Sie überall in der Oberfläche sehen möchten.',
            'other'                     => 'Wählen Sie aus, wo das Prozentzeichen für Steuern angezeigt wird. Sie können Rabatte auf Positionen und auf den Gesamtbetrag für Rechnungen aktivieren.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Rechnungspräfix, Nummer, Bedingungen, Fußzeile usw. anpassen',
        'search_keywords'               => 'anpassen, Rechnung, Nummer, Präfix, Ziffer, nächste, Logo, Name, Preis, Menge, Vorlage, Titel, Unterüberschrift, Fußzeile, Notiz, ausblenden, fällig, Farbe, Zahlung, Bedingungen, Spalte',
        'prefix'                        => 'Nummernpräfix',
        'digit'                         => 'Stellenanzahl',
        'next'                          => 'Nächste Nummer',
        'logo'                          => 'Logo',
        'custom'                        => 'Benutzerdefiniert',
        'item_name'                     => 'Artikelbezeichnung',
        'item'                          => 'Artikel',
        'product'                       => 'Produkte',
        'service'                       => 'Dienstleistungen',
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
        'due_custom'                    => 'Benutzerdefinierte(r) Tag(e)',
        'due_custom_day'                => 'nach Tag',
        'choose_template'               => 'Rechnungsvorlage auswählen',
        'default'                       => 'Standard',
        'classic'                       => 'Klassisch',
        'modern'                        => 'Modern',
        'logo_size_width'               => 'Logo-Breite',
        'logo_size_height'              => 'Logo-Höhe',
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
            'default'                   => 'Wenn Sie die Standardwerte für Rechnungen auswählen, werden Titel, Unterüberschriften, Notizen und Fußzeilen vorbelegt. Sie müssen also nicht jedes Mal Rechnungen bearbeiten, um ein professionelles Erscheinungsbild zu erzielen.',
            'column'                    => 'Passen Sie an, wie die Rechnungsspalten benannt sind. Wenn Sie Artikelbeschreibungen und -beträge in Zeilen ausblenden möchten, können Sie dies hier ändern.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Umbuchungsvorlage auswählen',
        'second'                        => 'Zweite',
        'third'                         => 'Dritte',
    ],

    'default' => [
        'description'                   => 'Standardkonto, Währung, Sprache Ihres Unternehmens',
        'search_keywords'               => 'Konto, Währung, Sprache, Steuer, Zahlung, Methode, Paginierung',
        'list_limit'                    => 'Datensätze pro Seite',
        'use_gravatar'                  => 'Gravatar verwenden',
        'income_category'               => 'Einnahmekategorie',
        'expense_category'              => 'Ausgabekategorie',
        'address_format'                => 'Adressformat',
        'address_tags'                  => '<strong>Verfügbare Tags:</strong> :tags',

        'form_description' => [
            'general'                   => 'Wählen Sie das Standardkonto, Steuern und die Zahlungsmethode aus, um Datensätze schnell zu erstellen. Ihr Dashboard und Ihre Berichte werden mit der Standardwährung angezeigt.',
            'category'                  => 'Wählen Sie die Standardkategorien, um die Datensatzerstellung zu beschleunigen.',
            'other'                     => 'Passen Sie die Standardeinstellungen der Unternehmenssprache an und konfigurieren Sie die Paginierung.',
        ],
    ],

    'email' => [
        'description'                   => 'Sendeprotokoll ändern',
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
        'sendmail_path'                 => 'Sendmail-Pfad',
        'log'                           => 'E-Mails protokollieren',
        'email_service'                 => 'E-Mail-Dienst',
        'email_templates'               => 'E-Mail-Vorlagen',

        'form_description' => [
            'general'                   => 'Senden Sie regelmäßig E-Mails an Ihr Team und Ihre Kontakte. Sie können die Protokoll- und SMTP-Einstellungen festlegen.',
        ],

        'templates' => [
            'description'               => 'E-Mail-Vorlagen ändern',
            'search_keywords'           => 'E-Mail, Vorlage, Betreff, Schlagwort, Tag',
            'subject'                   => 'Betreff',
            'body'                      => 'Inhalt',
            'tags'                      => '<strong>Verfügbare Platzhalter:</strong> :tag_list',
            'invoice_new_customer'      => 'Vorlage für neue Rechnungen (an Kunden gesendet)',
            'invoice_remind_customer'   => 'Vorlage für Rechnungserinnerungen (an Kunden gesendet)',
            'invoice_remind_admin'      => 'Vorlage für Rechnungserinnerungen (an Admin gesendet)',
            'invoice_recur_customer'    => 'Vorlage für wiederkehrende Rechnungen (an Kunden gesendet)',
            'invoice_recur_admin'       => 'Vorlage für wiederkehrende Rechnungen (an Admin gesendet)',
            'invoice_view_admin'        => 'Vorlage für aufgerufene Rechnungen (an Admin gesendet)',
            'invoice_payment_customer'  => 'Vorlage für Zahlungseingangsbestätigung (an Kunden gesendet)',
            'invoice_payment_admin'     => 'Vorlage für Zahlungseingang (an Admin gesendet)',
            'bill_remind_admin'         => 'Vorlage für Eingangsrechnungserinnerungen (an Admin gesendet)',
            'bill_recur_admin'          => 'Vorlage für wiederkehrende Eingangsrechnungen (an Admin gesendet)',
            'payment_received_customer' => 'Vorlage für Zahlungsbestätigung (an Kunden gesendet)',
            'payment_made_vendor'       => 'Vorlage für Zahlungsbestätigung (an Lieferanten gesendet)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Planung',
        'description'                   => 'Automatische Erinnerungen und Befehl für wiederkehrende Aktionen',
        'search_keywords'               => 'automatisch, Erinnerung, Wiederholung, Cron, Befehl',
        'send_invoice'                  => 'Rechnungserinnerung senden',
        'invoice_days'                  => 'Senden nach Fälligkeit (Tage)',
        'send_bill'                     => 'Eingangsrechnungserinnerung senden',
        'bill_days'                     => 'Senden vor Fälligkeit (Tage)',
        'cron_command'                  => 'Cron-Befehl',
        'command'                       => 'Befehl',
        'schedule_time'                 => 'Ausführungszeit (volle Stunde)',

        'form_description' => [
            'invoice'                   => 'Aktivieren oder deaktivieren Sie Erinnerungen für Ihre Rechnungen, wenn diese überfällig sind.',
            'bill'                      => 'Aktivieren oder deaktivieren Sie Erinnerungen für Ihre Eingangsrechnungen, bevor sie überfällig sind.',
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
        'description'                   => 'Feste, normale, inklusive und zusammengesetzte Steuersätze',
        'search_keywords'               => 'Steuer, Satz, Typ, fest, inklusiv, zusammengesetzt, Quellensteuer',
    ],

];
