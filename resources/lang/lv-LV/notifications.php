<?php

return [

    'whoops'              => 'Klau!',
    'hello'               => 'Sveicināts!',
    'salutation'          => 'Sveicināti,<br>:uzņēmuma nosaukums',
    'subcopy'             => 'Ja rodas problēmas, noklikšķinot uz ":text" pogu, nokopējiet šo URL un ielīmējiet to savā web pārlūkprogrammā: [:url](:url)',
    'mark_read'           => 'Atzīmēt kā izlasītu',
    'mark_read_all'       => 'Atzīmēt visu kā izlasītu',
    'empty'               => 'Oho, nav jaunu paziņojumu!',
    'new_apps'            => ':app ir pieejama. <a href=":url">Apskatiet to tūlīt</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Atjaunināšana neizdevās :domain',
            'description'   => 'Šāda atjaunināšana no :alias :current_version uz :new_version neizdevās <strong>:solis</strong> solis ar šādu ziņojumu: :error_message',

        ],

        'slack' => [

            'description'   => 'Atjaunināšana neizdevās :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importēšana pabeigta',
            'description'   => 'Importēšana ir pabeigta, un ieraksti ir pieejami panelī.',

        ],

        'failed' => [

            'title'         => 'Importēšana neizdevās',
            'description'   => 'Failu nevar importēt šādu problēmu dēļ:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Eksportēšana ir pabeigta',
            'description'   => 'Eksportēšanas fails ir gatavs lejupielādei no šīs saites:',

        ],

        'failed' => [

            'title'         => 'Eksportēšana neizdevās',
            'description'   => 'Nevar izveidot eksportēšanas failu šādas problēmas dēļ:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Eksportēšana ir pabeigta',
            'description'   => 'Jūsu <strong>:type</strong> eksporta fails ir gatavs <a href=":url" target="_blank"><strong>lejupielādei</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Eksportēšana neizdevās',
            'description'   => 'Nevar izveidot eksporta failu šādas problēmas dēļ: :issues',

        ],

        'import_completed' => [

            'title'         => 'Importēšana pabeigta',
            'description'   => 'Jūsu <strong>:type</strong> līnijas <strong>:count</strong> dati ir veiksmīgi importēti.',

        ],

        'import_failed' => [

            'subject'       => 'Importēšana neizdevās',
            'description'   => 'Nevar importēt failu vairāku problēmu dēļ. Sīkāku informāciju skatiet savā e-pastā.',

        ],

        'new_apps' => [

            'title'         => 'Jauna lietotne',
            'description'   => '<strong>:name</strong> lietotne ir beigusies. Jūs varat <a href=":url">nospiežot šeit</a> redzēt detaļas.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Jauns rēķins',
            'description'   => '<strong>:invoice_number</strong>rēķins ir izveidots. Jūs varat <a href=":invoice_portal_link">noklikšķiniet šeit</a> lai redzētu detalizētu informāciju un turpinātu maksājumu.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Nokavēts rēķins',
            'description'   => '<strong>:invoice_number</strong> rēķins bija jāsamaksā <strong>:invoice_due_date</strong>. Varat <a href=":invoice_portal_link">noklikšķināt šeit</a> lai skatītu detalizētu informāciju un turpinātu maksājumu.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Nokavēts rēķins',
            'description'   => '<strong>:invoice_number</strong> rēķins bija jāsamaksā<strong>:invoice_due_date</strong>. Varat <a href=":invoice_admin_link">noklikšķināt šeit</a> lai skatītu detalizētu informāciju.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Jauns periodisks rēķins',
            'description'   => 'Rēķins <strong>:invoice_number</strong> tiek izveidots, pamatojoties uz jūsu periodiskumu. Varat <a href=":invoice_portal_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju un turpinātu maksājumu.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Jauns periodisks rēķins',
            'description'   => '<strong>:invoice_number</strong> rēķins tiek izveidots, pamatojoties uz <strong>:customer_name</strong> periodiskumu. Varat <a href=":invoice_admin_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Rēķins skatīts',
            'description'   => '<strong>:customer_name</strong> ir skatījis <strong>:invoice_number</strong> rēķinu. Varat <a href=":invoice_admin_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Saņemts maksājums',
            'description'   => 'Paldies par apmaksu rēķinam <strong>:invoice_number</strong>. Varat <a href=":invoice_portal_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Saņemts maksājums',
            'description'   => 'Paldies par apmaksu rēķinam <strong>:invoice_number</strong>. Varat <a href=":invoice_portal_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Saņemts maksājums',
            'description'   => ':customer_name reģistrēts maksājums par rēķinu<strong>:invoice_number</strong>. Varat <a href=":invoice_admin_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Rēķins Nokavēts ',
            'description'   => '<strong>:bill_number</strong> rēķins bija jāsamaksā <strong>:bill_demis_date</strong>. Varat <a href=":bill_admin_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Jauns periodisks rēķins',
            'description'   => 'Rēķins <strong>:bill_number</strong> tiek izveidots, pamatojoties uz <strong>:vendor_name</strong> periodiskumu. Varat <a href=":bill_admin_link">noklikšķināt šeit</a>, lai skatītu detalizētu informāciju.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type ir izlasīts šis paziņojums!',
        'mark_read_all'         => ':type ir izlasīti visi paziņojumi!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Firefox Ikonas konfigurācija',
            'description'  => '<span class="font-medium">Ja jūsu ikonas neparādās, lūdzu;</span> <br /> <span class="font-medium">Lūdzu, ļaujiet lapām pašām izvēlēties fontus, nevis pēc jūsu atlases iepriekš< /span> <br /><br /> <span class="font-bold"> Iestatījumi (Preferences) > Fonti > Papildu </span>',

        ],

    ],

];
