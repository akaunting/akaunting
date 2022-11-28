<?php

return [

    'edit_columns'              => 'Redigera kolumner',
    'empty_items'               => 'Du har inte lagt till några objekt.',
    'grand_total'               => 'Totalsumma',
    'accept_payment_online'     => 'Acceptera betalningar online',
    'transaction'               => 'En betalning på :amount gjordes med :account.',
    'billing'                   => 'Fakturering',
    'advanced'                  => 'Avancerade inställningar',

    'invoice_detail' => [
        'marked'                => '<b>Du</b> markerade denna faktura som',
        'services'              => 'Tjänster',
        'another_item'          => 'En annan artikel',
        'another_description'   => 'och en annan beskrivning',
        'more_item'             => '+:count fler element',
    ],

    'statuses' => [
        'draft'                 => 'Utkast',
        'sent'                  => 'Skickat',
        'expired'               => 'Förfallen',
        'viewed'                => 'Visad',
        'approved'              => 'Godkänd',
        'received'              => 'Mottagen',
        'refused'               => 'Vägrades',
        'restored'              => 'Återställd',
        'reversed'              => 'Omvänd',
        'partial'               => 'Delvis',
        'paid'                  => 'Betald',
        'pending'               => 'Väntande',
        'invoiced'              => 'Fakturerad',
        'overdue'               => 'Förfallen',
        'unpaid'                => 'Obetald',
        'cancelled'             => 'Avbruten',
        'voided'                => 'Ogiltig',
        'completed'             => 'Slutförd',
        'shipped'               => 'Skickad',
        'refunded'              => 'Återbetald',
        'failed'                => 'Misslyckades',
        'denied'                => 'Nekad',
        'processed'             => 'Bearbetad',
        'open'                  => 'Öppna',
        'closed'                => 'Stängd',
        'billed'                => 'Fakturerad',
        'delivered'             => 'Levererad',
        'returned'              => 'Returnerad',
        'drawn'                 => 'Väntande',
        'not_billed'            => 'Ej fakturerad',
        'issued'                => 'Utfärdad',
        'not_invoiced'          => 'Ej fakturerad',
        'confirmed'             => 'Bekräftad',
        'not_confirmed'         => 'Ej bekräftad',
        'active'                => 'Aktiv',
        'ended'                 => 'Avslutad',
    ],

    'form_description' => [
        'companies'             => 'Ändra adress, logotyp och annan information för ditt företag.',
        'billing'               => 'Faktureringsuppgifter visas i ditt dokument.',
        'advanced'              => 'Välj kategori, lägg till eller redigera sidfoten och lägg till bilagor till din :type.',
        'attachment'            => 'Ladda ner filerna bifogade till denna :type',
    ],

    'messages' => [
        'email_sent'            => ':type e-post har skickats!',
        'marked_as'             => ':type markerad som :status!',
        'marked_sent'           => ':type markerad som skickad!',
        'marked_paid'           => ':type markerad som betalad!',
        'marked_viewed'         => ':type markerad som visad!',
        'marked_cancelled'      => ':type markerad som avbruten!',
        'marked_received'       => ':type markerad som mottagen!',
    ],

    'recurring' => [
        'auto_generated'        => 'Auto-genererad',

        'tooltip' => [
            'document_date'     => ':type datum tilldelas automatiskt baserat på :type schema och frekvens.',
            'document_number'   => ':type numret kommer automatiskt att tilldelas när varje återkommande :type genereras.',
        ],
    ],

];
