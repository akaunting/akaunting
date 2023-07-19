<?php

return [

    'success' => [
        'added'             => ':type pievients!',
        'updated'           => ':type atjaunināts!',
        'deleted'           => ':type dzēsts!',
        'duplicated'        => ':type kopēts!',
        'imported'          => ':type importēts!',
        'import_queued'     => ':type importēšana ir ieplānota! Jūs saņemsit e-pasta ziņojumu, kad tas būs pabeigts.',
        'exported'          => ':veids eksportēts!',
        'export_queued'     => ':type ir ieplānota pašreizējās lapas eksportēšana! Jūs saņemsit e-pasta ziņojumu, kad tas būs gatavs lejupielādei.',
        'enabled'           => ':type iespējots!',
        'disabled'          => ':type atspējots!',
        'connected'         => ':type savietots!',
        'invited'           => ':type ielūgts!',
        'ended'             => ':type pabeigts!',

        'clear_all'         => 'Lieliski! Jūs notīrījāt visus savus :veids.',
    ],

    'error' => [
        'over_payment'      => 'Kļūda: maksājums nav pievienots! Ievadītā summa veido šādu kopsummu: :summa',
        'not_user_company'  => 'Kļūda: Jums nav tiesības strādāt ar šo uzņēmumu!',
        'customer'          => 'Kļūda: Lietotājs nav izveidots! :name jau lieto šādu e-pasta adresi.',
        'no_file'           => 'Kļūda: Fails nav izvēlēts!',
        'last_category'     => 'Kļūda: Nevar izdzēst pēdējo :type kategoriju!',
        'transfer_category' => 'Kļūda: Nevar izdzēst pārsūtīšanas kategoriju <b>:type</b>!',
        'change_type'       => 'Kļūda: tipu nevar mainīt, jo tas ir saistīts ar tekstu!',
        'invalid_apikey'    => 'Kļūda: ievadītā API atslēga nav derīga!',
        'import_column'     => 'Kļūda: :ziņa Lapas nosaukums: :kolonna. Rindas numurs: :līnija',
        'import_sheet'      => 'Kļūda: Lapas nosaukums nav pareizs. Lūdzu pārbaudiet parauga failu.',
        'same_amount'       => 'Kļūda: kopējai sadalījuma summai ir jābūt tieši tādai pašai kā :transaction kopsumma: :amount',
        'over_match'        => 'Kļūda: :type nav savienots! Ievadītā summa nedrīkst pārsniegt maksājuma kopējo summu: :amount',
    ],

    'warning' => [
        'deleted'           => 'Brīdinājums: Jums nav tiesību dzēst <b>:name</b> jo tas ir saistīts ar :text.',
        'disabled'          => 'Brīdinājums: Jums nav tiesību atspējot <b>:name</b> jo tas ir saistīts ar :text.',
        'reconciled_tran'   => 'Brīdinājums: jums nav atļauts mainīt/dzēst transakciju, jo tā ir saskaņota!',
        'reconciled_doc'    => 'Brīdinājums. Jums nav atļauts mainīt/dzēst :type, jo tam ir saskaņotas transakcijas!',
        'disable_code'      => 'Brīdinājums. Jums nav atļauts atspējot vai mainīt šādu valūtu  <b>:nosaukums</b>, jo tam ir saistīts :teksts.',
        'payment_cancel'    => 'Brīdinājums. Jūs atcēlāt savu neseno :metode maksājumu!',
        'missing_transfer'  => 'Brīdinājums! Trūkst ar šo darījumu saistītā pārskaitījuma. Jums vajadzētu apsvērt šī darījuma dzēšanu.',
    ],

];
