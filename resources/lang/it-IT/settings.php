<?php

return [

    'company' => [
        'description'                   => 'Cambia il nome dell\'azienda, l\'e-mail, il numero di tasse ecc',
        'search_keywords'               => 'azienda, nome, email, telefono, indirizzo, paese, codice fiscale, logo, città, paese, stato, provincia, codice postale',
        'name'                          => 'Nome',
        'email'                         => 'Email',
        'phone'                         => 'Telefono',
        'address'                       => 'Indirizzo',
        'edit_your_business_address'    => 'Modifica il tuo indirizzo business',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Queste informazioni sono visibili nei record creati.',
            'address'                   => 'L\'indirizzo verrà utilizzato nelle fatture, fatture e altri documenti emessi.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Imposta anno fiscale, fuso orario, formato data e altri locali',
        'search_keywords'               => 'finanziario, anno, inizio, denota, ora, zona, data, formato, separatore, sconto, percentuale',
        'financial_start'               => 'Inizio Anno finanziario',
        'timezone'                      => 'Fuso Orario',
        'financial_denote' => [
            'title'                     => 'Anno finanziario Indicare',
            'begins'                    => 'Entro l\'anno in cui inizia',
            'ends'                      => 'Entro l\'anno in cui termina',
        ],
        'preferred_date'                => 'Data preferita',
        'date' => [
            'format'                    => 'Formato data',
            'separator'                 => 'Separatore di data',
            'dash'                      => 'Trattino (-)',
            'dot'                       => 'Punto (.)',
            'comma'                     => 'Virgola (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Spazio ( )',
        ],
        'percent' => [
            'title'                     => 'Percentuale (%) Posizione',
            'before'                    => 'Prima del Numero',
            'after'                     => 'Dopo il Numero',
        ],
        'discount_location' => [
            'name'                      => 'Posizione di sconto',
            'item'                      => 'Alla linea',
            'total'                     => 'In totale',
            'both'                      => 'Sia la linea che il totale',
        ],

        'form_description' => [
            'fiscal'                    => 'Imposta il periodo dell\'anno finanziario utilizzato dalla tua azienda per la tassazione e la rendicontazione.',
            'date'                      => 'Seleziona il formato della data che desideri vedere ovunque nell\'interfaccia.',
            'other'                     => 'Selezionare dove viene visualizzato il segno di percentuale per le tasse. È possibile abilitare sconti sulle voci e sul totale per fatture e ricevute.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizza prefisso fattura, numero, termini, piè di pagina ecc',
        'search_keywords'               => 'personalizzare, fattura, numero, prefisso, cifra, successivo, logo, nome, prezzo, quantità, modello, titolo, sottotitolo, piè di pagina, nota, nascondi, scadenza, colore, pagamento, termini, colonna',
        'prefix'                        => 'Prefisso del numero',
        'digit'                         => 'Numero cifre',
        'next'                          => 'Codice fiscale',
        'logo'                          => 'Logo',
        'custom'                        => 'Personalizzato',
        'item_name'                     => 'Nome dell\'elemento',
        'item'                          => 'Elementi',
        'product'                       => 'Prodotti',
        'service'                       => 'Servizi',
        'price_name'                    => 'Nome del prezzo',
        'price'                         => 'Prezzo',
        'rate'                          => 'Valutazione',
        'quantity_name'                 => 'Quantità Nome',
        'quantity'                      => 'Quantità',
        'payment_terms'                 => 'Condizioni di pagamento',
        'title'                         => 'Titolo',
        'subheading'                    => 'Sottotitolo',
        'due_receipt'                   => 'Scaduto al ricevimento',
        'due_days'                      => 'Scade entro :days',
        'due_custom'                    => 'Giorno/i personalizzato/i',
        'due_custom_day'                => 'dopo il giorno',
        'choose_template'               => 'Scegli modello di fattura',
        'default'                       => 'Predefinito',
        'classic'                       => 'Classico',
        'modern'                        => 'Moderno',
        'logo_size_width'               => 'Larghezza logo',
        'logo_size_height'              => 'Altezza logo',
        'hide' => [
            'item_name'                 => 'Nascondi Nome Elemento',
            'item_description'          => 'Nascondi Descrizione Elemento',
            'quantity'                  => 'Nascondi Quantità',
            'price'                     => 'Nascondi Prezzo',
            'amount'                    => 'Nascondi Importo',
        ],
        'column'                        => 'Colonna|Colonne',

        'form_description' => [
            'general'                   => 'Imposta le impostazioni predefinite per la formattazione dei numeri delle fatture e dei termini di pagamento.',
            'template'                  => 'Seleziona uno dei modelli seguenti per le tue fatture.',
            'default'                   => 'La selezione delle impostazioni predefinite per le fatture precompilerà titoli, sottotitoli, note e piè di pagina. Quindi non avrai bisogno di modificare le fatture ogni volta per avere un aspetto più professionale.',
            'column'                    => 'Personalizza il nome delle colonne della fattura. Se desideri nascondere le descrizioni degli articoli e gli importi nelle righe, puoi modificarlo qui.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Scegli il modello di trasferimento',
        'second'                        => 'Secondo',
        'third'                         => 'Terzo',
    ],

    'default' => [
        'description'                   => 'Account predefinito, valuta, lingua della tua azienda',
        'search_keywords'               => 'conto, valuta, lingua, imposta, pagamento, metodo, impaginazione',
        'list_limit'                    => 'Risultati per Pagina',
        'use_gravatar'                  => 'Utilizzare Gravatar',
        'income_category'               => 'Categoria Reddito',
        'expense_category'              => 'Categoria Spese',
        'address_format'                => 'Formato indirizzo',
        'address_tags'                  => '<strong>Tag disponibili:</strong> :tags',

        'form_description' => [
            'general'                   => 'Seleziona il conto, le imposte e il metodo di pagamento predefiniti per creare rapidamente i record. Dashboard e report vengono visualizzati nella valuta predefinita.',
            'category'                  => 'Selezionare le categorie predefinite per accelerare la creazione del record.',
            'other'                     => 'Personalizza le impostazioni predefinite della lingua aziendale e il funzionamento dell\'impaginazione.',
        ],
    ],

    'email' => [
        'description'                   => 'Modifica il protocollo di invio e i modelli email',
        'search_keywords'               => 'e-mail, invio, protocollo, smtp, host, password
Protocollo',
        'protocol'                      => 'Protocollo',
        'php'                           => 'Mail PHP',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Host SMTP',
            'port'                      => 'Porta SMTP',
            'username'                  => 'Nome utente SMTP',
            'password'                  => 'Password SMTP',
            'encryption'                => 'Protezione SMTP',
            'none'                      => 'Nessuno',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Percorso Sendmail',
        'log'                           => 'Registra le email',
        'email_service'                 => 'Servizio di posta elettronica',
        'email_templates'               => 'Modelli di posta elettronica',

        'form_description' => [
            'general'                   => 'Invia e-mail regolari al tuo team e ai tuoi contatti. È possibile configurare il protocollo e le impostazioni SMTP.',
        ],

        'templates' => [
            'description'               => 'Modificare i modelli di posta elettronica',
            'search_keywords'           => 'e-mail, modello, oggetto, corpo, tag',
            'subject'                   => 'Oggetto',
            'body'                      => 'Corpo',
            'tags'                      => '<strong>Tag Disponibili:</strong> :tag_list',
            'invoice_new_customer'      => 'Nuovo modello di fattura (inviato al cliente)',
            'invoice_remind_customer'   => 'Nuovo Promemoria di fattura (inviato al cliente)',
            'invoice_remind_admin'      => 'Nuovo Promemoria di fattura (inviato al proprietario)',
            'invoice_recur_customer'    => 'Nuova Ricorrenza di fattura (inviato al cliente)',
            'invoice_recur_admin'       => 'Nuova Ricorrenza di fattura (inviato al proprietario)',
            'invoice_view_admin'        => 'Modello di visualizzazione fattura (inviato all\'amministratore)',
            'invoice_payment_customer'  => 'Modello di pagamento ricevuto (inviato al cliente)',
            'invoice_payment_admin'     => 'Modello di pagamento ricevuto (inviato all\'amministratore)',
            'bill_remind_admin'         => 'Modello promemoria fattura (inviato all\'amministratore)',
            'bill_recur_admin'          => 'Modello di fatturazione ricorrente (inviato all\'amministratore)',
            'payment_received_customer' => 'Modello di ricevuta di pagamento (inviato al cliente)',
            'payment_made_vendor'       => 'Modello pagamento effettuato (inviato al fornitore)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Pianificazioni',
        'description'                   => 'Promemoria e comando automatici per le ricorrenze',
        'search_keywords'               => 'automatico, promemoria, ricorrente, cron, comando',
        'send_invoice'                  => 'Inviare promemoria fattura',
        'invoice_days'                  => 'Inviare dopo Due giorni',
        'send_bill'                     => 'Inviare promemoria pagamenti',
        'bill_days'                     => 'Inviare entro Due giorni',
        'cron_command'                  => 'Comando cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Ora di esecuzione',

        'form_description' => [
            'invoice'                   => 'Abilita o disabilita e imposta promemoria per le tue fatture quando sono scadute.',
            'bill'                      => 'Abilita o disabilita e imposta promemoria per le fatture prima che scadano.',
            'cron'                      => 'Copia il comando cron che il tuo server dovrebbe eseguire. Imposta l\'ora per attivare l\'evento.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorie illimitate per reddito, spese e articolo',
        'search_keywords'               => 'categoria, entrate, spese, voce',
    ],

    'currencies' => [
        'description'                   => 'Crea e gestisci le valute e imposta i loro tassi',
        'search_keywords'               => 'predefinito, valuta, valute, codice, tasso, simbolo, precisione, posizione, decimale, migliaia, contrassegno, separatore',
    ],

    'taxes' => [
        'description'                   => 'Aliquote fiscali fisse, normali, inclusive e composte',
        'search_keywords'               => 'imposta, aliquota, tipo, fisso, compreso, composto, ritenuta',
    ],

];
