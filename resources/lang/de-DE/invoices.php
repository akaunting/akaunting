<?php

return [

    'invoice_number'        => 'Rechnungsnummer',
    'invoice_date'          => 'Rechnungsdatum',
    'total_price'           => 'Gesamtpreis',
    'due_date'              => 'Fälligkeitsdatum',
    'order_number'          => 'Bestellnummer',
    'bill_to'               => 'Rechnung an',

    'quantity'              => 'Menge',
    'price'                 => 'Preis',
    'sub_total'             => 'Zwischensumme',
    'discount'              => 'Rabatt',
    'tax_total'             => 'Steuern Gesamt',
    'total'                 => 'Gesamt',

    'item_name'             => 'Artikelname|Artikelnamen',

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
    'download_pdf'          => 'PDF herunterladen',
    'send_mail'             => 'E-Mail senden',
    'all_invoices'          => 'Melden Sie sich an, um alle Rechnungen anzuzeigen',
    'create_invoice'        => 'Rechnung erstellen',
    'send_invoice'          => 'Rechnung senden',
    'get_paid'              => 'Zahlung erhalten',
    'accept_payments'       => 'Onlinezahlungen akzeptieren',

    'statuses' => [
        'draft'             => 'Entwurf',
        'sent'              => 'Versandt',
        'viewed'            => 'Gelesen',
        'approved'          => 'Freigegeben',
        'partial'           => 'Teilweise',
        'paid'              => 'Bezahlt',
        'overdue'           => 'Überfällig',
        'unpaid'            => 'Unbezahlt',
    ],

    'messages' => [
        'email_sent'        => 'Rechnung wurde per E-Mail versendet!',
        'marked_sent'       => 'Rechnung als gesendet markiert!',
        'marked_paid'       => 'Rechnung als bezahlt markiert!',
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

];
