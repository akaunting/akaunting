<?php

return [

    'auth'                  => 'Autenticazione',
    'profile'               => 'Profilo',
    'logout'                => 'Esci',
    'login'                 => 'Accedi',
    'forgot'                => 'Dimenticato',
    'login_to'              => 'Accedi per iniziare la sessione',
    'remember_me'           => 'Ricordami',
    'forgot_password'       => 'Password dimenticata',
    'reset_password'        => 'Reimposta la Password',
    'change_password'       => 'Cambia password',
    'enter_email'           => 'Inserisci il tuo indirizzo email',
    'current_email'         => 'Email attuale',
    'reset'                 => 'Reimposta',
    'never'                 => 'mai',
    'landing_page'          => 'Pagina di destinazione',
    'personal_information'  => 'Informazioni personali',
    'register_user'         => 'Registra utente',
    'register'              => 'Registrati',

    'form_description' => [
        'personal'          => 'Il collegamento di invito verrà inviato al nuovo utente, quindi assicurati che l\'indirizzo email sia corretto. Potranno inserire la propria password.',
        'assign'            => 'L\'utente avrà accesso alle aziende selezionate. È possibile limitare le autorizzazioni dalla pagina <a href=":url" class="border-b border-black">ruoli</a>.',
        'preferences'       => 'Selezionare la lingua predefinita dell\'utente. Puoi anche impostare la pagina di destinazione dopo che l\'utente ha effettuato l\'accesso.',
    ],

    'password' => [
        'pass'              => 'Password',
        'pass_confirm'      => 'Conferma password',
        'current'           => 'Password',
        'current_confirm'   => 'Conferma Password',
        'new'               => 'Nuova Password',
        'new_confirm'       => 'Conferma Nuova Password',
    ],

    'error' => [
        'self_delete'       => 'Errore: Non puoi eliminarlo!',
        'self_disable'      => 'Errore: Non puoi disabilitarlo!',
        'unassigned'        => ': impossibile annullare l\'assegnazione della società! Alla società :company deve essere assegnato almeno un utente.',
        'no_company'        => 'Errore: Nessuna società assegnata al tuo account. Per favore, contatta l\'amministratore di sistema.',
    ],

    'login_redirect'        => 'Verifica effettuata! Stai per essere reindirizzato...',
    'failed'                => 'Credenziali non corrispondenti ai dati registrati.',
    'throttle'              => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',
    'disabled'              => 'Questo account è disattivato. Si prega di contattare l\'amministratore di sistema.',

    'notification' => [
        'message_1'         => 'Hai ricevuto questa email perché abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account.',
        'message_2'         => 'Se non hai richiesto la reimpostazione della password, non sono necessarie ulteriori azioni.',
        'button'            => 'Resetta la Password',
    ],

    'invitation' => [
        'message_1'         => 'Hai ricevuto questa email perché sei stato invitato a partecipare ad Akaunting.',
        'message_2'         => 'Se non vuoi iscriverti, non sono necessarie ulteriori azioni.',
        'button'            => 'Inizia',
    ],

    'information' => [
        'invoice'           => 'Crea facilmente fatture',
        'reports'           => 'Ottieni report dettagliati',
        'expense'           => 'Tieni traccia di tutte le spese',
        'customize'         => 'Personalizza il tuo Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Ammin',
            'description'   => 'Ottengono pieno accesso al tuo Akaunting inclusi clienti, fatture, report, impostazioni e app.',
        ],
        'manager' => [
            'name'          => 'Gestore',
            'description'   => 'Hanno accesso completo al tuo Akaunting, ma non possono gestire utenti e app.',
        ],
        'customer' => [
            'name'          => 'Cliente',
            'description'   => 'Possono accedere al Portale Clienti e pagare le fatture online tramite i metodi di pagamento impostati.',
        ],
        'accountant' => [
            'name'          => 'Ragioniere',
            'description'   => 'Possono accedere a fatture, transazioni, report e creare voci di giornale.',
        ],
        'employee' => [
            'name'          => 'Dipendente',
            'description'   => 'Possono creare note spese e tenere traccia del tempo per i progetti assegnati, ma possono visualizzare solo le proprie informazioni.',
        ],
    ],

];
