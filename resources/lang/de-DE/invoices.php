<?php

return [

    'invoice_number'        => 'Rechnungsnummer',
    'invoice_date'          => 'Rechnungsdatum',
    'invoice_amount'        => 'Rechnungsbetrag',
    'total_price'           => 'Gesamtpreis',
    'due_date'              => 'Fälligkeitsdatum',
    'order_number'          => 'Bestellnummer',
    'bill_to'               => 'Rechnung an',
    'cancel_date'           => 'Stornodatum',

    'quantity'              => 'Menge',
    'price'                 => 'Preis',
    'sub_total'             => 'Zwischensumme',
    'discount'              => 'Rabatt',
    'item_discount'         => 'Positionsrabatt',
    'tax_total'             => 'Steuern gesamt',
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
    'download_pdf'          => 'Als PDF herunterladen',
    'send_mail'             => 'E-Mail senden',
    'all_invoices'          => 'Melden Sie sich an, um alle Rechnungen anzuzeigen',
    'create_invoice'        => 'Rechnung erstellen',
    'send_invoice'          => 'Rechnung senden',
    'get_paid'              => 'Zahlung erhalten',
    'accept_payments'       => 'Onlinezahlungen akzeptieren',
    'payments_received'     => 'Erhaltene Zahlungen',
    'over_payment'          => 'Der eingegebene Betrag überschreitet den Gesamtbetrag: :amount',

    'form_description' => [
        'billing'           => 'Rechnungsdetails erscheinen in Ihrer Rechnung. Das Rechnungsdatum wird im Dashboard und in Berichten verwendet. Wählen Sie das voraussichtliche Zahlungsdatum als Fälligkeitsdatum aus.',
    ],

    'messages' => [
        'email_required'    => 'Es existiert keine E-Mail-Adresse zu diesem Kunden!',
        'totals_required'   => 'Rechnungssummen sind erforderlich. Bitte bearbeiten Sie :type und speichern Sie erneut.',

        'draft'             => 'Dies ist eine <b>ENTWURFS</b>-Rechnung und wird nach dem Versand in den Diagrammen sichtbar.',

        'status' => [
            'created'       => 'Erstellt am :date',
            'viewed'        => 'Gelesen',
            'send' => [
                'draft'     => 'Noch nicht versandt',
                'sent'      => 'Gesendet am :date',
            ],
            'paid' => [
                'await'     => 'Zahlung ausstehend',
            ],
        ],

        'name_or_description_required' => 'Ihre Rechnung muss mindestens einen der Werte <b>:name</b> oder <b>:description</b> enthalten.',
    ],

    'share' => [
        'show_link'         => 'Ihr Kunde kann die Rechnung unter diesem Link ansehen',
        'copy_link'         => 'Kopieren Sie den Link und teilen Sie ihn mit Ihrem Kunden.',
        'success_message'   => 'Link in die Zwischenablage kopiert!',
    ],

    'sticky' => [
        'description'       => 'Sie sehen eine Vorschau, wie Ihr Kunde die Web-Version Ihrer Rechnung sehen wird.',
    ],

];
