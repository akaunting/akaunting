<?php

return [

    'invoice_number'        => 'Rechnungsnummer',
    'invoice_date'          => 'Rechnungsdatum',
    'invoice_amount'        => 'Rechnungsbetrag',
    'total_price'           => 'Gesamtpreis',
    'due_date'              => 'Fälligkeitsdatum',
    'order_number'          => 'Bestellnummer',
    'bill_to'               => 'Rechnung an',
    'cancel_date'           => 'Storniert am',

    'quantity'              => 'Menge',
    'price'                 => 'Preis',
    'sub_total'             => 'Zwischensumme',
    'discount'              => 'Rabatt',
    'item_discount'         => 'Positions-Rabatt',
    'tax_total'             => 'Steuern Gesamt',
    'total'                 => 'Gesamt',

    'item_name'             => 'Artikelname|Artikelnamen',
    'recurring_invoices'    => 'Wiederkehrende Rechnung|Wiederkehrende Rechnungen',

    'show_discount'         => ':discount% Rabatt',
    'add_discount'          => 'Rabatt hinzufügen',
    'discount_desc'         => 'der Zwischensumme',

    'payment_due'           => 'Fälligkeit der Zahlung',
    'paid'                  => 'Bezahlt',
    'histories'             => 'Historie',
    'payments'              => 'Zahlungen',
    'add_payment'           => 'Zahlung hinzufügen',
    'mark_paid'             => 'Als bezahlt markieren',
    'mark_sent'             => 'Als gesendet markieren',
    'mark_viewed'           => 'Als gelesen markieren',
    'mark_cancelled'        => 'Als storniert markieren',
    'download_pdf'          => 'PDF herunterladen',
    'send_mail'             => 'E-Mail senden',
    'all_invoices'          => 'Melden Sie sich an, um alle Rechnungen anzuzeigen',
    'create_invoice'        => 'Rechnung erstellen',
    'send_invoice'          => 'Rechnung senden',
    'get_paid'              => 'Zahlung erhalten',
    'accept_payments'       => 'Onlinezahlungen akzeptieren',
    'payment_received'      => 'Zahlung erhalten',

    'form_description' => [
        'billing'           => 'Rechnungsdetails erscheinen in Ihrer Rechnung. Rechnungsdatum wird im Dashboard und in Berichten verwendet. Wählen Sie das voraussichtliche Zahlungsdatum als Fälligkeitsdatum aus.
',
    ],

    'messages' => [
        'email_required'    => 'Es existiert keine E-Mailadresse zu diesem Kunden!',
        'draft'             => 'Dies ist eine <b>Vorschau</b>-Rechnung und wird nach dem Versand in den Charts ersichtlich.',

        'status' => [
            'created'       => 'Erstellt am :date',
            'viewed'        => 'Gelesen',
            'send' => [
                'draft'     => 'Noch nicht versandt',
                'sent'      => 'Gesendet am :date',
            ],
            'paid' => [
                'await'     => 'Zahlung erwartet',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user hat diese Rechnung am :date erstellt',
        'create_recurring'  => ':user hat diese wiederkehrende Vorlage am :date erstellt',
        'schedule'          => 'Wiederhole alle :interval :frequency seit :date',
        'children'          => ':count Rechnungen wurden automatisch erstellt',
    ],

    'share' => [
        'show_link'         => 'Ihr Kunde kann die Rechnung unter diesem Link ansehen',
        'copy_link'         => 'Kopieren Sie den Link und teilen Sie ihn mit Ihrem Kunden.',
        'success_message'   => 'Link in die Zwischenablage kopiert!',
    ],

    'sticky' => [
        'description'       => 'Sie sehen wie Ihr Kunde die Web-Version Ihrer Rechnung sehen wird.',
    ],

];
