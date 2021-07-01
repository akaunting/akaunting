<?php

return [

    'invoice_number'        => 'Fakturanummer',
    'invoice_date'          => 'Fakturadatum',
    'invoice_amount'        => 'Faktura belopp',
    'total_price'           => 'Summa pris',
    'due_date'              => 'Förfallodatum',
    'order_number'          => 'Ordernummer',
    'bill_to'               => 'Fakturera till',

    'quantity'              => 'Antal',
    'price'                 => 'Pris',
    'sub_total'             => 'Delsumma',
    'discount'              => 'Rabatt',
    'item_discount'         => 'Radrabatt',
    'tax_total'             => 'Summa Skatt',
    'total'                 => 'Totalt',

    'item_name'             => 'Artikelnamn | Artikelnamn',

    'show_discount'         => ':discount % Rabatt',
    'add_discount'          => 'Lägg till rabatt',
    'discount_desc'         => 'av delsumman',

    'payment_due'           => 'Betalning',
    'paid'                  => 'Betald',
    'histories'             => 'Historia',
    'payments'              => 'Betalningar',
    'add_payment'           => 'Lägg till betalning',
    'mark_paid'             => 'Markera som betald',
    'mark_sent'             => 'Markera som skickad',
    'mark_viewed'           => 'Markera som visad',
    'mark_cancelled'        => 'Markera som avbruten',
    'download_pdf'          => 'Ladda ner PDF',
    'send_mail'             => 'Skicka E-post',
    'all_invoices'          => 'Logga in för att visa alla fakturor',
    'create_invoice'        => 'Skapa faktura',
    'send_invoice'          => 'Skicka faktura',
    'get_paid'              => 'Få betalt',
    'accept_payments'       => 'Acceptera onlinebetalningar',

    'messages' => [
        'email_required'    => 'Ingen e-postadress för den här kunden!',
        'draft'             => 'Detta är en <b>utkast</b> faktura och kommer att speglas till diagramet efter det skickas.',

        'status' => [
            'created'       => 'Skapad den :date',
            'viewed'        => 'Visad',
            'send' => [
                'draft'     => 'Inte skickat',
                'sent'      => 'Skickat den :date',
            ],
            'paid' => [
                'await'     => 'Väntar på betalning',
            ],
        ],
    ],

];
