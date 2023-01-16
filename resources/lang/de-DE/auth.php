<?php

return [

    'auth'                  => 'Authentifizierung',
    'profile'               => 'Profil',
    'logout'                => 'Abmelden',
    'login'                 => 'Anmelden',
    'forgot'                => 'Vergessen',
    'login_to'              => 'Anmelden, um Ihre Sitzung zu starten',
    'remember_me'           => 'Angemeldet bleiben',
    'forgot_password'       => 'Ich habe mein Passwort vergessen',
    'reset_password'        => 'Passwort zurücksetzen',
    'change_password'       => 'Passwort ändern',
    'enter_email'           => 'Geben Sie Ihre E-Mail-Adresse ein',
    'current_email'         => 'Aktuelle E-Mail',
    'reset'                 => 'Zurücksetzen',
    'never'                 => 'niemals',
    'landing_page'          => 'Startseite',
    'personal_information'  => 'Persönliche Informationen',
    'register_user'         => 'Benutzer anmelden',
    'register'              => 'Registrieren',

    'form_description' => [
        'personal'          => 'Der Einladungslink wird an den neuen Benutzer gesendet, also stellen Sie sicher, dass die E-Mail-Adresse korrekt ist. Sie können anschließend ihr Passwort festlegen.',
        'assign'            => 'Der Benutzer hat Zugriff auf die ausgewählten Unternehmen. Sie können die Berechtigungen auf der Seite <a href=":url" class="border-b border-black">Rollen</a> einschränken.',
        'preferences'       => 'Wählen Sie die Standardsprache des Benutzers. Sie können auch die Startseite nach dem Anmelden des Benutzers festlegen.',
    ],

    'password' => [
        'pass'              => 'Passwort',
        'pass_confirm'      => 'Passwortbestätigung',
        'current'           => 'Passwort',
        'current_confirm'   => 'Passwortbestätigung',
        'new'               => 'Neues Passwort',
        'new_confirm'       => 'Passwortbestätigung',
    ],

    'error' => [
        'self_delete'       => 'Fehler: Sie können sich nicht selbst löschen!',
        'self_disable'      => 'Fehler: Sie können Ihr Profil nicht selbst löschen!',
        'unassigned'        => 'Fehler: Die Firma :company muss mindestens einem Benutzer zugeordnet sein.',
        'no_company'        => 'Fehler: Ihrem Konto wurde kein Unternehmen zugewiesen. Bitte kontaktieren Sie den Systemadministrator.',
    ],

    'login_redirect'        => 'Verifizierung erledigt! Sie werden nun weitergeleitet...',
    'failed'                => 'Diese Anmeldeinformationen entsprechen nicht unseren Aufzeichnungen.',
    'throttle'              => 'Zu viele fehlgeschlagene Anmeldeversuche. Bitte versuchen Sie es erneut in :seconds Sekunden.',
    'disabled'              => 'Dieses Konto ist deaktiviert! Bitte kontaktieren Sie den Systemadministrator.',

    'notification' => [
        'message_1'         => 'Sie erhalten diese E-Mail, da Sie das Passwort für Ihr Konto zurücksetzen lassen wollen.',
        'message_2'         => 'Falls Sie keine Anfrage für das Zurücksetzen ihres Passwort gestellt haben, ist keine weitere Aktion erforderlich.',
        'button'            => 'Passwort zurücksetzen',
    ],

    'invitation' => [
        'message_1'         => 'Sie erhalten diese E-Mail, weil Sie zur zusammenarbeit bei Akaunting eingeladen wurden.',
        'message_2'         => 'Wenn Sie nicht beitreten möchten, ist keine weitere Aktion erforderlich.',
        'button'            => 'Legen Sie los',
    ],

    'information' => [
        'invoice'           => 'Rechnungen einfach erstellen',
        'reports'           => 'Detaillierte Berichte abrufen',
        'expense'           => 'Alle Ausgaben verfolgen',
        'customize'         => 'Passen Sie Ihr Akaunting an',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Admin',
            'description'   => 'Sie erhalten vollen Zugriff auf Akaunting, einschließlich Kunden, Rechnungen, Berichte, Einstellungen und Apps.',
        ],
        'manager' => [
            'name'          => 'Manager',
            'description'   => 'Sie erhalten vollen Zugriff auf Akaunting, können aber keine Benutzer und Apps verwalten.',
        ],
        'customer' => [
            'name'          => 'Kunde',
            'description'   => 'Sie können auf das Kundenportal zugreifen und ihre Rechnungen online über die Zahlungsmethoden, die Sie eingerichtet haben, bezahlen.',
        ],
        'accountant' => [
            'name'          => 'Buchhalter',
            'description'   => 'Sie können auf Rechnungen, Transaktionen, Berichte und Journaleinträge zugreifen.',
        ],
        'employee' => [
            'name'          => 'Mitarbeiter/in',
            'description'   => 'Sie können nur Ihre eigenen Ausgabenansprüche erstellen und Zeit für zugewiesene Projekte erfassen.',
        ],
    ],

];
