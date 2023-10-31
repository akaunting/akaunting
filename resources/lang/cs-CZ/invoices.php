<?php

return [

    'invoice_number'        => 'Číslo faktury',
    'invoice_date'          => 'Datum faktury',
    'invoice_amount'        => 'Částka faktury',
    'total_price'           => 'Celková cena',
    'due_date'              => 'Datum splatnosti',
    'order_number'          => 'Číslo objednávky',
    'bill_to'               => 'Faktura pro',
    'cancel_date'           => 'Zrušit datum',

    'quantity'              => 'Množství',
    'price'                 => 'Cena',
    'sub_total'             => 'Mezisoučet',
    'discount'              => 'Sleva',
    'item_discount'         => 'Řádková sleva',
    'tax_total'             => 'Daň celkem',
    'total'                 => 'Celkem',

    'item_name'             => 'Název položky|Název položek',
    'recurring_invoices'    => 'Opakující se faktura|Opakující se faktury',

    'show_discount'         => 'Sleva :discount%',
    'add_discount'          => 'Přidat slevu',
    'discount_desc'         => 'z mezisoučtu',

    'payment_due'           => 'Splatnost platby',
    'paid'                  => 'Zaplaceno',
    'histories'             => 'Historie',
    'payments'              => 'Platby',
    'add_payment'           => 'Přidat platbu',
    'mark_paid'             => 'Označit jako zaplaceno',
    'mark_sent'             => 'Označit jako odesláno',
    'mark_viewed'           => 'Označit jako zobrazené',
    'mark_cancelled'        => 'Označit jako zrušené',
    'download_pdf'          => 'Stáhnout PDF',
    'send_mail'             => 'Odeslat e-mail',
    'all_invoices'          => 'Přihlašte se pro zobrazení všech faktur',
    'create_invoice'        => 'Vytvoření faktury',
    'send_invoice'          => 'Odeslání faktury',
    'get_paid'              => 'Uhrazení faktury',
    'accept_payments'       => 'Přijímat online platby',
    'payments_received'     => 'Obdržené platby',

    'form_description' => [
        'billing'           => 'Fakturační údaje jsou uvedeny ve vaší faktuře. Datum faktury se používá na nástěnce a reportech. Vyberte datum, které chcete zaplatit jako termín splatnosti.',
    ],

    'messages' => [
        'email_required'    => 'Zákazník nemá uvedenou e-mailovou adresu!',
        'totals_required'   => 'Součet faktury je vyžadován. Prosím upravte :type a znovu ji uložte.',

        'draft'             => 'Toto je <b>KONCEPT</b> faktury. Faktura bude promítnuta do grafů, jakmile bude odeslána.',

        'status' => [
            'created'       => 'Vytvořeno :date',
            'viewed'        => 'Zobrazeno',
            'send' => [
                'draft'     => 'Neodesláno',
                'sent'      => 'Odesláno dne :date',
            ],
            'paid' => [
                'await'     => 'Čeká na platbu',
            ],
        ],

        'name_or_description_required' => 'Vaše faktura musí obsahovat alespoň jeden z <b>:name</b> nebo <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Váš zákazník může zobrazit fakturu na tomto odkazu',
        'copy_link'         => 'Zkopírujte odkaz a sdílejte jej s Vaším zákazníkem.',
        'success_message'   => 'Odkaz pro sdílení byl zkopírován do schránky!',
    ],

    'sticky' => [
        'description'       => 'Prohlížíte, jak Váš zákazník uvidí webovou verzi Vaší faktury.',
    ],

];
