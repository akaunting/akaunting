<?php

return [

    'invoice_number'        => 'Sąskaitos faktūros numeris',
    'invoice_date'          => 'Sąskaitos-faktūros data',
    'invoice_amount'        => 'Sąskaitos faktūros suma',
    'total_price'           => 'Bendra kaina',
    'due_date'              => 'Terminas',
    'order_number'          => 'Užsakymo numeris',
    'bill_to'               => 'Pirkėjas',
    'cancel_date'           => 'Atšaukimo data',

    'quantity'              => 'Kiekis',
    'price'                 => 'Kaina',
    'sub_total'             => 'Tarpinė suma',
    'discount'              => 'Nuolaida',
    'item_discount'         => 'Nuolaida',
    'tax_total'             => 'Mokesčių suma',
    'total'                 => 'Iš viso',

    'item_name'             => 'Prekė/paslauga|Prekės/paslaugos',
    'recurring_invoices'    => 'Pasikartojanti sąskaita faktūra|Pakartotinės sąskaitos faktūros',

    'show_discount'         => ':discount% nuolaida',
    'add_discount'          => 'Pridėti nuolaidą',
    'discount_desc'         => 'tarpinė suma',

    'payment_due'           => 'Mokėjimo terminas',
    'paid'                  => 'Apmokėta',
    'histories'             => 'Istorijos',
    'payments'              => 'Mokėjimai',
    'add_payment'           => 'Pridėti mokėjimą',
    'mark_paid'             => 'Pažymėti kaip apmokėtą',
    'mark_sent'             => 'Pažymėti kaip išsiųstą',
    'mark_viewed'           => 'Pažymėti kaip peržiūrėtą',
    'mark_cancelled'        => 'Pažymėti kaip atšauktą',
    'download_pdf'          => 'Parsisiųsti PDF',
    'send_mail'             => 'Siųsti laišką',
    'all_invoices'          => 'Prisijunkite norėdami peržiūrėti visas sąskaitas faktūras',
    'create_invoice'        => 'Sukurti sąskaitą-faktūrą',
    'send_invoice'          => 'Siųsti sąskaitą-faktūrą',
    'get_paid'              => 'Gauti apmokėjimą',
    'accept_payments'       => 'Priimti atsiskaitymus internetu',
    'payment_received'      => 'Mokėjimas gautas',

    'form_description' => [
        'billing'           => 'Sąskaitos faktūros duomenys pateikiami sąskaitoje faktūroje. Sąskaitos faktūros data naudojama prietaisų skydelyje ir ataskaitose. Pasirinkite datą, kada tikitės gauti apmokėjimą, kaip Mokėjimo termino datą.',
    ],

    'messages' => [
        'email_required'    => 'Klientas neturi el. pašto!',
        'draft'             => 'Tai yra <b>JUODRAŠTINĖ</b> sąskaita ir ji bus įtraukta į grafikus po to kai bus išsiųsta.',

        'status' => [
            'created'       => 'Sukurta :date',
            'viewed'        => 'Peržiūrėta',
            'send' => [
                'draft'     => 'Neišsiųsta',
                'sent'      => 'Išsiųsta :date',
            ],
            'paid' => [
                'await'     => 'Laukiama apmokėjimo',
            ],
        ],
    ],

    'slider' => [
        'create'            => ':user sukūrė šią sąskaitą faktūrą :date',
        'create_recurring'  => ':user sukūrė šį pasikartojantį šabloną :data :date',
        'schedule'          => 'Kartoti kas :interval :frequency nuo :date',
        'children'          => ':count sąskaitos faktūros buvo sukurtos automatiškai',
    ],

    'share' => [
        'show_link'         => 'Klientas gali peržiūrėti sąskaitą faktūrą naudodamasis šia nuoroda.',
        'copy_link'         => 'Nukopijuokite nuorodą ir pasidalykite ja su klientu.',
        'success_message'   => 'Bendrinimo nuoroda nukopijuota į iškarpinę!',
    ],

    'sticky' => [
        'description'       => 'Peržiūrite, kaip klientas matys internetinę sąskaitos faktūros versiją.',
    ],

];
