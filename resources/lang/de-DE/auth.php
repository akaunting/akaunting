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
    'never'                 => 'Niemals',
    'landing_page'          => 'Startseite',
    'personal_information'  => 'Persönliche Informationen',
    'register_user'         => 'Benutzer registrieren',
    'register'              => 'Registrieren',

    'form_description' => [
        'personal'          => 'Der Einladungslink wird an den neuen Benutzer gesendet, stellen Sie also sicher, dass die E-Mail-Adresse korrekt ist. Der Benutzer kann anschließend sein Passwort festlegen.',
        'assign'            => 'Der Benutzer hat Zugriff auf die ausgewählten Unternehmen. Sie können die Berechtigungen auf der Seite <a href=":url" class="border-b border-black">Rollen</a> einschränken.',
        'preferences'       => 'Wählen Sie die Standardsprache des Benutzers. Sie können auch die Startseite festlegen, die nach der Anmeldung des Benutzers angezeigt wird.',
    ],

    'password' => [
        'pass'              => 'Passwort',
        'pass_confirm'      => 'Passwortbestätigung',
        'current'           => 'Aktuelles Passwort',
        'current_confirm'   => 'Aktuelle Passwortbestätigung',
        'new'               => 'Neues Passwort',
        'new_confirm'       => 'Neue Passwortbestätigung',
    ],

    'error' => [
        'self_delete'       => 'Fehler: Sie können sich nicht selbst löschen!',
        'self_disable'     => 'Fehler: Sie können sich nicht selbst deaktivieren!',
        'unassigned'        => 'Fehler: Das Unternehmen kann nicht abgemeldet werden! Dem Unternehmen :company muss mindestens ein Benutzer zugeordnet sein.',
        'no_company'        => 'Fehler: Ihrem Konto wurde kein Unternehmen zugewiesen. Bitte kontaktieren Sie den Systemadministrator.',
    ],

    'login_redirect'        => 'Verifizierung erledigt! Sie werden nun weitergeleitet...',
    'failed'                => 'Diese Anmeldeinformationen entsprechen nicht unseren Aufzeichnungen.',
    'throttle'              => 'Zu viele fehlgeschlagene Anmeldeversuche. Bitte versuchen Sie es in :seconds Sekunden erneut.',
    'disabled'              => 'Dieses Konto ist deaktiviert! Bitte kontaktieren Sie den Systemadministrator.',

    'notification' => [
        'message_1'         => 'Sie erhalten diese E-Mail, weil eine Anfrage zum Zurücksetzen des Passworts für Ihr Konto gestellt wurde.',
        'message_2'         => 'Falls Sie keine Anfrage zum Zurücksetzen Ihres Passworts gestellt haben, ist keine weitere Aktion erforderlich.',
        'button'            => 'Passwort zurücksetzen',
    ],

    'invitation' => [
        'message_1'         => 'Sie erhalten diese E-Mail, weil Sie zur Mitarbeit bei Akaunting eingeladen wurden.',
        'message_2'         => 'Wenn Sie nicht beitreten möchten, ist keine weitere Aktion erforderlich.',
        'button'            => 'Loslegen',
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
            'description'   => 'Er erhält vollen Zugriff auf Akaunting, einschließlich Kunden, Rechnungen, Berichte, Einstellungen und Apps.',
        ],
        'manager' => [
            'name'          => 'Manager',
            'description'   => 'Er erhält vollen Zugriff auf Akaunting, kann aber keine Benutzer und Apps verwalten.',
        ],
        'customer' => [
            'name'          => 'Kunde',
            'description'   => 'Er kann auf das Kundenportal zugreifen und seine Rechnungen online über die von Ihnen eingerichteten Zahlungsmethoden bezahlen.',
        ],
        'accountant' => [
            'name'          => 'Buchhalter',
            'description'   => 'Er kann auf Rechnungen, Transaktionen, Berichte und Journaleinträge zugreifen.',
        ],
        'employee' => [
            'name'          => 'Mitarbeiter',
            'description'   => 'Er kann Ausgabenansprüche erstellen und Zeit für zugewiesene Projekte erfassen, sieht aber nur seine eigenen Informationen.',
        ],
    ],

];
