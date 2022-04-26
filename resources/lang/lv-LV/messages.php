<?php

return [

    'success' => [
        'added'             => ':veids pievienots!',
        'updated'           => ':veids atjaunināts!',
        'deleted'           => ':veids dzēsts!',
        'duplicated'        => ':veids kopēts!',
        'imported'          => ':veids importēts!',
        'import_queued'     => ':veids importēšana ir ieplānota! Jūs saņemsit e-pasta ziņojumu, kad tas būs pabeigts.',
        'exported'          => ':veids eksportēts!',
        'export_queued'     => ':veids ir ieplānota pašreizējās lapas eksportēšana! Jūs saņemsit e-pasta ziņojumu, kad tas būs gatavs lejupielādei.',
        'enabled'           => ':veids iespējots!',
        'disabled'          => ':veids atspējots!',

        'clear_all'         => 'Lieliski! Jūs notīrījāt visus savus :veids.',
    ],

    'error' => [
        'over_payment'      => 'Kļūda: maksājums nav pievienots! Ievadītā summa veido šādu kopsummu: :summa',
        'not_user_company'  => 'Kļūda: Jums nav tiesības strādāt ar šo uzņēmumu!',
        'customer'          => 'Kļūda: Lietotājs nav izveidots! :vārds jau lieto šādu e-pasta adresi.',
        'no_file'           => 'Kļūda: Fails nav izvēlēts!',
        'last_category'     => 'Kļūda: Nevar izdzēst pēdējo :veids kategoriju!',
        'change_type'       => 'Kļūda: tipu nevar mainīt, jo tas ir saistīts ar tekstu!',
        'invalid_apikey'    => 'Kļūda: ievadītā API atslēga nav derīga!',
        'import_column'     => 'Kļūda: :ziņa Lapas nosaukums: :kolonna. Rindas numurs: :līnija',
        'import_sheet'      => 'Kļūda: Lapas nosaukums nav pareizs. Lūdzu pārbaudiet parauga failu.',
    ],

    'warning' => [
        'deleted'           => 'Brīdinājums: Jums nav tiesību dzēst <b>:vārds</b> jo tas ir saistīts ar :teksts.',
        'disabled'          => 'Brīdinājums: Jums nav tiesību atspējot <b>:vārds</b> jo tas ir saistīts ar :teksts.',
        'reconciled_tran'   => 'Brīdinājums: jums nav atļauts mainīt/dzēst transakciju, jo tā ir saskaņota!',
        'reconciled_doc'    => 'Brīdinājums. Jums nav atļauts mainīt/dzēst :tips, jo tam ir saskaņotas transakcijas!',
        'disable_code'      => 'Brīdinājums. Jums nav atļauts atspējot vai mainīt šādu valūtu  <b>:nosaukums</b>, jo tam ir saistīts :teksts.',
        'payment_cancel'    => 'Brīdinājums. Jūs atcēlāt savu neseno :metode maksājumu!',
    ],

];
