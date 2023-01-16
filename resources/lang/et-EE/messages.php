<?php

return [

    'success' => [
        'added'             => ':type lisatud!',
        'updated'           => ':type uuendatud!',
        'deleted'           => ':type kustutatud!',
        'duplicated'        => ':type koopia loodud!',
        'imported'          => ':type imporditud!',
        'import_queued'     => ':type import on kavandatud! Kui see on lõppenud, saadame meiliteavituse.',
        'exported'          => ':type eksporditud!',
        'export_queued'     => ':type eksport on kavandatud! Kui see on allalaadimiseks valmis, saate meiliteavituse.',
        'enabled'           => ':type sisse lülitatud!',
        'disabled'          => ':type välja lülitatud!',
    ],

    'error' => [
        'over_payment'      => 'Viga: Makseviisi pole lisatud. Sisestatud summa ületab summa: :amount',
        'not_user_company'  => 'Viga: Teil pole lubatud seda ettevõtet haldama!',
        'customer'          => 'Viga: Kasutajat ei loodud! :name juba kasutab seda e-posti aadressi.',
        'no_file'           => 'Viga: Ühtegi faili pole valitud!',
        'last_category'     => 'Viga: Viimast :type kategooriat ei kustutatud!',
        'change_type'       => 'Viga: Tüüpi ei saa muuta kuna see on seotud :text!',
        'invalid_apikey'    => 'Viga: Sisestatud API võti on vigane!',
        'import_column'     => 'Viga: :message Tulba nimi: :column. Rea number: :line.',
        'import_sheet'      => 'Viga: Lehe nimi ei sobi. Palun kontrollige näidisfaili.',
    ],

    'warning' => [
        'deleted'           => 'Hoiatus: Teil pole lubatud kustutada <b>:name</b> kuna see on seotud :text.',
        'disabled'          => 'Hoiatus: Teil pole lubatud keelata  <b>:name</b> kuna see on seotud :text.',
        'reconciled_tran'   => 'Hoiatus: Teil pole lubatud tehingut muuta/kustutada, kuna see on vastavusse viidud!',
        'reconciled_doc'    => 'Hoiatus: :type pole lubatud muuta/kustutada, kuna see on seotud vastavusse viidud tehingutega!',
        'disable_code'      => 'Hoiatus: <b>:name</b>  valuutat pole lubatud muuta/kustutada, kuna see on seotud :text.',
        'payment_cancel'    => 'Hoiatus: Olete tühistanud oma hiljutise makseviisi :method!',
    ],

];
