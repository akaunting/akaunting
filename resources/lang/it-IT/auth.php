<?php

return [

    'auth'                  => 'Autenticazione',
    'profile'               => 'Profilo',
    'logout'                => 'Esci',
    'login'                 => 'Accedi',
    'forgot'                => 'Dimenticata',
    'login_to'              => 'Accedi per iniziare la sessione',
    'remember_me'           => 'Ricordami',
    'forgot_password'       => 'Password dimenticata',
    'reset_password'        => 'Reimposta password',
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
        'assign'            => 'L\'utente avrà accesso alle aziende selezionate. Puoi limitare le autorizzazioni dalla pagina <a href=":url" class="border-b border-black">ruoli</a>.',
        'preferences'       => 'Seleziona la lingua predefinita dell\'utente. Puoi anche impostare la pagina di destinazione dopo che l\'utente ha effettuato l\'accesso.',
    ],

    'password' => [
        'pass'              => 'Password',
        'pass_confirm'      => 'Conferma password',
        'current'           => 'Password attuale',
        'current_confirm'   => 'Conferma password attuale',
        'new'               => 'Nuova password',
        'new_confirm'       => 'Conferma nuova password',
    ],

    'error' => [
        'self_delete'       => 'Errore: Non puoi eliminare te stesso!',
        'self_disable'      => 'Errore: Non puoi disabilitare te stesso!',
        'unassigned'        => 'Errore: Impossibile annullare l\'assegnazione della società! Alla società :company deve essere assegnato almeno un utente.',
        'no_company'        => 'Errore: Nessuna società assegnata al tuo account. Per favore, contatta l\'amministratore di sistema.',
    ],

    'login_redirect'        => 'Verifica effettuata! Stai per essere reindirizzato...',
    'failed'                => 'Credenziali non corrispondenti ai dati registrati.',
    'throttle'              => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',
    'disabled'              => 'Questo account è disattivato. Si prega di contattare l\'amministratore di sistema.',

    'notification' => [
        'message_1'         => 'Hai ricevuto questa email perché abbiamo ricevuto una richiesta di reimpostazione della password per il tuo account.',
        'message_2'         => 'Se non hai richiesto la reimpostazione della password, non sono necessarie ulteriori azioni.',
        'button'            => 'Reimposta password',
    ],

    'invitation' => [
        'message_1'         => 'Hai ricevuto questa email perché sei stato invitato a partecipare ad Akaunting.',
        'message_2'         => 'Se non vuoi iscriverti, non sono necessarie ulteriori azioni.',
        'button'            => 'Inizia',
    ],

    'information' => [
        'invoice'           => 'Crea facilmente fatture',
        'reports'           => 'Ottieni report dettagliati',
        'expense'           => 'Tieni traccia di ogni uscita',
        'customize'         => 'Personalizza il tuo Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Amministratore',
            'description'   => 'Hanno pieno accesso al tuo Akaunting inclusi clienti, fatture, report, impostazioni e app.',
        ],
        'manager' => [
            'name'          => 'Manager',
            'description'   => 'Hanno accesso completo al tuo Akaunting, ma non possono gestire utenti e app.',
        ],
        'customer' => [
            'name'          => 'Cliente',
            'description'   => 'Possono accedere al portale clienti e pagare le fatture online tramite i metodi di pagamento impostati.',
        ],
        'accountant' => [
            'name'          => 'Ragioniere',
            'description'   => 'Possono accedere a fatture, transazioni, report e creare registrazioni contabili.',
        ],
        'employee' => [
            'name'          => 'Dipendente',
            'description'   => 'Possono creare note spese e tenere traccia del tempo per i progetti assegnati, ma possono visualizzare solo le proprie informazioni.',
        ],
    ],

];
