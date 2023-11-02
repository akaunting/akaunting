<?php

return [

    'edit_columns'              => 'Úprava sloupců',
    'empty_items'               => 'Nepřidali jste žádné položky.',
    'grand_total'               => 'Celkem',
    'accept_payment_online'     => 'Přijímat online platby',
    'transaction'               => 'Platba :amount byla provedena pomocí :account.',
    'billing'                   => 'Fakturace',
    'advanced'                  => 'Rozšířené',

    'item_price_hidden'         => 'Tento sloupec je skryt na vašem :type.',

    'actions' => [
        'cancel'                => 'Zrušit',
    ],

    'invoice_detail' => [
        'marked'                => '<b>Vy</b> jste tuto fakturu označili jako',
        'services'              => 'Služby',
        'another_item'          => 'Další položka',
        'another_description'   => 'a další popis',
        'more_item'             => '+:count více položek',
    ],

    'statuses' => [
        'draft'                 => 'Koncept',
        'sent'                  => 'Odeslané',
        'expired'               => 'Vypršela platnost',
        'viewed'                => 'Zobrazeno',
        'approved'              => 'Schváleno',
        'received'              => 'Obdrženo',
        'refused'               => 'Odmítnuto',
        'restored'              => 'Obnoveno',
        'reversed'              => 'Obrácené',
        'partial'               => 'Částečné',
        'paid'                  => 'Zaplaceno',
        'pending'               => 'Nevyřízeno',
        'invoiced'              => 'Vyfakturované',
        'overdue'               => 'Po termínu',
        'unpaid'                => 'Nezaplaceno',
        'cancelled'             => 'Zrušeno',
        'voided'                => 'Neplatný',
        'completed'             => 'Dokončeno',
        'shipped'               => 'Odesláno',
        'refunded'              => 'Refundace',
        'failed'                => 'Neúspěšný',
        'denied'                => 'Zamítnuto',
        'processed'             => 'Zpracováno',
        'open'                  => 'Otevřeno',
        'closed'                => 'Uzavřeno',
        'billed'                => 'Vyúčtováno',
        'delivered'             => 'Doručeno',
        'returned'              => 'Vrácené',
        'drawn'                 => 'Nerozhodný',
        'not_billed'            => 'Neúčtováno',
        'issued'                => 'Vystavené',
        'not_invoiced'          => 'Nefakturováno',
        'confirmed'             => 'Potvrzeno',
        'not_confirmed'         => 'Nepotvrzeno',
        'active'                => 'Aktivní',
        'ended'                 => 'Ukončeno',
    ],

    'form_description' => [
        'companies'             => 'Změňte adresu, logo a další informace pro vaši společnost.',
        'billing'               => 'Fakturační údaje jsou uvedeny ve vašem dokladu.',
        'advanced'              => 'Vyberte kategorii, přidejte nebo upravte zápatí a přidejte přílohy k vašemu :type.',
        'attachment'            => 'Stáhnout soubory připojené k tomuto :type',
    ],

    'slider' => [
        'create'            => ':user vytvořil tento :type dne :date',
        'create_recurring'  => ':user vytvořil tuto opakovanou šablonu dne :date',
        'send'              => ':user odeslal tento :type dne :date',
        'schedule'          => 'Opakovat každý :interval :frequency od :date',
        'children'          => ':count :type byl vytvořen automaticky',
        'cancel'            => ':user zrušil tento :type dne :date',
    ],

    'messages' => [
        'email_sent'            => ':type e-mail byl odeslán!',
        'restored'              => ':type byl obnoven!',
        'marked_as'             => ':type byl označen jako :status!',
        'marked_sent'           => ':type byl označen jako odeslán!',
        'marked_paid'           => ':type byl označen jako zaplacený!',
        'marked_viewed'         => ':type byl označen jako zobrazený!',
        'marked_cancelled'      => ':type označeno jako zrušený!',
        'marked_received'       => ':type byl označen jako přijatý!',
    ],

    'recurring' => [
        'auto_generated'        => 'Automaticky vygenerováno',

        'tooltip' => [
            'document_date'     => ':type datum bude automaticky přiřazeno na základě :type plánu a frekvence.',
            'document_number'   => ':type číslo bude automaticky přiřazeno při každém opakování :type',
        ],
    ],

    'empty_attachments'         => 'Není zde žádná poznámka připojená k tomuto pádu.',
];
