<?php

return [

    'success' => [
        'added'             => ':type toegevoegd!',
        'updated'           => ':type bijgewerkt!',
        'deleted'           => ':type verwijderd!',
        'duplicated'        => ':type gedupliceerd!',
        'imported'          => ':type geïmporteerd!',
        'import_queued'     => ':type import is ingepland! U ontvangt een e-mail wanneer het klaar is.',
        'exported'          => ':type geëxporteerd!',
        'export_queued'     => ':type export van de huidige pagina is ingepland! U ontvangt een e-mail wanneer deze klaar is om te downloaden.',
        'enabled'           => ':type ingeschakeld!',
        'disabled'          => ':type uitgeschakeld!',
        'connected'         => ':type verbonden!',
        'invited'           => ':type uitgenodigd!',
        'ended'             => ':type beëindigd!',

        'clear_all'         => 'Geweldig! U heeft al uw :type verwijderd.',
    ],

    'error' => [
        'over_payment'      => 'Fout: Betaling niet toegevoegd! Het door u ingevoerde bedrag passeert het totaal: :amount',
        'not_user_company'  => 'Fout: U heeft niet de juiste bevoegdheid om dit bedrijf te beheren!',
        'customer'          => 'Fout: Gebruiker niet aangemaakt! :name heeft dit e-mailadres al in gebruik.',
        'no_file'           => 'Fout: geen bestand geselecteerd!',
        'last_category'     => 'Fout: Kan de laatste categorie niet verwijderen: :type',
        'transfer_category' => 'Fout: Kan de overdracht niet verwijderen <b>:type</b> categorie!',
        'change_type'       => 'Fout: Kan het type niet wijzigen omdat :text gerelateerd is!',
        'invalid_apikey'    => 'Fout: De ingevoerde API-sleutel is ongeldig!',
        'import_column'     => 'Fout: :message Blad naam: :sheet. Lijnnummer: :line.',
        'import_sheet'      => 'Fout: Bladnaam is niet geldig. Vergelijk het met het voorbeeldbestand.',
        'same_amount'       => 'Fout: Het totale bedrag van de splitsing moet precies hetzelfde zijn als het :transaction total: :bedrag',
        'over_match'        => 'Fout: :type niet verbonden! Het door u ingevoerde bedrag kan niet hoger zijn dan het betalingstotaal: :amount',
    ],

    'warning' => [
        'deleted'           => 'Waarschuwing: Het is voor u niet toegestaan om <b>:name</b> te verwijderen omdat het gerelateerd is aan :text.',
        'disabled'          => 'Waarschuwing: U mag <b>:name</b> niet uitschakelen omdat het gerelateerd is aan :text.',
        'reconciled_tran'   => 'Waarschuwing: U heeft geen toestemming om de transactie te wijzigen/verwijderen omdat deze is afgestemd!',
        'reconciled_doc'    => 'Waarschuwing: U heeft geen rechten om :type te wijzigen/wijzigen omdat deze transacties is afgestemd!',
        'disable_code'      => 'Waarschuwing: U mag <b>:name</b> niet uitschakelen omdat het gerelateerd is aan :text.',
        'payment_cancel'    => 'U heeft uw recente :method betaling geannuleerd!',
        'missing_transfer'  => 'Waarschuwing: De overdracht met betrekking tot deze transactie ontbreekt. Je kunt overwegen deze transactie te verwijderen.',
    ],

];
