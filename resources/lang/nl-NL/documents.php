<?php

return [

    'edit_columns'              => 'Kolommen Bewerken',
    'empty_items'               => 'U heeft nog geen artikelen toegevoegd.',
    'grand_total'               => 'Eindtotaal',
    'accept_payment_online'     => 'Online Betalingen Accepteren',
    'transaction'               => 'Een betaling voor :amount werd gedaan met :account.',
    'billing'                   => 'Facturatie',
    'advanced'                  => 'Geavanceerd',

    'item_price_hidden'         => 'Deze kolom is verborgen in uw :type.',

    'actions' => [
        'cancel'                => 'Annuleren',
    ],

    'invoice_detail' => [
        'marked'                => '<b>U</b> heeft deze factuur gemarkeerd als',
        'services'              => 'Diensten',
        'another_item'          => 'Een ander item',
        'another_description'   => 'een andere beschrijving',
        'more_item'             => '+:count meer items',
    ],

    'statuses' => [
        'draft'                 => 'Concept',
        'sent'                  => 'Verzonden',
        'expired'               => 'Verlopen',
        'viewed'                => 'Bekeken',
        'approved'              => 'Goedgekeurd',
        'received'              => 'Ontvangen',
        'refused'               => 'Afgewezen',
        'restored'              => 'Herstelt',
        'reversed'              => 'Teruggedraaid',
        'partial'               => 'Gedeeltelijk',
        'paid'                  => 'Betaald',
        'pending'               => 'In afwachting',
        'invoiced'              => 'Gefactureerd',
        'overdue'               => 'Achterstallig',
        'unpaid'                => 'Onbetaald',
        'cancelled'             => 'Geannuleerd',
        'voided'                => 'Nietig verklaard',
        'completed'             => 'Voltooid',
        'shipped'               => 'Verzonden',
        'refunded'              => 'Teruggestort',
        'failed'                => 'Mislukt',
        'denied'                => 'Geweigerd',
        'processed'             => 'Verwerkt',
        'open'                  => 'Open',
        'closed'                => 'Gesloten',
        'billed'                => 'Gefactureerd',
        'delivered'             => 'Afgeleverd',
        'returned'              => 'Geretourneerd',
        'drawn'                 => 'Ingetrokken',
        'not_billed'            => 'Niet gefactureerd',
        'issued'                => 'Verwerkt',
        'not_invoiced'          => 'Niet Gefactureerd',
        'confirmed'             => 'Bevestigd',
        'not_confirmed'         => 'Niet bevestigd',
        'active'                => 'Actief',
        'ended'                 => 'Voltooid',
    ],

    'form_description' => [
        'companies'             => 'Wijzig het adres, het logo en andere informatie voor uw bedrijf.',
        'billing'               => 'De factuurgegevens worden in uw document weergegeven.',
        'advanced'              => 'Selecteer de categorie, voeg de voettekst toe of bewerk deze, en voeg bijlagen toe aan uw :type.',
        'attachment'            => 'Download de bijgevoegde bestanden :type',
    ],

    'slider' => [
        'create'            => ':user heeft dit :type aangemaakt op :date',
        'create_recurring'  => ':user maakte deze terugkerende sjabloon aan op :date',
        'send'              => ':user verstuurde dit :type op :date',
        'schedule'          => 'Herhaal elke :interval :frequentie sinds :date',
        'children'          => ':count :type werden automatisch aangemaakt',
        'cancel'            => ':user annuleerde dit :type op :date',
    ],

    'messages' => [
        'email_sent'            => ':type e-mail verzonden!',
        'restored'              => ':type is hersteld!',
        'marked_as'             => ':type gemarkeerd als :status!',
        'marked_sent'           => ':type gemarkeerd als verzonden!',
        'marked_paid'           => ':type gemarkeerd als betaald!',
        'marked_viewed'         => ':type gemarkeerd als gelezen!',
        'marked_cancelled'      => ':type gemarkeerd als geannuleerd!',
        'marked_received'       => ':type gemarkeerd als ontvangen!',
    ],

    'recurring' => [
        'auto_generated'        => 'Automatisch gegenereerde',

        'tooltip' => [
            'document_date'     => 'De :type datum wordt automatisch toegewezen op basis van het :type schema en de frequentie.',
            'document_number'   => 'Het :type nummer wordt automatisch toegekend wanneer elk terugkerend :type wordt gegenereerd.',
        ],
    ],

    'empty_attachments'         => 'Aan dit :type zijn geen bestanden gekoppeld.',
];
